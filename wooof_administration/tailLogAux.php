<?php
	require_once '../setup.inc.php';
	
    $__isAdminPage = true;
    
	$requestedAction='read';
	$pageLocation='1';
	$browserTitle='Tail Log Files';
	
	$timers = array();
	
	$wooofConfigCustomOptions['debug'] = array();
	
	
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
	
	
	if (isset($_GET['filename']) ) {
	  $l_filename = urldecode($_GET['filename']);
	  //echo json_encode(array("size" => 0, "data" => array($l_filename)));
	  //return;
	}
	else {
	  echo json_encode(array("size" => 0, "data" => array()));
	  return;	
	}
	
	$l_textType = isset($_GET['textType']);
	
	$tail = new Tail(
	  $l_filename,
	  $l_textType
	);
	
	/**
	 * We're getting an AJAX call
	 */
	if(isset($_GET['ajax']))  {
		echo $tail->getNewLines($_GET['lastsize'], $_GET['grep'], $_GET['invert']);
		die();
	}
	/**
	 * Regular GET/POST call, print out the GUI
	 */
	$tail->generateGUI();
	echo "Waiting...";
