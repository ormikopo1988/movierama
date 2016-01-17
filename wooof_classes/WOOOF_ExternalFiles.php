<?php

class WOOOF_ExternalFiles
{
	const _ECP = 'WEF';	// Error Code Prefix
	
    public static function createEmptyFile(WOOOF $wo, $fileName)
    {
        $fullFilename = $wo->getConfigurationFor('absoluteFilesRepositoryPath') . $wo->randomString(40);
        
        while(file_exists($fullFilename))
        {
            $fullFilename = $wo->getConfigurationFor('absoluteFilesRepositoryPath') . $wo->randomString(40);
        }
        
        $result = touch($fullFilename);
        if ($result === FALSE)
        {
            $wo->logError(self::_ECP."0001 createEmptyFile: External File was not created by the file system.");
            return true;
        }
        
        $newId=$wo->db->getNewId('__externalFiles');
        
        $result = $wo->db->query('insert into __externalFiles '
                . '(id, entryDate, fileName, originalFileName) values '
                . '('. $newId .', '. $wo->getCurrentDateTime() .', '. $fullFilename .', '. $wo->cleanUserInput($fileName) .')');
        
        if ($result === FALSE)
        {
            return FALSE;
        }
        
        
    }	// createEmptyFile


	/***************************************************************************/
	/***************************************************************************/
	
    public static function deleteExternalFileByAddress(  WOOOF $wo, $tableId, $columnId, $rowId ) 
    {
        // TODO: check rights ???
        // CAUTION: The file deletion cannot be undone (as opposed to a db transaction).
        // Make sure that this function is called as near as possible to the end
        // of any relevant transaction. 

    	// TODO: What about deletion of images ????

        $fileData = self::getExternalFileIdThroughAddress($wo, $tableId, $columnId, $rowId);
        if ( $fileData === FALSE ) { return FALSE; }
        
        return self::deleteExternalFile($wo, $fileData['id'], false );
    }	// deleteExternalFileByAddress
    
    /***************************************************************************/
	/***************************************************************************/
	
    public static function deleteExternalFile( WOOOF $wo, $idOrFilename, $isImage ) 
    {
        // TODO: check rights ???
        // CAUTION: The file deletion cannot be undone (as opposed to a db transaction).
        // Make sure that this function is called as near as possible to the end
        // of any relevant transaction. 

        if ( !$wo->hasContent($idOrFilename) ) {
                return TRUE;
        }

        $wo->debug("deleteExternalFile [$idOrFilename] [$isImage]");

        $fullFilename = '';

        if ( $isImage ) {
        	$fullFilename = 
            	$wo->getConfigurationFor('siteBasePath') . 
                $wo->getConfigurationFor('imagesRelativePath') .
                $idOrFilename
            ;
        }
        else {
           	$efRow = $wo->db->getRow( '__externalFiles', $idOrFilename );
            if ( $efRow === FALSE ) { return FALSE; }
            if ( $efRow === NULL ) {
	            $wo->logError(self::_ECP."0002 deleteExternalFile: External File with id [$idOrFilename] was not found");
                return true;	// pretend that nothing happened.
            }

            $fullFilename = 
    	        $wo->getConfigurationFor('absoluteFilesRepositoryPath') .
                $efRow['fileName']
            ;
        }	// image or file


        if ( !file_exists($fullFilename) ) {
            $wo->logError(self::_ECP."0003 deleteExternalFile: [$idOrFilename] File not found: [$fullFilename]");
            return true;
        }

        $succ = chmod($fullFilename, 0777); // do not blink if we fail to chmod.
        if ( !unlink($fullFilename) ) {
        	$wo->logError(self::_ECP."0004 deleteExternalFile: [$idOrFilename] File could not be deleted: [$fullFilename]");
            // goon as if nothing has happened. File will remain without anything pointing to it.
        }

        if ( !$isImage ) {
            $sql = "delete from __externalFiles where id = '$idOrFilename'";
            $succ = $wo->db->query($sql);
            if ( !$succ ) {
            	$wo->logError(self::_ECP."0005 deleteExternalFile: Failed to delete row from __externalFiles");
                return false;	// that's nasty :-(  The file might be removed, so the row will point to a non-existent file.
            }
        }

        return true;
    }	// deleteExternalFile

