<?php
/*
 * WOOOF virtual file system
 * 
 * either free for all 
 * or restircted by LORABASE
 * multiple Virtual Filesystems manager.
 * 
 * uses the external file storage facilities of WOOOF
 * can be used in conjunction with any project
 * it is a singleton class that offers VFS functionality
 * 
 * Requires WOOOF and WOOOF_fileManagement 
 * Addresses are starting with 7_ and
 * the next item should be the VFS id. 
 * If left blank the main VFS is used.
 * The VFS entity's id should then follow.
 * 
 * Therefore there are either 2 or 3 parts in each address: 7_VFSID_ENTITYID
 * If one wants to address the root directory of a VFS should pass
 * the following address 7_VFSID_ (the third piece should be blank).
 * The actual VFS address should be 7_VFSID though.
 * 
 */

/*
 * DataBase Table
 * 
 * __vfsEntities
 * 
 * id
 * name
 * parentId
 * vfsId
 * entityType
 * fileId
 * 
 */

/*
 * DataBase Table
 * 
 * __vfsGroupUsers
 * 
 * groupId
 * userId
 * 
 */

class WOOOF_VirtualFileSystem
{
    private $wo;
    private $db;
    private $vfsTable;
    private $externalFiles;
    
    const _ECP = _ECP .'';
    
    const DIRECTORY = 1; 
    const FILE      = 2;
    const VFS       = 4;
    const GROUP     = 8;
    
    /*
     * protected constructor so that the class can be subclassed
     */
    
    protected function __construct(WOOOF $instance, WOOOF_dataBase $theDataBase = NULL) 
    {   
        $this->wo               = $instance;
        if ($theDataBase === NULL)
        {
            $this->db           = $this->wo->db;
        }else
        {
            $this->db           = $theDataBase;
        }
        $this->vfsTable         = new WOOOF_dataBaseTable($this->db, '__vfsEntities');
    }
    
    /*
     * private clone and wakeup so that the instance cannot be duplicated.
     */
    
    private function __clone()
    {
    }
    
    private function __wakeup()
    {
    }
    
    /*
     * static function used to retrieve the Virtual File System Instance
     * that does the inital setup
     */
    
    public static function getInstance()
    {
        static $instance = NULL;
        if (NULL === $instance)
        {
            $instance = new static(WOOOF::$instance, WOOOF::$instance->db);
        }
        
        return $instance;
    }
    
    /*
     * isAValidAddress
     * 
     * @param   string  $theAddress         An address to check for validity
     * @param   array   $productsArray           An array passed b reference that will receive byproducts of the process and/or error related data
     * @param   const   entityType          Default is NULL. If not null should be one of the constants declared in this class that represent the type of the entity.
     * 
     * @return boolean True or False depending on the internal checks. 
     * 
     */
    
