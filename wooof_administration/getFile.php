<?php
	require_once '../setup.inc.php';
	
	$requestedAction='viewUncontroled';
	$pageLocation = '3';
	$pageTitle='Download File.';
	
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
	
	$result = $wo->db->query('select * from __tableMetaData where id=\''. $pieces[1] .'\'');
	if (mysqli_num_rows($result)!=1)
	{
	    die('Malformed file location. Specified HEAD location is invalid!');
	}
	
	$tMD = $wo->db->fetchAssoc($result);
	
	$result = $wo->db->query('select * from __columnMetaData where id=\''. $pieces[2] .'\'');
	if (mysqli_num_rows($result)!=1)
	{
	    die('Malformed file location. Specified BODY location is invalid!');
	}
	
	$cMD = $wo->db->fetchAssoc($result);
	
	$result = $wo->db->query('select * from '. $tMD['tableName'] .' where id=\''. $pieces[3] .'\'');
	if (mysqli_num_rows($result)!=1)
	{
	    die('Malformed file location. Specified FEET location is invalid!');
	}
	
	$row = $wo->db->fetchAssoc($result);
	
	$result = $wo->db->query('select * from __externalFiles where id=\''. $row[$cMD['name']] .'\'');
	if (mysqli_num_rows($result)!=1)
	{
	    die('Malformed file location. Specified PAYLOAD location is invalid!');
	}
	
	$fileData = $wo->db->fetchAssoc($result);
	
	$absoluteFilesRepositoryPath = $wo->getConfigurationFor('absoluteFilesRepositoryPath');
	
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='. basename($fileData['originalFileName']));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($absoluteFilesRepositoryPath . $fileData['fileName']));
	ob_clean();
	flush();
	readfile($absoluteFilesRepositoryPath . $fileData['fileName']);
	exit;

?>