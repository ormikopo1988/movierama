<?php

	// Example script for calling WOOOF_Metadata functions
	//

	// id ascii_bin (1/3 of bytes!)
  	// same for all xxxId

	// Ball buster :-(
	// https://dev.mysql.com/doc/refman/5.0/en/identifier-case-sensitivity.html	

	// View is insertable/updateable if derived FKs are omitted from the statement
/*
create or replace view v_applicationsFK
as
select
  a.*, t.name as typeNameFK, s.name as statusNameFK
from
  applications a
  left outer join applicationTypes t on t.id = a.appTypeId
  left outer join applicationStatuses s on s.id = a.statusId
;

	// left outer is good for starting with applications

 */	

	$__isSiteBuilderPage = true;
  	$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
	$__actualPath = dirname($__actualPath);
  
	require_once $__actualPath . '/setup.inc.php';
  
	$requestedAction='edit';
	$pageLocation='1';
  
	
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }

	// User tests...
	/*
	$succ = WOOOF_User::changePassword($wo, 'user1', 'user1', $errors, 'user2', false);
	var_dump($succ);
	echo nl2br($wo->getErrorsAsStringAndClear());
	var_dump($errors);
	$wo->db->commit();
	die();
	*/
	
	/*
	$succ = WOOOF_User::createUser($wo, 'admin', 'admin123', array('Normal User','System Operator'), '', false );
	//$succ = WOOOF_User::createMultipleUsers($wo, array( array('a','b', 'c', '', true) ), $newUserIds);
	var_dump($succ); echo $wo->getErrorsAsStringAndClear(); 
	$wo->db->commit();
	die();	
	*/
	
	var_dump($wo->sid);
	$database = $wo->db->getDatabaseName();
	$dbString = "$database@" . $wo->getConfigurationFor('databaseHost')[$wo->getConfigurationFor('defaultDBIndex')];
	echo "<h3>Db: " . $dbString . '</h3>';
	echo "<h3>".'MetaData Version: [' . WOOOF_MetaData::$version . ']'."</h3>";
	
	// echo WOOOF_Util::fromCamelCase('v_applicationsFK'); die();
	
	/*
	$doCommit = true;
	$objName = 'anewtable';
	echo "<h3>Deleting $objName</h3>";
	$succ = WOOOF_MetaData::deleteObjectMetaData($wo, $objName );
	var_dump($succ);
  	if ( $succ ) { if ($doCommit) {$wo->db->commit();} echo '<h4>Ok</h4>';} 
  	else { $wo->db->rollback(); echo '<h4>***FAILED***</h4>'; echo $wo->getErrorsAsStringAndClear(); }
	die();	
	*/
	
	//  $database = 'ait_mba';
	///*
	$doCommit = true;	// false for testing. Does NOT apply to updateMetaDataFromOtherMetaData!!
	//$tmp = $wo->getConfigurationFor('databaseName');
  	//$database = $tmp[0];
  	$succ = false;
  	
  	// This
  	//$succ = WOOOF_MetaData::createMetaDataTables($wo);
  	
  	// or that
	//$succ = WOOOF_MetaData::selfUpgradeMetaData($wo, $database);
  	
  	// or that (REFRESH ALL!)
  	//$succ = WOOOF_MetaData::reverseEngineerObjects( $wo, $database, 'refresh', array('chat') );

  	// or that (Just One)
	//$objName = 'applications';	// applications v_applicationsFK
  	//$succ = WOOOF_MetaData::reverseEngineerObject($wo, $database, $objName, 'refresh');

  	// or that (Indexes)
  	//$succ = WOOOF_MetaData::reverseEngineerObjects( $wo, $database, 'indexes' );
  	
  	// or that (ascii collation)
  	//$succ = WOOOF_MetaData::reverseEngineerObjects( $wo, $database, 'ascii' );
  	 
  	  
  	// or that
  	//$succ = WOOOF_MetaData::exportMetaData($wo, false ); var_dump($succ);  //WOOOF_Util::do_dump($succ);
  	
  	// or that
  	//$succ = WOOOF_MetaData::importMetaData( $wo, 'mdExp_20150714154548.json' ); 
  	//if ( $succ ) {
  	//	$succ = WOOOF_MetaData::updateMetaDataFromOtherMetaData($wo, $ddl, $dml, $sqlPerTable, false, true); 
  	//}
  	
  	/*
  	// or that (create indexes for all Tables from metaData)
  	$res = WOOOF_MetaData::buildIndexesForAllTables($wo, $database, false );
  	foreach( $res as $aTable => $aSQLArray ) { 	echo implode("<br>", $aSQLArray);	}
  	echo "<br><br>";
  	die("Finished with buildIndexesForAllTables");
  	*/
  	
  	/*
  	// or that (create indexes for a Table from metaData)
  	$objName = 'stories';	// applications
  	$res = WOOOF_MetaData::buildIndexesForTable($wo, $database, $objName, false);
  	foreach( $res as $aTable => $aSQLArray ) { 	echo implode("<br>", $aSQLArray);	}
  	echo "<br><br>";
  	die("Finished with buildIndexesForTable [$objName]");
  	*/
  	   

	
  	if ( $succ !== FALSE ) { if ($doCommit) {$wo->db->commit();} echo '<h4>Ok</h4>';} 
  	else { $wo->db->rollback(); echo '<h4>***FAILED***</h4>'; echo $wo->getErrorsAsStringAndClear(); }
  	//*/
	
  	echo '<h4>Finished</h4>';

?>