<?php 

// User functions

class WOOOF_User {
	const _ECP = 'WUS';	// Error Code Prefix
	const ID_OF_NOT_LOGGED_IN = '0123456789';
	
	// 7000
	
	/***************************************************************************/
	/***************************************************************************/
	/**
	 * 
	 * @param WOOOF $wo
	 * @param array $usersArray // array( array( 0: loginName, 1: password, 2:string[]|string (of role names) 3: id (may be '' ), 4: checkPassword=true ), ... )
	 * @return boolean
	 */
	public static
	function createMultipleUsers( WOOOF $wo, $usersArray, &$newUserIds, $commitEach=true )
	{
		$newUserIds = array();
		
		foreach( $usersArray as $aKey => $aUserData ) {
			if ( !isset($aUserData[0]) or !isset($aUserData[1]) or !isset($aUserData[2]) ) {
				$wo->logError(self::_ECP."0018 No loginName or password or role(s) provided for user #$aKey" );
				return false;
			}
			
			if ( !$wo->hasContent($aUserData[0]) ) {
				$wo->logError(self::_ECP."0020 No loginName provided for user #$aKey" );
				return false;
			}
			if ( !$wo->hasContent($aUserData[1]) and $aUserData[0] == self::ID_OF_NOT_LOGGED_IN ) {
				$wo->logError(self::_ECP."0022 No password provided for user #$aKey [{$aUserData[0]}]" );
				return false;
			} 
			if ( !is_array($aUserData[2]) and !$wo->hasContent($aUserData[2]) ) {
				$wo->logError(self::_ECP."0024 No role(s) provided for user #$aKey [{$aUserData[0]}]" );
				return false;
			} 
		}
		
		foreach( $usersArray as $aKey => $aUserData ) {
			if ( !isset($aUserData[3]) ) { $aUserData[3] = ''; }
			if ( !isset($aUserData[4]) ) { $aUserData[4] = true; }

			$newId = self::createUser( $wo, $aUserData[0], $aUserData[1], $aUserData[2], $aUserData[3], $aUserData[4] );
			if ( $newId === FALSE ) {
				$wo->logError(self::_ECP."0030 Failed creating user [{$aUserData[0]}]");
				if ( !$commitEach ) {
					$newUserIds = array();
					return false; 
				}
				else { 
					$wo->db->rollback(); 
				}
			}	// failed to create user
			else {
				// user created ok 
				$newUserIds[$aUserData[0]] = $newId;
				if ( $commitEach ) {
					$wo->db->commit();
				}
			}	// user created ok or not
		}	// foreach user
		
		return true;
				
	}	// createMultipleUsers
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 */
	public static
	function createUser( WOOOF $wo, $loginName, $loginPass, $roles=[], $id='', $checkPassword=true )
	{
		
		if ( !$wo->hasContent($loginName) or ( !$wo->hasContent($loginPass) and $id!=self::ID_OF_NOT_LOGGED_IN) ) {
			$wo->logError('7000 Both loginName and loginPass must be provided' );
			return false;
		}
		
		if ( $wo->hasContent($id) ) {
			$newUserId = $id;
			if ( $wo->db->getSingleValueResult("select 1 from __users where id='".$wo->db->escape($newUserId). "'") !== NULL ) {
				$wo->logError(self::_ECP."0012 User with id [$id] already exists");
				return FALSE;
			} 
		}
		else {
			$newUserId = $wo->db->getNewId('__users');
		}
		// Alternatively: $newUserId = $wo->db->getNewId('__users', $id );
		// But it does not respect the given id and might silently return a different one.
		
		if ( $newUserId === FALSE ) { return FALSE; }
		
		
		// Check uniqueness of $loginName
		$existingUserRow = $wo->db->getRowByColumn('__users', 'loginName', $loginName);
		if ( $existingUserRow !== NULL ) {
			$wo->logError(self::_ECP."0013 User with loginName [$loginName] already exists");
			return FALSE;
		}
	    
		if ( $id == self::ID_OF_NOT_LOGGED_IN ) {
	    	$newUserPassword = '';
	    }
	    else {
	    	if ( $checkPassword ) {
	    		if ( $wo->evaluatePassword($loginPass, $loginPass, $passwordErrors) === FALSE ) {
	    			$wo->logError(self::_ECP."0003 Password is not accepted: " . implode(' | ', $passwordErrors));
	    			return FALSE;
	    		}
	    	}
	    	$newUserPassword = $wo->getPasswordHash($loginPass, $newUserId);
	    }
	    
    	if ( $newUserPassword === FALSE ) { return FALSE; }
    	$newUserPassword = $wo->db->escape($newUserPassword);
    	 
    	$succ = $wo->db->query('insert into __users (id, loginName, loginPass) values (\''. $newUserId .'\',\''. $loginName .'\',\''. $newUserPassword .'\')');
    	if ( $succ === FALSE ) { return FALSE; }

    	if ( !is_array($roles) ) {
	    	$userRolesArr = explode(',', $roles);
    	}
    	else {
    		$userRolesArr = $roles;
    	}
    	
    	foreach ($userRolesArr as $aRole)
    	{
        	$roleData = $wo->db->getRowByColumn('__roles','role',$aRole);
        	if ( $roleData === FALSE ) { return FALSE; }
        	if ( $roleData === NULL ) {
        		$wo->logError(self::_ECP."0005 Role [$aRole] does not exist"); 
        		return FALSE;
        	}
        	
            $succ - $wo->db->query('insert into __userRoleRelation (userId, roleId) values (\''. $newUserId .'\',\''. $roleData['id'] .'\')');
    	   	if ( $succ === FALSE ) { return FALSE; }
    	}	// foreach role
    	
    	return $newUserId;
    }	// createUser
	
