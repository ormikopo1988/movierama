<?php 

class Generic {
	private $wo;
	private $tableName;
	
	/***************************************************************************/
	/***************************************************************************/
	
	public function __construct( $tableName, WOOOF $wo=NULL )
	{
		$this->wo = ( $wo===NULL ? WOOOF::$instance : $wo );
		$this->tableName = $this->wo->cleanUserInput($tableName);
	}

	/***************************************************************************/
	/***************************************************************************/
	
	public function listRows( $in ) {
		$wo 		= $this->wo;
		$tableName	= $this->tableName;
		
		if ( !WOOOF::hasContent($tableName) ) {
			return
				array(
					'contentTop'	=> '<h1>Please select a table</h1>',
				);
			//$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1000 No tableName was provided!" );
			//return false;
		}
		
		$timers = array();
		
		$tplContentTop = '';
		$tplContent = '';
		$tplContentDataHeaders = '';
		$tplContentData = '';
		$tplErrorMessage = '';
		$tplMessage = '';
		
		$extraWhere 	= $wo->getFromArray($in, '_where' );
		$orderBy		= $wo->getFromArray($in, '_orderBy', 'id asc' );
		$fragmentsFile	= $wo->getFromArray($in, '_fragmentsFile', '' /*$tableName . '_html.php'*/ );
		$cudFormURL		= $wo->getFromArray($in, '_cudFormURL', "_genericEdit.php");
	
		$wo->debug( "Generic.show for '$tableName'. fragmentFile is [$fragmentsFile]. Form is [$cudFormURL]." );
		
		$table = new WOOOF_dataBaseTable($wo->db, $tableName );
		if ( !$table->constructedOk ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1010 Failed to init '$tableName'!" ); 
			return false; 
		 }
		
		 $wheres = array();
		// TODO: will not be available to all tables
		// $wheres['isDeleted!']='1';
		
		$noOfRowsArr = $table->getResult( $wheres, $orderBy, 0, 20, $extraWhere);
		if ( $noOfRowsArr === FALSE ) { 
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1020 Failed to get '$tableName'!" ); 
			return false; 
		}
		
		$tplContentTop .= "<h1>'$tableName' List</h1>";
		$tplContentTop .= "<h2>[where: $extraWhere] [orderBy: $orderBy]</h2>";

		$tplContent .=
<<<SOMEHTMLSTRING
		<a href="{$cudFormURL}?_action=insert&_tableName={$tableName}">Create new...</a>
		<form method="POST" action="_genericEdit.php">
			<input type="hidden" name="_tableName" value="$tableName">
			<input type="text" name="_id" size="10">
			<input class="btn btn-medium" type="submit" name="_viewButton" 		value="View" >
			<input class="btn btn-medium" type="submit" name="_editButton" 		value="Edit" >
			<input class="btn btn-medium" type="submit" name="_deleteButton" 	value="Delete" >
			<br>
		</form>
		<br>
		<form method="POST" action="_genericList.php">
			<input type="hidden" name="_tableName" value="$tableName">
			<input type="hidden" name="_fragmentsFile" value="$fragmentsFile">
			<input type="hidden" name="_cudFormURL" value="$cudFormURL">
								
			<label>Where</label><input type="text" name="_where" size="120" value="$extraWhere">
			<br>
			<label>Order by</label><input type="text" name="_orderBy" size="120" value="$orderBy">
			<br>
			<input class="btn btn-medium" type="submit" name="_searchButton" value="Search" >
		</form>
		<br><br>
SOMEHTMLSTRING;
		
		if ( WOOOF::hasContent($fragmentsFile) ) {
			$htmlParts = $wo->fetchApplicationFragment($fragmentsFile);
		}
		else {
			$htmlParts = array( 'htmlListHead' => '', 'htmlFragment' => '' );
		}
		
		$tplContentDataHeaders .= $htmlParts['htmlListHead'];
		
		$tplContentData .= $table->presentResults( $htmlParts['htmlFragment'] );
		if ( $tplContentData === FALSE ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1030 Failed to presentResults" ); 
			return false; 
		}
		
		$tplContent .= $tplContentDataHeaders . $tplContentData;
		
		return (
			array(
				'contentTop'			=> $tplContentTop,
				'content' 				=> $tplContent,		// includes contentData...
				'contentDataHeaders' 	=> $tplContentDataHeaders,
				'contentData'			=> $tplContentData,
				'errorMessage'			=> $tplErrorMessage,
				'message'				=> $tplMessage,
			) 
		);
		
	}	// show
	
	
	/***************************************************************************/
	/***************************************************************************/
	
