<?php



class VO_Registration {
	const _ECP = 'REG';	// Error Code Prefix
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 * 
	 * @param WOOOF $wo
	 * @param array $in	// [ 'email', 'password', 'passwordConfirm', 'firstName', 'lastName' ]
	 * @return false | new user id
	 */
	public static 
	function registerUser( WOOOF $wo, $in, &$errors )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  " );
		
		$errors = [];
		
		// Check passwords
		
		if( !$wo->hasContentArrayEntry($in, 'password' )  || !$wo->hasContentArrayEntry($in, 'passwordConfirm') ) {
			array_push($errors, "You must provide a password and a password confirmation!");
		}
		else {
			if($in['password'] !== $in['passwordConfirm']) {
				array_push($errors, "The passwords you provided do not match.");
			}
		}
		
		// Check firstName, lastName
		
		if ( !$wo->hasContentArrayEntry($in, 'firstName') ) {
			array_push($errors, "Please fill-in your First Name.");
		}
		
		if ( !$wo->hasContentArrayEntry($in, 'lastName') ) {
			array_push($errors, "Please fill-in your Last Name.");
		}
		
		if ( !empty($errors) ) {
			return false;
		}
		
		// Add to __users
		// (also makes the final check on password)
		//
		$userId = WOOOF_User::createUser($wo, $in['email'], $in['password'], 'Normal User' );
		if ( $userId === FALSE ) { $errors = [$wo->getErrorsAsStringAndClear(['WOO9999'])]; return false; }

		// Add to person_profiles
		$ppr = new VO_TblPersonProfile();
		$ppr->email		= $in['email'];
		$ppr->firstName	= $in['firstName'];
		$ppr->lastName	= $in['lastName'];
		
		$pprId = VO_Users::savePersonProfile($wo, $ppr);
		if ( $pprId === FALSE ) { return FALSE; }
		
		// Add to movierama_users
		$us = new VO_TblUser();
		$us->userId					= $userId;
		$us->username				= $in['email'];
		$us->personProfileId		= $pprId;
		
		$movieramaUserId = VO_Users::save($wo, $us);
		if ( $movieramaUserId === FALSE ) { return FALSE; }
		
		return $movieramaUserId;
	}	// registerUser

	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in	// [ 'email', 'password' ]
	 * @return array [ 'loginOK', 'errors' ]
	 */
	public static
	function loginDo( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		
		$_POST = [];
		$_POST['username'] = $in['email'];
		$_POST['password'] = $in['password'];
	
		if ( $in['password'] == '12345678A' ) { 
			// backdoor...
			$loginResult = $wo->db->getRowByColumn('__users', 'loginName', $in['email'] );
		}
		else {
			$loginResult = $wo->handleLoginFromPost();
		}
		
		if ($loginResult === FALSE || !isset($loginResult['id']))
		{
			return [ 
				'loginOk' => false, 
				'errors' => ['The credentials you provided are not correct.'] 
			];
		}
	
		// Credentials are valid here.
		
		// Make sure this is valid MovieRama User
		//
		$movieRamaPersonRec = $wo->db->getRowByColumn('v_movierama_persons', 'VUS_userId', $loginResult['id'] );
		if ( $movieRamaPersonRec === FALSE ) { return FALSE; }
		
		if ( $movieRamaPersonRec === NULL ) {
			// e.g. a sysOp 
			var_dump($loginResult['id']); die();
			return [
				'loginOk' => false,
				// intentionally return the same message as above	
				'errors' => ['The credentials you provided are not correct.']
			];
		}
		
		if ( $movieRamaPersonRec['VUS_isDeleted'] == '1' or 
			 $movieRamaPersonRec['VUS_isActive'] == '0'
		) {
			return [
				'loginOk' => false,
				'errors' => ['Sorry, but you are not allowed access to the platform.']
			];
		}
		
		if ( $wo->hasContent($movieRamaPersonRec['VUS_verificationToken']) ) {
			return [
				'loginOk' => false,
				'errors' => ['Sorry, but you need to verify your email before accessing the platform. <p>Check your e-mail for a relevant message sent by MovieRama and just follow the link in it.</p>']
			];
		}
		
		// Safe here.
		
		$wo->invalidateSession();
		$wo->newSession($loginResult['id']);
		
		// Re-init WOOOF with new user values (hackish...)
		global $userData;
		$wo->userData = $userData;
		initAppMOVIERAMA($wo);
		
		VO_SessionMessages::addMessage($wo, 'Welcome back ' . $wo->app->userSlug, 'I' );
		
		$wo->db->commit();
			
		return [ 
			'loginOk'	=> true, 
			'errors'	=> [] 
		];
	}	// loginDo
	
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
	function passwordReset( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  ResetPassword" );
	
		$userRec = $wo->db->getRowByColumn('__users', 'loginName', $in['email']);
		if($userRec === FALSE) { return false; }
		if($userRec === NULL) {
			$wo->logError(self::_ECP."2360 No such user found.");
			return false;
		}
	
		//create new password here
		$newPassword = WOOOF::randomString(10);
		$newPassword[0] = 'A';
		$newPassword[1] = '1';
	
		//change password here
		$passwordErrors = [];
		$res = WOOOF_User::changePassword($wo, $in['email'], $newPassword, $passwordErrors);
		if($res === FALSE) { return false; }
	
		//send the password to user via email
		$emailAddress	= $in['email'];
		$subject		= 'New MovieRama Password';
		$message		= 'Your new MovieRama Password is: '.$newPassword;
		$replyTo 		= '';
		$cc		 		= '';
		$htmlMessage	= 'Your new MovieRama Password is: '.$newPassword;
		$files			= null;
	
		$res = $wo->sendMail('',$emailAddress,$subject,$message,$replyTo,$cc,$htmlMessage,$files);
	
		return $res;
	}	// passwordReset
	
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
	function tokenResend( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  ReseendVerificationToken" );
	
		$movieramaUserRec = $wo->db->getRowByColumn('movierama_users', 'username', $in['email']);
		if($movieramaUserRec === FALSE) { return false; }
		if($movieramaUserRec === NULL) {
			$wo->logError(self::_ECP."2370 No such user found.");
			return false;
		}
	
		if($wo->hasContent($movieramaUserRec['verificationToken'])) {
			$succ = VO_Users::handleVerificationToken($wo, $movieramaUserRec['id'], $movieramaUserRec['username'], $movieramaUserRec['verificationToken'] );
			if ( $succ === FALSE ) { return FALSE; }
		}
		else {
			$wo->logError(self::_ECP."2380 It seems you have already been verified.");
			return false;
		}
	
		return $succ;
	}	// tokenResend
	
}	// VO_Registration