    /***************************************************************************/
    /***************************************************************************/
    
    public static function getExternalFileByAddress( WOOOF $wo, $tableId, $columnId, $rowId, $handle='file transfer' )
    {
    	// 0400

		$wo->debug("getExternalFileByAddress [$tableId] [$columnId] [$rowId] [$handle]");
    	
		$fileData = self::getExternalFileIdThroughAddress($wo, $tableId, $columnId, $rowId);
		if ( $fileData === FALSE ) { return FALSE; }
		
		return self::getExternalFileById($wo, $fileData['id'], $fileData, $handle );

/*    	// Old code
		$result = $wo->db->query('select * from __tableMetaData where id=\''. $tableId .'\'');
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
*/		
    }	// getExternalFileByAddress
    
    /***************************************************************************/
    /***************************************************************************/
    
    public static function getExternalFileIdThroughAddress( WOOOF $wo, $tableId, $columnId, $rowId )
    {
    	// 0300

    	if ( !$wo->hasContent($tableId) ) {
    		$wo->logError(self::_ECP."0310 No value defined for 'tableId'" );
    		return false;
    	}
    	if ( !$wo->hasContent($columnId) ) {
    		$wo->logError(self::_ECP."0312 No value defined for 'columnId'" );
    		return false;
    	}
    	if ( !$wo->hasContent($rowId) ) {
    		$wo->logError(self::_ECP."0314 No value defined for 'rowId'" );
    		return false;
    	}
    	
    	$tableId	= trim($wo->db->escape($tableId) );
    	$columnId	= trim($wo->db->escape($columnId) );
    	$rowId		= trim($wo->db->escape($rowId) );
     
    	$sql = "
	    	select
	    		tmd.tableName, cmd.name columnName
	    	from
	    		__tableMetaData tmd, __columnMetaData cmd
	    	where
	    		tmd.id = '$tableId' and
	    		cmd.id = '$columnId' and
	    		cmd.tableId = tmd.id
		";
    	$res1 = $wo->db->query( $sql ); 
    	if ( $res1 === FALSE ) { return FALSE; }
    	
    	if ( ( $data1 = $wo->fetchAssoc($res1) ) === NULL ) {
    		$wo->logError(self::_ECP."0320 Bad tableId [$tableId] and columnId [$columnId] combination.");
    		return false;
    	}
    	
    	// TODO: Should check that column is a FILE column ???

    	$sql = "
	    	select
	    		ef.*
	    	from
	    		`{$data1['tableName']}` t, __externalFiles ef
	    	where
	    		t.id = '$rowId' and
	    		ef.id = t.{$data1['columnName']}
		";    	
    	 
    	$res2 = $wo->db->query( $sql ); 
    	if ( $res2 === FALSE ) { return FALSE; }
    	
    	if ( ( $fileData = $wo->fetchAssoc($res2) ) === NULL ) {
    		$wo->logError(self::_ECP."0330 Bad tableId [$tableId] columnId [$columnId] and rowId [$rowId] combination.");
    		return false;
    	}
    	
    	/*
    		id	char(10)	 
			entryDate	varchar(255)	 
			fileName	char(40)	 
			originalFileName varchar(255)
    	 */

    	return $fileData;

    }	// getExternalFileIdThroughAddress
    
    /***************************************************************************/
	/***************************************************************************/
	