    private function isAValidAddress($theAddress, &$productsArray, $entityType = NULL)
    {
        $addressPieces = explode('_', $theAddress);
        $productsArray['addressPieces'] = $addressPieces;
        
        if ($addressPieces[0]!='7') // must start with a 7
        {
            $productsArray['errorCode'] = _ECP .'0000';
            $productsArray['errorDescription'] = 'Address belongs to a different LORABASE domain.';
            $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
            return FALSE;
        }
        
        if (count($addressPieces)!=3 && count($addressPieces)!=2)
        {
            $productsArray['errorCode'] = _ECP .'0001';
            $productsArray['errorDescription'] = 'Address doesn\'t contain three pieces and is invalid.';
            $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
            return FALSE;
        }

        $result = $this->db->query('SELECT * FROM __vfsEntities WHERE id=\''. $this->db->escape($addressPieces[2]) .'\' AND vfsId=\''. $this->db->escape($addressPieces[1]) .'\'');
        if ($result === FALSE)
        {
            $productsArray['errorCode'] = _ECP .'0005';
            $productsArray['errorDescription'] = 'Selection query failed in address verification';
            $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
            return FALSE;
        }
        
        if ($this->db->getNumRows($result)>0)
        {
            $productsArray['vfsRow'] = $this->db->fetchAssoc($result);
            if ($entityType == $this->DIRECTORY && $productsArray['vfsRow']['entityType']!=$this->DIRECTORY)
            {
                $productsArray['errorCode'] = _ECP .'0003';
                $productsArray['errorDescription'] = 'This VFS entry is not a directory.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            if ($entityType == $this->FILE && $productsArray['vfsRow']['entityType']!=$this->FILE)
            {
                $productsArray['errorCode'] = _ECP .'0004';
                $productsArray['errorDescription'] = 'This VFS entry is not a file.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            if ($entityType == $this->VFS && $productsArray['vfsRow']['entityType']!=$this->VFS)
            {
                $productsArray['errorCode'] = _ECP .'0022';
                $productsArray['errorDescription'] = 'This entry does not represent a VFS.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            if ($entityType == $this->GROUP && $productsArray['vfsRow']['entityType']!=$this->GROUP)
            {
                $productsArray['errorCode'] = _ECP .'0023';
                $productsArray['errorDescription'] = 'This entry does not represent a GROUP.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
        }else
        {
            $productsArray['errorCode'] = _ECP .'0002';
            $productsArray['errorDescription'] = 'No VFS entry was found for the specific address.';
            $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
            return FALSE;
        }
                    
        // TODO: possibly add more checks here
        
        return TRUE;
    }
    
    /*
     * getDirectoryContents
     * 
     * @param   string  $directoyAddress    An address that represents a directory.
     * @param   array   $productsArray      An array that will contain an element called 'directoryContents' with the contents list in array form. On error relevant info will be here.
     * 
     * @return  boolean Returns true on success False on failure. 
     * 
     */
    
    public function getDirectoryContents($directoyAddress, &$productsArray)
    {
        if ($this->isAValidAddress($directoyAddress, $productsArray, $this->DIRECTORY))
        {
            $productsArray['directoryContents'] = array();
            $result = $this->db->query('select * from __vfsEntities where parentId=\''. $this->db->escape($productsArray['addressPieces'][2]) /'\' order by name');
            if ($result === FALSE)
            {
                $productsArray['errorCode'] = _ECP .'0006';
                $productsArray['errorDescription'] = 'Selection query failed in directory content retreival.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            while($row = $this->db->fetchAssoc($result))
            {
                if ($row['entityType']==$this->FILE || $row['entityType']==$this->DIRECTORY)
                {
                    $productsArray['directoryContents'][] = $row;
                }
            }
            return TRUE;
        }else
        {
            return FALSE;
        }
    }
    
     /*
     * createVFS Creates a new VFS
     * 
     * @param   string  $entityName     The name of the entity to be created.
     * @param   array   $productsArray  An array that on error will contain relevant info. On success an entry named 'insertId' will contain the insert id for the new VFS entry.
     * 
     * @return  array   Returns true on success False on failure.  
     * 
     */
    
    public function createVFS($entityName, &$productsArray)
    {
        $columnsToFill['parentId']  = '';
        $columnsToFill['name']      = $this->db->escape($entityName);
        $columnsToFill['entityType']= $this->VFS;
        $columnsToFill['vfsId']     = '';

        $result = $this->vfsTable->insertFromArray($columnsToFill);
        if ($result==FALSE)
        {
            return FALSE;
        }else
        {
            $productsArray['insertId']=$result;
            return TRUE;
        }
    }
    
    /*
     * createEntity Creates a new VFS, Group or Directory
     * 
     * @param   string  $parentAddress  An address that represents the parent directory of the new entity. This is IGNORED when a new VFS is cerated.
     * @param   string  $entityName     The name of the entity to be created.
     * @param   int     $entityType     The new entity's type.
     * @param   array   $productsArray  An array that on error will contain relevant info. On success an entry named 'insertId' will contain the insert id for the new VFS entry.
     * 
     * @return  array   Returns true on success False on failure. 
     * 
     */
    
    public function createEntity(string $parentAddress, string $entityName, int $entityType, array &$productsArray)
    {
        if ($entityType == $this->VFS)
        {
            return $this->createVFS($entityName, $productsArray);
        }
        
        if ($this->isAValidAddress($parentAddress, $productsArray, $entityType))
        {
            if ($entityType == $this->FILE)
            {
                return $this->createEmptyFile($parentAddress, $fileName, $productsArray);
            }
            $uniqueNameTest = $this->db->query('select * from __vfsEntities where parentId=\''. $productsArray['vfsRow'][2] .'\' and name=\''. $this->db->escape($entityName) .'\'');
            if ($uniqueNameTest === FALSE)
            {
                $productsArray['errorCode'] = _ECP .'0007';
                $productsArray['errorDescription'] = 'Unique name Verification query failed in entity creation.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            if ($this->db->getNumRows($uniqueNameTest)>0)
            {
                $productsArray['errorCode'] = _ECP .'0008';
                $productsArray['errorDescription'] = 'The supplied name is not unique.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }else
            {
                $columnsToFill['parentId']  = $productsArray['addressPieces'][2];
                $columnsToFill['name']      = $this->db->escape($entityName);
                $columnsToFill['entityType']= $entityType;
                $columnsToFill['vfsId']     = $productsArray['addressPieces'][1];
                
                $result = $this->vfsTable->insertFromArray($columnsToFill);
                if ($result === FALSE)
                {
                    $productsArray['errorCode'] = _ECP .'0009';
                    $productsArray['errorDescription'] = 'Entity Insertion failed in the VFS.';
                    $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                    return FALSE;
                }else
                {
                    $productsArray['insertId'] = $result;
                    return TRUE;
                }
            }
        }else
        {
            return FALSE;
        }
    }
    
    /*
     * createEmptyFile
     * 
     * 
     * 
     */
    
    public function createEmptyFile($parentAddress, $fileName, &$productsArray)
    {
        if ($this->isAValidAddress($parentAddress, $productsArray, $this->DIRECTORY))
        {
            $newFileId = WOOOF_ExternalFiles::createEmptyFile($this->wo, $fileName);
            if ($newFileId === FALSE)
            {
                return FALSE;
            }else
            {
                $columnsToFill['parentId']  = $productsArray['addressPieces'][2];
                $columnsToFill['name']      = $this->db->escape($fileName);
                $columnsToFill['entityType']= $this->FILE;
                $columnsToFill['vfsId']     = $productsArray['addressPieces'][1];
                $columnsToFill['fileId']    = $newFileId;
                
                $result = $this->vfsTable->insertFromArray($columnsToFill);
                if ($result === FALSE)
                {
                    $productsArray['errorCode'] = _ECP .'0024';
                    $productsArray['errorDescription'] = 'File Entry Insertion failed in the VFS @ create empty file.';
                    $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                    return FALSE;
                }else
                {
                    $productsArray['insertId'] = $result;
                    return TRUE;
                }
            }
        }else
        {
            return FALSE;
        }
    }
    
    /*
     * deleteDirectory
     * 
     * @param   string  $directoryAddress An address that represents a directory that will be deleted
     * @param   array   $productsArray          An array that on error will contain relevant info. On success it will be an empty array.
     * 
     * @return  boolean TRUE on success FALSE on failure.
     * 
     */
    
    public function deleteDirectory($directoryAddress, &$productsArray)
    {
        if ($this->isAValidAddress($directoryAddress, $productsArray, TRUE))
        {
            $result = $this->getDirectoryContents($directoryAddress, $productsArray);
            if ($result === FALSE)
            {
                return FALSE;
            }else
            {
                foreach ($productsArray['directoryContents'] as $vfsEntity) 
                {
                    if ($vfsEntity['entityType']=='file')
                    {
                        $this->deleteFile('7_'. $productsArray['addressPieces'][1].'_'.$vfsEntity['id']);
                    }elseif ($vfsEntity['entityType']=='directory')
                    {
                        $tmpArray = array();
                        $result = $this->deleteDirectory('7_'. $productsArray['addressPieces'][1].'_'.$vfsEntity['id'], $tmpArray);
                        if ($result === FALSE)
                        {
                            $productsArray['errorCode'] = $tmpArray['errorCode'];
                            $productsArray['errorDescription'] = $tmpArray['errorDescription'];
                            $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                            return FALSE;
                        }
                    }
                }
                $result = $this->vfsTable->deleteRow($productsArray['arrayPieces'][2]);
                if ($result === FALSE)
                {
                    $productsArray['errorCode'] = _ECP .'0010';
                    $productsArray['errorDescription'] = 'Item deletion failed.';
                    $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                    return FALSE;
                }else
                {
                    $productsArray = array();
                    return true;
                }
            }
        }else
        {
            return FALSE;
        }
    }
    
    /*
     * addFile
     * 
     * @param   mixed  $fileIds           Either a string id from the __externalFiles table, or an array of such ids.
     * @param   string $directoryAddress  The address of a directory to include the file(s) to.
     * @param   array  $productsArray     An array that on error will contain relevant info. On success it will be an empty array.
     * 
     * @return boolean TRUE on success FALSE on failure.
     */
    public function addFile($fileIds, $directoryAddress, &$productsArray)
    {
        if ($this->isAValidAddress($directoryAddress, $productsArray, $this->DIRECTORY))
        {
            if (!is_array($fileIds))
            {
                $fileIds = array($fileIds);
            }
            
            foreach ($fileIds as $fileId) 
            {
                $fileRow = $this->db->getRow('__externalFiles', $fileId);
                if ($fileRow === FALSE)
                {
                    $productsArray['errorCode'] = _ECP .'0011';
                    $productsArray['errorDescription'] = 'File ID is invalid.';
                    return FALSE;
                }
                $columnsToFill['parentId']  = $productsArray['vfsRow']['id'];
                $columnsToFill['name']      = $fileRow['originalFileName'];
                $columnsToFill['entityType']= 'file';
                $columnsToFill['vfsId']     = $productsArray['addressPieces'][1];
                $columnsToFill['fileId']     = $fileRow['id'];
                
                $result = $this->vfsTable->insertFromArray($columnsToFill);
                if ($result === FALSE)
                {
                    $productsArray['errorCode'] = _ECP .'0012';
                    $productsArray['errorDescription'] = 'File '. $this->wo->cleanUserInput($fileRow['originalFileName'], FALSE, TRUE) .' insertion failed due to database error.';
                    $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                    return FALSE;
                }
            }
        }else
        {
            return FALSE;
        }
    }
    
    /*
     * addZippedContent Adds the contents of a .zip file into a VFS directory
     * 
     * @param   string  $directoryAddress   The address of the directory where the contents of the zip file will be added.
     * @param   string  $zipFileNameAndPath The full path of the zip file whose contents are to be added to the directory.
     * @param   array   $productsArray      An array that on error will contain the error specifics and on success will be empty.
     * 
     * @return  boolean TRUE on success FALSE on failure.
     */
    public function addZippedContent($directoryAddress, $zipFileNameAndPath, &$productsArray)
    {
        if (!extension_loaded('zip'))
        {
            $productsArray['errorCode'] = _ECP .'0013';
            $productsArray['errorDescription'] = 'Error PHP zip extension is NOT available. Cannot open archive.';
            $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
            return FALSE;
        }else
        {
            $zip = new ZipArchive();
            $result = $zip->open($zipFileNameAndPath);
            if ($result === TRUE) 
            {
                $extractionLocation = $this->wo->getConfigurationFor('absoluteFilesRepositoryPath');
                $aRandomDirName = $this->wo->randomString(20);
                
                while(file_exists($extractionLocation . $aRandomDirName))
                {
                    $aRandomDirName = $this->wo->randomString(20);
                }
                $extractionLocation .= $aRandomDirName;
                $result2 = $zip->extractTo($extractionLocation);
                if ($result2 === FALSE)
                {
                    $productsArray['errorCode'] = _ECP .'0015';
                    $productsArray['errorDescription'] = 'Error while trying to extract archive.';
                    $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                    return FALSE;
                }
                $zip->close();
                $result = $this->addMassContent($directoryAddress, $extractionLocation, $productsArray);
                $it = new RecursiveDirectoryIterator($extractionLocation, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                foreach($files as $file) 
                {
                    if ($file->isDir())
                    {
                        rmdir($file->getRealPath());
                    }else
                    {
                        unlink($file->getRealPath());
                    }
                }
                rmdir($extractionLocation);
            }else 
            {
                $productsArray['errorCode'] = _ECP .'0014';
                $productsArray['errorDescription'] = 'Error while trying to open archive.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
        }
    }
    
    /*
     * addMassContent Adds the contents of REAL directory on the server into a VFS directory
     * 
     * @param   string  $directoryAddress   The address of the directory where the contents of the zip file will be added.
     * @param   string  $sourceDirName      The full path of the directory whose contents are to be added to the directory.
     * @param   array   $productsArray      An array that on error will contain the error specifics and on success will be empty.
     * 
     * @return  boolean TRUE on success FALSE on failure.
     */
    public function addMassContent($directoryAddress, $sourceDirName, &$productsArray)
    {

    }
    
    /*
     * getFile  Redirects to a file retrieval URL
     * 
     * @param   string  $fileAddress        The address of the file to be retieved.
     * @param   array   $productsArray      An array that on error will contain the error specifics and on success will be empty.
     * 
     * @return  boolean FALSE on failure. On success the user is redirected to the actual file retrieval facility.
     */
    
    public function getFile($fileAddress, &$productsArray)
    {
        if ($this->isAValidAddress($fileAddress, $productsArray, $this->FILE) == TRUE)
        {
            $targetAddress = '6_'. $this->vfsTable->getTableId() .'_'. $this->vfsTable->columns['fileId']->getId() .'_'. $productsArray['vfsRow']['fileId'];
            header('Location: getFile.php?location?'. $targetAddress);
            exit;
        }else
        {
            return FALSE;
        }
    }
    
    /*
     * getFileLink Produces a relative URL for file retrieval.
     * 
     * @param   string  $fileAddress        The address of the file to be retieved.
     * @param   array   $productsArray      An array that on error will contain the error specifics. On success will contain an array entry with 'theFileRetrievalAddress' as key and the file retrieval address as its value.
     * 
     * @return  boolean FALSE on failure TRUE on success.
     */

    public function getFileLink($fileAddress, &$productsArray)
    {
        if ($this->isAValidAddress($fileAddress, $productsArray, $this->FILE) === TRUE)
        {
            $targetAddress = '6_'. $this->vfsTable->getTableId() .'_'. $this->vfsTable->columns['fileId']->getId() .'_'. $productsArray['vfsRow']['fileId'];
            $productsArray['theFileRetrievalAddress'] = 'getFile.php?location?'. $targetAddress;
            return TRUE;
        }else
        {
            return FALSE;
        }
    }
    
    /*
     * deleteFile Deletes a file from the VFS, the actual file system and the __externalFiles
     * 
     * @param   string  $fileAddress        The address of the file to be deleted.
     * @param   array   $productsArray      An array that on error will contain the error specifics. On success will be empty.
     * 
     * @return boolean FALSE on failure TRUE on success.
     */
    public function deleteFile($fileAddress, &$productsArray)
    {
        if ($this->isAValidAddress($fileAddress, $productsArray, $this->FILE) == TRUE)
        {
            $fileRow = $this->db->getRow('__externalFiles', $productsArray['vfsRow']['fileId']);
            
            if ($fileRow===FALSE)
            {
                $productsArray['errorCode'] = _ECP .'0016';
                $productsArray['errorDescription'] = 'File requested could not be extracted from __externalFiles table.';    
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }elseif (!isset($fileRow['id']))
            {
                $productsArray['errorCode'] = _ECP .'0017';
                $productsArray['errorDescription'] = 'File requested could not be found in __externalFiles table.';    
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            
            $result = $this->vfsTable->deleteRow($productsArray['arrayPieces'][2]);
            if ($result === FALSE)
            {
                $productsArray['errorCode'] = _ECP .'0018';
                $productsArray['errorDescription'] = 'Item deletion failed in __VFS.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            
            WOOOF_ExternalFiles::deleteFile($fileRow['id']);
            
            $result = unlink($this->wo->getConfigurationFor('absoluteFilesRepositoryPath') . $fileRow['fileName']);
            if ($result === FALSE)
            {
                $productsArray['errorCode'] = _ECP .'0020';
                $productsArray['errorDescription'] = 'Item deletion failed from actual file system.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            
            $productsArray = array();
            return TRUE;
        }else
        {
            return FALSE;
        }
    }
    
    /*
     * renameEntity Changes the name of an entity
     * 
     * @param   string  $entityAddress  The address of the entity to be renamed
     * @param   string  $newName        The new name of the entity
     * @param   array   $productsArray  An array that on error will contain the error specifics. On success will be empty.
     * 
     * @return  boolean True on success false on error.
     */
    public function renameEntity($entityAddress, $newName, &$productsArray)
    {
        if ($this->isAValidAddress($entityAddress, $productsArray)===FALSE)
        {
            return FALSE;
        }else
        {
            if ($this->vfsTable->getResult($productsArray['addressPieces'][2])===FALSE)
            {
                return FALSE;
            }else
            {
                if (count($this->vfsTable->resultRows)==0)
                {
                    $productsArray['errorCode'] = _ECP .'0021';
                    $productsArray['errorDescription'] = 'The item doesn\'t exist in the __vfs table.';
                    $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                    return FALSE;
                }else
                {
                    $this->vfsTable->resultRows[$productsArray['addressPieces'][2]]['name'] = $newName;
                    if ($this->vfsTable->updateRowFromResults($productsArray['addressPieces'][2])!==FALSE)
                    {
                        $productsArray = array();
                        return TRUE;
                    }else
                    {
                        return FALSE;
                    }                            
                }
            }
        }
    }
    
    /*
     * setPermissions   Set the permissions for a specific entity. Permissions must be set per group and/or per user.
     * 
     * @param   string  $entityAddress  The address of the entity that will have its permissions set.
     * @param   array   $permissions    An array containing permissions to be set.  
     * @param   &array  $productsArray  An array that on error will contain the error specifics or will be empty on success.
     * 
     * @return  boolean True on Success, False on failure.   
     * 
     */
    public function setPermissions($entityAddress,array $permissions, &$productsArray, $recursively = FALSE)
    {
        if ($this->isAValidAddress($entityAddress, $productsArray)===FALSE)
        {
            return FALSE;
        }else
        {
            $foundPermissions = FALSE;
            if (isset($permissions['groups']))
            {
                foreach ($permissions['groups'] as $groupId => $itemPermissions) 
                {
                    foreach ($itemPermissions as $permission => $allow) 
                    {
                        if (in_array($permission, $this->allowedPermissions))
                        {
                            $testResult = $this->db->query('select * from __lrbs where location=\''. $entityAddress .'\' and role=\''. $groupId .'\' and action=\''. $permission .'\'');
                        }else
                        {
                            $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, _ECP .'0028 The supplied permission to Set Permissions in VFS were not in the allowed range.');
                        }
                    }
                }
            }
            if (isset($permissions['users']))
            {
                $foundPermissions = TRUE;
                
                foreach($permissions['users'] as $key => $value)
                {
                    
                }
            }
            
            if ($foundPermissions === FALSE)
            {
                $this->wo->log(WOOOF_loggingLevels::WOOOF_WARNING, _ECP .'0027 The supplied permissions to Set Permissions in VFS were not in the expected format.');
            }
            return TRUE;
        }
    }
    
    /*
     * getAllPermissions   Get the permissions for a specific entity.
     * 
     * @param   string  $entityAddress  The address of the entity that will have its permissions set.
     * @param   &array  $productsArray  An array that on success will have a 'permissions' key containing an array with all the permitions for the entity.
     * 
     * @return  boolean True on Success, False on failure.   
     * 
     */
    public function getPermissions($entityAddress, &$productsArray)
    {
        $result = $this->db->query('select * from __lrbs where address like \''. $this->db->escape($entityAddress) .'%\'');
        if ($result===FALSE)
        {
            return FALSE;
        }
        
        while ($row = $this->db->fetchAssoc($result))
        {
            $productsArray['permissions'][] = $row;
        }
        
        return TRUE;
    }
    
    /*
     * listGroups returns all defined groups for a specific VFS
     * 
     * @param   string  $vfsAddress     Contains the address of the VFS to get the groups from.
     * @param   &array  $productsArray  Will have error infromation on failure or an entry with key 'groupList' that will contain an array with all the groups as row.
     * 
     * @return  boolean True on success False on failure.
     * 
     */
    public function listGroups($vfsAddress, &$productsArray)
    {
        if ($this->isAValidAddress($vfsAddress, $productsArray, $this->VFS)===FALSE)
        {
            return FALSE;
        }else
        {
            $whereClauses['vfsId'] = $productsArray['addressPieces'][1];
            $whereClauses['entityType'] = $this->GROUP;
            $result = $this->vfsTable->getResult($whereClauses);
            if ($result === FALSE)
            {
                return FALSE;
            }
            $productsArray['groupList'] = $this->vfsTable->resultRows;
            return TRUE;
        }
    }
    
    /*
     * addUsersToGroup 
     * 
     * @param   string  $groupAddress   Contains the group address to get the users into.
     * @param   array   $userIds        Contains the user ids to add to the group.
     * @param   &array  $productsArray  Will have error infromation on failure or will be empty on success.
     * 
     * @return  boolean True on success False on failure.
     * 
     */
    public function addUsersToGroup($groupAddress, array $userIds, &$productsArray)
    {
        if ($this->isAValidAddress($groupAddress, $productsArray, $this->GROUP)===FALSE)
        {
            return FALSE;
        }else
        {
            $query = 'insert into __vfsGroupUsers (id, groupId, userId) values ';
            $inString='';
            foreach ($userIds as $userId)
            {
                $userId = (int)$userId;
                $testRow = $this->db->getRow('__users', $userId);
                
                if ($testRow === FALSE)
                {
                    return FALSE;
                }
                
                if (!isset($testRow['id']))
                {
                    $productsArray['errorCode'] = _ECP .'0025';
                    $productsArray['errorDescription'] = 'The user with id "'. $userId .'" doesn\'t exist in the __users table.';
                    $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                    return FALSE;
                }
                if ($inString!='')
                {
                    $inString.=','. $userId;
                    $query.=', (\''. $this->db->getNewId('__vfsGroupUsers') .'\', \''. $this->db->escape($productsArray['addressPieces'][2]) .'\', \''. $userId .'\')';
                }else
                {
                    $inString='('.$userId;
                    $query.='(\''. $this->db->getNewId('__vfsGroupUsers') .'\', \''. $this->db->escape($productsArray['addressPieces'][2]) .'\', \''. $userId .'\')';
                }
            }             
            $inString .= ')';
            $result = $this->db->query('select * from __vfsGroupUsers where groupId=\''. $this->db->escape($productsArray['addressPieces'][2]) .'\' and userId in '. $inString);
            if ($result === FALSE)
            {
                return FALSE;
            }
            if ($this->db->getNumRows($result))
            {
                $productsArray['errorCode'] = _ECP .'0026';
                $productsArray['errorDescription'] = 'One or more users are already in the group.';
                $this->wo->log(WOOOF_loggingLevels::WOOOF_ERROR, $productsArray['errorCode'] .' '. $productsArray['errorDescription']);
                return FALSE;
            }
            
            $result = $this->db->query($query);
            if ($result===FALSE)
            {
                return FALSE;
            }else
            {
                $productsArray = array();
                return TRUE;
            }
        }
    }
    
    /*
     * removeUsersFromGroup 
     * 
     * @param   string  $groupAddress   Contains the group address to get the users into.
     * @param   array   $userIds        Contains the user ids to remove from the group.
     * @param   &array  $productsArray  Will have error infromation on failure or will be empty on success.
     * 
     * @return  boolean True on success False on failure.
     * 
     */
    
    public function removeUsersFromGroup($groupAddress, array $userIds, &$productsArray)
    {
        if ($this->isAValidAddress($groupAddress, $productsArray, $this->GROUP)===FALSE)
        {
            return FALSE;
        }else
        {
            $inString='';
            foreach ($userIds as $userId)
            {
                $userId = (int)$userId;
                if ($inString!='')
                {
                    $inString.=','. $userId;
                    $query.=', (\''. $this->db->getNewId('__vfsGroupUsers') .'\', \''. $this->db->escape($productsArray['addressPieces'][2]) .'\', \''. $userId .'\')';
                }else
                {
                    $inString='('. $userId;
                    $query.='(\''. $this->db->getNewId('__vfsGroupUsers') .'\', \''. $this->db->escape($productsArray['addressPieces'][2]) .'\', \''. $userId .'\')';
                }
            }
            $result = $this->db->query('delete from __vfsGroupUsers where groupId=\''. $this->db->escape($productsArray['addressPieces'][2]) .'\' and userId in '. $inString);
            if ($result===FALSE)
            {
                return FALSE;
            }else
            {
                $productsArray = array();
                return TRUE;
            }
        }
    }
    
}