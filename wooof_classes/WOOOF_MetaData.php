<?php 

class WOOOF_MetaData {
	const _ECP = 'WMD';	// Error Code Prefix
	
	public static $version 				= 'A2.0.12.53';	// xx.Wooof.version
	
	// CAUTION:
	// In case of structural change(s) in metadata tables:
	// - Change the version above
	// - Update createTableMetaDataSQL and createColumnMetaDataSQL with the new metadata structures.
	// - Update getMDTColsExist with added columns that are used in the reverseEngineerObject()
	// - Handle new table meta data item in WOOOF_dataBaseTable class
	// - Handle new column meta data item in WOOOF_dataBaseColumn class (fromMetaRow, ... ) 
	// - With this file/class installed, run WOOOF_MetaData::selfUpgradeMetaData($wo, $database)
	// - and copy paste the produced sql statements.
	
	// A2:	__tableMetaData: added [dbEngine]. 
	//		__columnMetaData: added [indexParticipation], [colCollation].
	// A1:	__tableMetaData: added [isView], [viewDefinition].
	
	private static $versionOptionName	= 'WOOOF_METADATA_VERSION';
	private static $tableMetaDataColumnsArr     = null;
	private static $columnMetaDataColumnsArr    = null;
	
	
	/***************************************************************************/
	/***************************************************************************/
	