    /**
     * 
     * @param WOOOF $wo
     * @param string $externalFileId
     * @param string $handle			// Optional, default 'file transfer'.
     * @return NO RETURN|false|string	// send file to browser, fail, or return contents as string
     */
    public static function getExternalFileById( WOOOF $wo, $externalFileId, $fileData = null, $handle='file transfer' )
    {
    	// TODO: CAUTION: Assume that rights are checked ???
    	
    	// 00200

		$wo->debug("getExternalFileById [$externalFileId] [$handle]");
		
		if ( $fileData == NULL && !$wo->hasContent($externalFileId) ) {
			$wo->logError(self::_ECP."00200 No value provided for 'externalFileId'.");
			return FALSE;
		}
    	
		if ( $fileData == NULL ) {
			$result = $wo->db->query('select * from __externalFiles where id=\''. $externalFileId .'\'');
			if (mysqli_num_rows($result)!=1)
			{
				$wo->logError(self::_ECP."00210 Malformed file location. Specified PAYLOAD location [$externalFileId] is invalid!");
				return FALSE;
			}
			
			$fileData = $wo->db->fetchAssoc($result);
		}
				
		$absoluteFilesRepositoryPath = $wo->getConfigurationFor('absoluteFilesRepositoryPath');
		$fullFilename = $absoluteFilesRepositoryPath . $fileData['fileName'];
		
		if ( !file_exists($fullFilename) ) {
			$wo->logError(self::_ECP."00220 File [$fullFilename] was not found!");
			return FALSE;
		}

		if ( $handle == 'file transfer' ) {
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
			$succ = readfile($fullFilename);
			// TODO: Check for FALSE ... ??
			exit;
		}	// file transfer

		return file_get_contents($fullFilename );
    }	// getExternalFileById
    
    /***************************************************************************/
    /***************************************************************************/
    
    /**
     *
     * @param WOOOF $wo
     * @param string $externalFileId
     * @return false | null | array	// return contents of row in table
     */
    public static function getExternalFileDataById( WOOOF $wo, $externalFileId)
    {
    	if (!$wo->hasContent($externalFileId) ) {
    		$wo->logError(self::_ECP."0290 No value provided for 'externalFileId'.");
    		return FALSE;
    	}
    	
    	$result = $wo->db->getRow('__externalFiles', $externalFileId);
    	
    	return $result; //return all row
    } //getExternalFileDataById
    
    /***************************************************************************/
    /***************************************************************************/
    
    /**
     *
     * @param WOOOF $wo
     * @param string $fieldValue
     * @return false | array ['externalFileId', 'fileName', 'originalFileName']	// return contents of file
     */
    
    public static
    function getFileObject( WOOOF $wo, $fieldValue ) {
    	$fileObject = [
    		'externalFileId' => $fieldValue,
    		'fileName' => '',
    		'originalFileName' => ''
    	];

      	if($wo->hasContent($fieldValue)) {
    		$externalFileData = self::getExternalFileDataById($wo, $fieldValue);
    		if($externalFileData === FALSE) { return false; }
    		if($externalFileData !== NULL) {
    			$fileObject['fileName'] = $externalFileData['fileName'];
    			$fileObject['originalFileName'] = $externalFileData['originalFileName'];
    		}
    	}
    
    	return $fileObject;
    } //getFileObject
    
    /***************************************************************************/
	/***************************************************************************/
	
    public static function createFilesView( WOOOF $wo, $viewName='__v_files' ) 
    {
        $sql = "
                select
                    tmd.id T_id, cmd.id C_id, tmd.tableName T_name, cmd.name C_name, 
                    case cmd.presentationType when 12 then 0 else 1 end C_isImage
                from
                    __tableMetaData tmd,
                    __columnMetaData cmd
                where
                    cmd.tableId = tmd.id and
                    cmd.presentationType in ( 12, 13 )
                order by
                    tmd.tableName, cmd.name
";
        $dbRes = $wo->db->query( $sql );
        if ( $dbRes === FALSE ) { return FALSE; }

        $vsql = '';
        // select cvFile,0 from applicants where cvFile is not null and cvFile!=''


        while ( ($aRow = $wo->db->fetchAssoc($dbRes)) !== NULL ) {
                if ( $aRow['C_isImage'] == '0' ) {
                        $subSel = "( select max(filename) from __externalFiles ef where ef.id={$aRow['C_name']} ) filename" ;
                }
                else {
                        $subSel = "{$aRow['C_name']} filename";
                }

                $vsql .= "
                        select 
                                '{$aRow['T_name']}' tableName, id rowId, '{$aRow['C_name']}' columnName,
                                `{$aRow['C_name']}` columnVal, '{$aRow['C_isImage']}' isImage,
                                $subSel
                        from `{$aRow['T_name']}`
                        where `{$aRow['C_name']}` is not null and `{$aRow['C_name']}` != ''
                ";
                $vsql .= ' union ';
        }	

        $vsql = substr( $vsql, 0, strlen($vsql)-7);

        $succ = $wo->db->query( "create or replace view $viewName as $vsql");

        return $succ;
    }	// createSQLForFilesView
		
/***************************************************************************/
/***************************************************************************/
	
