<?php

	// _genericList.php
	
	//$__isSiteBuilderPage = true;
	
	require_once '../setup.inc.php';
	
	$requestedAction='read';
	$pageLocation='1';
	$browserTitle='Generic List';
	
	$timers = array();
	
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
	
	$paramNames = array(
			'_tableName', 
			'_where', '_orderBy', 
			'_fragmentsFile', '_cudFormURL', 
	);
	$in = $wo->getMultipleGetPost( $paramNames );
	
	//$in['where'] = "region='Greece'";

	$tableName = $in['_tableName'];
	$table = new Generic( $tableName, $wo );
	
	$tpl = $table->listRows( $in );
	// if ( $tpl === FALSE ) { $wo->handleShowStopperError( print_r($errors,true) ); }
	if ( $tpl === FALSE ) {
		// $wo->handleShowStopperError( $error );
		$tpl = array(
				'browserTitle'	=> $browserTitle,
				'content' 		=> 'Sorry, smg went wrong',
				'errorMessage'	=> nl2br( $wo->getErrorsAsStringAndClear() ),
				'message'		=> '',
				'contentTop'	=> '',
		);
	}
	else {
		if ( !isset($tpl['browserTitle']) ) { $tpl['browserTitle'] = $tableName . ' ' . $browserTitle; }
	}
	
	$showTables = $table->showTablesAs('links');
	if ( $showTables !== FALSE ) {
		$tpl['contentTop'] .= $showTables . '<br><br>';
	}

	$wo->fetchApplicationFragment('structural/generic_template.php');
	
	// UNREACHEABLE: As generic_template.php exits at its end!
	
	

// End of file genericList.php