	private static
	function createTableMetaDataSQL( $tbl ) {	// __tableMetaData or __tableMetaDataNew
		$sql = <<<EOSQL
CREATE TABLE `$tbl` (
  `id` char(10) NOT NULL,
  `tableName` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `subtableDescription` varchar(255) NOT NULL,
  `orderingColumnForListings` varchar(255) NOT NULL,
  `appearsInAdminMenu` char(1) NOT NULL DEFAULT '0',
  `adminPresentation` int(10) NOT NULL DEFAULT '1',
  `adminItemsPerPage` int(10) NOT NULL DEFAULT '0',
  `adminListMarkingCondition` varchar(255) DEFAULT NULL,
  `adminListMarkedStyle` varchar(255) DEFAULT NULL,
  `groupedByTable` varchar(255) DEFAULT NULL,
  `remoteGroupColumn` varchar(255) DEFAULT NULL,
  `localGroupColumn` varchar(255) DEFAULT NULL,
  `tablesGroupedByThis` varchar(255) DEFAULT NULL,
  `hasActivationFlag` char(1) NOT NULL DEFAULT '0',
  `availableForSearching` char(1) NOT NULL DEFAULT '0',
  `hasGhostTable` char(1) NOT NULL DEFAULT '0',
  `hasDeletedColumn` char(1) NOT NULL DEFAULT '0',
  `hasEmbededPictures` char(1) NOT NULL DEFAULT '0',
  `columnForMultipleTemplates` varchar(255) NOT NULL,
  `showIdInAdminForms` char(1) NOT NULL DEFAULT '0',
  `showIdInAdminLists` char(1) NOT NULL DEFAULT '0',
  `isView` char(1) NOT NULL DEFAULT '0',
  `viewDefinition` longtext,
  `dbEngine` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY tableName (`tableName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
EOSQL;
		
		return $sql;
	}	// createTableMetaDataSQL

	
	/***************************************************************************/
	/***************************************************************************/
	
	private static
	function createColumnMetaDataSQL( $tbl ) {	// __columnMetaData or __columnMetaDataNew
		$sql = <<<EOSQL
CREATE TABLE `$tbl` (
  `id` char(10) NOT NULL,
  `tableId` char(10) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `length` varchar(255) DEFAULT NULL,
  `presentationType` int(10) NOT NULL DEFAULT '1',
  `isReadOnly` char(1) NOT NULL DEFAULT '0',
  `notNull` char(1) NOT NULL DEFAULT '0',
  `isInvisible` char(1) NOT NULL DEFAULT '1',
  `appearsInLists` char(1) NOT NULL DEFAULT '0',
  `isASearchableProperty` char(1) NOT NULL DEFAULT '0',
  `isReadOnlyAfterFirstUpdate` char(1) NOT NULL DEFAULT '0',
  `isForeignKey` char(1) NOT NULL DEFAULT '0',
  `presentationParameters` varchar(255) DEFAULT NULL,
  `valuesTable` varchar(255) DEFAULT NULL,
  `columnToShow` varchar(255) DEFAULT NULL,
  `columnToStore` varchar(255) DEFAULT NULL,
  `defaultValue` varchar(255) DEFAULT NULL,
  `orderingMirror` varchar(255) DEFAULT NULL,
  `searchingMirror` varchar(255) DEFAULT NULL,
  `resizeWidth` varchar(255) DEFAULT NULL,
  `resizeHeight` varchar(255) DEFAULT NULL,
  `thumbnailWidth` varchar(255) DEFAULT NULL,
  `thumbnailHeight` varchar(255) DEFAULT NULL,
  `midSizeWidth` varchar(255) DEFAULT NULL,
  `midSizeHeight` varchar(255) DEFAULT NULL,
  `thumbnailColumn` varchar(255) DEFAULT NULL,
  `midSizeColumn` varchar(255) DEFAULT NULL,
  `ordering` int(10) unsigned NOT NULL DEFAULT '0',
  `adminCSS` varchar(255) NOT NULL DEFAULT 'objectPropertyCellMedium',
  `indexParticipation` varchar(255) DEFAULT NULL,
  `colCollation`  varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tableId` (`tableId`),
  UNIQUE KEY tableIdColumnName (`tableId`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
EOSQL;
		
		return $sql;
	}	// createColumnMetaDataSQL
	
	
	/***************************************************************************/
	/***************************************************************************/
	
	private static
	function getMDTColsExist( WOOOF $wo, $p_database ) {
		// Find out whether certain columns exist or not in tableMetaData.
		// These are used in the inserts for putting the md tables themselves in the md,
		// so we need to exclude them if not present for avoiding a Catch-21
		// with dbs on older versions of WOOOF metadata.
		
		$sql = "
		SELECT
			a.col,
			(
				SELECT COUNT(*)
				FROM INFORMATION_SCHEMA.COLUMNS isc
				WHERE isc.table_schema = '$p_database'
				AND isc.table_name = '__tableMetaData'
				AND isc.column_name = a.col
			) doesExist
		from
		(select 'isView' col union select 'viewDefinition' union select 'dbEngine' ) a
		";

		$colsExistResult = $wo->db->query($sql);
		if ( $colsExistResult === FALSE ) { return FALSE; }

		$colsExistArray = array();
		while( ($aRes = $wo->fetchRow($colsExistResult)) ) {
			$colsExistArray[$aRes[0]] = ( $aRes[1] == '1' );
		}

		return $colsExistArray;
	}	// getMDTColsExist
	
	/***************************************************************************/
	/***************************************************************************/
	
	private static
	function getMDCColsExist( WOOOF $wo, $p_database ) {
		// Find out whether certain columns exist or not in columnMetaData.
		// These are used in the inserts for putting the md tables themselves in the md,
		// so we need to exclude them if not present for avoiding a Catch-21
		// with dbs on older versions of WOOOF metadata.
		
		$sql = "
		SELECT
			a.col,
			(
				SELECT COUNT(*)
				FROM INFORMATION_SCHEMA.COLUMNS isc
				WHERE isc.table_schema = '$p_database'
				AND isc.table_name = '__columnMetaData'
				AND isc.column_name = a.col
			) doesExist
		from
		(select 'indexParticipation' col union select 'colCollation' ) a
		";

		$colsExistResult = $wo->db->query($sql);
		if ( $colsExistResult === FALSE ) { return FALSE; }

		$colsExistArray = array();
		while( ($aRes = $wo->fetchRow($colsExistResult)) ) {
			$colsExistArray[$aRes[0]] = ( $aRes[1] == '1' );
		}

		return $colsExistArray;
	}	// getMDCColsExist
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @return boolean
	 */
	public static
	function versionWriteToDB( WOOOF $wo )
	{
		
		if ( $wo->getFromArray( $wo->db->getRefinedOptions(), self::$versionOptionName ) !== NULL ) {
			$sql = "update __options set optionValue = '" . self::$version ."' where optionName='". self::$versionOptionName. "'";
		}
		else {
			$sql = "
				insert into __options set 
					id = '" . $wo->db->getNewId('__options') . "',
					optionName='" . self::$versionOptionName . "',
					optionValue = '" . self::$version ."',
					optionDisplay = 4		
			";
		}
		
		return $wo->db->query($sql);
	}	// writeVersionToDB
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @return false|NULL|string
	 */
	public static
	function versionReadFromDB( WOOOF $wo, WOOOF_dataBase $db )
	{
		return $wo->getFromArray( $db->getRefinedOptions(), self::$versionOptionName, 'UNDEFINED' );
	}	// writeVersionToDB
	
	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function deleteObjectMetaData( WOOOF $wo, $p_objectName ) 
	{
		$sql = "
			select id
		    from __tableMetaData
		    where tablename = '$p_objectName'
		";

	    $objId = $wo->db->getSingleValueResult( $sql );
	    
	    if ( $objId === FALSE ) {return FALSE; }
		if ( $objId === NULL ) {
			$wo->logError(self::_ECP."0030 No object with name [$p_objectName] in __tableMetaData");
			return false;
		}
		
		$sql = "
			delete from __columnMetaData where tableId = '$objId'
		";
		$res = $wo->db->query($sql);
		if ( $res === FALSE ) { return FALSE; }
		
		$sql = "
		delete from __tableMetaData where id = '$objId'
		";
		$res = $wo->db->query($sql);
		if ( $res === FALSE ) { return FALSE; }
		
		return true;
	}	// deleteObjectMetaData
	
	
	// Update metaData from db schema info (reverse engineer) 	 
	
	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function reverseEngineerObjects( WOOOF $wo, $p_database, $p_action, $p_excludeObjectsArray = null ) 
	{
		echo '<h2>'.__CLASS__.'.'.__FUNCTION__.'</h2>';
		
		if ( !in_array($p_action, array('refresh', 'show', 'indexes', 'ascii') ) ) {
			$wo->logError(self::_ECP."7010 Bad action param value [$p_action]");
			return false;
		}
		
		
		$objectResults = $wo->db->query( 'show tables' );
		if ( $objectResults === FALSE ) { return false;	}
	
		while ($object = $wo->fetchRow($objectResults) ) {
			$name = $object[0];
			if (substr($name,0,2) == '__' ) {
				continue;
			}
			if ( is_array($p_excludeObjectsArray) && in_array($name, $p_excludeObjectsArray) ) {
				continue;
			}
	
			$succ = self::reverseEngineerObject( $wo, $p_database, $name, $p_action );
	
			// if ( !$succ ) { return false; }
			if ( $succ === FALSE ) {
				$wo->db->rollback();
				$wo->logError(self::_ECP."7020 Failed to [$p_action] for object [$name]" ); 
			}
			else {
				$wo->db->commit();
			}
		}
	
		return true;
	}	// reverseEngineerObjects
	
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * 
	 * @param WOOOF $wo
	 * @param string $p_database
	 * @param string $p_objectName
	 * @param string $action			// Optional, default 'refresh'. Other values: 'show', 'indexes'.
	 * @param string $p_objectNameFrom	// Optional, default ''. Different name to look in the db.
	 * @return boolean
	 */
	public static
	function reverseEngineerObject( WOOOF $wo, $p_database, $p_objectName, $action='refresh', $p_objectNameFrom='' ) 
	{
		// $action: refresh, show, indexes, ascii
		if ( !in_array($action, array('refresh', 'show', 'indexes', 'ascii') ) ) {
			$wo->logError(self::_ECP."7010 Bad action param value [$action]");
			return false;			
		}
		
		//$sql = "DESCRIBE ".$p_database.".".$p_objectName." ";
	
		//$p_objectName = 'v_applicationfk';  // just testing...
		
		echo '<h4>/*'.__CLASS__.'.'.__FUNCTION__.': ' .$p_objectName . ' */</h4>';
		
		if ( !$wo->hasContent($p_objectNameFrom) ) { 
			$p_objectNameFrom = $p_objectName;
		}
	
		$sql = "
		SELECT *
		FROM INFORMATION_SCHEMA.TABLES
		WHERE table_schema = '$p_database'
		AND table_name = '$p_objectNameFrom'
		";
		
		if(!($objectResult = $wo->db->query($sql))){
			return false;
		}
	
		if ( $action != 'indexes' && $action != 'ascii' ) {
			echo "<h5>$p_objectNameFrom</h5>";
		}
		
		$objectInfo =  $wo->fetchAssoc($objectResult);
		
		if ( $objectInfo === NULL ) {
			$wo->logError(self::_ECP."7100 [$p_objectNameFrom] does not exist in Database [$p_database]!!!");
			return FALSE;
		}
		
		if ( $action == 'ascii' ) {
			echo "SET FOREIGN_KEY_CHECKS=0;<br>";	
		}
	
		$objColNames = array();
		
		/*
		'TABLE_CATALOG' => string 'def' (length=3)
		'TABLE_SCHEMA' => string 'ait_mba' (length=7)
		'TABLE_NAME' => string '__bannedips' (length=11)
		'TABLE_TYPE' => string 'BASE TABLE' (length=10)  or VIEW
		'ENGINE' => string 'MyISAM' (length=6)
		'VERSION' => string '10' (length=2)
		'ROW_FORMAT' => string 'Fixed' (length=5)
		'TABLE_ROWS' => string '0' (length=1)
		'AVG_ROW_LENGTH' => string '0' (length=1)
		'DATA_LENGTH' => string '0' (length=1)
		'MAX_DATA_LENGTH' => string '24769797950537727' (length=17)
		'INDEX_LENGTH' => string '1024' (length=4)
		'DATA_FREE' => string '0' (length=1)
		'AUTO_INCREMENT' => null
		'CREATE_TIME' => string '2015-06-01 17:46:40' (length=19)
		'UPDATE_TIME' => string '2015-06-01 17:46:40' (length=19)
		'CHECK_TIME' => null
		'TABLE_COLLATION' => string 'utf8_general_ci' (length=15)
		'CHECKSUM' => null
		'CREATE_OPTIONS' => string '' (length=0)
		'TABLE_COMMENT' => string '' (length=0)
		*/
		
		if ( $action != 'indexes' && $action != 'ascii' ) {
			echo '<b>' . $objectInfo['TABLE_TYPE'] . '</b>';
		}
		
		$isView			= false;
		$viewDefinition	= '';
		if ( $objectInfo['TABLE_TYPE'] == 'VIEW' ) {
			$isView = true;
		}
		
		
		if ( $isView ) {
	    	$sql = "
		    	SELECT *
		      	FROM INFORMATION_SCHEMA.VIEWS
		      	WHERE table_schema = '$p_database'
		      	AND table_name = '$p_objectNameFrom'
		     ";
	      
		     if( ($viewResult = $wo->db->query($sql)) === FALSE ) { return FALSE; }
		     $objectInfo = $wo->fetchAssoc($viewResult);
		
		      /*
		      'TABLE_CATALOG' => string 'def' (length=3)
		      'TABLE_SCHEMA' => string 'ait_mba' (length=7)
		      'TABLE_NAME' => string 'v_applicationfk' (length=15)
		      'VIEW_DEFINITION' => string 'select `a`.`id` AS `id`,`a`.`isDeleted` AS `isDeleted`,`a`.`statusId` AS `statusId`,`a`.`submittedDate` AS `submittedDate`,`a`.`appTypeId` AS `appTypeId`,`a`.`amount` AS `amount`,`a`.`studentOrganisation` AS `studentOrganisation`,`a`.`companyName` AS `companyName`,`a`.`noOfApplicants` AS `noOfApplicants`,`a`.`invoiceName` AS `invoiceName`,`a`.`invoiceVAT` AS `invoiceVAT`,`a`.`invoiceProfesssion` AS `invoiceProfesssion`,`a`.`isIssueInvoice` AS `isIssueInvoice`,`a`.`invoiceDOY` AS `invoiceDOY`,`a`.`invoiceAdd'... (length=1164)
		      'CHECK_OPTION' => string 'NONE' (length=4)
		      'IS_UPDATABLE' => string 'YES' (length=3)
		      'DEFINER' => string 'root@localhost' (length=14)
		      'SECURITY_TYPE' => string 'DEFINER' (length=7)
		      'CHARACTER_SET_CLIENT' => string 'utf8' (length=4)
		      'COLLATION_CONNECTION' => string 'utf8_general_ci' (length=15)
		      */
		     
		     $viewDefinition = $objectInfo['VIEW_DEFINITION'];	
		     // CAUTION: from tables are prefixed with current schema. Non-portable.
		     // So:
		     $viewResult = $wo->db->query("show create view `$p_objectName`");
		     if ( $viewResult === FALSE ) { return FALSE; }
		     $viewDefinition = $wo->fetchAssoc($viewResult)['Create View'];
		     $viewDefinition = substr( $viewDefinition, strpos($viewDefinition, "` AS select `")+5 );
		     
		}	// get view def
		else {
			$viewDefinition = $objectInfo['VIEW_DEFINITION'] = '';
		}

		if ( $action == 'show' ) {
			echo WOOOF_Util::do_dump($objectInfo);
		}
		
		$mdTableColsExistArray = self::getMDTColsExist( $wo, $p_database);
		if ( $mdTableColsExistArray === FALSE ) { return FALSE; }
		
		$mdColumnColsExistArray = self::getMDCColsExist( $wo, $p_database );
		if ( $mdColumnColsExistArray === FALSE ) { return FALSE; }
		
		$sql = "
			select id
		    from __tableMetaData
		    where tablename = '$p_objectName'
		";

	    $objId = $wo->db->getSingleValueResult( $sql );
	    
	    if ( $objId === FALSE ) { return FALSE; }
		
		/*
		 id	char(10)
		 tableName	varchar(255)
		 description	varchar(255)
		 subtableDescription	varchar(255)
		 orderingColumnForListings
		*/

	    $objExisted = true;

	    if ( $objId === NULL ) {
	      	$objExisted = false;

	      	if ( $action != 'indexes' && $action != 'ascii' ) {
		      	echo '<br>Object is new!<br>';	
	      	}
	      	      	
	      	if ( $action == 'refresh' ) {
	      		echo 'Will add it.<br>';

		      	$objId = $wo->db->getNewId('__tableMetaData');

		      	$description = WOOOF_Util::fromCamelCase( $p_objectName );
		      	
		      	$sql = "
		      		insert into __tableMetaData 
		      		set id = '$objId', 
		      		tableName = '$p_objectName', 
		      		description = '$description',
		      		appearsInAdminMenu = '1'
		      	";

	      		if ( $mdTableColsExistArray['isView'] ) {
		      		$sql .= ", isView = '" . ( $isView ? '1' : 0 ) . "'";
		      	}
	      		if ( $mdTableColsExistArray['viewDefinition'] ) {
		      		$sql .= ", viewDefinition = '" . $wo->db->escape($viewDefinition) . "'";
		      	}
		      	if ( !$isView && $mdTableColsExistArray['dbEngine'] ) {
		      		$sql .= ", dbEngine = '" . $wo->db->escape($objectInfo['ENGINE']) . "'";
		      	}
		      	 
		      	//echo $sql.'<br>';
		      	$res = $wo->db->query( $sql );
		      	if ( $res === FALSE ) { return FALSE; }
		      	//$cmdTable = new WOOOF_dataBaseTable($wo->db, '__tableMetaData');
		      	//$objId = $cmdTable->insertFromArray(
		      	//	array( 'tableName' => $p_objectName )
		      	//);
		      	//if ( $objId === FALSE ) { return FALSE; }
	    	}
	    }
	    else {
	      	// object exists
	    	if ( $action != 'indexes' && $action != 'ascii' ) {
	    		echo '<br>Object metaData exist.<br>';	
	    	}

	    	if ( $action == 'refresh' ) {
	    		echo "Will refresh object's metaData.";

	    		$sql = "update __tableMetaData set ";
	    		$smgToUpdate = false;	
	    		
	    		if ( $mdTableColsExistArray['isView'] ) {
	    			$smgToUpdate = true;
	    			
					$sql .= "isView = " . ( $isView ? '1' : 0 ) . "	";
		    		
		    		if ( $isView ) {
						$sql .= ", 	viewDefinition = '" . $wo->db->escape($viewDefinition) . "'
			      		";
		    		}
	    		}	// isView column exists in metaData

	    		if ( !$isView && $mdTableColsExistArray['dbEngine'] ) {
	    			$sql .= ", dbEngine = '" . $wo->db->escape($objectInfo['ENGINE']) . "'";
	    			$smgToUpdate = true;
	    		}
	    		
	    		if ( $smgToUpdate ) {
		    		$sql .= " where id = '$objId'";
		    		
		    		//echo '<br>' . $sql.'<br>';
			      	$res = $wo->db->query( $sql );
			      	if ( $res === FALSE ) { return FALSE; }
	    		}
	    	}	// refresh
	    }	// object existed or is new
	    
	    if ( $action != 'indexes' && $action != 'ascii' ) {
		    echo '<h4>Columns</h4>';
	    }
	    
	    $sql = "
			SELECT 
				COLUMN_NAME, DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH, 
				CHARACTER_OCTET_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE, 
				COLUMN_DEFAULT, COLLATION_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_schema = '$p_database'
			AND table_name = '$p_objectNameFrom'
	    ";

	    if(!($columnsResult = $wo->db->query($sql))){
	      	return false;
	    }
	
	    $ordering = 0;
	
	    while ($columnInfo = $wo->fetchAssoc($columnsResult) ) {
	    	$ordering += 10;
	      	/*
	      	INFORMATION_SCHEMA Name	SHOW Name	Remarks
	      	TABLE_CATALOG	 	def
	      	TABLE_SCHEMA
	      	TABLE_NAME
	      	COLUMN_NAME	Field
	      	ORDINAL_POSITION	 	see notes
	      	COLUMN_DEFAULT	Default
	      	IS_NULLABLE	Null
	      	DATA_TYPE	Type
	      	CHARACTER_MAXIMUM_LENGTH	Type
	      	CHARACTER_OCTET_LENGTH
	      	NUMERIC_PRECISION	Type
	      	NUMERIC_SCALE	Type
	      	CHARACTER_SET_NAME
	      	COLLATION_NAME	Collation
	      	COLUMN_TYPE	Type	MySQL extension
	      	COLUMN_KEY	Key	MySQL extension
	      	EXTRA	Extra	MySQL extension
	      	PRIVILEGES	Privileges	MySQL extension
	      	COLUMN_COMMENT	Comment	MySQL extension
	      	*/

	    	$colName = $columnInfo['COLUMN_NAME'];
	    	$colType = $columnInfo['DATA_TYPE'];
	    	$colTypeWOOOF = WOOOF_dataBaseColumnTypes::getColumnTypeReverse($colType);
	    	
	    	if ( $colTypeWOOOF === FALSE ) {
	    		if ( $action == 'refresh' ) {
	    			$wo->logError(self::_ECP."0066 Column type [$colType] is non-WOOOF compatible.");
	    			return FALSE;
	    		} 
	    	}
	    	
	    	$objColNames[] = $colName;
	    	
	    	if ( $action == 'show' ) {
		    	echo WOOOF_Util::do_dump($columnInfo);
		    	continue;
	    	}
	    	
		
	    	if ( $action == 'indexes' && !$isView ) {
	    		if ( $colName != 'id' and strtolower(substr($colName,-2)) == 'id' ) {
	    			echo "alter table $p_objectName add index `$colName` (`$colName`);" . '<br>';
	    		}	
	    	}
	    	
	    	if ( $action == 'ascii' && !$isView ) {
	    		if ( $colType == 'char' && $columnInfo['COLLATION_NAME'] != 'ascii_bin' ) {
	    			echo 
	    				"alter table $p_objectName modify column `$colName` " . 
	    				$colType . '(' . $columnInfo['CHARACTER_MAXIMUM_LENGTH'] . ') ' .
	    				/*"CHARACTER SET 'ascii'*/ " COLLATE ascii_bin " .
	    				( $columnInfo['IS_NULLABLE'] == 'NO' ? 'NOT NULL' : 'NULL' ) .
	    				';' . '<br>'
	    			;
	    		}	
	    	}
	    	
	    	$colExisted	= false;
	    	$colId		= null;
	    	
	    	if ( $objExisted ) {
		    	$sql = "
					select id
			    	from __columnMetaData
			    	where tableId = '$objId' and name = '$colName'
				";
	
		    	$colId = $wo->db->getSingleValueResult( $sql );
		    	if ( $colId === FALSE ) {  return FALSE; }
		    	$colExisted = ( $colId !== NULL );
	    	}
	    			
	    	if ( $action == 'refresh' ) {
		    	$length = (
		      		in_array( $colType, array( 'char', 'varchar' ) ) ? $columnInfo['CHARACTER_MAXIMUM_LENGTH'] :
		      		( $columnInfo['NUMERIC_PRECISION'] )
		      	);
	      		if ( is_numeric($columnInfo['NUMERIC_SCALE']) && $columnInfo['NUMERIC_SCALE'] != '0' ) {
	      			$length .= ',' . $columnInfo['NUMERIC_SCALE'];
	      		}
	
	      		$noNull 		= ( $columnInfo['IS_NULLABLE']=='YES' ? 0 : 1 );
		      	$defaultValue	= $columnInfo['COLUMN_DEFAULT']; 
		      	$collation		= $columnInfo['COLLATION_NAME'];
	      	
    			if ( !$colExisted ) {
    				$colId = $wo->db->getNewId('__columnMetaData');
		      		
		      		$presentationType = WOOOF_columnPresentationTypes::textBox;
		      		if ( strtolower(substr($colName,-4)) == 'date' or 
		      			 strtolower(substr($colName,-4)) == 'time'  ) {
		      			$presentationType = WOOOF_columnPresentationTypes::dateAndTime;
		      		}
		      		elseif ( in_array( strtoupper($colType), array('TINYTEXT','TEXT','MEDIUMTEXT','LONGTEXT') ) ) {
		      			$presentationType = WOOOF_columnPresentationTypes::textArea;
		      		}
		      		
		      		$colDescr = WOOOF_Util::fromCamelCase($colName);
	
		   			/*
		   			$cmdTable = new WOOOF_dataBaseTable($wo->db, '__columnMetaData');
		   			$colId = $cmdTable->insertFromArray(
		   				array(
		      				'tableId' 	=> $objId,
		      				'name'		=> $columnInfo['COLUMN_NAME'],
		      				'type'		=> $columnInfo['DATA_TYPE'],
		      				'length' 	=> $length,
			    			'noNull' 	=> $noNull,
			    			'defaultValue' => $defaultValue,
						)
			    	);
			    	if ( $colId === FALSE ) { return FALSE; }
			    	*/

		      		$sql = "
		    			insert into __columnMetaData
		    			set
		    			id = '$colId',
		    			tableId = '$objId',
		    			name = '$colName',
		    			description = '$colDescr',
		    			type = '$colTypeWOOOF',
		    			length = '$length',
		    			notNull = $noNull,
		    			defaultValue = '$defaultValue',
		    			appearsInLists = '1',
		    			isInvisible = '0',
		    			ordering = $ordering,
		    			presentationType = '$presentationType'
		    		";
    				if ( $mdColumnColsExistArray['colCollation'] ) {
		      			$sql .= ", colCollation = '$collation' ";
		      		}
    			}	// col not existed
		      	else {
		      		// existed
		      		$sql = "
		    			update  __columnMetaData 
		    			set
		    				type = '$colTypeWOOOF',
		    				length = '$length',
		    				notNull = $noNull,
		    				defaultValue = '$defaultValue'
		    		";
		      		if ( $mdColumnColsExistArray['colCollation'] ) {
		      			$sql .= ", colCollation = '$collation' ";
		      		}
		      		
		      		$sql .= "
		    			where id = '$colId'
			    	";
	      		}	// col existed or not
		      	
	      		//echo $sql.'<br>';
	      		$res = $wo->db->query( $sql );
		    	if ( $res === FALSE ) { return FALSE; }
	      	}	// refresh
	      	
	      	/*
	    	id	char(10)
			tableId	char(10)
			name	varchar(255)
			description	varchar(255)
			type	varchar(255)
			length	varchar(255) NULL
			presentationType	int(10) [1]
			isReadOnly	char(1) [0]
			notNull
		 	*/
	      	
	    }	// foreach column of object
	
	    
	    if ( $objExisted ) { 
	    	if ( $action == 'refresh' ) {
		    	$sql = "select id, name from __columnMetaData where tableId='$objId'";
		    	if ( ( $res = $wo->db->query($sql) ) === FALSE ) { return FALSE; }
		    	while( ( $aMDCol = $wo->fetchAssoc($res) ) != NULL ) {
		    		if ( !in_array($aMDCol['name'], $objColNames) ) {
		    			echo "need to drop column {$aMDCol['name']} from metadata<br>";
		    			$sql = "delete from __columnMetaData where id='{$aMDCol['id']}'";
		      			//echo $sql . '<br>';
		    			if ( $wo->db->query($sql) === FALSE ) { return FALSE; }
		    		}
		    	}	// foreach dropped column
		    }	// refresh
	    }	// objExisted
	    
		if ( $action == 'ascii' ) {
			echo "SET FOREIGN_KEY_CHECKS=1;<br>";	
		}
		
	    return true;
	  
	}	// reverseEngineerObject
	

	/***************************************************************************/
	/***************************************************************************/
	
	// TODO: Just started...
	// Create a view based on a table and its foreign keys.
	public static
	function createViewFor( WOOOF $wo, $p_database, $p_objectName ) {
	
		$t = new WOOOF_dataBaseTable($wo->db, $p_objectName);
		if ( !$t->constructedOk ) { return FALSE; }
		
		if ( $t->getIsView() == '1' ) {
			return '';
		}
		
		$sql = "
			select 
";
		
		return 'What?';
	}	// 
	
	/***************************************************************************/
	/***************************************************************************/
		  
	// Update metaData from other metaData 	 
	
	/***************************************************************************/
	/***************************************************************************/
	private static
	function tableMetaDataColumns(WOOOF $wo, $forceReselect=false) {
		
	  	if ( self::$tableMetaDataColumnsArr !== NULL and !$forceReselect ) {
	  		return self::$tableMetaDataColumnsArr;
	  	} 
	  	
	  	//$tmp = $wo->getConfigurationFor('databaseName');
  		//$database = $tmp[0];
  		$database = $wo->db->getDataBaseName();
	  	
	  	$sql = "
	  		SELECT COLUMN_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_schema = '$database'
			AND table_name = '__tableMetaData'
	  	";
	  	
	  	$succ = $wo->db->getResultByQuery( $sql, true, false );
	  	if ( $succ === FALSE ) { return FALSE; }
	  	
	  	self::$tableMetaDataColumnsArr = array();
	  	foreach( $wo->db->resultRows as $aRow ) {
	  		if ( $aRow['COLUMN_NAME'] == 'id' ) { continue; }
	  		self::$tableMetaDataColumnsArr[] = $aRow['COLUMN_NAME'];
	  	}
	  	
	  	return self::$tableMetaDataColumnsArr;
	  	
	  	/*
	  	return array(
	  			'tableName',
	  			'orderingColumnForListings',
	  			'appearsInAdminMenu',
	  			'adminPresentation',
	  			'adminItemsPerPage',
	  			'adminListMarkingCondition',
	  			'adminListMarkedStyle',
	  			'groupedByTable',
	  			'remoteGroupColumn',
	  			'localGroupColumn',
	  			'tablesGroupedByThis',
	  			'hasActivationFlag',
	  			'availableForSearching',
	  			'hasGhostTable',
	  			'hasDeletedColumn',
	  			'description',
	  			'columnForMultipleTemplates',
	  			'subtableDescription',
	  			'hasEmbededPictures',
	  			'showIdInAdminForms',
	  			'showIdInAdminLists',
	  			'isView',
	  			'viewDefinition',
	  	);
	  	*/
	}	// tableMetaDataColumns
	  	
	private static
	function columnMetaDataColumns(WOOOF $wo, $forceReselect=false) {
		
		if ( self::$columnMetaDataColumnsArr !== NULL and !$forceReselect ) {
	  		return self::$columnMetaDataColumnsArr;
	  	} 
	  	
	  	$tmp = $wo->getConfigurationFor('databaseName');
  		$database = $tmp[0];
	  	
	  	$sql = "
	  		SELECT COLUMN_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_schema = '$database'
			AND table_name = '__columnMetaData'
	  	";
	  	
	  	$succ = $wo->db->getResultByQuery( $sql, true, false );
	  	if ( $succ === FALSE ) { return FALSE; }
	  	
		self::$columnMetaDataColumnsArr = array();
	  	foreach( $wo->db->resultRows as $aRow ) {
	  		if ( $aRow['COLUMN_NAME'] == 'id' or $aRow['COLUMN_NAME'] == 'tableId'  ) { continue; }
	  		self::$columnMetaDataColumnsArr[] = $aRow['COLUMN_NAME'];
	  	}
	  	
	  	return self::$columnMetaDataColumnsArr;
	  	
	  	
	  	/*
	  	return array(
	  			'tableId',
	  			'name',
	  			'type',
	  			'length',
	  			'presentationType',
	  			'isReadOnly',
	  			'notNull',
	  			'isInvisible',
	  			'appearsInLists',
	  			'isASearchableProperty',
	  			'isReadOnlyAfterFirstUpdate',
	  			'isForeignKey',
	  			'presentationParameters',
	  			'valuesTable',
	  			'columnToShow',
	  			'columnToStore',
	  			'defaultValue',
	  			'orderingMirror',
	  			'searchingMirror',
	  			'description',
	  			'resizeWidth',
	  			'resizeHeight',
	  			'thumbnailWidth',
	  			'thumbnailHeight',
	  			'thumbnailColumn',
	  			'midSizeWidth',
	  			'midSizeHeight',
	  			'midSizeColumn',
	  			'ordering',
	  			'adminCSS',
	  	);
	  	*/
	}	// columnMetaDataColumns
	  
	private static
	function createAnyMetaDataTables( WOOOF $wo, $new = '' ) {
		$succ = $wo->db->query(self::createTableMetaDataSQL("__tableMetaData$new"));
		if ( !$succ ) { return false; }

		$succ = $wo->db->query(self::createColumnMetaDataSQL("__columnMetaData$new"));
		if ( !$succ ) { return false; }
		
		return true;
	}	// createAnyMetaDataTables
	  
	public static
	function createMetaDataTables( WOOOF $wo ) {
		return self::createAnyMetaDataTables( $wo, '' );
	}	// createMetaDataTables
		 
	public static
	function createNewMetaDataTables( WOOOF $wo ) {
		$succ = $wo->db->query( 'drop table if exists __tableMetaDataNew' );
		if ( $succ === FALSE ) { return FALSE; }
		$succ = $wo->db->query( 'drop table if exists __columnMetaDataNew' );
		if ( $succ === FALSE ) { return FALSE; }
		
		return self::createAnyMetaDataTables( $wo, 'New' );
	}	// createNewMetaDataTables
			
	
	// DDLs for db
	//
	  
	private static
	function ddlCreateTable( $tmdRow, $cmdRowsArray ) {
		if ( $tmdRow['isView'] == '0' ) {
			$res = "create table `" . $tmdRow['tableName'] . '` ( ';
		  	$res .= "
  				`id` char(10) COLLATE ascii_bin NOT NULL,
  				`isDeleted` char(1) NOT NULL DEFAULT '0',
		  	";
		  	
		  	foreach( $cmdRowsArray as $aCMD ) {
		  		$res .= '`' . $aCMD['name'] . '` ' . self::columnSpec($aCMD) .', '; 
		  	}
		  	//$res = substr($res, 0, -2) . ')';
		  	$res .= "PRIMARY KEY (`id`)" . " )";
		  	
		  	if ( isset($tmdRow['dbEngine']) && $tmdRow['dbEngine'] != '' ) {
			  	$res .= " ENGINE=" . $tmdRow['dbEngine'] . " ";
		  	}
		  	$res .=  " DEFAULT CHARSET=utf8 ";
	  	}
	  	else {
	  		$res = "create or replace view `" . $tmdRow['tableName'] . '` as ' .$tmdRow['viewDefinition'];
	  	}
	  	
		return $res . ';';
	}
	  
	private static
	function ddlDropTable( $tmdRow ) {
		// sql commented for avoiding accidental drops! 
	  	$res = "/*drop table `" . $tmdRow['tableName'] . '` */';
	  	return $res . ';';
	}
	  
	private static
	function ddlCreateColumn( $tmdRow, $cmdRow ) {
	  	$res = "alter table `" . $tmdRow['tableName'] . '` add column `' . $cmdRow['name'] . '` ' . self::columnSpec($cmdRow);
	  	return $res . ';';
	}
	  
	private static
	function ddlAlterColumn( $tmdRow, $cmdRow ) {
	  	$res = "alter table `" . $tmdRow['tableName'] . '` modify column `' . $cmdRow['name'] . '` ' . self::columnSpec($cmdRow);
	  	return $res . ';';
	}
	  
	private static
	function ddlDropColumn( $tmdRow, $cmdRow ) {
	  	$res = "alter table `" . $tmdRow['tableName'] . '` drop column ' . $cmdRow['name'];
	  	return $res . ';';
	}
	  
	private static
	function columnSpec( $cmdRow ) {
	  	$wo = WOOOF::$instance;
	  	
	  	$res = WOOOF_dataBaseColumnTypes::getColumnTypeLiteral($cmdRow['type']);
	  	if ($cmdRow['length']!='') {
	  		$res .= '('.$cmdRow['length'] .')';
	  	}
	  	
		if ( $wo->hasContent($wo->getFromArray($cmdRow,'colCollation')) ) {
	  		$res .= " COLLATE " .  $cmdRow['colCollation'];
	  	} 
	  	
	  	if ( $wo->hasContent($cmdRow['defaultValue']) ) {
	  		$res .= ' DEFAULT \''. $cmdRow['defaultValue'] .'\'';
	  	}
	  	
	  	if ($cmdRow['notNull'] == '1') {
	  		$res .= ' NOT NULL ';
	  	}
	  	
	  	
	  	return $res;
	  	
	}	// columnSpec
	
	// DMLs for metadata
	//
	  
	private static
	function mdAddTable( $tmdRow, &$id ) {
	  	$wo = WOOOF::$instance;
	  	$id = $wo->db->getNewId('__tableMetaData', $tmdRow['id'] );
	  	$res = "insert into __tableMetaData set id='" . $id . "', ";
	  	foreach( self::tableMetaDataColumns($wo) as $aCol ) {
	  		$res .= $aCol . "='" . $wo->db->escape($tmdRow[$aCol]) . "', ";
	  	}
	  	$res = substr( $res, 0, strlen($res)-2 );
	  	return $res . ';';
	}
	  
	private static
	function mdDeleteTable( $tmdRow ) {
	  	$res = "delete from __tableMetaData where id = '" . $tmdRow['id'] . "'";
	  	return $res . ';';
	}
	  
	private static
	function mdUpdateTable( $tmdRow, $tableId ) {
	  	$wo = WOOOF::$instance;
	  	$res = 'update __tableMetaData set ';
	  	foreach( self::tableMetaDataColumns($wo) as $aCol ) {
	  		$res .= $aCol . "='" . $wo->db->escape($tmdRow[$aCol]) . "', ";
	  	}
	  	$res = substr( $res, 0, strlen($res)-2 );
	  	$res .= "where id='$tableId'";
	  	return $res . ';';
	}
	  
	private static
	function mdAddColumn( $cmdRow ) {
	  	$wo = WOOOF::$instance;
	  	$id = $wo->db->getNewId('__columnMetaData', $cmdRow['id'] );
	  	$res = "insert into __columnMetaData set id='$id', tableId='{$cmdRow['tableId']}', ";
	  	foreach( self::columnMetaDataColumns($wo) as $aCol ) {
	  		$res .= $aCol . "='" . $wo->db->escape($cmdRow[$aCol]) . "', ";
	  	}
	  	$res = substr( $res, 0, strlen($res)-2 );
	  	return $res . ';';
	}
	  
	private static
	function mdDeleteColumn( $cmdRow ) {
	  	$res = "delete from __columnMetaData where id = '" . $cmdRow['id'] . "'";
	  	return $res . ';';
	}
	  
	private static
	function mdDeleteAllTableColumns( $tmdRow ) {
	  	$res = "delete from __columnMetaData where tableId = '" . $tmdRow['id'] . "'";
	  	return $res . ';';
	}
	 
	private static
	function mdUpdateColumn( $cmdRow ) {
		$wo = WOOOF::$instance;
	  	$res = "update __columnMetaData set ";
	  	foreach( self::columnMetaDataColumns($wo) as $aCol ) {
	  		$res .= $aCol . "='" . $wo->db->escape($cmdRow[$aCol]) . "', ";
	  	}
	  	$res = substr( $res, 0, strlen($res)-2 );
	  	$res .= "where id='" . $cmdRow['id'] . "'";
	  	return $res . ';';
	}
	  
	// Compare routines
	//
	  
	private static
	function tableMetaDataDiffer( $row1, $row2 ) {
	  	$wo = WOOOF::$instance;
	  	foreach( self::tableMetaDataColumns($wo) as $aCol ) {
	  		if( $row1[$aCol] != $row2[$aCol] ) {
	  			//echo " ($aCol: [{$row1[$aCol]}] [{$row2[$aCol]}]) ";
	  			return true;
	  		}
	  	}
	  	return false;
	}
	  
	private static
	function columnMetaDataDiffer( $row1, $row2 ) {
	  	$wo = WOOOF::$instance;
	  	foreach( self::columnMetaDataColumns($wo) as $aCol ) {
	  		if( $row1[$aCol] != $row2[$aCol] ) {
	  			return true;
	  		}
	  	}
	  	return false;
	}
	  
	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function updateMetaDataFromOtherMetaData( WOOOF $wo, &$po_ddl, &$po_dml, &$po_sqlPerTable, $dropTables=false, $dropColumns=false ) 
	{
		echo '<h2>'.__CLASS__.'.'.__FUNCTION__.'</h2>';
		
		// Bring views last, to help ensure that relevant tables are created prior to them.
		$tmdNewResults = $wo->db->query( "select * from __tableMetaDataNew order by isView, concat(field( tableName, '__columnMetaData', '__tableMetaData' ), tableName) desc");
		if ( $tmdNewResults === FALSE ) { return false; }
		
		if ( $tmdNewResults->num_rows == 0  ) {
			$wo->logError(self::_ECP.'0003 Nothnig found inside__tableMetaDataNew');
			return false;
		}
		
		$tmdCurrentTestResult = $wo->db->query( "select count(*) from __tableMetaData order by tableName");
		if ( $tmdCurrentTestResult === FALSE ) { 
			return false; 
		}
		
		// 
		$tmp = $wo->getConfigurationFor('databaseName');
  		$database = $tmp[0];
  		
  		$succ = self::reverseEngineerObject($wo, $database, '__tableMetaData');
		if ( !$succ ) { return FALSE; }
		$succ = self::reverseEngineerObject($wo, $database, '__columnMetaData');
		if ( !$succ ) { return FALSE; }
		
		//$newTables = array();
		
		$ddl = array();  // alter, create, drop ...
		$dml = array();  // insert/update/delete on __MetaData
		$sqlPerTable = array();
		
		$noOfNewTables = 0;
		$noOfDroppedTables = 0;
		$noOfNewColumns = 0;        // excluding cols of new tables
		$noOfDroppedColumns = 0;    // not from dropped tables
		
		$tableIsNew = null;
		
		echo "<h1>Sync this db's [$database] WOOOF metadata with imported WOOOF metadata</h1>";
		echo '<pre><code>';
		
		$resetAfterColumnMetaData = false;
		
		while ( ($tmdNewRow = $wo->db->fetchAssoc($tmdNewResults)) !== NULL ) {
			echo $tmdNewRow['id'] . ' ' . str_pad($tmdNewRow['tableName'],40) . ': ';
		
			if ( $resetAfterColumnMetaData ) {
				// (In case commands are executed) Execute immediately in order to use possible newly created columns in comparisons etc.
				if ( count($ddl) > 0 && $wo->db->queryMultiple($ddl) === FALSE ) { return FALSE; }
				if ( count($dml) > 0 && $wo->db->queryMultiple($dml) === FALSE ) { return FALSE; }
				$wo->db->commit();
				$ddl = array();
				$dml = array();
							
				$tmp = self::tableMetaDataColumns($wo, true);
				$tmp = self::columnMetaDataColumns($wo, true);
				$resetAfterColumnMetaData = false;
			}
			
			$isView = ( $tmdNewRow['isView'] == '1' );
			
			//$newTables[$tmdNewRow['id']] = 1;
		
			$tmdOldResult = $wo->db->query( "select * from __tableMetaData where tableName='{$tmdNewRow['tableName']}'");
			if ( $tmdOldResult === FALSE ) { return false; }
		
			$tmdOldRow = $wo->db->fetchAssoc($tmdOldResult);
		
			$tableId = 'x';
			
			if ( $tmdOldRow === NULL ) {
				$tableIsNew = true;
				$noOfNewTables++;
				echo ' is new!'  . str_repeat('+', 60);;
				// insert on __tableMetaData
				$sqlPerTable[$tmdNewRow['tableName']][] = $dml[] = self::mdAddTable($tmdNewRow, $tableId);
				// insert on __columnMetaData (will happen below at the columns section)
				// create table/view (will happen below at the columns section)
			}
			else {
				$tableIsNew = false;
				$tableId	= $tmdOldRow['id'];
				echo ' already exists. ';
		
				if ( self::tableMetaDataDiffer( $tmdNewRow, $tmdOldRow ) ) {
					echo 'But has changed. ';
					// update __tableMetaData
					$sqlPerTable[$tmdNewRow['tableName']][] = $dml[] = self::mdUpdateTable($tmdNewRow, $tableId);
					if ( $isView ) {
						$sqlPerTable[$tmdNewRow['tableName']][] = $ddl[] =  self::ddlCreateTable($tmdNewRow, []);
					}
				}
			}
		
			$cmdNewResults = $wo->db->query( "select * from __columnMetaDataNew where tableId='{$tmdNewRow['id']}' order by ordering, name");
			if ( $cmdNewResults === FALSE ) { return false; }
		
			$cmdRowsArray = array();
		
			while ( ($cmdNewRow = $wo->db->fetchAssoc($cmdNewResults)) !== NULL ) {
				$cmdOldResult = $wo->db->query( "select * from __columnMetaData where name='{$cmdNewRow['name']}' and tableId='$tableId' ");
				if ( $cmdOldResult === FALSE ) { return false; }
		
				if ( $cmdNewRow['name'] == 'id' or $cmdNewRow['name'] == 'isDeleted' ) {
					// no Metadata for these columns (as in dbManager)
					continue;
				}
				
				$cmdRowsArray[] = $cmdNewRow;
				
				$cmdNewRow['tableId'] = $tableId;	// for brevity, use this side's tableId
				
				$cmdOldRow = $wo->db->fetchAssoc($cmdOldResult);
		
				if ( $cmdOldRow === NULL ) {
					if ( !$tableIsNew ) {
						$noOfNewColumns++;
						echo '[' . $cmdNewRow['id'] . ' ' . str_pad($cmdNewRow['name'],20) . ': ';
						echo ' is new! ' . '] ';
					}
					// insert on __columnMetaData
					$sqlPerTable[$tmdNewRow['tableName']][] = $dml[] = self::mdAddColumn($cmdNewRow);
		
					// alter table add column (if !$tableIsNew)
					if ( !$tableIsNew and !$isView ) {
						$sqlPerTable[$tmdNewRow['tableName']][] = $ddl[] = self::ddlCreateColumn($tmdNewRow, $cmdNewRow);
					}
				}
				else {
					//echo ' already exists.';
					if ( self::columnMetaDataDiffer( $cmdNewRow, $cmdOldRow ) ) {
						$cmdNewRow['id'] = $cmdOldRow['id'];
						// update __columnMetaData
						$sqlPerTable[$tmdNewRow['tableName']][] = $dml[] = self::mdUpdateColumn($cmdNewRow);
		
						// alter table modify column
						if ( !$isView ) {
							$sqlPerTable[$tmdNewRow['tableName']][] = $ddl[] = self::ddlAlterColumn( $tmdNewRow, $cmdNewRow );
						}
					}
				}
		
			}   // foreach column
		
			if ( $tableIsNew ) {
				// create table ... all columns
				$sqlPerTable[$tmdNewRow['tableName']][] = $ddl[] =  self::ddlCreateTable($tmdNewRow, $cmdRowsArray);

				// to create indexes from metaData, the new metaData must be put in the DB first!
			}	// tableIsNew
		
		
			if ( !$tableIsNew ) {
				if ( $dropColumns ) {
					//$newMDTableIdString = (
					//	( $tmdOldRow === NULL ? '' : " n.tableName='{$tmdOldRow['tableName']}' and " )
					//);
					$sql = "
						select
						    o.*
						from
						    __columnMetaData o,
						    __tableMetaData ot 
						where
						    ot.id = o.tableId and
						    o.name not in ('id', 'isDeleted') and
						    ot.tableName='{$tmdOldRow['tableName']}' and 
						    not exists ( 
						        select 1 
						        from __columnMetaDataNew n, __tableMetaDataNew nt
						        where
						            nt.id = n.tableId and
						            nt.tableName = ot.tableName and  
						            n.name = o.name 
						    ) 
						order by ordering
				";
					//$cmdDeletedResults = $wo->db->query( "select * from __columnMetaData o where tableName='{$tmdOldRow['tableName']}' and not exists ( select 1 from __columnMetaDataNew n where $newMDTableIdString n.name = o.name ) order by ordering");
					$cmdDeletedResults = $wo->db->query( $sql );
					if ( $cmdDeletedResults === FALSE ) { return false; }
			
					while ( ($cmdDeletedRow = $wo->db->fetchAssoc($cmdDeletedResults)) !== NULL ) {
						$noOfDroppedColumns++;
						echo ' *** ' . $cmdDeletedRow['id'] . ' ' . $cmdDeletedRow['name'] . ' is deleted! *** ';
			
						// delete from __columnMetaData
						$sqlPerTable[$tmdNewRow['tableName']][] = $dml[] =self::mdDeleteColumn($cmdDeletedRow);
			
						// drop column
						if ( !$isView ) {
							$sqlPerTable[$tmdNewRow['tableName']][] = $ddl[] = self::ddlDropColumn($tmdNewRow, $cmdDeletedRow);
						}
					}	// deleted columns
				}	// drop columns
			}   // not new table
		
			echo '<br>';
		
			if ( $tmdNewRow['tableName'] == '__columnMetaData' ) {
				$resetAfterColumnMetaData = true;
			}
		}   // foreach new tmd
		
		echo '<br>';
		
		if ( $dropTables ) {
			$tmdDeletedResults = $wo->db->query( "select * from __tableMetaData o where not exists ( select 1 from __tableMetaDataNew n where n.tableName = o.tableName ) order by tableName");
			if ( $tmdDeletedResults === FALSE ) { return false; }
			
			while ( ($tmdDeletedRow = $wo->db->fetchAssoc($tmdDeletedResults)) !== NULL ) {
				$noOfDroppedTables++;
				echo $tmdDeletedRow['id'] . ' ' . $tmdDeletedRow['tableName'] . ': is missing!<br>';
			
				// delete from __columnMetaData
				$sqlPerTable[$tmdDeletedRow['tableName']][] = $dml[] =self::mdDeleteAllTableColumns($tmdDeletedRow);
			
				// delete from __tableMetaData
				$sqlPerTable[$tmdDeletedRow['tableName']][] = $dml[] =self::mdDeleteTable($tmdDeletedRow);
			
				$sqlPerTable[$tmdDeletedRow['tableName']][] = $ddl[] = self::ddlDropTable($tmdDeletedRow);
			}	// foreach deleted table
		}	// drop tables		
		
		echo '<br><br>';
		
		//echo '<h2>/*DDLs*/</h2>';
		
		echo '<h2>/* DDLs and DMLs for all Tables/Views*/</h2>';
		
		echo '<textarea rows="50" cols="150">';
		echo '/***********DDLs************/'.PHP_EOL.PHP_EOL;
		echo htmlentities(implode(PHP_EOL,$ddl));
		echo PHP_EOL.PHP_EOL.'/***********DMLs************/'.PHP_EOL.PHP_EOL;
		echo htmlentities(implode(PHP_EOL,$dml));
		echo '</textarea>';
		
		
		//echo implode( '<br>', $ddl );
		//echo '<br><br>';
		
		//echo '<h2>/*DMLs*/</h2>';
		//echo implode( '<br>', $dml );
		
		echo '<br><br>';
		
		echo '<h2>/* Per Table/View */</h2>';
		foreach( $sqlPerTable as $aTableName => $anSQLArray ) {
			echo "<h3>/*$aTableName*/</h3>";
			echo '<textarea rows="10" cols="150">';
			echo htmlentities(implode(PHP_EOL,$anSQLArray));
			echo '</textarea>';
				
			//echo implode( '<br>', $anSQLArray );
			//echo '<br>';
		}
			
		echo '<br><br>';
		
		echo '</code></pre>';
		
		echo "noOfNewTables [$noOfNewTables]<br>";
		echo "noOfDroppedTables [$noOfDroppedTables]<br>";
		echo "noOfNewColumns [$noOfNewColumns]<br>";
		echo "noOfDroppedColumns [$noOfDroppedColumns]<br>";
		
		echo '<br><br>';
		
		
		echo '***The End***';
		
		$po_ddl = $ddl;
		$po_dml = $dml;
		$po_sqlPerTable = $sqlPerTable;
		
		return true;
		
	}	// updateMetaDataFromOtherMetaData 


	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * 
	 * @param WOOOF $wo
	 * @param bool $justTheMetaData
	 * @param bool $reverseEngineer
	 * @return false|string (fileName or JSON string)|array
	 */
	
	public static
	function exportMetaData( WOOOF $wo, $justTheMetaData=false, $justThisTable='', $reverseEngineer=true ) {
		echo '<h2>'.__CLASS__.'.'.__FUNCTION__.'</h2>';
		
		$tmp = $wo->getConfigurationFor('databaseName');
  		$database = $tmp[0];
  	
  		if ( $reverseEngineer ) {
	  		$succ = self::reverseEngineerObject($wo, $database, '__tableMetaData');
	  		if ( !$succ ) { return FALSE; }
		  	$succ = self::reverseEngineerObject($wo, $database, '__columnMetaData');
	  		if ( !$succ ) { return FALSE; }
  		}
  		  		
  		$res = WOOOF_MetaData::exportMetaDataAux($wo, $justTheMetaData, $justThisTable, 'FILE' );
  		
  		return $res;
	}	// exportMetaData
	
	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function exportMetaDataAux( WOOOF $wo, $justTheMetaData=false, $justThisTable = '', $exportType='FILE' ) {
		$tablesArray	= array(); 
		$columnsArray 	= array();
		
		$wherClause1 = "where 1=1 ";
		
		$justThisTable = trim($wo->db->escape($justThisTable));
		
		$wherClause1 .= (
			$justTheMetaData ? 
				" and tableName in ( '__tableMetaData', '__columnMetaData' ) " 
				:
				( $wo->hasContent($justThisTable) ?
				  " and tableName = '$justThisTable' "
				  :
				  ''
				)
		);
		
		$wherClause2 = (
			$justTheMetaData ? 
				", __tableMetaData t where t.id = c.tableId and t.tableName in ( '__tableMetaData', '__columnMetaData' ) "
				:
				( $wo->hasContent($justThisTable) ?
				  ", __tableMetaData t where t.id = c.tableId and t.tableName = '$justThisTable' "
				  :
				  ''
				)
		);
		
		$tmdResults = $wo->db->query( "select * from __tableMetaData  $wherClause1 order by tableName");
		if ( $tmdResults === FALSE ) { return false; }
		
		while ( ($tmdRow = $wo->db->fetchAssoc($tmdResults)) !== NULL ) {
			$tablesArray[] = $tmdRow;
		}
		
		$cmdResults = $wo->db->query( "select c.* from __columnMetaData c $wherClause2 order by c.tableId, c.ordering, c.name");
		if ( $cmdResults === FALSE ) { return false; }
		
		while ( ($cmdRow = $wo->db->fetchAssoc($cmdResults)) !== NULL ) {
			$columnsArray[] = $cmdRow;
		}
		
		$retArray = array(
			'version'	=> self::$version, //$wo->version,
			'tables'	=> $tablesArray,
			'columns'	=> $columnsArray,
		);
		
		if ( $exportType == 'FILE' ) {
			// save to file as JSON in debugLogPath
			$fullFileName = $wo->getConfigurationFor('debugLogPath') . 'mdExp_' . $wo->getCurrentDateTime() . $justThisTable . '.json'; 
			$succ = file_put_contents(
				$fullFileName,
				json_encode($retArray)
			);
			return ( $succ === FALSE ? FALSE : $fullFileName );
		}
		
		if ( $exportType=='ARRAY' ) {
			return $retArray;
		} 
		else {
			return json_encode($retArray);
		}
		
	}	// exportMetaData
	
	/***************************************************************************/
	/***************************************************************************/
	
	
	public static
	function importMetaData( WOOOF $wo, $filename, $pathName='' ) {
		echo '<h2>'.__CLASS__.'.'.__FUNCTION__.'</h2>';
		
		if ( $pathName=='') {
			$pathName = $wo->getConfigurationFor('debugLogPath');
		}

		$contents = file_get_contents( $pathName . $filename );
		if ( $contents === FALSE ) {
			$wo->logError(self::_ECP."0050 Failed to read from file [$pathName . $filename]"); 
			return FALSE; 
		}
		
		$contentsArray = json_decode($contents,true);
		
		//echo WOOOF_Util::do_dump($contentsArray); die();
		
		$version = $contentsArray['version'];
		
		if ( $version > self::$version ) {
			$wo->logError(self::_ECP."0000 Version mismatch. Imported file is [$version] vs current class [" . self::$version . "]");
			return FALSE;
		}
		
		$tables		= $contentsArray['tables'];
		$columns	= $contentsArray['columns'];
		
		$succ = self::createNewMetaDataTables($wo);
		if ( $succ === FALSE ) { return FALSE; }
		
		foreach( $tables as $aTableCols ) {
			$sql = 'insert into __tableMetaDataNew set ';
			foreach( $aTableCols as $aCol => $aVal ) {
				$sql .= "`$aCol` = '" . $wo->db->escape($aVal) ."', ";
			}
			$sql = substr($sql, 0, -2);
			$succ = $wo->db->query($sql);
			if ( !$succ ) { return FALSE; }
		}
		
		foreach( $columns as $aColumnCols ) {
			$sql = 'insert into __columnMetaDataNew set ';
			foreach( $aColumnCols as $aCol => $aVal ) {
				$sql .= "`$aCol` = '" . $wo->db->escape($aVal) ."', ";
			}
			$sql = substr($sql, 0, -2);
			$succ = $wo->db->query($sql);
			if ( !$succ ) { return FALSE; }
		}
		
		return true;		
	}	// importMetaData
	
	/***************************************************************************/
	/***************************************************************************/
	
	
	public static
	function initDatabase( WOOOF $wo, $databaseName, $usersArray=NULL, $recreate=false ) {
		//
		echo '<h2>'.__CLASS__.'.'.__FUNCTION__.'</h2>';
		
		if ( !$wo->hasContent($databaseName) ) {
			$wo->logError(self::_ECP."0045 Empty 'databaseName' was provided");
			return false;
		}
		
		if ( !$recreate ) {
			$sql = "
				SELECT count(*)
				FROM information_schema.tables
				WHERE 
					table_schema = '$databaseName' and
					table_name   = '__tableMetaData'
			";
		
			$res = $wo->db->query($sql);
			if ($res === FALSE) {
				return false;
			}
			if ( $wo->db->fetchArray($res)[0] != '0' ) {
				$wo->logError(self::_ECP."0070 Database [$databaseName] looks already initialized.");
				return false;
			}
		}	// no recreate 
		//
		//echo "Remember to remove this file as soon as the db is initialised for WOOOF!!!!<br><br>";
		
		$ddl = array();
		
		$ddl[] = 'DROP TABLE IF EXISTS __dbLog';
		$ddl[] = 'CREATE TABLE __dbLog (
		id int unsigned not null auto_increment primary key,
		executionTime   DECIMAL(16,5) NOT NULL,
		queryText       LONGTEXT
		) ENGINE = MyISAM CHARACTER SET = utf8';
		
		$ddl[] = 'DROP TABLE IF EXISTS __columnMetaData';
		$ddl[] = 'DROP TABLE IF EXISTS __tableMetaData';
		$ddl[] = 'DROP TABLE IF EXISTS __lrbs';
		$ddl[] = 'DROP TABLE IF EXISTS __roles';
		$ddl[] = 'DROP TABLE IF EXISTS __userRoleRelation';
		$ddl[] = 'DROP TABLE IF EXISTS __users';
		$ddl[] = 'DROP TABLE IF EXISTS __sessions';
		$ddl[] = 'DROP TABLE IF EXISTS __cache';
		$ddl[] = 'DROP TABLE IF EXISTS __userPaths';
		$ddl[] = 'DROP TABLE IF EXISTS __bannedIPs';
		$ddl[] = 'DROP TABLE IF EXISTS __externalFiles';
		$ddl[] = 'DROP TABLE IF EXISTS __domains';
                $ddl[] = 'DROP TABLE IF EXISTS __domain_values';
		$ddl[] = 'DROP TABLE IF EXISTS __options';

		$ddl[] = 'CREATE TABLE `__cache` ( `address` varchar(255) NOT NULL DEFAULT \'0\', `payload` longblob, PRIMARY KEY (`address`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$ddl[] = 'CREATE TABLE `__options` (`id` int unsigned not null auto_increment primary key, `optionName` varchar(255) not null, `optionValue` varchar(255) not null, `optionDisplay` int not null default \'1\', ord int unsigned not null default \'0\') ENGINE=InnoDB CHARSET=utf8';
		$ddl[] = 'CREATE TABLE `__domains` ( `id` char(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL, `isDeleted` char(1) NOT NULL DEFAULT \'0\', `code` varchar(20) NOT NULL, `description` varchar(255) NOT NULL, `comments` mediumtext, PRIMARY KEY (`id`), UNIQUE KEY `__domains_idxA` (`code`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$ddl[] = 'CREATE TABLE `__userPaths` ( `sessionId` char(40) DEFAULT NULL, `requestPage` longblob, `requestData` longblob, `timeStamp` char(14) NOT NULL, KEY `sessionId` (`sessionId`), KEY `timeStamp` (`timeStamp`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8';
		$ddl[] = 'CREATE TABLE `__domain_values` ( `id` char(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL, `isDeleted` char(1) NOT NULL DEFAULT \'0\', `active` int(11) DEFAULT \'1\', `domainId` char(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL, `domainValueCode` varchar(100) NOT NULL, `description` varchar(255) DEFAULT NULL, `comments` mediumtext, `subDomain` varchar(255) DEFAULT NULL, `picture` varchar(255) DEFAULT NULL, `iconFont` varchar(255) DEFAULT NULL, `extraInfo1` varchar(255) DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY `__domain_values_idxA` (`domainId`,`domainValueCode`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$ddl[] = 'CREATE TABLE `__bannedIPs` ( `IP` char(15) NOT NULL, `banExpiration` char(14) NOT NULL, KEY `IP` (`IP`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		$ddl[] = self::createTableMetaDataSQL('__tableMetaData'); 
		$ddl[] = self::createColumnMetaDataSQL('__columnMetaData'); 

		$ddl[] = 'CREATE TABLE `__externalFiles` ( 
			`id` char(10) NOT NULL, 
			`entryDate` varchar(255) NOT NULL, 
			`fileName` char(40) NOT NULL, 
			`originalFileName` varchar(255) NOT NULL, 
			PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		
		$ddl[] = 'CREATE TABLE `__lrbs` ( 
			`location` varchar(255) NOT NULL DEFAULT \'0\', 
			`role` char(10) NOT NULL DEFAULT \'none\', 
			`action` varchar(255) NOT NULL DEFAULT \'none\', 
			`allowed` char(1) NOT NULL DEFAULT \'0\', 
			PRIMARY KEY (`location`,`role`,`action`))
			ENGINE=InnoDB DEFAULT CHARSET=utf8';

		$ddl[] = 'CREATE TABLE `__roles` ( 
			`id` char(10) NOT NULL DEFAULT \'0\', 
			`role` varchar(255) NOT NULL DEFAULT \'none\', 
			PRIMARY KEY (`id`)) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8';

		$ddl[] = 'CREATE TABLE `__userRoleRelation` ( 
			`userId` char(10) NOT NULL DEFAULT \'0\', 
			`roleId` char(10) NOT NULL DEFAULT \'none\', 
			PRIMARY KEY (`userId`,`roleId`), 
			KEY `userId` (`userId`,`roleId`)) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8';

		$ddl[] = 'CREATE TABLE `__users` ( 
			`id` char(10) NOT NULL DEFAULT \'0\', 
			`loginName` varchar(255) NOT NULL, 
			`loginPass` varchar(255) NOT NULL, 
			PRIMARY KEY (`id`),
			KEY `loginName` (`loginName`,`loginPass`))
			ENGINE=InnoDB DEFAULT CHARSET=utf8';

		$ddl[] = 'CREATE TABLE `__sessions` ( 
			`sessionId` char(40) NOT NULL, 
			`userId` char(10) NOT NULL DEFAULT \'0\', 
			`loginDateTime` char(14) NOT NULL, 
			`loginIP` char(15) NOT NULL, 
			`active` char(1) NOT NULL DEFAULT \'1\', 
			`lastAction` char(14) NOT NULL, 
			PRIMARY KEY (`sessionId`), 
			KEY `active` (`active`,`sessionId`))
			ENGINE=MyISAM DEFAULT CHARSET=utf8';
		
		// Execute all ddl commands
		$succ = $wo->db->queryMultiple($ddl);
		if ( $succ === FALSE ) { return FALSE; }
		
		// Fill-in initial data
		//
		
		$dml = array();
		
		// Roles
		$dml[] = "insert into __roles (id, role) values ('0000000000','System Operator')";
		$dml[] = "insert into __roles (id, role) values ('0123456789','Not Logged In')";
		$dml[] = "insert into __roles (id, role) values ('9876543210','Email Not Active')";
		$dml[] = "insert into __roles (id, role) values ('9999999999','Normal User')";
		
		
		// system operator role rights on database creation and manipulation 
		$dml[] = "insert into __lrbs (location, role, action, allowed) values
		('3', '0000000000', 'viewUncontroled','1'),
		('3', '0000000000', 'logIn','1'),
		('3', '0000000000', 'signOut','1'),
		('1', '0000000000', 'modifyProperties','1'),
		('1', '0000000000', 'read','1'),
		('1', '0000000000', 'edit','1'),
		('1', '0000000000', 'activate', '1'),
		('1', '0000000000', 'deactivate', '1'),
		('1', '0000000000', 'delete', '1'),
		('1', '0000000000', 'insert', '1'),
		('1', '0000000000', 'moveDown', '1'),
		('1', '0000000000', 'moveUp', '1'),
		('1', '0000000000', 'signOut', '1'),
		('1', '0000000000', 'update', '1'),
		('1', '0000000000', 'users', '1')
		";
		
		$dml[] = "insert into __lrbs (location, role, action, allowed) values ('3','0123456789','logIn','1'),('3','0123456789','signIn','1'),('3','0123456789','viewUncontroled','1')";
		$dml[] = "insert into __lrbs (location, role, action, allowed) values ('3','9876543210','activateEmail','1')";
		$dml[] = "insert into __lrbs (location, role, action, allowed) values
			('3','9999999999','viewUncontroled','1'),
			('3','9999999999','signOut','1'),
			('3','9999999999','test','1')
		";
		
		$succ = $wo->db->queryMultiple( $dml );
		if ( $succ === FALSE ) { return FALSE; }
		
		$wo->db->commit();
		
		// MD Version in __options
		$succ = self::versionWriteToDB($wo);
		if ( $succ === FALSE ) { return FALSE; }
		
		// Users
		//
		if ( $usersArray === NULL or !is_array($usersArray) ) {
			$usersArray = array(
				array( 'sysOp', 'ultrex', array('System Operator', 'Normal User'), null, false ),
				array( 'notLoggedIn', '', 'Not Logged In', WOOOF_User::ID_OF_NOT_LOGGED_IN, false ),
				array( 'admin', 'admin123', array('System Operator', 'Normal User'), null, false ),
				array( 'user1', 'user1', 'Normal User', null, false ),
			);
		}
		
		$succ = WOOOF_User::createMultipleUsers($wo, $usersArray, $newUserIds);
		if ( $succ === FALSE ) { return FALSE; }
		
		$wo->db->commit();
		
		return true;
	}	// initDatabase

	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * Echoes sql statements (calling updateMetaDataFromOtherMetaData)
	 * - Creates new metaData tables (using this class)
	 * - Reverse engineers new metaData tables into normal metaData tables (hack!)
	 * - Exports metaData tables.
	 * - Rollbacks any changes from the reverse engineering above.
	 * - Imports exported metaData and updateMetaDataFromOtherMetaData
	 * 
	 * @param WOOOF $wo
	 * @return boolean
	 */
	public static
	function selfUpgradeMetaData( WOOOF $wo, $database ) {
		echo '<h2>'.__CLASS__.'.'.__FUNCTION__.'</h2>';
		$succ = WOOOF_MetaData::createNewMetaDataTables($wo);
		if ( $succ === FALSE ) { return FALSE; }
		
		$succ = WOOOF_MetaData::reverseEngineerObject($wo, $database, '__tableMetaData', 'refresh', '__tableMetaDataNew' );
		if ( $succ === FALSE ) { return FALSE; }

		$succ = WOOOF_MetaData::reverseEngineerObject($wo, $database, '__columnMetaData', 'refresh', '__columnMetaDataNew' );
		if ( $succ === FALSE ) { return FALSE; }
		
		$fullFileName = WOOOF_MetaData::exportMetaData($wo, true, false);
		if ( $fullFileName === FALSE ) { return FALSE; }
		
		$wo->db->rollback();	// take back dmls of reverseEngineer
		$succ = WOOOF_MetaData::importMetaData( $wo, basename($fullFileName) );
		if ( $succ ) {
			$succ = WOOOF_MetaData::updateMetaDataFromOtherMetaData($wo, $ddl, $dml, $sqlPerTable, false, false);
			if ( $succ === FALSE ) { return FALSE; }
		}
		
		// CAUTION: Assuming that the produced SQL statements will be executed.
		$succ = WOOOF_MetaData::versionWriteToDB( $wo );
		if ( $succ === FALSE ) { return FALSE; }
		
		return true;
	}	// selfUpgradeMetaData
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 * 
	 * @param WOOOF $wo
	 * @param string $databaseName
	 * @param bool $execute		// Optional, default is false. Set to true to actually execute the statements
	 * @return false|true|array	// true on successful execution of statements. array if execute=false
	 */
	public static
	function buildIndexesForAllTables( WOOOF $wo, $databaseName, $execute=false ) 
	{
		echo '<h2>'.__CLASS__.'.'.__FUNCTION__.'</h2>';
		
		$allResults = array();
		
		$tablesRes = $wo->db->query( "select tableName from __tableMetaData" );
		if ( $tablesRes === FALSE ) { return FALSE; }
		
		while ( ($aTable = $wo->db->fetchRow($tablesRes)) !== NULL ) {
			$allResults[$aTable[0]] = self::buildIndexesForTable($wo, $databaseName, $aTable[0], $execute );
			if ( $allResults[$aTable[0]] === FALSE ) { return FALSE; }
		}
		
		return $allResults;
		
	}	// buildIndexesForAllTables
	
	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function getDBIndexesForTable( WOOOF $wo, $databaseName, $tableName )
	{
		$sql = "
			SELECT index_name, index_type, non_unique, column_name, seq_in_index, collation
			FROM INFORMATION_SCHEMA.STATISTICS
			WHERE
			table_schema = '$databaseName' and
			table_name = '$tableName'
			order  by index_name, seq_in_index
		";
			
		$dbIndexesRes = $wo->db->query($sql);
		if ( $dbIndexesRes === FALSE ) { return FASLE; }
		
		$dbIndexesArray		= [];
		$dbIndexesArray2	= [];
		
		while( ($aDBIndexEntry = $wo->db->fetchAssoc($dbIndexesRes)) !== NULL ) {
			$dbIndexName = substr( $aDBIndexEntry['index_name'], -1);
			$dbIndexesArray2[$dbIndexName] = 
				( $aDBIndexEntry['index_type'] == 'FULLTEXT' ? 't' :
				( ( $aDBIndexEntry['non_unique'] == '1' ? 'i' : 'u' ) ) );
			$dbIndexesArray[$dbIndexName][$aDBIndexEntry['seq_in_index']] =
				array( $aDBIndexEntry['column_name'], ( $aDBIndexEntry == 'A' ? 'a' : '' ) )
			;
		}	// foreach db index entry
		
		/*
		echo WOOOF_Util::do_dump($dbIndexesArray);
		echo WOOOF_Util::do_dump($dbIndexesArray2);
		
		*/
		
		return array( $dbIndexesArray2, $dbIndexesArray );
		
	}	//	getDBIndexesForTable
	
	/***************************************************************************/
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param string $databaseName
	 * @param string $tableName
	 * @param bool $execute		// Optional, default is false. Set to true to actually execute the statements
	 * @return false|true|array	// true on successful execution of statements. array if execute=false
	 */
	public static
	function buildIndexesForTable( WOOOF $wo, $databaseName, $tableName, $execute=false )
	{
		$lc_legalPattern = '/^([piuts])([A-Z])([1-9])([ad])?$/';
		// Type of index: p -> primary, i -> index, u -> unique, t -> fullText, s -> spatial
		// 'Name' of index: just a different letter for each index
		// Position: of column to the specified index
		// Collation: (optional) a -> ASC, d -> DESC (not implemented in MySQL!!)
	
		//echo '<h2>'.__CLASS__.'.'.__FUNCTION__.'</h2>';
		
		$sql = "
			select c.name, c.indexParticipation
			from __tableMetaData t, __columnMetaData c
			where t.tableName = '$tableName' and c.tableId = t.id and c.indexParticipation is not null and c.indexParticipation != ''
		";
		
		$ipResults = $wo->db->query( $sql );
		if ( $ipResults === FALSE ) {
			$wo->logError(self::_ECP."0135 You may need to WOOOF_MetaData:selfUpgradeMetaData your db first!"); 
			return FALSE; 
		}
		
		$indexesArray = array();	// hold the columns: array( iName => array( [0] => array( colName, collation ), ... ), ... ) 
		$indexesArray2 = array();	// hold the type: array( iName => iType )
		
		$dbIndexesArray = array();
		$dbIndexesArray2 = array();
		
		while ( ($aResult = $wo->db->fetchAssoc($ipResults)) !== NULL ) {
			// $aResult: array( name, indexParticipation )
			//var_dump($aResult);
			
			$colName = $aResult['name'];
			
			$indexParticipationsArray = explode(',', $aResult['indexParticipation']);
			
			foreach( $indexParticipationsArray as $anIndexParticipationString ) {
				$anIndexParticipationString = trim($anIndexParticipationString);
				if ( !$wo->hasContent($anIndexParticipationString) ) { continue; }
				
				$matches = null;
				$matchOk = preg_match( $lc_legalPattern, $anIndexParticipationString, $matches );
				if ( $matchOk === 0 or $matchOk === FALSE ) {
					$wo->logError(self::_ECP."0100 Bad IndexParticipation value [$anIndexParticipationString] for column [$tableName.$colName]");
					return FALSE;
				}
				
				// var_dump($matches);
				list($dummy, $iType, $iName, $iSeq ) = $matches;
				$iCollation = $wo->getFromArray($matches, 4);
				
				if ( isset($indexesArray[$iName][$iSeq]) ) {
					$wo->logError(self::_ECP."0105 Multiple columns ([$colName], [{$indexesArray[$iName][$iSeq][0]}]) with same sequence number [$iSeq] for index [$iName] on column [$tableName.$colName]");
					return FALSE;
				}
				$indexesArray[$iName][$iSeq] = array( $colName, $iCollation );
				
				if ( !isset($indexesArray2[$iName]) ) {
					$indexesArray2[$iName] = $iType; 
				}
				else {
					if ( $indexesArray2[$iName] != $iType ) {
						$wo->logError(self::_ECP."0110 Index [$iName] of column [$tableName.$colName] defined with multiple types: [$iType] and [{$indexesArray2[$iName]}]");
						return false;
					}		
				}
				
			}	// foreach one of the column's participations
		}	// foreach column with indexParticipation(s)
		
		//var_dump($indexesArray);
		if ( count($indexesArray) == 0 ) {
			return ( $execute ? true : array() );
		}
		
		// Load existing indexes
		//
		$dbIndTemp = self::getDBIndexesForTable($wo, $databaseName, $tableName);
		if ( $dbIndTemp === FALSE ) { return FALSE; }
		list( $dbIndexesArray2, $dbIndexesArray ) = $dbIndTemp;

		
		$sqlStatements = array();
		
		foreach( $indexesArray as $anIndexCode => &$anIndexColumns ) {
			//echo "$anIndexCode<br>"; 
			$sqlOut = '';
			$indexName = $tableName . '_idx' . $anIndexCode;
			
			ksort($anIndexColumns);	// sort according to specified position and not leave according to order of entry in the array
				
			// Check if already built/exists in DB
			//
			$needToRecreateIndex	= false;
			$needToCreateIndex	= false;
				
			if ( isset($dbIndexesArray2[$anIndexCode]) ) {
				if ( $dbIndexesArray2[$anIndexCode] == $indexesArray2[$anIndexCode] ) {
					if ( count($dbIndexesArray[$anIndexCode]) == count($indexesArray[$anIndexCode]) ) {
						$i = 1;
						foreach( $anIndexColumns as $aColumn ) {
							if ( $aColumn[0] == $dbIndexesArray[$anIndexCode][$i][0] ) {
								; // ignore collation differences as collation is a joke (ASC only) in MySQL
							}
							else {
								$needToRecreateIndex = true;
								break;
							}	// same column or not in that position
							$i++;
						}	// foreach column in index
					}
					else {
						$needToRecreateIndex = true;
					}	// count of cols same or not
				}
				else {
					$needToRecreateIndex = true;
				}	// index type same or not
			}	// index with that name already exists in db
			else {
				$needToCreateIndex = true;
			}
			
			// var_dump($needToRecreateIndex, $needToCreateIndex);
			
			if ( $needToRecreateIndex ) {
				$sqlStatements[] = "ALTER TABLE `$tableName` DROP INDEX `$indexName`;";
			}
			
			if ( $needToCreateIndex or $needToRecreateIndex ) {
				$sqlOut .= "ALTER TABLE `$tableName` ADD ";
	
				switch( $indexesArray2[$anIndexCode] ) {
					case 'p' : $sqlOut .= "CONSTRAINT PRIMARY KEY "; break;
					case 'u' : $sqlOut .= "UNIQUE KEY `$indexName` "; break;
					case 'i' : $sqlOut .= "INDEX `$indexName` "; break;
					case 's' : $sqlOut .= "SPATIAL INDEX `$indexName` "; break;
					case 't' : $sqlOut .= "FULLTEXT INDEX `$indexName` "; break;
					default  : $sqlOut .= " ".$indexesArray2[$anIndexCode]." ***not implemented*** "; 
				}
				
				$sqlOut .= '( ';
				
				foreach( $anIndexColumns as $aColumn ) {
					$sqlOut .= "`". $aColumn[0] . "` ";
					if ( isset($aColumn[1]) ) { 
						$sqlOut .= ' ' . ( $aColumn[1] == 'd' ? 'DESC' : 'ASC' );
					}
					$sqlOut .= ', ';
				}	// foreach column
				
				$sqlOut = substr( $sqlOut, 0, -2 );
				$sqlOut .= ' ) ';
				$sqlOut .= ';';
				
				$sqlStatements[] = $sqlOut;
			}	// create index
							
		}	// foreach index
		
		// var_dump($sqlStatements);
		
		if ( $execute ) {
			if ( count($sqlStatements) > 0 ) {
				$succ = $wo->db->queryMultiple( $sqlStatements );
				return $succ;
			}
			else {
				return true;
			}
		}

		return $sqlStatements;
	}	// buildIndexesForTable

	/***************************************************************************/
	/***************************************************************************/
	
	public static
	function createTplCodeForTable( WOOOF $wo, $tableName, $classSubName ) {
		$out = '';
		
		$l_className = "VO_Tbl" . ucfirst($classSubName);
		
		$out .= '<?php' . PHP_EOL;
		$out .= "/* Class [$l_className] for table [$tableName] */" . PHP_EOL . PHP_EOL;
		$out .= 'class ' . $l_className . ' extends VO_TblAbstract { ' . PHP_EOL;
		
		/* not all columns are found in the Metadata
		$t = new WOOOF_dataBaseTable($wo->db, $tableName );
		if ( !$t->constructedOk ) { return false; }
		for( $i=0; $i<count($t->columns)/2; $i++ ) {
			$out .= '  public $' . $t->columns[$i]->getName() . ';' . PHP_EOL;
		}
		*/
		
		$out .= PHP_EOL;
		$out .= "    const TABLE_NAME = '$tableName';" . PHP_EOL . PHP_EOL;
		
		
		$result2 = $wo->db->query('desc '. $tableName);
		while($column = $wo->db->fetchRow($result2))
		{
			$out .= '    public $' . $column[0] . ';' . PHP_EOL;
		}
		
		$out .= PHP_EOL;
		$out .= '}  // ' . $l_className;

		return $out;
	}	// createTplCodeForTable
	
	
}	// MetaData

// End of MetaData.php