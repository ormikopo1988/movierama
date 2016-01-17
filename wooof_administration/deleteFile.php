<?php
	require_once '../setup.inc.php';
	
	// TODO: Change the action
	$requestedAction='viewUncontroled';
	$pageLocation = '3';
	$pageTitle='Delete File.';
	
	$wo = new WOOOF();
	
	$pageLocation='6_'.$wo->cleanUserInput($_GET['location']);
	
	$pieces = explode('_', $pageLocation);
	
	if (count($pieces)!=4)
	{
	    die('Malformed file location. Please try again !');
	}
	
	// antonis ???? The specific field is ignored?
	$pageLocationTrue = '6_'. $pieces[1] .'_'. $pieces[3];
	
	$permitions = $wo->db->getSecurityPermitionsForLocationAndUser($pageLocationTrue, $userData['id']);
	
	//antonis. TODO: Fix and uncomment!!!
	/*
	if (!isset($permitions['download']) || $permitions['download']!='1')
	{
	    die('Security failure: you don\'t have permission to perform the requested action.');
	}
	*/
	
	$succ = WOOOF_ExternalFiles::deleteExternalFileByAddress($wo, $pieces[1], $pieces[2],$pieces[3] );

	if ( $succ ) {
		$wo->db->commit();
	}
	else {
		$wo->db->rollback();
	}
	
	// TODO: go where ???
?>