    public static function checkFilesMissingFromFS( WOOOF $wo, $viewName='__v_files' ) 
    {
        $sql = "select * from $viewName order by tableName, rowId, columnName";

        $dbRes = $wo->db->query( $sql );
        if ( $dbRes === FALSE ) { return FALSE; }

        $out = array();

        $paths = array(
                0 => $wo->getConfigurationFor('absoluteFilesRepositoryPath') ,	// isImage == 0 
                1 => $wo->getConfigurationFor('siteBasePath') .					// isImage == 1
                         $wo->getConfigurationFor('imagesRelativePath')
        );

        while ( ($aDbFile = $wo->db->fetchAssoc($dbRes)) !== NULL ) {
                $fullFileName = $paths[$aDbFile['isImage']] . $aDbFile['filename'];
                if ( !file_exists($fullFileName) ) {
                        $out[] = $aDbFile;
                }
        }

        return $out;
    }	// checkDbAsFiles
	
	/***************************************************************************/
	/***************************************************************************/
	
    // TODO: Much better if we were to put all read filenames in a temp table
    // and make a minus with __v_files.filename ???

    public static function checkFilesMissingFromDB( WOOOF $wo, $doDelete=false, $viewName='__v_files' ) 
    {
        $sql = "select * from $viewName order by tableName, rowId, columnName";

        $dbRes = $wo->db->query( $sql );
        if ( $dbRes === FALSE ) { return FALSE; }

        $out = array();

        $paths = array(
                0 => $wo->getConfigurationFor('absoluteFilesRepositoryPath') ,	// isImage == 0 
                1 => $wo->getConfigurationFor('siteBasePath') .					// isImage == 1
                         $wo->getConfigurationFor('imagesRelativePath')
        );

        foreach( $paths as $isImage => $aPath ) {
                // Assume the two types are in distinct paths!
                $actualContents = scandir( $aPath );
                foreach( $actualContents as $aContent ) {
                        if ( is_dir($aContent) ) { continue; }
                        $sql = "select count(*) from $viewName where filename = '$aContent'";

                        $c = $wo->db->getSingleValueResult( $sql, true, true );
                        if ( $c === FALSE ) { return FALSE; }				
                        if ( $c === '0' ) {
                                $out[] = array( $aContent, $isImage );
                        }

                        if ( $doDelete ) {
                                echo "del $aPath$aContent<br>";
                        }
                }	// foreach file in path
        }	// foreach path
        return $out;
    }	// checkFilesInDb
	
	/***************************************************************************/
	/***************************************************************************/
	
    public static function checkFiles( WOOOF $wo, &$missingFromFS, &$missingFromDB, $doDelete=false, $viewName='__v_files' ) 
    {
        $succ = self::createFilesView($wo, $viewName);
        if ( $succ === FALSE ) { return FALSE; }

        $missingFromFS = self::checkFilesMissingFromFS($wo, $viewName);

        $missingFromDB = self::checkFilesMissingFromDB($wo, $doDelete, $viewName );

        return true;

    }	// checkFiles
    
}	// WOOOF_ExternalFiles

?>