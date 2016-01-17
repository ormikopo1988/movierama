<?php
	require_once '../setup.inc.php';
	  
	header('Content-Type: text/html; charset=utf-8');
	 
	$__isAdminPage = true;
	  
	$pageLocation='1';
	$requestedAction='users';
	  
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
	
	// PLEASE, SET THE FOLLOWING
	// just an example
	$users['loginName'] = 'newPassword';
	$users['loginName2'] = 'newPassword2';
	
	var_dump($wo->sid);
	$database = $wo->db->getDatabaseName();
	$dbString = "$database@" . $wo->getConfigurationFor('databaseHost')[$wo->getConfigurationFor('defaultDBIndex')];
	 
	echo "<h1>Change user passwords</h1>";
	echo "<h2>Db: $dbString</h2>";
  
	foreach ($users as $key => $value) 
	{
		echo "Changing [$key] ...";
		
	    /*
		$cUser = $wo->db->getRowByColumn('__users','loginName', $key);
		
		if ( $cUser === NULL ) {
			echo " user not found!<br>";
			continue;
		}
		
		$thePassword = $wo->getPasswordHash($wo->cleanUserInput($value), $cUser['id']);
	
		if ( $thePassword == FALSE ) {
			echo "FAILED*****";
		}
		else {
			$succ = $wo->db->query('update __users set loginPass=\''. $thePassword .'\' where id=\''. $cUser['id'] .'\'');
			if ( $succ === FALSE ) {
				echo "Failed to update: " . nl2br($wo->getErrorsAsStringAndClear()) ;
			}
			else {
				$wo->db->commit();
				echo "Ok";
			}
		}
		*/

	    $checkPasswordValidity = true;
	    
		$succ = WOOOF_User::changePassword($wo, $key, $value, $errors, '', $checkPasswordValidity );
	    echo "$key: " . ( $succ ? "changed OK" : "FAILED to change password" ) . "<br>";
		echo nl2br($wo->getErrorsAsStringAndClear());
		var_dump($errors);
	    if ( $succ ) { $wo->db->commit(); } else { $wo->db->rollback(); }
	    
		echo "<br>";
	}
	
	
	echo "Finished."
	

?>