	public function showMetaData( $in ) {
		$wo 		= $this->wo;
		$tableName	= $this->tableName;
	
		if ( !WOOOF::hasContent($tableName) ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1000 No value for tableName" );
			return false;
		}
		
		$sql = "
select * from __v_columnMetaData
where T_name = '$tableName'
order by C_ordering, C_name
";
		
		$res = $wo->db->query($sql);
		
		if ( $res === FALSE ) {
			return FALSE;
		}
		
		return print_r($res,true);
		
	}	// showMetaData
	
	/***************************************************************************/
	/***************************************************************************/
	
	public function showTablesAs( $how ) {
		$wo 		= $this->wo;

		// TODO: Implement select box ...
		
		$result = $wo->db->query('select tableName from __tableMetaData order by tableName');
		if ( $result === FALSE ) { $wo->handleShowStopperError( "1010 Failed to get tables" ); }
		
		$out = '';
		
		while ($t = $wo->db->fetchRow($result))
		{
			if (substr($t[0],0,2)=='__') {
				continue;
			}
			
			if ( $how == 'select' ) {
				$out .= ' <a href="_genericList.php?_tableName='. $t[0] .'" style="font-size:70%" >'.$t[0].'</a> &#8226; ';
			}
			else {
				$out .= ' <a href="_genericList.php?_tableName='. $t[0] .'" style="font-size:70%" >'.$t[0].'</a> &#8226; ';
			}
		}
		
		return $out;
		
	}	// tablesSelect
	
	/***************************************************************************/
	/***************************************************************************/
	
