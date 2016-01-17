<?php 


$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';
  
	header('Content-Type: text/html; charset=utf-8');
	  
	$__isAdminPage = true;
	$pageLocation='1';
	$requestedAction='users';
	  
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }

        $database = $wo->db->getDatabaseName();
	$dbString = "$database@" . $wo->getConfigurationFor('databaseHost')[$wo->getConfigurationFor('defaultDBIndex')];
	 
	echo "<h1>Create users</h1>";
	echo "<h2>Db: $dbString</h2>";
  
  
    // array( array( 0: loginName, 1: password, 2:string[]|string (of role names) 3: id (may be '' ) 4: checkPassword (default true) ), ... )
    // The following is an example. Edit as desired.
	// PLEASE, SET THE FOLLOWING
    $newUsers = array(
        array( 'sysJohnL', '12345678A', array( 'Normal User', 'System Operator') ),
        array( 'sysApapanto', '12345678A', array( 'Normal User', 'System Operator') ),
        //array( 'someAdmin1', 'someNiceAdminPassword1', array( 'Normal User', 'System Operator') ),
    );
    $newUsers = array();    // COMMENT AFTER CHANGING $newUsers above

    $commitEach = false;	// set to true to save users one by one. set to false to save them all or none!
    
	$succ = WOOOF_User::createMultipleUsers($wo, $newUsers, $newUserIds, $commitEach);
    //var_dump($succ, $newUsers, $newUserIds);
    echo "<h2>Given Users</h2>";
    echo WOOOF_Util::do_dump($newUsers);

    echo "<h2>Created Users</h2>";
    echo WOOOF_Util::do_dump($newUserIds);
    
    if ( $succ === TRUE ) { $wo->db->commit(); echo "<h2>Ok</h2>"; } 
    else { $wo->db->rollback(); echo "<h2>Failed</h2>"; }
    
    

?>