<?php

	/* examples:
	 * ...../publicSite/tailLog.php?forceFromStart=1&session=cRw2kGwD34lNmMfFt492g4d6xX1UcChUxCnJtOyD
	 * ...../publicSite/tailLog.php?forceFromStart=1&errors
	 */
	require_once '../setup.inc.php';
	
    $__isAdminPage = true;
    
	$requestedAction='read';
	$pageLocation='1';
	$browserTitle='Tail Log';
	
	$timers = array();
	
	$wooofConfigCustomOptions['debug'] = array();
	
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
	
	if(isset($_GET['currentSession']) ) {
		$l_filename = $wo->getConfigurationFor('debugLogPath') . $wo->getConfigurationFor('siteName') . '_debugMessages_' . $wo->sid . '.log';

	}
	elseif (isset($_GET['session']) ) {
		$l_filename = $wo->getConfigurationFor('debugLogPath') . $wo->getConfigurationFor('siteName') . '_debugMessages_' . $_GET['session'] . '.log';
	}
	elseif (isset($_GET['errors']) ) {
		$l_filename = $wo->getConfigurationFor('debugLogPath') . $wo->getConfigurationFor('siteName') . '_errorMessages.log';
	}
	elseif (isset($_GET['filename']) ) {
		$l_filename = $_GET['filename']; // full path is expected
	}
	else {
		echo "ERROR: Either a 'session=....' or a 'errors' or a 'filename=...' is required. 'forceFromStart' is optional.";
		die('Aborting._');
	}

	$l_textType = true; //( isset($_GET['textType']);
	$l_forceFromStart = isset($_GET['forceFromStart']);;

	$tail = new Tail(
	  	$l_filename,
	  	//"C:/xampp/htdocs/_eeae_core/log/script_logs/acquire_log.html",
	  	$l_textType,
	  	$l_forceFromStart
	);

	$tail->generateGUI();
	echo "Waiting...";
