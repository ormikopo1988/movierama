<?php 

	// _genericEdit.php
	
	//$__isSiteBuilderPage = true;
	
	require_once '../setup.inc.php';
	
	
	$requestedAction='edit';
	$pageLocation='1';
	$browserTitle='Generic Edit Form';
	
	$timers = array();
	
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
	
	Timer::restart();
	
	$paramNames = array( 
		'_tableName',
		'_id', '_action',
		'_viewButton', '_editButton', '_deleteButton',
		'_doAction',
		'_showDetails',
		'fragmentsFile', 'listURL',
	);
	$in = $wo->getMultipleGetPost( $paramNames );
	
	$tableName = $in['_tableName'];

	$table = new Generic( $tableName, $wo );
	
	$tpl = $table->cud( $in );
	if ( $tpl === FALSE ) { 
		// $wo->handleShowStopperError( $error );
		$tpl = array(
				'browserTitle'	=> $browserTitle,
				'content' 		=> 'Sorry, smg went wrong',
				'errorMessage'	=> nl2br( $wo->getErrorsAsStringAndClear() ),
				'message'		=> '',
				);
	}
	else {
		if ( !isset($tpl['browserTitle']) ) { $tpl['browserTitle'] = $tableName . ' ' . $browserTitle; }
	}
	
	
	Timer::stopSave($timers, "_genericEdit.php for $tableName" );
	
	$wo->fetchApplicationFragment('structural/generic_template.php');

/* End of file genericEdit.php */

