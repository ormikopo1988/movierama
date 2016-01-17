<?php

	// _genericMetaData.php
    
    // CAUTION: TODO: Work in progress
	
	require_once '../setup.inc.php';
	
	$requestedAction='read';
	$pageLocation='1';
	$browserTitle='MetaData';

	$timers = array();
	
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
	
	$paramNames = array(
			'_tableName'
	);
	$in = $wo->getMultipleGetPost( $paramNames );
	
	//$in['where'] = "region='Greece'";

	$tableName = $in['_tableName'];

	$table = new Generic( $tableName, $wo );

    // requires view : __v_columnMetaData
	$res = $table->showMetaData( $in );
	// if ( $tpl === FALSE ) { $wo->handleShowStopperError( print_r($errors,true) ); }
	if ( $res === FALSE ) {
		// $wo->handleShowStopperError( $error );
		$tpl = array(
				'browserTitle'	=> $browserTitle,
				'content' 		=> 'Sorry, smg went wrong',
				'errorMessage'	=> nl2br( $wo->getErrorsAsStringAndClear() ),
				'message'		=> '',
		);
	}
	else {
		$tpl = array(
				'browserTitle'	=>  $tableName . ' ' . $browserTitle,
				'content' 		=> $res,
				'message'		=> '',
		);
	}
	
	
	$wo->fetchApplicationFragment('structural/generic_template.php');
	
	// UNREACHEABLE: As generic_template.php exits at its end!
	
	

// End of file _genericMetaData.php