	public function cud( $in ) {
		$wo 		= $this->wo;
		$tableName	= $this->tableName;
		
		if ( !WOOOF::hasContent($tableName) ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1000 No value for tableName" );
			return false;
		}
		
		$tplContentTop		= '';
		$tplContent 		= '';
		$tplErrorMessage 	= '';
		$tplMessage = 		'';
		
		$wo->debug( "Generic.cud for '$tableName'." );
		
		$table = new WOOOF_dataBaseTable($wo->db, $tableName );
		if ( !$table->constructedOk ) { 
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1010 Failed to init '$tableName'!" );
			return false; 
		}
		
		// form html
		// $htmlFragment = '';
		
		$id			= $wo->getFromArray($in, '_id');
		$action 	= $wo->getFromArray($in, '_action');
		$do 		= ( $wo->getFromArray($in, '_doAction') == '1' );
		$redirectToPageAfterSuccess =  $wo->getFromArray($in, 'redirectToPageAfterSuccess', '');
		$showDetails = ( $wo->getFromArray($in, '_showDetails') == '1' );
		
		
		if ( !WOOOF::hasContent($action) ) {
			if ( WOOOF::hasContent( $wo->getFromArray($in, '_editButton') ) ) {
				$action = 'edit';
			}
			elseif ( WOOOF::hasContent( $wo->getFromArray($in, '_deleteButton') ) ) {
				$action = 'delete';
			}
			elseif ( WOOOF::hasContent( $wo->getFromArray($in, '_viewButton') ) ) {
				$action = 'view';
			}
		}
		
		$tplContentTop .= "<h1>'$tableName' Form</h1>";
		$tplContentTop .= "<h2>[action: $action] [id: $id] [do: $do]</h2>";
		
		// Checks...
		//
		
		if ( !in_array( $action, array( 'insert', 'edit', 'delete', 'view' ) ) ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1020 Unknown action [$action]" ); 
			return false;
		}
		
		// action is valid here...
		
		if ( !WOOOF::hasContent($id) and $action != 'insert' ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1030 id must be provided for action [$action]" );
			return false;
		}
		
		if (WOOOF::hasContent($id) and $action == 'insert' ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1040 id must not be provided for action [$action]" ); 
			return false;
		}
		
		if ( $action != 'view' ) {
			$tplContent .=
<<<SOMEHTMLSTRING
			<form method="post" action="_genericEdit.php" enctype="multipart/form-data">
				<input type="hidden" name="_tableName" 	value="$tableName">				
				<input type="hidden" name="_doAction" 	value="1">				
SOMEHTMLSTRING;
		}			
		
		$succ = false;

		$columnsToFill = array();
		for($counter=0; $counter<count($table->columns)/2; $counter++)
		{
			$metaData = $table->columns[$counter]->getColumnMetaData();
			$columnsToFill[] = $metaData['name'];
		}
		
		$submitButtonLabel = 'Save';
		
		$errorsString = '';
		
		// Do the action
		//
		
		if ( $do ) {
			if ( $action == 'insert' ) {
				$succ = $table->handleInsertFromPost($columnsToFill);
			}
			elseif ( $action == 'edit' ) {
				// $_POST['name'] .= 'x'; 	// demonstrate post-processing / alteration of input
				$succ = $table->updateRowFromPost($id, $columnsToFill, $errorsString);
			}
			elseif ( $action == 'delete' ) {
				$succ = $table->deleteRow($id);
			}
			else {
				$wo->handleShowStopperError( "1050 Unexpected action [$action]" );
			}
		
			if ( $succ !== FALSE ) {
				$wo->db->commit();
				// commit...
				// TODO: somehow display an ok message (at the page we will redirect)
				if ( $redirectToPageAfterSuccess != '' ) {
					header( 'Location: ' . $redirectToPageAfterSuccess );
					die();
				}
				$tplMessage .= 'Action perfomed ok.';
				
				if ( $action == 'insert' ) { $action = 'edit'; $id = $succ; } 
					
			}
			else {
				// $succ is FALSE
				$tplErrorMessage .= "Failed to $action: " . $wo->getLastErrorAsStringAndClear() . ' ' . $errorsString . '<br>';
				$wo->db->rollback();
			}	// $succ or not
		}
		
		
		// Draw the data
		//
		
		// TODO: Allow optional htmlFragment param
		
		$formBody = '';
		
		if ( $action == 'insert' ) {
			$formBody = $table->presentRowForInsert();
		}
		elseif ( $action == 'edit' ) {
			$formBody = $table->presentRowForUpdate($id);
		}
		elseif ( $action == 'view' ) {
			$formBody = $table->presentRowReadOnly($id);
		}
		elseif ( $action == 'delete' ) {
			if ( !$do or $succ === FALSE ) {
				$submitButtonLabel = 'Delete';
				$formBody = $table->presentRowReadOnly($id);
			}
		}
		else {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1060 Unknown action [$action]" );
			return FALSE;
		}
		
		if ( $formBody === FALSE  ) {
			$tplContent .= "Failed to perform $action!";
			$tplErrorMessage .= $wo->getLastErrorAsStringAndClear() . '<br>';
		}
		else {
			$tplContent .= $formBody;
		}
		
		// Finish up things
		//
		
		if ( $action != 'view' ) {
			// edit, insert, delete
			// $action and $id may change after their initial value.
			$tplContent .=
<<<SOMEHTMLSTRING
				<input type="hidden" name="_action" 	value="$action">				
				<input type="hidden" name="_id" 		value="$id">				
				<br>
				<input class="btn btn-medium" type="submit" name="submit" value="$submitButtonLabel" >
			</form>
SOMEHTMLSTRING;
		}			

		if ( $action == 'view' ) {
			if ( $showDetails ) {
				$detailsContent = $this->viewDetailsRecords( array( 'id' => $id) );
				if ( $detailsContent === FALSE ) {
					return FALSE;
				}
				$tplContent .= '<br><br>' . $detailsContent;
			}
			else {
				$tplContent .= 
					'<a href="_genericEdit.php?_tableName=' . $tableName .
					'&_id=' . $id .
					'&_action=' . $action .
					'&_showDetails=1' .
					'">Show Details Records...</a>' .
					'<br>'
				;				
			}
		}	// action is view
		
		$tplContent .=
<<<SOMEHTMLSTRING
		<br>
		<a href="_genericList.php?_tableName=$tableName">Goto '$tableName' list...</a>
SOMEHTMLSTRING;
		
		return (
			array(
				'contentTop'	=> $tplContentTop,
				'content' 		=> $tplContent,
				'errorMessage'	=> $tplErrorMessage,
				'message'		=> $tplMessage,
			)
		);
		
	}	// cud

