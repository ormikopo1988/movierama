<?php

// WOOOF Version: 0.12.39
// WOOOF MetaData Version: 0.12.39 
// June 19, 2015

	require_once '../setup.inc.php';
		
	
	// WARNING !!! Running this script with the 'recreate=1' option
	// will ELLIMINATE ANY AND ALL DATA in WOOOF's 
	// "system" tables if they already exist. Use With Caution (tm)
	
	$wo = new WOOOF(FALSE);
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
	
	echo "<h1>Initialize DB with WOOOF tables/data</h1>";
	
	if ( !is_array($wo->getConfigurationFor('databaseName')) ) {
		echo "No Database defined in the configuration options.<br>";
		die('Aborting execution.');
	}
	
	if ( $wo->getConfigurationFor('databaseLog')[0] == true ) {
		echo "Please set the databaseLog config option to false and try again.<br>";
		die('Aborting execution.');
	}
	
	$paramNames = array( 'recreate' );
	$in = $wo->getMultipleGetPost($paramNames);
	$recreate = ( $in['recreate'] == '1' );
	
	$dbName = $wo->getConfigurationFor('databaseName')[0];
	
	$usersArray = null;	// set to null to create default users: sysOp, notLoggedIn, admin, user1
	/*
	 * // Example. 
	$usersArray = array(
			array( 'sysOp', 'passowrd1', array('System Operator', 'Normal User'), null, false ),
			array( 'notLoggedIn', '', 'Not Logged In', WOOOF_User::ID_OF_NOT_LOGGED_IN, false ),
			array( 'admin', 'passowrd2', array('System Operator', 'Normal User'), null, false ),
			array( 'user1', 'passowrd3', 'Normal User', null, false ),
	);
	*/
	
	$succ = WOOOF_MetaData::initDatabase($wo, $dbName, $usersArray, $recreate );
	
	$dbString = $dbName . '@' . $wo->getConfigurationFor('databaseHost')[0];
	
	if ( $succ ) {
		echo "<h2>Db [$dbString] init ok</h2>";
        echo '<a href="dbManager.php">Go to the dbManager page...</a>';
	}
	else {
		echo "<h2>Db [$dbString] init FAILED!!!!</h2>";
	}

	echo nl2br($wo->getErrorsAsStringAndClear());
	
	echo "<br>Finished</br>";


?>