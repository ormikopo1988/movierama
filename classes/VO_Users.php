<?php

class VO_Users {
	const _ECP = 'USE';	// Error Code Prefix
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 * 
	 * @param WOOOF $wo
	 * @param VO_TblUser $obj
	 * @param bool $fetchBack
	 * @return false | id
	 */
	public static 
	function save( WOOOF $wo, VO_TblUser &$obj )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  " );
	    
		$t1 = new WOOOF_dataBaseTable($wo->db, 'movierama_users');
		if ( !$t1->constructedOk ) { return false; }
		
		if ( !$wo->hasContent($obj->userId) ) {
			$wo->logError(self::_ECP."0010 No value provided for [userId]" );
			return false;
		}
		
		if ( $wo->hasContent($obj->id) ) {
			// update
			$res = $t1->updateRowFromArraySimple( $obj->toArray() );
			if ( $res === FALSE ) { return FALSE; }
		}
		else {
			// insert
			$verificationToken		= WOOOF::randomString(255);
			 
			$obj->isDeleted			= '0';
			$obj->isActive			= '1';
			$obj->isVerified		= '0';
			$obj->verificationToken = $verificationToken;
			$obj->createdDateTime 	= WOOOF::currentGMTDateTime();
			$obj->updatedDateTime	= $obj->createdDateTime;
			
			$newId = $t1->insertRowFromArraySimple( $obj->toArray() );
			if ( $newId === FALSE ) { return false; }
			$obj->id = $newId;
			
			$succ = self::handleVerificationToken($wo, $obj->id, $obj->username, $verificationToken );
			if ( $succ === FALSE ) { return FALSE; }
		}
		
		return $obj->id;
	}	// save
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 * 
	 * @param WOOOF $wo
	 * @param VO_TblPersonProfile $obj
	 * @return false | id
	 */
	public static 
	function savePersonProfile( WOOOF $wo, VO_TblPersonProfile &$obj )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  " );
	    
		$t1 = new WOOOF_dataBaseTable($wo->db, 'person_profiles');
		if ( !$t1->constructedOk ) { return false; }
		
		if ( $wo->hasContent($obj->id) ) {
			// update
			$obj->updatedDateTime	= WOOOF::currentGMTDateTime();

			$res = $t1->updateRowFromArraySimple($obj->toArray());
			if($res === FALSE) { return false; }
		}
		else {
			// insert
			$obj->isDeleted 	= '0';
			$obj->createdDateTime 	= WOOOF::currentGMTDateTime();
			$obj->updatedDateTime	= $obj->createdDateTime;
			
			$newId = $t1->insertRowFromArraySimple($obj->toArray());
			if ( $newId === FALSE ) { return false; }
			$obj->id = $newId;
		}
	  
		return $obj->id;
	}	// savePersonProfile
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $movieramaUserId
	 * @param string $emailTo
	 * @param string $token
	 * @return bool
	 */
	public static
	function handleVerificationToken( WOOOF $wo, $movieramaUserId, $emailTo, $token )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		
		$route = 'register/verify?token='.$token;
		
		$fragmentParams = array( 'URL' => $wo->fullURL($route) );
		$fragments = $wo->fetchApplicationFragment('registrationVerificationEmail.php', $fragmentParams );
		
		$emailAddress	= $emailTo;
		$subject		= $fragments['subject'];
		$message		= $fragments['messageText'];
		$replyTo 		= '';
		$cc		 		= '';
		$htmlMessage	= $fragments['messageHTML'];
		$files			= null;
		
		$res = $wo->sendMail('',$emailAddress,$subject,$message,$replyTo,$cc,$htmlMessage,$files);
		
		if ( $res === FALSE ) {
			$wo->logError( self::_ECP."0020 Failed to send registration verification email to [$emailTo]" );
			return FALSE;
		}
		
		return true;
		
	}	// handleVerificationToken
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 * 
	 * @param WOOOF $wo
	 * @param array $in
	 * @return false | id
	 * Returns actually saved $obj 
	 */
	public static 
	function saveMainInfo( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  " );
		
		if(!$wo->hasContent($in['firstName'])) {
			$wo->logError(self::_ECP."0012 No value for first name");
			return false;
		}
		
		if(!$wo->hasContent($in['lastName'])) {
			$wo->logError(self::_ECP."0014 No value for last name");
			return false;
		}
		
		$movieRamaUser = $wo->db->getRowByColumn('movierama_users', 'userId', $wo->userData['id']); //fernei olo to row
		if($movieRamaUser === FALSE) { return false; }
		
		$movieRamaUserProfile = $wo->db->getRow('person_profiles', $movieRamaUser['personProfileId']); //fernei olo to row
		if($movieRamaUserProfile === FALSE) { return false; }
		
		$tblProfile = new VO_TblPersonProfile($movieRamaUserProfile);
		$tblProfile->firstName = $in['firstName'];
		$tblProfile->lastName = $in['lastName'];
		
		$res = VO_Users::savePersonProfile($wo, $tblProfile);
		if($res === FALSE) { return false; }
			
		return $res;
	}	// saveMainInfo
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in
	 * @return boolean
	 */
	public static
	function passwordChange( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  ChangePassword" );
	
		$movieRamaPerson = $wo->db->getRowByColumn('v_movierama_persons', 'VUS_id', $in['movieRamaUserId']);
		if($movieRamaPerson === FALSE) { return false; }
		if($movieRamaPerson === NULL) {
			$wo->logError(self::_ECP."3352 No MovieRama person found.");
			return false;
		}
	
		$user = $wo->db->getRow('__users', $movieRamaPerson['VUS_userId']);
		if($user === FALSE) { return false; }
		if($user === NULL) {
			$wo->logError(self::_ECP."3357 No user found.");
			return false;
		}
	
		//change password here
		$passwordErrors = [];
		$res = WOOOF_User::changePassword($wo, $user['loginName'], $in['newPass'], $passwordErrors, $in['oldPass']);
		if($res === FALSE) { return false; }
	
		return $res;
	}	// passwordChange
	
}	// VO_Users