	/***************************************************************************/
	/***************************************************************************/
	
	public function viewDetailsRecords( $in ) {
		$wo 		= $this->wo;
		$tableName	= $this->tableName;
		
		if ( !WOOOF::hasContent($tableName) ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1000 No value for tableName" );
			return false;
		}
		
		$id 	= $wo->getFromArray($in, 'id' );
	
		$wo->debug( "Generic.viewDetailsRecords for '$tableName' and Record [$id]" );

		$out = '';
		
		$sql = "
			select
			tmd.tableName, cmd.name, cmd.columnToStore
			from
			__columnMetaData cmd, __tableMetaData tmd
			where
			cmd.valuesTable = '$tableName' and
			tmd.id = cmd.tableId
		";

		$detailTables = $wo->db->query( $sql );
		if ( $detailTables === FALSE ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1080 Failed look for Detail Tables" );
			return false;
		}
		
		$out .= '<h3>Detail Records</h3>';
		
		while( ($aDetailTable = $wo->db->fetchAssoc($detailTables)) !== NULL ) {
			$sql = "select count(*) from {$aDetailTable['tableName']} where {$aDetailTable['name']} = '$id'";
			$aDetailsCount = $wo->db->query( $sql );
			if ( $aDetailsCount === FALSE ) {
				$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1085 Failed look for Detail Count" );
				return false;
			}
			$detailsCount = $wo->db->fetchRow($aDetailsCount)[0];
			$out .= '<h4>' . $aDetailTable['tableName'] . ' ' . $detailsCount . '</h4>';
		
			if ( $detailsCount > 0 ) {
				$detGen = new Generic($aDetailTable['tableName']);
				$detOutput = $detGen->listRows( array( '_where' => "{$aDetailTable['name']} = '$id'" ) );
				if ( $detOutput === FALSE ) {
					return FALSE;
				}
				$out .= $detOutput['contentData'];
				$out .= '<br>';
			}
		}	// foreach detail Table
		
		return $out;
		
	}	// viewDetailsRecords
	
	/***************************************************************************/
	/***************************************************************************/
	
	public static function showLinkToRecord( $tableName, $id, $action='view', $label='', $cssClass='' ) {
		$label = ( $label != '' ? $label : $id );
		
		$res = 
			'<a href="_genericEdit.php?_tableName=' . 
			$tableName . '&_id='.$id.'&_action=' . $action . '" ' .
			'class="'.$cssClass.'" >'.$label.'</a>'
		;
		
		return $res;
	}
		
	/***************************************************************************/
	/***************************************************************************/
	
	// TODO: Remove as it is subsumed in cud action=view
	// TODO: Remove as it is subsumed in cud action=view
	public function view( $in ) {
		$wo 		= $this->wo;
		$tableName	= $this->tableName;
		
		if ( !WOOOF::hasContent($tableName) ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1000 No value for tableName" );
			return false;
		}
		
		$showDetails = $wo->getFromArray( $in, '_showDetails', true );
		
		$tplContentTop		= '';
		$tplContent 		= '';
		$tplErrorMessage 	= '';
		$tplMessage			= '';
		
		$wo->debug( "Generic.show for '$tableName'." );
		
		$table = new WOOOF_dataBaseTable($wo->db, $tableName );
		if ( !$table->constructedOk ) { 
			$wo->log(WOOOF_loggingLevels::WOOOF_ERROR, "1010 Failed to init '$tableName'!" );
			return false; 
		}
		
		// form html
		// $htmlFragment = '';
		
		$id			= $wo->getFromArray($in, '_id');
		
		$tplContentTop .= "<h1>'$tableName' View for [$id]</h1>";
		
		
		// Show the data
		//
		
		$formBody = $table->presentRowReadOnly($id);
		
		if ( $formBody === FALSE  ) {
			$tplContent .= "Failed to perform 'view'!";
			$tplErrorMessage .= $wo->getLastErrorAsStringAndClear() . '<br>';
		}
		else {
			$tplContent .= $formBody;
		}
		
		return (
			array(
				'contentTop'	=> $tplContentTop,
				'content' 		=> $tplContent,
				'errorMessage'	=> $tplErrorMessage,
				'message'		=> $tplMessage,
			)
		);
		
	}	// view

}	// class Generic



/* End of Generic.php */