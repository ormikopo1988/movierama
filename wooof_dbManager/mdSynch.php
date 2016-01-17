<?php

	$__isSiteBuilderPage = true;
  	$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
	$__actualPath = dirname($__actualPath);
  
	require_once $__actualPath . '/setup.inc.php';
  
	$requestedAction='edit';
	$pageLocation='1';
  
	
	$wo = new WOOOF();
	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
	
	$paramNames = array(
			'what', 
			'doTransaction',
			'param1', 'param2', 'param3', 'param4',  
			'param5', 'param6', 'param7', 'param8',  
	);
	$in = $wo->getMultipleGetPost( $paramNames );

	$what = $in['what'];
	$doTransaction = ( $in['doTransaction'] != '0' );	// save on by default
	
	// TODO: Form with dropdown for 'what', inputs for params
	//
	
	/*
	if ( !WOOOF::hasContent($what) ) {
		echo "Please specify a 'what' parameter...";
		echo "<br>updateApplicationAmount, calculateAmount";
		echo "<br>viewApplication, ";
		echo "<br>";
		die();
	}
	*/

	$tm = WOOOF::getCurrentDateTime();
	
	$errorMessage = '';
	$content = '';
	$content2 = '';
	
	$contentTop = "$what<br>";
	for( $i=1; $i<=8; $i++ ) {
		$contentTop .= "param$i: [". $in['param'.$i] . "] ";
	}
	$contentTop .= "<br>";
	$contentTop .= ( $doTransaction ? "Transaction is ON" : "No Transaction" );
	$contentTop .= '<br>';
	
	$contentTop .= '<a href="dbManager.php?tm='.$tm. '" class="normalTextCyan">Back to Main Page</a><br/>';
	
	$database = $wo->db->getDatabaseName();
	$dbString = "$database@" . $wo->getConfigurationFor('databaseHost')[$wo->getConfigurationFor('defaultDBIndex')];
	
	$contentTop .= $wo->sid . '<br>';
	$contentTop .= "<h3>Db: " . $dbString . '</h3>';
	$contentTop .= "<h3>".'MetaData Version: [' . WOOOF_MetaData::$version . ']'."</h3>";
	
	
	$tpl = NULL;
	$res = FALSE;
	
	ob_start();

	switch ( $what ) {
        // Return value (put always in $res) can be:
        //   false: call failed. Errors should be logged in wooof and/or special errors returned from call as return parameter
        //   array of HTML strings with keys: contentTop, content, message, errorMessage. Ready to pass to template.
        //   single HTML string: just copy to $tpl['content']
        //   anyother value: format as desired and copy to $tpl['content'].
        //   Assign directly to $tpl if you want smg custom.
        
    	// It is possible to get params with custom names.
    	// $paramNames = array( 'fromBookmarks', 'id', 'action', 'userId', 'objectType', 'objectId', 'htmlElementId' );
    	// $in = $wo->getMultipleGetPost( $paramNames );
	
        /* Example cases */

		case 'showWooofObject':
			$res = WOOOF_Util::do_dump($wo, 'wo');
			break;

		case 'selfUpgradeMetaData':
			$res = WOOOF_MetaData::selfUpgradeMetaData($wo, $database);
			break;

		case 'reverseEngineerObjects':
			// refresh, index, ascii, show
			$action = ( $wo->hasContent($in['param1']) ? $in['param1'] : 'refresh' );
			$excludeObjectsArray = null;
			if ( $wo->hasContent($in['param2']) ) {
				$excludeObjectsArray = explode(',', $in['param2'] );
			}
			$res = WOOOF_MetaData::reverseEngineerObjects( $wo, $database, $action, $excludeObjectsArray );
			break;
			
		case 'reverseEngineerObject':
			// refresh, index, ascii, show
			$action = ( $wo->hasContent($in['param2']) ? $in['param2'] : 'refresh' );
			$res = WOOOF_MetaData::reverseEngineerObject( $wo, $database, $in['param1'], $action );
			break;
				
		case 'exportMetaData':
			$justTheMetaData = ( $in['param1'] == 'jtmd' );
			$reverseEngineer = ( $in['param2'] == '0' );
			$justThisTable	 = $in['param3'];
			$res = WOOOF_MetaData::exportMetaData($wo, $justTheMetaData, $justThisTable, $reverseEngineer );
			break;
			
		case 'importAndUpdateMetaData':
	  		$res = WOOOF_MetaData::importMetaData( $wo, $in['param1'] ); 
  			if ( $res ) {
  				$res = WOOOF_MetaData::updateMetaDataFromOtherMetaData($wo, $ddl, $dml, $sqlPerTable, false, true); 
  			}
			break;
			
		case 'buildIndexes':
			$res = true;
			$res2 = WOOOF_MetaData::buildIndexesForAllTables($wo, $database, false );
			if ( $res2 !== FALSE ) {
				foreach( $res2 as $aTable => $aSQLArray ) {
					if ( count($aSQLArray) > 0 ) {
						$content2 .= '<br>' . implode("<br>", $aSQLArray);
					}	
				}
			}
			break;

		case 'buildIndex':
			$res = true;
			$execute = ( $in['param2'] == '1');
			$res2 = WOOOF_MetaData::buildIndexesForTable($wo, $database, $in['param1'], $execute );
			if ( $res2 !== FALSE ) {
				$content2 .= implode("<br>", $res2);
			}
			$content2 .= '<br><br>';
			break;

        		
		default:
			$tpl = array(
				'browserTitle'	=> "$what test",
				'content' 		=> $content,
				'errorMessage'	=> "Unknown 'what' [$what]",
				'message'		=> "Finished with '$what'.",
			);
	}	// which what
	
	$content = ob_get_contents() . 	$content2 . '<br>';
	ob_end_clean();
	
	// Tidy things up
	//
	if ( $tpl === NULL ) {
		if ( is_array($res) ) {
			$tpl = $res;
		}
		else {
			$content .= ( ($res===TRUE) ? "OK" : ($res===FALSE ? "****Failed! !!!! ****" : $res ) );
			$tpl = array(
				'contentTop'	=> $contentTop,
				'content' 		=> $content,
				'errorMessage'	=> $errorMessage . '<br>' . nl2br( $wo->getErrorsAsStringAndClear() ),
			);
		}
	}
    
	if ( !isset($tpl['browserTitle']) ) {
		$tpl['browserTitle'] = "$what test";
	}
	if ( !isset($tpl['message']) ) {
		$tpl['message'] = "Finished with '$what'.";
	}
	
	// Do not forget the transaction
	//
	if ( $doTransaction ) {
		if ( $res !== FALSE ) { $wo->db->commit(); }
		else { $wo->db->rollback(); }
	}
	
	require_once 'template.php';
		
	// UNREACHEABLE: As template exits at its end!
	
	

// End of file