	/***************************************************************************/
	/***************************************************************************/

    /**
     * 
     * @param WOOOF $wo
     * @param string $loginName
     * @param string $newPassword
     * @param string[] &$passwordErrors	// return possible new password problems
     * @param string $oldPassword		// Optional, default '', do not verify old pass validity
     * @param string $checkPassword		// Optional, default true. Check new pass is ok
     * @return boolean
     */
	public static
	function changePassword( WOOOF $wo, $loginName, $newPassword, &$passwordErrors, $oldPassword='', $checkPassword=true )
	{
		$passwordErrors = array();
		
		if ( !$wo->hasContent($loginName) or !$wo->hasContent($newPassword) ) {
			$wo->logError('7055 Both loginName and mew Password must be provided' );
			return false;
		}
		
		$userRes = $wo->db->query(
			"select * from __users where loginName='$loginName'"
		);
		if ( $userRes === FALSE ) { return FALSE; }

		$userRow = $wo->db->fetchAssoc($userRes);
		if ( $userRow === NULL ) {
			$wo->logError(self::_ECP."0057 User with loginName [$loginName] was not found");
			return FALSE;
		}
		
		if ( $userRow['id'] == self::ID_OF_NOT_LOGGED_IN ) {
			$wo->logError(self::_ECP."0059 Cannot changePassword of this user");
			return FALSE;
		}
		
		if ( $wo->hasContent($oldPassword) ) {
			$oldPassHashed = $wo->getPasswordHash( $oldPassword, $userRow['id'] );
			if ( $oldPassHashed === FALSE or $oldPassHashed != $userRow['loginPass'] ) {
				$wo->logError(self::_ECP."0060 Bad old password was given");
				return false;
			}
		}
		
		if ( $checkPassword ) {
			if ( $wo->evaluatePassword($newPassword, $newPassword, $passwordErrors) === FALSE ) {
				$wo->logError(self::_ECP."0063 Password is not accepted");
				return FALSE;
			}
		}
		
		$newPassHashed = $wo->getPasswordHash($newPassword, $userRow['id']);
		if ( $newPassHashed === FALSE ) { return FALSE; }
		$newPassHashed = $wo->db->escape($newPassHashed);
		
		$succ = $wo->db->query(
			"update __users set loginPass = '$newPassHashed' where id = '{$userRow['id']}'"
		);
		if ( $succ === FALSE ) { return FALSE; }
		
		return true;
		
	}	// changePassword	
    /***************************************************************************/
    /***************************************************************************/
    
    // TODO!!!
    
    public static
    function addRole()
    {

    }

    /***************************************************************************/
    /***************************************************************************/
    
    // TODO!!!
    
    public static
    function removeRole()
   	{
    		 
   	}
    
    /***************************************************************************/
    /***************************************************************************/
    
    
}	// WOOOF_User