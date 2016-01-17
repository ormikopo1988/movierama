<?php
/*
 * Wipe Out Object Oriented Framework
 * 
 * The handy-dandy mini framework for your site building pleasure.
 * Implements:
 *              - Mysql Database Encapsulation
 *              - Mysql Database Modification and Development
 *              - Automatic input sanitization
 *              - Complete and continuous DB logging so that the database can be recreated up to any point in time
 *              - Location Role Action Based Security (LoRaBaSe)
 *              - Various facilities that save you time and typing (again and again).
 *              - Automated Administration System.
 *              - Much more ...
 * 
 * Copyright Ioannis Loukeris, Antonis Papantonakis 
 * 
 * Open Source Software . Distributed under the GPL2.
 * 
 * PHP:     5.4.0+ required  (5.3.7+ if directly array dereferencing the result of a function or method call is removed from code) 
 * MySQL:   5+ required
 */

// WOOOF Setup is already loaded by setup.inc.php
// which includes a config/config_XXXXX.php file.  
// All setup is done in this file, edit it at will.

// WOOOF Definitions - Do not edit below unless you know what you are doing!

$WOOOF_VERSION='0.12.54';

class WOOOF_tablePresentationTypes
{
    const   asList                  =   1;
    const   CategorizedList         =   2;
    const   treeView                =   3;
    const   TreeCategorizdedList    =   4;
    const   CompositeTree           =   5;
    
    public static function getTablePresentationLiteral($type)
    {
        switch ($type) {
            case 1:
                $literal='List';
                break;
            case 2:
                $literal='Categorized List';
                break;
            case 3:
                $literal='Tree View';
                break;
            case 4:
                $literal='Tree Categorized List';
                break;
            case 5:
                $literal='Composite Tree';
                break;
        }
        return $literal;
    }
}

class WOOOF_columnPresentationTypes
{
    const   textBox     =   1;
    const   dropList    =   2;
    const   textArea    =   3;
    const   htmlText    =   4;
    const   checkBox    =   5;
    const   date        =   6;
    const   time        =   7;
    const   dateAndTime =   8;
    const   autoComplete=   9;
    const   radioHoriz  =   10;
    const   radioVert   =   11;
    const   file        =   12;
    const   picture     =   13;
    
    public static function getColumnPresentationLiteral($type)
    {
        switch ($type) {
            case 1:
                $literal='textBox';
                break;
            case 2:
                $literal='dropList';
                break;
            case 3:
                $literal='textArea';
                break;
            case 4:
                $literal='htmlText';
                break;
            case 5:
                $literal='checkBox';
                break;
            case 6:
                $literal='date';
                break;
            case 7:
                $literal='time';
                break;
            case 8:
                $literal='dateAndTime';
                break;
            case 9:
                $literal='autoComplete';
                break;
            case 10:
                $literal='radioHoriz';
                break;
            case 11:
                $literal='radioVert';
                break;
            case 12:
                $literal='file';
                break;
            case 13:
                $literal='picture';
                break;
        }
        return $literal;
    }
}

class WOOOF_dataBaseColumnTypes
{
    const int           = 1;
    const float         = 2;
    const char          = 3;
    const varchar       = 4;
    const decimal       = 5;
    const mediumtext    = 6;
    const longtext      = 7;
    
    public static function getColumnTypeLiteral($type)
    {
        switch ($type) {
            case 1:
                $literal='int';
                break;
            case 2:
                $literal='float';
                break;
            case 3:
                $literal='char';
                break;
            case 4:
                $literal='varchar';
                break;
            case 5:
                $literal='decimal';
                break;
            case 6:
                $literal='mediumtext';
                break;
            case 7:
                $literal='longtext';
                break;
            default:
            	$literal=FALSE;
        }
        return $literal;
    }

    public static function getColumnTypeReverse($literal)
    {
    	$literal = strtolower($literal);
    	
        switch ($literal) {
            case 'int':
                $constant=1;
                break;
            case 'float':
                $constant=2;
                break;
            case 'char':
                $constant=3;
                break;
            case 'varchar':
                $constant=4;
                break;
            case 'decimal':
                $constant=5;
                break;
            case 'mediumtext':
                $constant=6;
                break;
            case 'longtext':
            case 'text':
            	$constant=7;
                break;
            default:
            	return false;
        }
        return $constant;
    }
}

class WOOOF_databaseLoggingModes
{
    const   logAllQueries               =   1;
    const   doNotLogSelects             =   2;
    const   doNotLogSelectsDescrShow    =   3;
}

class WOOOF_loggingLevels
{
    const WOOOF_NO_LOGGING              = FALSE;
    const WOOOF_CRITICAL_ERROR          = 1;
    const WOOOF_LOG_ERRORS_ONLY         = 2;
    const WOOOF_ERROR                   = 2;
    const WOOOF_LOG_WARNINGS            = 3;
    const WOOOF_WARNING                 = 3;
    const WOOOF_LOG_NOTICES             = 4;
    const WOOOF_NOTICE                  = 4;
    const WOOOF_LOG_STATUSES            = 5;
    const WOOOF_STATUS                  = 5;

    public static function getErrorTypeLiteral($type)
    {
        switch ($type) 
        {
            case 1:
                $literal='WOOOF_CRITICAL_ERROR';
                break;
            case 2:
                $literal='WOOOF_ERROR';
                break;
            case 3:
                $literal='WOOOF_WARNING';
                break;
            case 4:
                $literal='WOOOF_NOTICE';
                break;
            case 5:
                $literal='WOOOF_STATUS';
                break;
            default:
            	$literal='WOOOF_CRITICAL_ERROR';
        }
        return $literal;
    }

    public static function getPHPErrorLiteral($type)
    {
        switch ($type) 
        {
            case E_ERROR:
                $literal='E_ERROR';
                break;
            case E_NOTICE:
                $literal='E_NOTICE';
                break;
            case E_WARNING:
                $literal='E_WARNING';
                break;
            case E_CORE_WARNING:
                $literal='E_CORE_WARNING';
                break;
            case E_COMPILE_ERROR:
                $literal='E_COMPILE_ERROR';
                break;
            case E_COMPILE_WARNING:
                $literal='E_COMPILE_WARNING';
                break;
            case E_USER_ERROR:
                $literal='E_USER_ERROR';
                break;
            case E_USER_WARNING:
                $literal='E_USER_WARNING';
                break;
            case E_USER_NOTICE:
                $literal='E_USER_NOTICE';
                break;
            case E_RECOVERABLE_ERROR:
                $literal='E_RECOVERABLE_ERROR';
                break;
            case E_DEPRECATED:
                $literal='E_DEPRECATED';
                break;
            case E_USER_DEPRECATED:
                $literal='E_USER_DEPRECATED';
                break;
            default:
                $literal='E_ERROR';
                break;
        }
        return $literal;
    }

    public static function getWOOOFErrorFromPHPErrorLevel($phpErrorLevel)
    {
        switch ($phpErrorLevel) {
            case E_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_NOTICE:
            case E_DEPRECATED:
                $wooofError = WOOOF_loggingLevels::WOOOF_ERROR;
                break;
            case E_USER_NOTICE:
            case E_USER_DEPRECATED:
                $wooofError = WOOOF_loggingLevels::WOOOF_NOTICE;
                break;
            case E_USER_WARNING:
            case E_COMPILE_WARNING:
            case E_CORE_WARNING:
            case E_WARNING:
                $wooofError = WOOOF_loggingLevels::WOOOF_WARNING;
                break;
            default:
                $wooofError = WOOOF_loggingLevels::WOOOF_ERROR;
                break;
        }
        return $wooofError;
    }
}

//Actual Classes start here

/*
 * WOOOF
 * 
 * The framework's main class
 * Automatically initializes the databases on initialization
 * and provides various helper methods for the construction and display of pages
 * It is advisable for performance reasons not to create more than one instance
 * TODO: modify this class to singleton
 * 
 */
class WOOOF
{
	const _ECP = 'WOO';	// Error Code Prefix
	
    public $dataBases;  			// array with all the database objects defined 
    public $db;         			// the default database for easy access
    public $sid = NULL;				// session identifier
    public $userData;				// a (safe) copy of the $userData global
    public $version;
    
    private $configuration;			// WOOOF configuration options/values
    private $configurationCustom;	// Custom (Application) configuration options/values
    private $dateTime;
    private $currentMicroTime;
    private $memCache;
    private $errors;				// array of array( 0: message, 1: type, 2: code, 3: time, 4: backtrace )	// antonis
    private $debugMessages;
    
    private $originalPostValues;
    private $originalFilesValues;
    
    public $constructedOk = true;	// check this after a new to ensure object was created successfully
    public $doDebug = false;		// Configurable but client can change at will (thus public).
    public $isAjax  = false;
    public $isProductionEnv = false;
    public $assetsURL = '';         // For use in loading css, js, etc. Ininitialised below.
    public $imagesURL = '';         // For use in loading user provided images
    
    public $app;					// Reserved for loading Application specific data
    
    // public $rightsCache = array();	// antonis
    

    public static $instance = NULL;
    
    private $_inLog = false;		// protect infinite recursion with the log function
    
    
    public function __construct(
    		$checkSessionAndActionAndActivateLogging=TRUE, 
    		$pageLocation=null, 
    		$requestedAction=null, 
    		$checkLocationAction=true 
    ) 
    {
        global $WOOOF_VERSION;
        if ( $pageLocation == NULL ) {
        	unset($pageLocation, $requestedAction);
			global $pageLocation;
			global $requestedAction;
        }
        global $userData;

        global $__isSiteBuilderPage;
        global $__isAdminPage;
        
        global $wooofConfigOptions;
        global $wooofConfigCustomOptions;
        
        if ( WOOOF::$instance !== NULL ) {
        	//return;															// forgive
        	exit('WOOOF constructor: WOOOF has already been constructed!');		// punish
        }
        
        if ( !$checkSessionAndActionAndActivateLogging ) {
        	$checkLocationAction = false;
        }
        
        $this->version  = $WOOOF_VERSION;
        
        $this->originalPostValues 	= $_POST;
        $this->originalFilesValues 	= $_FILES;
        
        $this->isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        // Expected WOOOF config option names
   		$configOptionNames = array(
			'siteName',
   			'siteURLStart',
			'defaultDBIndex',
			'databaseName',
			'databaseUser',
			'databasePass',
			'databaseHost',
			'databaseLog',
			'databaseAutoCommit',
			'databaseSQLMode',
   			'fileLog',
			'logTable',
			'logFilePath',
			'debugLogPath',
			'debugMessagesLogLevel',
			'sendEmailOnError',
			'displayDatabaseErrors',
			'displayScriptErrors',
   			'displaySQLStatementsLevel',
   			'debugSQLStatementsLevel',
			'sessionExpirationPeriod',
			'aggressiveSecurity',
			'antiFloodProtection',
			'storeUserPaths',

   			'siteBaseURL',
			'siteBasePath',
   			'publicSite',
			'absoluteFilesRepositoryPath',
			'imagesRelativePath',

   			'adminMainFileName',
			'adminURL',
			'adminIncludesDirectory',
   			'dbManagerBaseURL',
   					
   			'templatesRepository',
			'applicationTemplatesRepository',
   			'cssFileNameForTinyMCE',
			'cssForFormItem',
			'isCacheEnabled',
			'isMemCacheEnabled',
			'memCacheServers',
			'domainNameForCookies',
			'minimumPasswordLength',
			'minimumCapitalsInPassword',
			'minimumNumbersInPassword',
			'minimumSymbolsInPassword',
   			'classesPath',
   			'wooofClassesPath',
   			'showStopperErrorRoutine',
   			'saltProductionMethod',
   			'initApplicationRoutine',
   		);

  		 
		// Init with some defaults
		//
		$this->isProductionEnv = ( defined('WOOOF_ENVIRONMENT') ? ( strtolower(substr(WOOOF_ENVIRONMENT,0,4))=='prod' ? true : false ) : true );

        $this->configuration = array(
        	'siteURLStart'						=> '',
			'defaultDBIndex'					=> 0,
			'debugMessagesLogLevel' 			=> WOOOF_loggingLevels::WOOOF_LOG_STATUSES,
			'sendEmailOnError'					=> '',
			'displayDatabaseErrors'				=> true,
			'displayScriptErrors'				=> true,
        	'displaySQLStatementsLevel'			=> 1,
        	'debugSQLStatementsLevel'			=> 1,
			'sessionExpirationPeriod' 			=> '6 months',
        	'templatesRepository'				=> '../wooof_fragments/',
        	'applicationTemplatesRepository' 	=> 'fragments/',	// inside publicSite
        	'showStopperErrorRoutine'			=> '',				// A funcion in commonApp.php, or any callable in one of the App Classes.
			'saltProductionMethod'				=> '',   
			'initApplicationRoutine'			=> '',
        	'publicSite'						=> 'publicSite/',
        	'dbManagerBaseURL'					=> 'wooof_dbManager/',
        	'adminURL'							=> 'wooof_administration/',
        	'adminMainFileName'					=> 'administration.php',
        	'adminIncludesDirectory'			=> 'adminIncludes/',
        	'classesPath'						=> 'classes/',
        	'wooofClassesPath'					=> 'wooof_classes/',
        );
        
        if ( $this->isProductionEnv ) {
        	// Override some defaults for Production environments. 
        	// Maybe overriden by actual config entries.
        	$this->configuration['displayDatabaseErrors'] 		= false;
        	$this->configuration['displayScriptErrors']   		= false;
        	$this->configuration['displaySQLStatementsLevel']	= 0;
        }
        
        $this->errors = array();

        // Reveal forgotten / extra config options expected in WOOOF
        // foreach($configOptionNames as $aVal ) { if ( !isset($wooofConfigOptions[$aVal]) ) { echo "A [$aVal]" . '<br>'; } }
        // foreach($wooofConfigOptions as $aKey => $aVal ) { if ( !in_array($aKey,$configOptionNames) ) { echo "B [$aKey]" . '<br>'; } }
        // die();
        
        
        // Allow backwards compatibility (config options defined as multiple global variables).
        $configInput = ( isset($wooofConfigOptions) ? $wooofConfigOptions : $GLOBALS );
        unset($GLOBALS['wooofConfigOptions']);
        
        foreach( $configInput as $aKey => $aVal ) {
        	if ( !in_array( $aKey, $configOptionNames) ) {
        		continue;
        	}
        	else {
        		$this->configuration[$aKey] = $aVal;
        	}
        }	// foreach provided option
        
        // TODO: Make some checks... e.g. for 
        
        $dbIsPresent = ( isset($this->configuration['databaseName']) );
        
        if ( $dbIsPresent ) {
	        foreach( $this->configuration['databaseName'] as $aKey => $aName ) {
	        	if ( !isset($this->configuration['databaseAutoCommit'][$aKey]) ) {
	        		$this->configuration['databaseAutoCommit'][$aKey] = true;
	        	}
	            if ( !isset($this->configuration['databaseSQLMode'][$aKey]) ) {
	        		$this->configuration['databaseSQLMode'][$aKey] = '';
	        	}
	        }
        }
                
		// Make 'applicationTemplatesRepository' absolute, so that it can be found by custom admin code as well.
        $this->configuration['applicationTemplatesRepository'] =
        	$this->configuration['siteBasePath'] . $this->configuration['publicSite'] . $this->configuration['applicationTemplatesRepository'];

        if ( !$this->hasContent($this->configuration['siteURLStart']) ) {
        	if (isset($_SERVER['HTTP_HOST']))
        	{
        		$siteURLStart = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        		$siteURLStart .= '://'. $_SERVER['HTTP_HOST'];
        		$siteURLStart .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        	}
        	
        	else
        	{
        		$siteURLStart = 'http://localhost/';
        	}
        }
        $this->configuration['siteURLStart'] = $siteURLStart;
        
        $this->assetsURL = $this->configuration['siteBaseURL'] . $this->configuration['publicSite'];
        $this->imagesURL = $this->configuration['siteBaseURL'] . $this->configuration['publicSite'] . $this->configuration['imagesRelativePath'];

        // Extra/Custom Configurations
        if ( isset($wooofConfigCustomOptions) && is_array($wooofConfigCustomOptions) ) {
        	$this->configurationCustom = $wooofConfigCustomOptions;
        }

        // General settings and handlers
        //
        // error_reporting(E_ALL  /*& ~E_NOTICE*/); // does not play a role ??
        mb_internal_encoding("UTF-8");
        set_error_handler(array($this, "handleError"));
        register_shutdown_function(array($this, "handleShutdown"));
        spl_autoload_register(array($this, 'handleClassAutoloader'));
        
        
        WOOOF::$instance = $this;
        
        $sessionsToDebug = $this->getConfigurationFor('sessions', 'debug');
        $debugAll = ( $sessionsToDebug != NULL && is_array($sessionsToDebug) && count($sessionsToDebug) > 0 && $sessionsToDebug[0] == 'ALL' );
        $this->doDebug = ( $debugAll || ( is_array($sessionsToDebug) && in_array( $this->sid, $sessionsToDebug ) ) );
        
        
        if ( $dbIsPresent ) {
	        for($dbCount=0; $dbCount<count($this->configuration['databaseName']); $dbCount++)
	        {
	            if ($this->configuration['databaseName'][$dbCount] != '')
	            {
	                $this->dataBases[$dbCount] = new WOOOF_dataBase(microtime(true));
	                
	                if ($this->configuration['defaultDBIndex'] == $dbCount)
	                {
	                    $this->db = $this->dataBases[$dbCount];
	                }
	                if ($checkSessionAndActionAndActivateLogging)
	                {
	                    $this->dataBases[$dbCount]->loggingToDatabase($this->configuration['databaseLog'][$dbCount], $this->configuration['logTable'][$dbCount]);
	                    $this->dataBases[$dbCount]->loggingToFile($this->configuration['fileLog'][$dbCount], $this->configuration['logFilePath'][$dbCount]);
	                }else
	                {
	                    $this->dataBases[$dbCount]->loggingToDatabase(FALSE, $this->configuration['logTable'][$dbCount]);
	                    $this->dataBases[$dbCount]->loggingToFile(FALSE, $this->configuration['logFilePath'][$dbCount]);
	                }
	                if ($__isAdminPage == true || $__isSiteBuilderPage == true)
	                {
	                    $this->dataBases[$dbCount]->setLoggingType(WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow,WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow);
	                }
	            }
	        }
        }
        
        $this->currentMicroTime=microtime(true);
        
        $this->dateTime = date('YmdHis');
        
        
        if ($checkSessionAndActionAndActivateLogging)
        {
            $bR = $this->db->query('select * from __bannedIPs where IP=\''. $this->cleanUserInput($_SERVER['REMOTE_ADDR']) .'\' and banExpiration>\''. $this->dateTime .'\'');
			if ( $bR === FALSE ) { $this->handleConstructorError('Failed checking banned IPs.'); return; }
			
            if(mysqli_num_rows($bR))
            {
            	// Intentionally die here as we are under attack (or so it seems).
            	//$this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, "IP [".$this->cleanUserInput($_SERVER['REMOTE_ADDR'] )." is banned");
                die('you are banned!');
                exit;
            }
            
            if (!$this->sessionCheck())
            {
                $this->newSession('0123456789');
            }
            
            // antonis
            // Global $userData has been set at this point.
            // $this->sid has been set at this point (but may be empty).
            
            $this->userData = $userData;	// needed here as it is used in getSecurityPermitionsForLocationAndUser. 
        	
            // Fill-in userRoles... cache
            $userId = $userData['id'];
        	$this->userRolesSQLString = '';
            $result = $this->db->query("select r.role, r.id from __userRoleRelation ur, __roles r where ur.userId = '$userId' and r.id = ur.roleId" );
            
            if ( $result === FALSE ) { $this->handleConstructorError('Failed getting user roles.'); return; }
            	
            while($p = $this->db->fetchRow($result)) {
            	$this->userRolesArray[$p[0]] = 1;	// 1 could be anything
            	$this->userRolesSQLString .= "'" . $p[1] . "',";
            }
            $this->userRolesSQLString = substr($this->userRolesSQLString, 0 , strlen($this->userRolesSQLString)-1 );
            
            
            if ($this->configuration['storeUserPaths'])
            {
                $this->db->query('insert into __userPaths set sessionId=\''. $this->cleanUserInput($this->sid) .'\', requestPage=\''. $this->cleanUserInput($_SERVER['REQUEST_URI']) .'\', requestData=\''. $this->cleanUserInput(serialize($_POST)) .'\', timeStamp=\''. $this->dateTime .'\'');
            }
        
            if ($this->configuration['antiFloodProtection']>0)
            {
            	$_ip = $this->cleanUserInput($_SERVER['REMOTE_ADDR']);
                $requestsLastSecondR = $this->db->query('SELECT count(*) FROM __userPaths where sessionId=\''. $this->cleanUserInput($this->sid) .'\' and timeStamp>\''. date('YmdH'). (date('is') -1) .'\'');
                $requestsLastSecond = $this->db->fetchRow($requestsLastSecondR);
                if ($requestsLastSecond[0] >= $this->configuration['antiFloodProtection']-1)
                {
                    $bR = $this->db->query('select * from __bannedIPs where IP=\''. $_ip .'\'');
                    if (mysqli_num_rows($bR)>5)
                    {
                        $when = strtotime("+3 days");
                    }elseif (mysqli_num_rows($bR)>1)
                    {
                        $when = strtotime("+2 days");
                    }elseif (mysqli_num_rows($bR))
                    {
                        $when = strtotime("+1 days");
                    }else
                    {
                        $when = strtotime("+6 hours");
                    }
                    $this->db->query('insert into __bannedIPs set IP=\''. $_ip .'\', banExpiration=\''. $when .'\'');
                    $this->db->commit();
                    $this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0010 IP [$_ip] is now banned!"  );
                    exit;
                }
            }
        }

        $this->userData = $userData;	// set also above.
        
        if ( $dbIsPresent ) {
	        $this->db->commit();			// Need to commit here to save session data for sure.
        }

        if ( $checkLocationAction ) {
			$res = $this->checkLocationAndAction( $pageLocation, $requestedAction );
			if ( $res === FALSE ) { $this->handleConstructorError('Failed in checkLocationAndAction.'); return; }
        }	// if $checkLocationAction
        
        if ($this->configuration['isMemCacheEnabled'])
        {
            $this->memCache = new Memcached();
            foreach($server as $this->configuration['memCacheServers'])
            {
                $this->memCache->addServer($server);
            }
        }
        
        if ( !$__isAdminPage and !$__isSiteBuilderPage ) {
        	// Call any defined app init routine
	        $customHandler = $this->getConfigurationFor('initApplicationRoutine');
	        if ( WOOOF::hasContent($customHandler) ) {
	        	if ( !is_callable($customHandler) ) {
	        		$this->logError( self::_ECP."0520 Custom Application Init function [$customHandler] not found!");
	        	}
	        	$res = call_user_func($customHandler, $this);
	        	if ( $res === FALSE ) {
	        		$this->logError( self::_ECP."0500 Custom Application Init function returned FALSE");
	        	}
	        }
        }	// not for admin or dbManager users        
    }	// __construct
    
    
    
    function __destruct()
    {
    	//$this->debug( 'in __destruct' );
    	$succ1 = $this->writeDebugsAndClear();
        $succ2 = $this->writeErrorsAndClear();
    }	// __destruct
    
    private function handleConstructorError( $message, $messageCode='' ) {
    	$this->constructedOk = false;
    	// As existing code does not make this check, die here to avoid messing things up.
    	die( trim($messageCode . ' ' . $message. '<br>Are you sure you connect to a WOOOF enabled Database??') );
    }
    
    /**
     * 
     * @return boolean
     */
    private function writeErrorsAndClear() 
    {
    	// TODO: consider using error_log instead ?
    	if (count($this->errors)>0)
    	{
    		$f=fopen($this->getConfigurationFor('debugLogPath') . $this->getConfigurationFor('siteName') . '_errorMessages.log', 'a');
    	    if ($f === FALSE ) { 
    	    	echo '<br>Error Log file opening failed!<br>';
    	    	return false; 
    	    }
    		
            //fputs($f, PHP_EOL . 'Start: ' . $this->currentGMTDateTimeAndXSeconds(). ' ' . str_repeat('=', 60) . PHP_EOL);
            foreach( $this->errors as $anError ) {
    			// array of array( 0: message, 1: type, 2: code, 3: time, 4: backtrace )
    			$fullMessageString =
    				WOOOF_loggingLevels::getErrorTypeLiteral($anError[1]) .' | '.
    				$anError[2] . ' | ' .
    				$anError[0]
    			;
                $endOfMessage = ( $anError[4] != '' ? PHP_EOL . rtrim($anError[4]) : '' );
    			fputs($f, PHP_EOL . $anError[3] . ' | ' . $this->sid . " | " . $_SERVER['REMOTE_ADDR'] . ' | ' . $fullMessageString . $endOfMessage . PHP_EOL );
    		}
    	    //fputs($f, 'End: ' . $this->currentGMTDateTimeAndXSeconds(). ' ' . str_repeat('=', 60) . PHP_EOL);
    		fclose($f);
    	}
    	$this->errors = array();
    	
    	return true;
    	   
    }	// writeErrorsAndClear
    
    /**
     * 
     * @return boolean
     */
    private function writeDebugsAndClear() 
    {
    	// TODO: consider using error_log instead ?
    	
    	if ( $this->doDebug )
        {
            $f=fopen($this->getConfigurationFor('debugLogPath') . $this->getConfigurationFor('siteName') . '_debugMessages_' . $this->sid . '.log', 'a');
            if ($f === FALSE ) { 
    	    	echo '<br>Debug Log file opening failed!<br>';
            	return false; 
            }
            
            fputs($f, PHP_EOL . 'Start: ' . '======================' .  str_repeat('=', 60) );
            fputs($f, PHP_EOL);

            fputs($f, $_SERVER['REQUEST_URI'] );
            fputs($f, PHP_EOL);

            if ( isset($this->originalPostValues) && count($this->originalPostValues) > 0 ) {
            	fputs($f, print_r($_POST,true) . PHP_EOL );
            }
            
            if (isset($this->originalFilesValues) && count($this->originalFilesValues) > 0 ) {
            	fputs($f, 'FILES:' . PHP_EOL );
            	foreach( $this->originalFilesValues as $aFile => $aFileData ) {
            		if ( is_array($aFileData['name']) ) {
            			foreach( $aFileData['name'] as $aCounter => $aFile2 ) {
            				fputs($f, $aFile . "($aCounter)" . ': [' . $aFileData['name'][$aCounter] . '] [' . $aFileData['type'][$aCounter] . '] [' . $aFileData['size'][$aCounter] . '] [' . $aFileData['error'][$aCounter] . ']' . PHP_EOL );
            			} 
            		}
            		else {
	            		fputs($f, $aFile . ': [' . $aFileData['name'] . '] [' . $aFileData['type'] . '] [' . $aFileData['size'] . '] [' . $aFileData['error'] . ']' . PHP_EOL );
            		}
            	}
            	fputs($f, PHP_EOL );
            }
            fputs($f, "WOOOF Version: " . $this->version  );
            if ( count($this->getConfigurationFor('databaseHost')) > 0 ) { 
            	fputs($f,            	
            	" | dbHost: " . $this->getConfigurationFor('databaseHost')[$this->getConfigurationFor('defaultDBIndex')] .
            	" | dbName: " . $this->getConfigurationFor('databaseName')[$this->getConfigurationFor('defaultDBIndex')]
            	);
            }

            fputs($f,
            	" | WOOOF_ENVIRONMENT: " . ( defined('WOOOF_ENVIRONMENT') ? WOOOF_ENVIRONMENT : '*Not set*' )
            );
            
            fputs($f, PHP_EOL );
            
            fputs($f,
            	"IP " . $_SERVER['REMOTE_ADDR'] .
            	" | userId: " . $this->userData['id'] .
            	PHP_EOL
            );
                        
            if (count($this->debugMessages) > 0) {
	            fputs($f, implode('', $this->debugMessages));
			}
            fputs($f, 'End: ' . $this->currentGMTDateTimeAndXSeconds() . ' ' . str_repeat('=', 60) . PHP_EOL);
            fclose($f);
        }
        $this->debugMessages = array();

        return true;
    }	// writeDebugsAndClear
    
    
    public function handleError($errno, $errstr, $errfile='', $errline='', $errcontext ) // antonis
    {
        //$message = '[PHP: '. WOOOF_loggingLevels::getPHPErrorLiteral($errno) .'] '. $errstr;

        $message = "lvl: " . WOOOF_loggingLevels::getPHPErrorLiteral($errno) . " | message:" . $errstr . " | file:" . $errfile . " | line:" .$errline;
        
        $this->log(WOOOF_loggingLevels::getWOOOFErrorFromPHPErrorLevel($errno), self::_ECP."9999 ".$message);
    }

    public function handleClassAutoloader( $className ) 
    {
    	// TODO: Just a naive implementation
    	// Looking at:
    	//		siteBasePath/classes/
    	//		siteBasePath/classes/thirdParty/
    	//		siteBasePath/wooof_classes/
    	//		siteBasePath/wooof_classes/thirdParty/
    	
        $filename = $this->getConfigurationFor('siteBasePath') . $this->getConfigurationFor('classesPath') . $className . '.php';
    	
    	if ( !file_exists($filename) ) {
    		//echo "[$filename] not found<br>";
    	    $filename = $this->getConfigurationFor('siteBasePath') . $this->getConfigurationFor('classesPath') . 'thirdParty/' . $className . '.php';
    	    if ( !file_exists($filename) ) {
	    		$filename = $this->getConfigurationFor('siteBasePath') . $this->getConfigurationFor('wooofClassesPath') . $className . '.php';
	    	    if ( !file_exists($filename) ) {
	    			//echo "[$filename] not found<br>";
	    	    	$filename = $this->getConfigurationFor('siteBasePath') . $this->getConfigurationFor('wooofClassesPath') . 'thirdParty/' . $className . '.php';
	    	    	if ( !file_exists($filename) ) {
	    				//echo "[$filename] not found<br>";
	    	    		return false;
	    	    	}
	    	    }
    	    }
		}
    	
    	require( $filename );
    }	// handleClassAutoloader

    public function handleShutdown() //will be called when php script ends (even for Fatal errors).
    {
    	//$this->debug( 'in handleShutdown' );
        $lasterror = error_get_last();
        
        if ( count($lasterror) > 0 ) {
	        switch ($lasterror['type'])
	        {
	            case E_ERROR:
	            case E_CORE_ERROR:
	            case E_COMPILE_ERROR:
	            case E_USER_ERROR:
	            case E_RECOVERABLE_ERROR:
	            case E_CORE_WARNING:
	            case E_COMPILE_WARNING:
	            case E_PARSE:
	            	$error = "[URECOVERABLE PHP ERROR] lvl: " . WOOOF_loggingLevels::getPHPErrorLiteral($lasterror['type']) . " | message:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | line:" . $lasterror['line'];
	            	$this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."9000 ".$error);
	            	break;
	            default:
	            	echo 'udefined level '. $lasterror['type'];
	            	break;
	        }

	        // need to call it only for fatal errors. In normal cases it is called right after this handler.
	        $this->__destruct();	
        }
                
    } 

    public static
    function getWOOOF( $pageLocation, $requestedAction, WOOOF $wo=NULL ) {
    	if ( $wo===NULL && ($wo=WOOOF::$instance)===NULL ) {
    		$wo = new WOOOF(true,$pageLocation,$requestedAction);
    		if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }
    	}
    	else {
    		$succ = $wo->checkLocationAndAction( $pageLocation, $requestedAction );
    		if ( $succ !== TRUE ) {
    			$wo->handleShowStopperError('Security Error');
    		}
    	}
    	
    	return $wo;
    }
    
    public
    function checkLocationAndAction( $pageLocation, $requestedAction ) {
    	global $__isAdminPage, $__isSiteBuilderPage;
    	
    	$security = $this->db->getSecurityPermitionsForLocationAndUser($pageLocation, $this->userData['id']);

    	if (isset($security) && is_array($security))
    	{
    		if (!isset($security[$requestedAction]) || $security[$requestedAction]!==TRUE)
    		{
    			if ($__isAdminPage == true || $__isSiteBuilderPage == true)
    			{
    				header("Location: logIn.php");
    				exit;
    			}else
    			{
    				die('Security failure: you don\'t have permission to perform the requested action.');
    			}
    		}
    	}else 
    	{
    		if ($__isAdminPage == true || $__isSiteBuilderPage == true)
    		{
    			header("Location: logIn.php");
    			exit;
    		}else
    		{
    			die('Security failure: you don\'t have permission to perform the requested action.');
    		}
    	}
    	
    	// we are ok
    	return true;
    } 	// checkLocationAndAction
    
    
    // Utility functions
    //
    
    /**
     * 
     * @param string | number | array $string
     * @return boolean
     */
    public static
    function hasContent($string) {
    	if ( $string === 0 ) return true;
    	if ( $string === 0.0 ) return true;
    	if ( $string == null ) {
    		return false;
    	}
    	if ( is_array($string) ) { return count($string) > 0; }
    	return trim($string)!='';
    }
    
    /**
     * 
     * @param mixed[] $array
     * @param mixed $index 
     * @return boolean
     */
    public static
    function hasContentArrayEntry($array, $index) {
    	if ( !isset($array[$index]) ) { return false; }
    	$elem = $array[$index];
    	if ( is_array($elem) ) {
    		return ( count($elem) > 0 );
    	}
    	else {
	    	return self::hasContent($array[$index]);
    	}
    }
    
    /**
     * 
     * @param mixed $p_string
     * @param mixed $p_default	// optional. Default is NULL
     * @return mixed
     */
    public static
    function myNVL( $p_string, $p_default=NULL) {
    	return ( self::hasContent( $p_string ) ? $p_string : $p_default );
    }
    
    /**
     * 
     * @param string[] $paramNames
     * @return string[''=>''] // associative array of param names/values
     */
	function getMultipleGetPost( $paramNames ) {
		$in = array();
		foreach( $paramNames as $aParamName ) { 
			$in[$aParamName] = $this->initFromGetPost($aParamName);
		}
		return $in;
	}

	/**
	 *
	 * @param mixed[] $p_array
	 * @param string|int $p_key
	 * @param mixed $p_default
	 * @return mixed
	 */
	function getFromArray( $p_array, $p_key, $p_default=null ) {
		if ( isset($p_array[$p_key]) ) { return $p_array[$p_key]; }
		return $p_default;
	}
	
    /**
     * 
     * @param number $xseconds
     * @return string
     */
    static function currentGMTDateTimeAndXSeconds($xseconds=3) {
    	$m = explode(' ',microtime());
    	return gmdate( "Y-m-d H:i:s" ) . "." . str_pad( (int)round($m[0]*(pow(10,$xseconds)),$xseconds), $xseconds, '0' ) ;
    }

    /**
     * 
     * @return string
     */
    static function currentGMTDateTime() {
    	return gmdate( "YmdHis" );
    }

    /**
     * 
     * @param string $p_parameter_name
     * @param string $p_default
     * @param string $p_post_bool
     * @param string $p_get_bool
     * @return NULL|string
     */
	public function initFromGetPost( $p_parameter_name, $p_default=null, $p_post_bool=true, $p_get_bool=true )
	  {
	    $l_ret = null;
	    
	    if ( $p_post_bool ) {
	    	$l_ret = ( isset($_POST[$p_parameter_name]) ) ? $_POST[$p_parameter_name] : null ;
	    }
	  	if ( !WOOOF::hasContent($l_ret) and $p_get_bool ) {
	    	$l_ret = ( isset($_GET[$p_parameter_name]) ) ? $_GET[$p_parameter_name] : null ;
	    }
	    if ( !WOOOF::hasContent($l_ret) ) {
	    	$l_ret = $p_default;
	    }
	    return $l_ret;
	  }  // initFromGetPost
  
  
    public function log($messageType, $message, $includeBackTrace = TRUE, $sendEmail = TRUE, $showInBrowser = TRUE)
    {
   	if ( $this->_inLog ) { /*echo $message;*/ return; }
   	$this->_inLog = true;

        list($first_word) = explode(' ', trim($message));

        $messageCode = ( preg_match('/^[a-zA-Z]{3}[0-9]{4}$/' ,trim($first_word) ) === 0 ? '' : trim($first_word) );

        if ($messageCode=='')
        {
            $messageCode = ( preg_match('/^[\d]{4}$/' ,trim($first_word) ) === 0 ? '' : trim($first_word) );
        }
    	if ( $messageCode != '' ) {
    		$message = trim( mb_substr( $message, mb_strlen($messageCode) ) );
    	}
    	$messageTime = $this->currentGMTDateTimeAndXSeconds();
    	
    	$backTrace= '';
    	
        if ($messageType == WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR || $includeBackTrace)
        {
        	$rawBacktrace = debug_backtrace();	// with arguments
        	//$rawBacktrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);	// without arguments
        	 
            foreach (array_reverse($rawBacktrace) as $value) 
            {
                if (!isset($value['function'])) $value['function']='';
                if (!isset($value['class'])) $value['class']='';
                if (!isset($value['file'])) $value['file']='';
                if (!isset($value['line'])) $value['line']='';
                
                if (  $value['class'] == 'WOOOF' && 
                	  ( in_array( $value['function'], array('log', 'logError', 'handleError', 'handleShowStopperError', 'debug' ) ) ) ) {
                	continue;
                }
                
                $backTrace .= ' - Function `'. $value['class'] . '.' . $value['function'] .'` was called at '. $value['file'] .' at line '. $value['line'] . PHP_EOL;
                
                if (isset($value['args']) && is_array($value['args']) && count($value['args']))
                {
                    $backTrace .= '   Parameters: ';
                    foreach ($value['args'] as $key => $val) 
                    {
                        if (is_object($val)) 
                        {
                            $backTrace .= "[Object of class ". get_class($val) ."] ";
                        }elseif (is_array($val))
                        {
                            $backTrace .= "[Array of ". count($val) ." items] ";
                        }elseif (is_resource($val))
                        {
                            $backTrace .= '[ Resource ]';
                        }else 
                        {
                            $backTrace .= "[" . mb_strimwidth($val, 0, 500, ' ...(truncated)') . "] ";
                        }
                    }
                    $backTrace .= PHP_EOL;
                }
            }
        }
        
	    $debugMessage = 
	      	$messageTime . ' | ' . 
	       	str_pad( WOOOF_loggingLevels::getErrorTypeLiteral($messageType), 15, ' ' ) .' | '. 
	       	str_pad( $messageCode, 7, ' ' ) . ' | ' .
	       	$message
	    ;
        
        if ($messageType == WOOOF_loggingLevels::WOOOF_ERROR || $messageType == WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR)
        {
        	// array of array( 0: message, 1: type, 2: code, 3: time, 4: backtrace )
        	$this->errors[] = array( $message, $messageType, $messageCode,  $messageTime, $backTrace );

        	if ($showInBrowser == TRUE && $this->configuration['displayScriptErrors'] && !$this->isAjax ) {
        		// Protect response to ajax by not displaying the error
	            echo '<br><span style="font-size:14px;color:red;background-color:yellow;">' . nl2br( $debugMessage ) . '</span><br>';
        	}
        	
            if ( WOOOF::hasContent($this->getConfigurationFor('sendEmailOnError')) && $sendEmail)
            {
            	// TODO: Fix for CLI mode
            	$messageForEmail = 
            		$_SERVER['SERVER_ADDR'] . ' | ' .
            		$_SERVER['REQUEST_URI'] . ' | ' . PHP_EOL .             	
            		"WOOOF Version: " . $this->version . 
            		" | userId: " . $this->userData['id']
				;            	
            	if ( count($this->getConfigurationFor('databaseHost')) > 0 ) {
            		$messageForEmail .=
            			" | dbHost: " . $this->getConfigurationFor('databaseHost')[$this->getConfigurationFor('defaultDBIndex')] .
            			" | dbName: " . $this->getConfigurationFor('databaseName')[$this->getConfigurationFor('defaultDBIndex')]
            		;
            	}
            	
            	$messageForEmail .=
            		" | WOOOF_ENVIRONMENT: " . ( defined('WOOOF_ENVIRONMENT') ? WOOOF_ENVIRONMENT : '*Not set*' )
            	;
            	 
                $this->sendMail(
                	'',
                	$this->getConfigurationFor('sendEmailOnError'), 
                	'Error occured on site '. $this->getConfigurationFor('siteName'), 
                	$messageForEmail . PHP_EOL . $debugMessage . PHP_EOL . $backTrace
                );
            }
        }
        
        if ( $this->doDebug && $messageType <= $this->getConfigurationFor('debugMessagesLogLevel'))
        {
	        $this->debugMessages[] = $debugMessage . PHP_EOL . $backTrace;
        }
        
        $this->_inLog = false;
        
        return;
        
    }	// log

    /**
     * 
     * @return boolean
     */
    public function clearErrors()
    {
        return $this->writeErrorsAndClear();
    }

    /**
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * 
     * @return string
     */
    public function getErrorsAsStringAndClear( $excludeCodes=[] )
    {
        if ( count($this->errors) == 0 ) {
    		return NULL;
    	}

    	$ret = '';
    	foreach( $this->errors as $anError ) {
    		if ( in_array($anError[2], $excludeCodes) ) { continue; }
    		$ret .= trim($anError[2] . ' ' . $anError[0]) . PHP_EOL;
    	}
    	$this->clearErrors();
        return $ret;
    }
    
    /**
     *
     * @return array
     */
    public function getErrorsAsArrayAndClear( $showCode = false, $excludeCodes=[] )
    {
    	if ( count($this->errors) == 0 ) {
    		return NULL;
    	}
    
    	$ret = [];
    	foreach( $this->errors as $anError ) {
    		if ( in_array($anError[2], $excludeCodes) ) { continue; }
    		$ret[] = ($showCode ? trim($anError[2]) . ' ' : '') . $anError[0];
    	}
    	$this->clearErrors();
    	return $ret;
    }
    
    /**
     * 
     * @return string
     */
    public function getLastErrorAsStringAndClear()
    {
    	if ( count($this->errors) == 0 ) {
    		return NULL;
    	}
    	$ret = end( $this->errors );
    	$this->clearErrors();
        return $ret[0];	// just the message
    }
    
    
    /**
     *
     * @return string
     */
    public function getLastErrorCode()
    {
      	if ( count($this->errors) == 0 ) {
    		return NULL;
    	}
    	$ret = end( $this->errors );
    	return $ret[2];
    }
    
    
    
    public function debug( $message, $includeBackTrace = FALSE ) {
    	$this->log( WOOOF_loggingLevels::WOOOF_STATUS, $message, $includeBackTrace, false );
    }

    public function logError( $message, $includeBackTrace = true ) {
    	$this->log( WOOOF_loggingLevels::WOOOF_ERROR, $message, $includeBackTrace, true );
    }

    public function debugTimers( $timers ) {
    	foreach( $timers as $aTimerElement ) {
    		foreach( $aTimerElement as $aName => $aTiming ) {
    			$this->debug("Timer '$aName' took $aTiming ms");
    		}
    	}
    }	// debugTimers
    

    public function handleFileUpload($postName='fileName', $existingExternalFilesId = '')
    {        
        $absoluteFilesRepositoryPath = WOOOF::$instance->getConfigurationFor('absoluteFilesRepositoryPath');

        $uploadSucceded = WOOOF::$instance->checkFileUpload($postName, self::_ECP.'9091');

        if ($uploadSucceded===TRUE)
        {
            if (!WOOOF::$instance->hasContent($existingExternalFilesId))
            {
                $tempName = WOOOF::randomString(40);
                while(file_exists($absoluteFilesRepositoryPath . $tempName))
                {
                    $tempName = WOOOF::randomString(40);
                }
            }else
            {
                $existingFile = $this->db->getRow('__externalFiles', $existingExternalFilesId);
                if ($existingFile === FALSE || !isset($existingFile['id']))
                {
                    WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."9085 " . 'File upload failed! Existing id was given but this id was not found in the database! Given ID: '. $existingExternalFilesId);
                    return FALSE;
                }
                $tempName = $existingFile['fileName'];
                if (chmod($absoluteFilesRepositoryPath . $tempName, 0755)===FALSE)
                {
                    WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_WARNING, self::_ECP."9086 " . 'Could not change existing file\'s permitions! This could lead to a failure in overwriting...');
                }
                if (unlink($absoluteFilesRepositoryPath . $tempName)===FALSE)
                {
                    WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."9087 " . 'File upload failed! Failed to erase existing file!'. $existingExternalFilesId .' '. $absoluteFilesRepositoryPath . $tempName .' -> '. print_r($existingFile,TRUE));
                    return FALSE;
                }
            }
            if (move_uploaded_file($_FILES[$postName]['tmp_name'], $absoluteFilesRepositoryPath . $tempName )===FALSE)
            {
                WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."9080 " . 'File upload failed! Could not move valid uploaded file. Tmp name : '. $_FILES[$postName]['tmp_name']);
                return FALSE;
            }
            if (chmod($absoluteFilesRepositoryPath . $tempName, 0444)===FALSE)
            {
                WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_WARNING, self::_ECP."9090 " . 'Could not change uploaded file\'s permitions! This could be a possible security issue.');
            }
            
            if ($existingExternalFilesId == '')
            {
                $externalFileId = $this->db->getNewId('__externalFiles');
                $result = $this->db->query('insert into __externalFiles set id=\''. $externalFileId .'\', entryDate=\''. WOOOF::getCurrentDateTime() .'\', fileName=\''. $tempName .'\', originalFileName=\''. WOOOF::$instance->cleanUserInput(basename($_FILES[$postName]['name'])) .'\'');
            }else
            {
                $externalFileId = $existingFile['id'];
                $result = $this->db->query('update __externalFiles set originalFileName=\''. WOOOF::$instance->cleanUserInput(basename($_FILES[$postName]['name'])) .'\' where id=\''. $externalFileId .'\'');
            }

            if ($result===FALSE)
            {
                return FALSE;
            }else
            {
                return $externalFileId;
            }
        }elseif ($uploadSucceded === 'INDF')
        {
            return $existingExternalFilesId;
        }else
        {
            return FALSE;
        }
    }

    public function handlePictureUpload($columnName, $rowId, $tableName, $postName='')
    {
        $table = new WOOOF_dataBaseTable($this->db, $tableName);

        if ($table->constructedOk === FALSE)
        {
            return FALSE;
        }

        return $table->handlePictureUpload($columnName, $rowId, $postName);
    }

    /**
     * 
     * @param string $userName
     * @return boolean
     */
    public function evaluateUserName($userName)
    {
        if (strlen(preg_replace('![0-9A-Za-z\\-\\_]+!', '', $userName)))
        {
            return FALSE;
        }else
        {
            return TRUE;
        }
    }
    
    /**
     * 
     * @param string $password
     * @param string $passwordConfirmation
     * @param string[] &$errorArray
     * @return boolean
     */
    public function evaluatePassword($password,$passwordConfirmation,&$errorArray)
    {
        $errorArray = array();
        
        if ($password!=$passwordConfirmation)
        {
            $errorArray[] ='0001 The password and password confirmation you provided don\'t match!';
        }

        if (strlen($password)<$this->configuration['minimumPasswordLength'])
        {
            $errorArray[] ='0002 The password you provided is shorter than 8 characters !';
        }
        
        if (strlen(preg_replace('![^0-9]+!', '', $password )) < $this->configuration['minimumNumbersInPassword'] && $this->configuration['minimumNumbersInPassword']>0)
        {
            $errorArray[] ='0003 The password you provided has less than '. $this->configuration['minimumNumbersInPassword'] .' digits !';
        }
        
        if (strlen(preg_replace('![^A-Z]+!', '', $password)) < $this->configuration['minimumCapitalsInPassword'] && $this->configuration['minimumCapitalsInPassword']>0)
        {
            $errorArray[] ='0004 The password you provided has less than '. $this->configuration['minimumCapitalsInPassword'] .' capital letters!';
        }
        
        if (strlen(preg_replace('![^\\!\\@\\#\\$\\%\\^\\&\\*\\(\\)]+!', '', $password)) < $this->configuration['minimumSymbolsInPassword'] && $this->configuration['minimumSymbolsInPassword']>0)
        {
            $errorArray[] ='0005 The password you provided has less than '. $this->configuration['minimumSymbolsInPassword'] .' of symbols "!,@,#,$,%,^,&,*,(,)"!';
        }
        
        if (strlen(preg_replace('![\\!\\@\\#\\$\\%\\^\\&\\*\\(\\)0-9A-Za-z]+!', '', $password)))
        {
            $errorArray[] ='0006 Illegal characters detected !';
        }
        
        if (count($errorArray)==0)
        {
            return TRUE;
        }else 
        {
            $this->log(WOOOF_loggingLevels::WOOOF_NOTICE, self::_ECP."0020 " . implode(PHP_EOL, $errorArray),TRUE);
            return FALSE;
        }
    }
    
    public static function getCurrentDateTime()
    {
        return date("YmdHis");
    }
    
    /**
     * Default routine for handling cases when the client wishes to abort execution.
     * @param string|string[] $errorInput
     */
    public function handleShowStopperError($errorInput=null)
    {
        if ( !is_array($errorInput) ) { $errorInput = array( $errorInput ); }
        if ( count($errorInput) > 0 ) {
	    	$this->log( WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."9010 " . implode(PHP_EOL, $errorInput), true );
        }
    	
    	$customHandler = $this->getConfigurationFor('showStopperErrorRoutine');
    	
    	if ( is_array($customHandler) or WOOOF::hasContent($customHandler) ) {
    		call_user_func($customHandler, $this, $errorInput );
    		die();
    	}
    	else {
    		// A simple show stopper handler
    		foreach( $errorInput as $anError ) {
    			echo $anError . '<br>';
    		}
    		echo 'Unable to continue. Aborting...';
    		die();
    	}
    }	// handleShowStopperError

    /**
     * 
     * @param int $length
     * @return boolean|string
     */
    public static function randomString($length)
    {
        if ($length<1)
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0030 " . 'Zero or negative length random string was requested.');
            return FALSE;
        }
        $pool="aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOqQrRsStTuUvVwWxXyYzZ1234567890";
        $poolLength=strlen($pool);
        $randomString="";
        for($idx=0;$idx<$length;$idx++) 
        {
            $randomString.=substr($pool,(rand()%($poolLength)),1);
        }
        return $randomString;
    }
    
    /**
     * 
     * @param string $value
     * @return string
     */
    public static function translateCheckBoxValue($value)
    {
        if ($value=='1')
        {
            return ' checked';
        }else
        {
            return '';
        }
    }
    
    public static function translateSelectValue($min,$max,$value)
    {
        $arr='';
        for($i=$min; $i<=$max; $i++)
        {
            if ($i == $value)
            {
                $arr[$i] = ' selected';
            }else
            {
                $arr[$i] = '';
            }
        }
        return $arr;
    }

    /**
     * 
     * @param string $tstamp
     * @param string $separator
     * @return boolean|string
     */
    public static function decodeDate($tstamp,$separator='-',$mustHaveValue=false)
    {
    	if ( (strlen($tstamp)==0 || $tstamp=='0') && !$mustHaveValue ) {
    		return '';
    	}
    	
        if (strlen($tstamp)!=14)
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0040 Invalid WOOOF time stamp [$tstamp] supplied to decodeDate.");
            return FALSE;
        }
        $out=substr($tstamp,6,2);
        $out.=$separator . substr($tstamp,4,2);
        $out.=$separator . substr($tstamp,0,4);
        return $out;
    }

    /**
     * 
     * @param string $tstamp
     * @param string $separator
     * @return boolean|string
     */
    public static function decodeDayMonth($tstamp,$separator='-',$mustHaveValue=false)
    {
       	if ( strlen($tstamp)==0 && !$mustHaveValue ) {
    		return '';
    	}
    	
    	if (strlen($tstamp)!=14)
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0050 Invalid WOOOF time stamp [$tstamp] supplied to decodeDayMonth.");
            return FALSE;
        }
        $out=substr($tstamp,6,2);
        $out.=$separator . substr($tstamp,4,2);
        //$out.="-" . substr($tstamp,0,4);
        return $out;
    }

    /**
     * 
     * @param string $tstamp
     * @param string $separator
     * @return boolean|string
     */
    public static function decodeDateTime($tstamp,$separator='-',$showSeconds=FALSE,$mustHaveValue=false)
    {
    	if ( (strlen($tstamp)==0 || $tstamp=='0') && !$mustHaveValue ) {
    		return '';
    	}
    	
    	if (strlen($tstamp)!=14)
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0060 Invalid WOOOF time stamp [$tstamp] supplied to decodeDateTime.");
            return FALSE;
        }
        $out=substr($tstamp,6,2);
        $out.=$separator . substr($tstamp,4,2);
        $out.=$separator . substr($tstamp,0,4);
        $out.=', '. substr($tstamp,8,2);
        $out.=":" . substr($tstamp,10,2);
        if ($showSeconds)
        {
            $out.=":" . substr($tstamp,12,2);
        }
        return $out;
    }

    /**
     * 
     * @param string $tstamp
     * @param string $separator
     * @return boolean|string
     */
    public static function decodeTime($tstamp,$mustHaveValue=false)
    {
        if ( strlen($tstamp)==0 && !$mustHaveValue ) {
    		return '';
    	}
    	
    	if (strlen($tstamp)!=14)
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0070 Invalid WOOOF time stamp [$tstamp] supplied to decodeTime.");
            return FALSE;
        }
        $out= substr($tstamp,8,2);
        $out.=":" . substr($tstamp,10,2);
        //$out.=":" . substr($tstamp,12,2);
        return $out;
    }

    public function sessionCleanUp()
    {
        return $this->db->query("update __sessions set active='0' where lastAction<'". date("YmdHis",  strtotime("-". $this->configuration['sessionExpirationPeriod'])) ."' ");
    }
    
    public function invalidateSession()
    {
        global $__isAdminPage;
        global $__isSiteBuilderPage;
    	
        list( $nameForCookie, $pathForCookie, $domainForCookie ) = $this->getCookieNameAndPathAndDomain();        
    	
    	if ($this->hasContent($domainForCookie) )
        {
            setcookie( $nameForCookie, '',  time()-36000, $pathForCookie, $domainForCookie );
        }else
        {
            setcookie( $nameForCookie, '',  time()-36000, $pathForCookie );
        }
    	
        return $this->db->query("update __sessions set active='0' where sessionId='". $this->cleanUserInput($this->sid) ."'");
    }
    
    /*
     * Obsolete: replaced by getCookieNameAndPathAndDomain
    
    private function getCookieName()
    {
    	global $__isSiteBuilderPage;
    	global $__isAdminPage;
    	
    	if ($__isSiteBuilderPage)
    	{
    		$nameForCookie = 'WOOOF_siteBuilderSessionId';
    	}elseif ( $__isAdminPage ) {
    		$nameForCookie = 'WOOOF_adminSessionId';
    	}
    	else {
    		$nameForCookie = 'WOOOF_sessionId';
    	}
    	return $nameForCookie;
    }
    */
    
    private function getCookieNameAndPathAndDomain()
    {
    	global $__isSiteBuilderPage;
    	global $__isAdminPage;
    	
    	if ($__isSiteBuilderPage)
    	{
    		$nameForCookie = 'WOOOF_siteBuilderSessionId';
            $pathForCookie = $this->configuration['siteBaseURL'] . $this->configuration['dbManagerBaseURL'];
    	}elseif ( $__isAdminPage ) {
    		$nameForCookie = 'WOOOF_adminSessionId';
        	$pathForCookie = $this->configuration['siteBaseURL'] . $this->configuration['adminURL'];
    	}
    	else {
    		$nameForCookie = 'WOOOF_sessionId';
        	$pathForCookie = $this->configuration['siteBaseURL'];
    	}

        if (isset($this->configuration['domainNameForCookies']) && $this->configuration['domainNameForCookies'] != '') {
        	$domainForCookie = $this->configuration['domainNameForCookies'];
        }
        else {
        	$domainForCookie = NULL;
        }
        
    	return array( $nameForCookie, $pathForCookie, $domainForCookie );
    }	// getCookieNameAndPathAndDomain
    
    /**
     * 
     * @return boolean
     */
    public function sessionCheck()
    {
        global $userData;
        
        $this->sessionCleanUp();
        list( $cookieName,,) = $this->getCookieNameAndPathAndDomain();

        if (!isset($_COOKIE[$cookieName]) or $_COOKIE[$cookieName] == '' )
        {
        	$this->sid = NULL;
            return FALSE;
        }

        $this->sid = $_COOKIE[$cookieName];

        $result = $this->db->query("select * from __sessions where sessionId='". $this->cleanUserInput($this->sid) ."' and active='1'");
        if( $result === FALSE || !mysqli_num_rows($result)) 
        {
        	setcookie($cookieName, "", time()-3600);
            return FALSE;
        }
        else
        {
        	$row = $this->db->fetchAssoc($result);
            $this->db->query("update __sessions set lastAction='". $this->dateTime ."' where sessionId='". $this->cleanUserInput($this->sid) ."' and active='1'");
            $uR = $this->db->query("select * from __users where id='". $row['userId'] ."'");
            if ( $uR === FALSE ) {
  				setcookie($cookieName, "", time()-3600);
            	return FALSE;
            }
            
            $userData = $this->db->fetchAssoc($uR);
            
            if($userData === NULL) {
            	setcookie($cookieName, "", time()-3600);
            	return FALSE;
            }            
            
            $userData['loginPass'] = 'absolutelyReallyDefinitelyNotYourBusiness';
            return TRUE;
        }
        
        return FALSE;	// should not reach here
    }	// sessionCheck
    

    /**
     * 
     * @param string $uid
     * @return boolean
     */
    public function newSession($uid)
    {
        global $userData;
        global $__isAdminPage;
        global $__isSiteBuilderPage;

        $this->sessionCleanUp();
        
        $uR = $this->db->query('select * from __users where id=\''. $this->cleanUserInput($uid) .'\'');
        if ( $uR === FALSE ) { return FALSE; }
        
        if (!mysqli_num_rows($uR))
        {
            $this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0080 " . 'Session for unknown id was requested!');
            return FALSE;
       	}
        
        $userData = $this->db->fetchAssoc($uR);
        
        if ($uid != '0123456789' && ( !$__isSiteBuilderPage || !$__isAdminPage ) )
        {
            $result = $this->db->query('update __sessions set active=\'0\' where userId=\''. $this->cleanUserInput($uid) .'\'');
            if ($result===FALSE)
        	{
                $this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0090" . 'Failed to deActivate older sessions for user `'. $userData['loginName'] .'`. Potential security breach!');
                return FALSE;
            }
        }
        $go_on=0;
        do
        {
            $sid = WOOOF::randomString(40);
            $new_sid_result=$this->db->query("select * from __sessions where sessionId='". $sid ."'");
            if (!mysqli_num_rows($new_sid_result)) $go_on=1;
        }while (!$go_on);
        
        if ($__isSiteBuilderPage)
        {
            $pathForCookie = $this->configuration['siteBaseURL'] . $this->configuration['dbManagerBaseURL'];
        }
        elseif ( $__isAdminPage ) {
        	$pathForCookie = $this->configuration['siteBaseURL'] . $this->configuration['adminURL'];
        }else
        {
        	$pathForCookie = $this->configuration['siteBaseURL'];
        }
        
        list( $nameForCookie, $pathForCookie, $domainForCookie ) = $this->getCookieNameAndPathAndDomain();
        
        if ( $this->hasContent($domainForCookie) )
        {
            setcookie($nameForCookie, $sid, strtotime("+".$this->configuration['sessionExpirationPeriod']), $pathForCookie, $domainForCookie );
        }else
        {
            setcookie($nameForCookie, $sid, strtotime("+".$this->configuration['sessionExpirationPeriod']), $pathForCookie );
        }

        $_COOKIE[$nameForCookie]=$sid;
        $this->sid = $sid;
        $result = $this->db->query("insert into __sessions (userId,sessionId,loginDateTime,lastAction,loginIP,active) values ('$uid','$sid','". $this->dateTime ."','". $this->dateTime ."','". $this->cleanUserInput($_SERVER["REMOTE_ADDR"]) ."','1')");
        if ($result===FALSE)
        {
            $this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0100 " . 'Failed to insert new session in the data base for user `'. $userData['loginName'] .'`. Potential security breach!');
            return FALSE;
        }
        return TRUE;
    }

    public function fullURL( $relativeURL ) {
    	return $this->configuration['siteURLStart'] . $relativeURL; 
    }
    
    public static function breakDateTime($dateTime,$mustHaveValue=false)
    {
        if ( strlen($dateTime)==0 && !$mustHaveValue ) {
    		return '';
    	}
    	
    	if ( strlen($dateTime)==8 ) {
    		$dateTime .= '000000';
    	}
    	
    	if (strlen($dateTime)!=14)
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0110 " . 'Invalid WOOOF time stamp [' . $dateTime . '] supplied to breakDateTime.');
            return FALSE;
        }        
        $out='';
        $out['day']=substr($dateTime,6,2);
        $out['month']=substr($dateTime,4,2);
        $out['year']=substr($dateTime,0,4);
        $out['hour']=substr($dateTime,8,2);
        $out['minute']=substr($dateTime,10,2);
        $out['second']=substr($dateTime,12,2);
        return $out;
    }

    /**
     * 
     * @param string[] $dateTime
     * @return boolean|string
     */
    public static function buildDateTime($dateTime)
    {
        if ( !isset($dateTime['day']) 
            || !isset($dateTime['month']) 
            || !isset($dateTime['year']) 
            || !isset($dateTime['hour']) 
            || !isset($dateTime['minute']) 
            || !isset($dateTime['second']) )
        {
             WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0120 " . 'Invalid date and time array supplied to buildDateTime.');
            return FALSE;
        }
        $dateTime['day'] = str_pad($dateTime['day'], 2, '0', STR_PAD_LEFT);
        $dateTime['month'] = str_pad($dateTime['month'], 2, '0', STR_PAD_LEFT);
        $dateTime['year'] = str_pad($dateTime['year'], 4, date('Y') , STR_PAD_LEFT);
        $dateTime['hour'] = str_pad($dateTime['hour'], 2, '0', STR_PAD_LEFT);
        $dateTime['minute'] = str_pad($dateTime['minute'], 2, '0', STR_PAD_LEFT);
        $dateTime['second'] = str_pad($dateTime['second'], 2, '0', STR_PAD_LEFT);
        return $dateTime['year'] . $dateTime['month'] . $dateTime['day'] . $dateTime['hour'] . $dateTime['minute'] . $dateTime['second'];
    }

    /**
     * 
     * @param string $columnName
     * @return boolean|string
     */
    public static function buildDateTimeFromAdminPost($columnName, $kind)
    {
        $out='';
        
        
        if ( $kind == WOOOF_columnPresentationTypes::date or $kind == WOOOF_columnPresentationTypes::dateAndTime )
        {
	        $out['day']		= str_pad($_POST[$columnName.'1'], 2, '0', STR_PAD_LEFT);
	        $out['month']	= str_pad($_POST[$columnName.'2'], 2, '0', STR_PAD_LEFT);
	        $out['year']	= str_pad($_POST[$columnName.'3'], 4, '0', STR_PAD_LEFT);
        }
        
        if ( $kind == WOOOF_columnPresentationTypes::date )
        {
        	$out['hour']	= '00';
        	$out['minute']	= '00';
        	$out['second']	= '00';
        }
        
        if ( $kind == WOOOF_columnPresentationTypes::time or $kind == WOOOF_columnPresentationTypes::dateAndTime )
        {
	        $out['hour']	= str_pad($_POST[$columnName.'4'], 2, '0', STR_PAD_LEFT);
	        $out['minute']	= str_pad($_POST[$columnName.'5'], 2, '0', STR_PAD_LEFT);
	        $out['second']	= str_pad($_POST[$columnName.'6'], 2, '0', STR_PAD_LEFT);
        }
        		
        if ( $kind == WOOOF_columnPresentationTypes::time )
        {
	        $out['day']		= '00';
	        $out['month']	= '00';
	        $out['year']	= '0000';
        }
        
        return WOOOF::buildDateTime($out);
    }

    /**
     * 
     * cleanUserInput used to sanitize user input for various uses.
     * 
     * @param string    $input                  A string to be sanitized
     * @param boolean   $preserveTags           Whether to strip tags or not
     * @param boolean   $htmlEntitiesEncode     Whether to encode html entities
     * @param boolean   $sanitizeForShell       Whether to sanitize for shell execution
     * @param boolean   $sanitizeForDataBase    Whether to sanitize for database input.
     * 
     * @return string   the sanitized string
     */
    public function cleanUserInput($input, $preserveTags=FALSE, $htmlEntitiesEncode=FALSE, $sanitizeForShell=FALSE, $sanitizeForDataBase=TRUE)
    {
        global $__isAdminPage;

        //return mysqli_real_escape_string(htmlentities(strip_tags(trim($input)),ENT_NOQUOTES | ENT_HTML5,'UTF-8'));
        if (!$__isAdminPage && !$preserveTags)  
        {
            $input = strip_tags($input);
        }
        
        if ($htmlEntitiesEncode)
        {
            $input = htmlentities(trim($input), ENT_NOQUOTES | ENT_HTML5, 'UTF-8');
        }
        
        if ($sanitizeForShell)
        {
            $input = escapeshellcmd($input);
        }
        
        if ($sanitizeForDataBase)
        {
            $input = $this->db->escape(trim($input));
        }

        return $input;
    }
    
    /**
     * 
     * @return false|string[] // __users row
     */
    public function handleLoginFromPost()
    {
        $userRow='';
        
        $rowForTest = $this->db->getRowByColumn('__users', 'loginName', $this->cleanUserInput($_POST['username']));
        
        if ( $rowForTest === FALSE )
        {
        	return FALSE;
        }

        if ( $rowForTest === NULL )
        {
        	$this->log(WOOOF_loggingLevels::WOOOF_NOTICE, self::_ECP."0130 " . 'Credentials supplied don\'t match a user account in the system.', FALSE);
        	return FALSE;
        }
        	
        
        $hash = $this->getPasswordHash($_POST['password'], $rowForTest['id']);
        
        $result = $this->db->query('select * from __users where binary loginName=\''. $this->cleanUserInput($rowForTest['loginName']) .'\' and binary loginPass=\''. $hash .'\'');
        if (mysqli_num_rows($result))
        {
            $userRow = $this->db->fetchAssoc($result);
            $userRow['loginPass']='not your business, really !';
            return $userRow;
        }else
        {
            $this->log(WOOOF_loggingLevels::WOOOF_NOTICE, self::_ECP."0140 " . 'Credentials supplied don\'t match a user account in the system.', FALSE);
            return FALSE;
        }
    }
    
    /**
     * 
     * @param string $password
     * @param string $userId
     * @param number $blowFishHashRounds
     * @return false|string
     */
    public function getPasswordHash($password, $userId, $blowFishHashRounds=8)
    {
		$salt = $this->produceSaltForUser($userId);
    	
    	$cryptResult=crypt($this->cleanUserInput($password), $salt);
    	$thePassword = substr($cryptResult, 28);
    	if ($thePassword=='' || $thePassword==NULL)
    	{
    		$this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0150 " . 'Encryption failure! password could not be encrypted.');
    		return FALSE;
    	}else 
    	{
    		return $thePassword;
    	}
    }
    
    private function produceSaltForUser($userId)
    {    	
    	$customHandler = $this->getConfigurationFor('saltProductionMethod');
    	 
    	if ( is_array($customHandler) or WOOOF::hasContent($customHandler) ) {
    		return call_user_func($customHandler, $userId);
    	}
    	else 
    	{
	    	if (PHP_VERSION_ID<50307)
	    	{
	    		WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_WARNING, self::_ECP."0160 " . 'PHP version less than 5.3.7 !!! Falling back to older hashing algorithm getPasswordHash().');
	    		$salt='$2a$08$';
	    	}else
	    	{
	    		$salt='$2y$08$';
	    	}
	    	$salt .= $userId . strrev($userId) . $userId;
	    	return $salt;
    	}
    }
    
    /**
     * 
     * @param string $email
     * @param string[] &$errorsArray
     * @return boolean
     */
    public static function isValidEmail($email,&$errorsArray)
    {
    	$errorsArray = array();
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex)
        {
        	$errorsArray[] = '0000 No @ sign found.';
            $isValid = false;
        }
        else
        {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            
            if ($localLen < 1 || $localLen > 64)
            {
                $errorsArray[] = '0001 Local part length exceeded';
                $isValid = false;
            }
            
            if ($domainLen < 1 || $domainLen > 255)
            {
                $errorsArray[] = '0002 Domain part length exceeded';
                $isValid = false;
            }
            
            
            if ($local[0] == '.' || $local[$localLen-1] == '.')
            {
                $errorsArray[] = '0003 Local part starts or ends with \'.\' ';
                $isValid = false;
            }
            
            if (preg_match('/\\.\\./', $local))
            {
                $errorsArray[] = '0004 Local part has two consecutive dots.';
                $isValid = false;
            }
            
            if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
            {
                $errorsArray[] = '0005 Character not valid in domain part.';
                $isValid = false;
            }
            
            if (preg_match('/\\.\\./', $domain))
            {
                $errorsArray[] = '0006 Domain part has two consecutive dots.';
                $isValid = false;
            }
            
            if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
            {
                if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
                {
                	$errorsArray[] = '0007 Character not valid in unquoted local part.';
                    $isValid = false;
                }
            }
            
            // $domain = idn_to_ascii($domain);
            if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
            {
                $errorsArray[] = '0008 Domain not found in DNS.';
                $isValid = false;
            }
        }
        return $isValid;
    }
    
    /**
     * 
     * @param string $from
     * @param string $emailAddress
     * @param string $subject
     * @param string $message
     * @param string $replyTo
     * @return boolean
     */
    public static function sendMailOriginal($from,$emailAddress,$subject,$message,$replyTo='')
    {
        $to      = $emailAddress;
        $subject = '=?UTF-8?B?'. base64_encode($subject) .'?=';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: '. $from ."\r\n";
        if ($replyTo!='')
        {
            $headers .= 'Reply-To: '. $replyTo ."\r\n";
        }
        return mail($to, $subject, $message, $headers);
    }
    
    /**
     * 
     * @param string $from
     * @param string $emailAddress
     * @param string $subject
     * @param string $message
     * @param string $replyTo
     * @return boolean
     */
    public function sendMail($from,$emailAddress,$subject,$message,$replyTo='',$ccEmailAddresses='',$htmlMessage='',$files=null)
    {
        $conf = $this->getConfigurationFor('', 'email_smtp' );
        if ( $conf === NULL ) {
        	$this->log( WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0200 " . 'email_smtp custom config was not found');
        	return false; 
        }
        
        if ( !WOOOF::hasContent($from) ) {
	        $from = $this->getConfigurationFor('from_general', 'email_addresses' );
	        if ( $from === NULL ) {
	        	$this->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0210 " . 'No config for from_general in email_addresses');
	        	return false;
	        }
        }        
        
        $options = 	
        	array(
        		'FROM' 			=> $from,
        		'REPLY_TO' 		=> $replyTo,
        		'SMTP_HOST'		=> $conf['SMTP_HOST'],
        		'SMTP_AUTH'		=> $conf['SMTP_AUTH'],
        		'SMTP_USERNAME'	=> $conf['SMTP_USERNAME'],
        		'SMTP_PASSWORD'	=> $conf['SMTP_PASSWORD'],
        		'SMTP_DEBUG'	=> $this->getFromArray( $conf, 'SMTP_DEBUG', false ),
        	)
        ;
        
        if ( WOOOF::hasContent($replyTo) ) {
        	$options['REPLY_TO'] = $replyTo; 
        }
        if ( WOOOF::hasContentArrayEntry($conf, 'SMTP_SECURE') ) {
        	$options['SMTP_SECURE'] = $conf['SMTP_SECURE']; 
        }
        if ( WOOOF::hasContentArrayEntry($conf, 'WORD_WRAP') ) {
        	$options['WORD_WRAP'] = $conf['WORD_WRAP'];
        }
        
        
        /*
        		$p_options_array,			// Associative array with the following keys:
        		// FROM (mandatory), REPLY_TO, SMTP_HOST, SMTP_AUTH (bool),
        		// SMTP_USERNAME, SMTP_PASSWORD, SMTP_SECURE (absent or 'tls' or 'ssl'),
        		// WORD_WRAP (word wrap after that many chars), SMTP_DEBUG
        		$p_addresses_array,		// Associative array:
        		// TO (mandatory) => <e-mail> or array( <e-mail>, <display_name> )
        		// CC => as TO, BCC as TO
        		$p_subject,
        		$p_html_message,
        		$p_text_message,
        		$p_attachments,				// array of filenames to send as attachments: <filename> or array( filename, display_name )
        		&$po_error_message
        */
        $errorMessage = '';
        
        if ( is_array($emailAddress) ) {
        	$emailAddressesArray = $emailAddress;
        }
        else {
	        $emailAddressesArray = explode(',', $emailAddress);
        }
        
        if ( is_array($ccEmailAddresses) ) {
        	$ccEmailAddressesArray = $ccEmailAddresses;
        }
        else {
	        $ccEmailAddressesArray = explode(',', $ccEmailAddresses);
        }
        
        $this->debug( __CLASS__ . '.' . __FUNCTION__ . ": send to [". implode(',', $emailAddressesArray) . ']' );
        
        $res = $this->sendPHPMailerMail(
        	$options,
        	array( 'TO' => $emailAddressesArray, 'CC' => $ccEmailAddressesArray ),
        	$subject,
        	$htmlMessage,
        	$message,
        	null,
        	$errorMessage
        );
        
        if ( $res === FALSE ) {
        	$this->log( WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0220 Failed to send e-mail to [" . implode(',', $emailAddressesArray) . "]: $errorMessage" );
        	return false;
        }

        return true;
        
    }	// sendMail
    
    public static function resizePicture($inputPicture,$outputPicture,$targetWidthMax,$targetHeightMax,$quality=90)
    {
        // Get dimensions
        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        //calculate aspect ratio
        $ratioOrig = $widthOrig/$heightOrig;
        //change target dimasnions so as to retain aspect ratio
        if ( $ratioOrig > 1 ) 
        {
            $targetHeightMax = $targetWidthMax/$ratioOrig;
        }else
        {
            $targetWidthMax = $targetHeightMax*$ratioOrig;
        }

        // create new image in memory
        $image_p = imagecreatetruecolor($targetWidthMax, $targetHeightMax);
        //load image depending on image type
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                break;
            default:
                $this->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0230 " . 'Input picture is not a valid picture or is a non supported file. Only JPG, GIF and PNG are supported.');
                return FALSE;
                break;
        }
        //copy with resample/resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $targetWidthMax, $targetHeightMax, $widthOrig, $heightOrig);
        //export jpg image at requested quality
        //        $result = imagejpeg($image_p, $outputPicture, $quality);
        
        $ext = strtolower(substr($outputPicture, -3));
        switch ($ext)
        {
            case 'jpg':
                $result = imagejpeg($image_p, $outputPicture, $quality);
                break;
            case 'gif':
                $result = imagegif($image_p, $outputPicture);
                break;
            case 'png':

                    $quality =  9;
                
                $result = imagepng($image_p, $outputPicture,$quality);
                break;
        }

        return $result;
    }

    public static function resizePictureStrict($inputPicture,$outputPicture,$targetWidth,$targetHeight,$quality=70)
    {
        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        $image_p = imagecreatetruecolor($targetWidth, $targetHeight);
        //load image depending on image type
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                break;
            default:
                $this->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0240 " . 'Input picture is not a valid picture or is a non supported file. Only JPG, GIF and PNG are supported.');
                return FALSE;
                break;
        }
        //copy with resample/resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $widthOrig, $heightOrig);
        //export  image at requested quality
        
        $ext = strtolower(substr($outputPicture, -3));
        switch ($ext)
        {
            case 'jpg':
                $result = imagejpeg($image_p, $outputPicture, $quality);
                break;
            case 'gif':
                $result = imagegif($image_p, $outputPicture);
                break;
            case 'png':
                $result = imagepng($image_p, $outputPicture,$quality);
                break;
        }
        //$result = imagejpeg($image_p, $outputPicture, $quality);
        return $result;
    }

    public static function resizePictureStrictWithOverlay($inputPicture,$outputPicture,$targetWidth,$targetHeight,$overlay,$quality=70)
    {
        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        $image_p = imagecreatetruecolor($targetWidth, $targetHeight);
        //load image depending on image type
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                $quality = 9;
                break;
            default:
                $this->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0250 " . 'Input picture is not a valid picture or is a non supported file. Only JPG, GIF and PNG are supported.');
                return FALSE;
                break;
        }
        //copy with resample/resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $widthOrig, $heightOrig);

        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                break;
        }

        $result = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $widthOrig, $heightOrig);

        if ($result===FALSE)
        {
            return FALSE;
        }

        //export jpg image at requested quality
        $result = imagejpeg($image_p, $outputPicture, $quality);
        return $result;
    }
    
    public static function cropPictureAndResize($inputPicture, $outputPicture, $targetWidth, $targetHeight, $overlay='', $quality=90)
    {
        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        $XRatio = $widthOrig / $targetWidth;
        $YRatio = $heightOrig / $targetHeight;
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                $quality = 9;
                break;
            default:
                $this->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0260 " . 'Input picture is not a valid picture or is a non supported file. Only JPG, GIF and PNG are supported.');
                return FALSE;
                break;
        }
        
        if ($XRatio < $YRatio)
        {
            $ratioDiff = round($XRatio * $targetHeight);
            $pixelDiff = $heightOrig - $ratioDiff;
            $image_t = imagecreatetruecolor($widthOrig, $ratioDiff);
            imagecopy($image_t, $image, 0, 0, 0, round($pixelDiff/2), $widthOrig, $heightOrig - round($pixelDiff/2));
        }else
        {
            $ratioDiff = round($YRatio * $targetWidth);
            $pixelDiff = $widthOrig - $ratioDiff;
            $image_t = imagecreatetruecolor($ratioDiff,$heightOrig);
            imagecopy($image_t, $image, 0, 0, round($pixelDiff/2), 0, $widthOrig - round($pixelDiff/2), $heightOrig);
        }
        
        $result = imagejpeg($image_t, $outputPicture.'interim.jpg',100);
        if ($result === TRUE )
        {
            if ($overlay!='')
            {
                $result = WOOOF::resizePictureStrictWithOverlay($outputPicture.'interim.jpg', $outputPicture, $targetWidth, $targetHeight, $overlay, $quality);
            }else
            {
                $result = WOOOF::resizePicture($outputPicture.'interim.jpg', $outputPicture, $targetWidth, $targetHeight, $quality);
            }
            unlink($outputPicture.'interim.jpg');
            return $result;
        }else
        {
            return FALSE;
        }
    }

    public static function cropCenterOfPicture($input, $output, $width, $height)
    {
        list($widthOrig, $heightOrig, $imageType) = getimagesize($input);
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($input);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($input);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($input);
                break;
            default:
                $this->log(WOOOF_loggingLevels::WOOOF_ERROR,self::_ECP."0270 " . 'Input picture is not a valid picture or is a non supported file. Only JPG, GIF and PNG are supported.');
                return FALSE;
                break;
        }
        if ($width<$widthOrig && $height<$heightOrig)
        {
            $image_d = imagecreatetruecolor($width, $height);
            imagecopy($image_d, $image, 0, 0, floor(($widthOrig - $width)/2), floor(($heightOrig - $height)/2), $width, $height);
            $ext = strtolower(substr($output, -3));
            switch ($ext)
            {
                case 'jpg':
                    $result = imagejpeg($image_d, $output, 100);
                    break;
                case 'gif':
                    $result = imagegif($image_d, $output);
                    break;
                case 'png':
                    $result = imagepng($image_d, $output,0);
                    break;
            }
            return $result;
        }else
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0280 " . 'Output picture dimensions are not allowed to exceed the original picture dimensions.');
            return FALSE;
        }
    }

    public static function customOrderTranslation($input)
    {
    $input = preg_split('//u',$input, -1, PREG_SPLIT_NO_EMPTY);
    $ln=count($input);
    $tmp="";
    for($z=0;$z<$ln;$z++)
    {
            switch($input[$z])
        {
            case "α":
            case "ά":
            case "Α":
            case "Ά":
            case "a":
            case "A":
                $tmp.='a1';
            break;
            case "β":
            case "Β":
            case "b":
            case "B":
            case "v":
            case "V":
                $tmp.='b1';
            break;
            case "γ":
            case "Γ":
            case "w":
            case "W":
            case "g":
            case "G":
                $tmp.='c1';
            break;
            case "δ":
            case "Δ":
            case "d":
            case "D":
                $tmp.='d1';
            break;
            case "ε":
            case "έ":
            case "Ε":
            case "Έ":
            case "e":
            case "E":
                $tmp.='e1';
            break;
            case "ζ":
            case "Ζ":
            case "z":
            case "Z":
            case "j":
            case "J":
                $tmp.='f1';
            break;
            case "η":
            case "ή":
            case "Η":
            case "Ή":
                $tmp.='h1';
            break;
            case "θ":
            case "Θ":
                $tmp.='i1';
            break;
            case "ι":
            case "ί":
            case "Ι":
            case "Ί":
            case "I":
            case "i":
                $tmp.='i2';
            break;
            case "κ":
            case "Κ":
            case "k":
            case "K":
            case "c":
            case "C":
            case "q":
            case "Q":
                $tmp.='k1';
            break;
            case "λ":
            case "Λ":
            case "l":
            case "L":
                $tmp.='l1';
            break;
            case "μ":
            case "Μ":
            case "M":
            case "m":
                $tmp.='m1';
            break;
            case "ν":
            case "Ν":
            case "n":
            case "N":
                $tmp.='n1';
            break;
            case "ξ":
            case "Ξ":
            case "x":
            case "X":
                $tmp.='n2';
            break;
            case "ο":
            case "Ο":
            case "ό":
            case "Ό":
            case "o":
            case "O":
                $tmp.='o1';
            break;
            case "π":
            case "Π":
            case "p":
            case "P":
                $tmp.='p1';
            break;
            case "Ρ":
            case "ρ":
            case "r":
            case "R":
                $tmp.='p2';
            break;
            case "σ":
            case "ς":
            case "Σ":
            case "s":
            case "S":
                $tmp.='s1';
            break;
            case "τ":
            case "Τ":
            case "T":
            case "t":
                $tmp.='t1';
            break;
            case "υ":
            case "ύ":
            case "Υ":
            case "Ύ":
            case "y":
            case "Y":
            case "u":
            case "U":
                $tmp.='u1';
            break;
            case "φ":
            case "Φ":
            case "f":
            case "F":
                $tmp.='v1';
            break;
            case "χ":
            case "Χ":
            case "h":
            case "H":
                $tmp.='x1';
            break;
            case "ψ":
            case "Ψ":
                $tmp.='y1';
            break;
            case "ω":
            case "ώ":
            case "Ω":
            case "Ώ":
                $tmp.='z1';
            break;
            case "΄":
            case "'":
            case "<":
            case ">":
                $tmp.="";
            break;
            default:
                $tmp.=$input[$z];
            }
        }
        return $tmp;
    }
 
 /**
 *  storeExternalFiles
 * 
 *  scans the $_FILE global variable and stores the files present there into the 
 *  specified folder (in $absoluteFilesRepositoryPath). Creates a unique random name for each file
 *  (default is 40 characters long) and returns an array with the names.
 *  If no file was uploaded the function returns false
 * 
 * 	input filename length (default is 40 chars)
 */

    public function storeExternalFiles()
    {
    	$filesFound=0;
    	while(list($key,$val)=each($_FILES))
    	{
                if ($val['error']==UPLOAD_ERR_OK)
                {
                    $filesFound++;
                    do 
                    {
                        $newName=$this->randomString(40);
                    }while(file_exists($this->configuration['absoluteFilesRepositoryPath'].$newName));
                    
                    if (!move_uploaded_file($val['tmp_name'],$this->configuration['absoluteFilesRepositoryPath'].$newName))
                    {
                        $this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0300 " . 'Possible file upload forgery!');
                        return FALSE;
                    }
                    if (!chmod($this->configuration['absoluteFilesRepositoryPath'].$newName,0444))
                    {
                        $this->log(WOOOF_loggingLevels::WOOOF_WARNING, self::_ECP."0310 " . 'Failed to change the new file\'s access mode. Actual file name:'. $this->configuration['absoluteFilesRepositoryPath'].$newName );
                    }
                    $fileNames[]=$newName;
                    $fileNames[$key]=$newName;
                    $this->db->query('insert into __externalFiles set id=\''. $this->db->getNewId('__externalFiles') .'\', entryDate=\''. $this->dateTime .'\', fileName=\''. $newName .'\', originalFileName=\''. $this->cleanUserInput($val['name']) .'\'');
                }else
                {
                    $this->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0320 " . 'Inside storeExternalFiles: '. $val['error']);
                    return FALSE;
                }
    	}
	
    	if ($filesFound==0)
    	{
    		return FALSE;
    	}else 
    	{
    		return $fileNames;
    	}
    }

    /**
     * 
     * @param string $query
     * @param string $headers
     * @param string $presentation
     * @param string $table
     * @param boolean $displayActivation
     * @param boolean $displayPreview
     * @param boolean $displayUpDown
     * @param boolean $showOrdering
     * @param boolean $columnNames
     * @param string $parentId
     * @return string
     */
 	public function getPresentationListFromQuery($query, $headers, $presentation, $table, $displayActivation = false, $displayPreview = false, $displayUpDown = false, $showOrdering = false, $columnNames = false, $parentId = '')
    {
        global $address;
        global $action;

        require($this->getConfigurationFor('templatesRepository') . 'wooof_getPresentationListFromQuery_1.activeTemplate.php');
        $output=$header;
        $z=0;
        if ($parentId!='')
        {
            $extraParameter='&wooofParent='. $parentId;
            $extraParameter.='&'. $table->getLocalGroupColumn() .'='. $parentId;
        }else
        {
            $extraParameter='';
        }
        foreach($headers as $key => $header)
        {
            if ($showOrdering)
            {
                require($this->getConfigurationFor('templatesRepository') . 'wooof_getPresentationListFromQuery_2.activeTemplate.php');
            }else
            {
                require($this->getConfigurationFor('templatesRepository') . 'wooof_getPresentationListFromQuery_3.activeTemplate.php');
            }
            $z++;
        }
        if ($displayActivation)
        {
            $output .= $statusColumn;
        }
        $output .= $controlsColumn;
        $output .= $tail;
        
        $table->getResultByQuery($query,true,false);
        $output.= $table->getAdminListRows($headers,$presentation,$displayActivation,$displayPreview,$displayUpDown);

        return $output;
    }

    /**
     * 
     * @param string $table
     * @param string $where
     * @param string $parentId
     * @return string
     */
    public function doTableList(WOOOF_dataBaseTable $table,$where='',$parentId='')
    {
        $query = 'SELECT id';
        
        if ($table->getShowIdInAdminLists())
        {
            $headers[0]='ID';
            $presentation[0]='objectPropertyCellMedium';
            $columnNames[0]='id';
        }
        
        foreach ($table->columns as $key => $value)
        {
        	if ( $key == 'id' ) { continue; }
            $column = $value->getColumnMetaData();
            if ($column['appearsInLists'] && !($column['name']=='active' && $table->getHasActivationFlag()) && !isset($headers[$column['ordering']]))
            {
                $headers[$column['ordering']] = $column['description'];
                $query .= ', '. $column['name'];
                $presentation[$column['ordering']] = $column['adminCSS'];
                $columnNames[$column['ordering']] = $column['name'];
            }
        }
        
        if ($table->getHasActivationFlag())
        {
            $query.=', active';
            $displayActivation = true;
        }else
        {
            $displayActivation = false;
        }
        if (trim($table->getAdminListMarkingCondition())!='')
        {
            $displayPreview = $table->getAdminListMarkingCondition();
        }else
        {
            $displayPreview = false;
        }
        if (trim($table->getOrderingColumnForListings())!='')
        {
            $tmp = str_replace(' desc', '', $table->getOrderingColumnForListings());
            $meta = $table->columns[$tmp]->getColumnMetaData();
            if ($meta['type'] == WOOOF_dataBaseColumnTypes::int)
            {
                $displayUpDown = true;
            }else{
                $displayUpDown = false;
            }
        }else
        {
            $displayUpDown = false;
        }
        $query .= ' from '. $table->getTableName();
        if ($where!='') 
        {
            $query .= ' '. $where;
        }
        if (isset($_GET['orderBy']) && in_array($_GET['orderBy'], $columnNames))
        {
            $query .= ' order by '. $this->cleanUserInput($_GET['orderBy']);
        }else if (trim($table->getOrderingColumnForListings())!='')
        {
            $query .= ' order by '. trim($table->getOrderingColumnForListings());
        }
        
        require($this->getConfigurationFor('templatesRepository') . 'wooof_doTableList_1.activeTemplate.php');
        
        if ($parentId!='')
        {
            $content = $addItemParent;
        }else
        {
            $content = $addItem;
        }
        
        $headers = array_values($headers);
        $presentation = array_values($presentation);
        $columnNames = array_values($columnNames);
        $content.=$this->getPresentationListFromQuery($query,$headers,$presentation, $table, $displayActivation, $displayPreview, $displayUpDown, TRUE, $columnNames, $parentId); 
        return $content;
    }

    /**
     * 
     * @param string $role	// a role name
     * @return boolean
     */
    public function amIA($role)
    {
    	// antonis
    	return isset($this->userRoleNamesArray[$role]);
    	/*
        global $userData;
        $roleR = $this->db->query('select * from __roles where role=\''. $this->db->escape($role) .'\'');
        if (!mysqli_num_rows($roleR))
        {
            return FALSE;
        }
        $role = $this->db->fetchAssoc($roleR);

        $result = $this->db->query('select * from __userRoleRelation where userId=\''. $userData['id'] .'\' and roleId=\''. $role['id'] .'\'');
        if (mysqli_num_rows($result))
        {
            return TRUE;
        }else
        {
            return FALSE;
        }
        */
    }

    /**
     * 
     * @param string $address
     * @param string $data
     * @return boolean
     */
    public function storeToCache($address, $data)
    {
        if ($this->configuration['isMemCacheEnabled'])
        {
            return $this->memCache->set($address,$data,0);
        }
        if ($this->configuration['isCacheEnabled'])
        {
            $tR = $this->db->query('select * From __cache where binary address=\''. $this->cleanUserInput($address) .'\'');
            if ( $tR === FALSE ) { return FALSE; }
            
            if (mysqli_num_rows($tR))
            {
                $result = $this->db->query('update __cache set payload=\''. $this->cleanUserInput($data, TRUE) .'\' where binary address = \''. $this->cleanUserInput($address) .'\',');
            }else
            {
                $result = $this->db->query('insert into __cache set address = \''. $this->cleanUserInput($address) .'\', payload=\''. $this->cleanUserInput($data,TRUE) .'\'');
            }

            return $result;
        }
        return FALSE;
    }

    public function fetchApplicationFragment($name, $parametersArray=[], $tpl=[] )
    {
        // fragment is expected to be of the form
        // - return array( 'subFragmentName' => subfragmentContent, ... )
        // - OR outputing (html) content: a site template
        $fullFilename = $this->getConfigurationFor('applicationTemplatesRepository') . $name;
       
        // Allow continuation with following check
        if ( !file_exists($fullFilename) ) {
            $fullFilename = "fragments/$name";
            if(!file_exists($fullFilename)) {
                $this->logError(self::_ECP."0510 fetchApplicationFragment: Fragment [$fullFilename] not found.");
                return FALSE;
            }
        }
        
        $wo = $this;
       
        return require $fullFilename;
    }

    /**
     * 
     * @param string $address
     * @return false|__cache row
     */
    public function fetchFromCache($address)
    {

        if ($this->configuration['isMemCacheEnabled'])
        {
            return $this->memCache->get($address);
        }
        if ($this->configuration['isCacheEnabled'])
        {
            $tR = $this->db->query('select * From __cache where binary address=\''. $this->cleanUserInput($address) .'\'');
            if ( $tR === FALSE ) { return FALSE; }
            
            if (mysqli_num_rows($tR))
            {
                return $this->db->fetchAssoc($tR);
            }else
            {
                return FALSE;
            }
        }
        return FALSE;
    }

    /**
     * 
     * @param string $address
     * @return boolean
     */
    public function deleteFromCache($address)
    {
        if ($this->configuration['isMemCacheEnabled'])
        {
            return $this->memCache->delete($address);
        }
        if ($this->configuration['isCacheEnabled'])
        {
            $tR = $this->db->query('delete From __cache where binary address=\''. $this->cleanUserInput($address) .'\'');
            if ( $tR !== FALSE )
            {
                return TRUE;

            }else
            {
                return FALSE;
            }
        }
        return FALSE;
    }

    
    public function setCustomConfigurationFor($customGroup, $customItem, $val)
    {
    	$this->configurationCustom[$customGroup][$customItem] = $val;
    }
    	
    /**
     * 
     * @param string $item		  // Leave empty for getting a customGroup as a whole 
     * @param string $customGroup // Optional
     * @return anything|anything[] // usually a string
     */
    public function getConfigurationFor($item, $customGroup='')
    {
    	if ( $customGroup == '' )
    	{
	        if (isset($this->configuration[$item]))
	        {
	            return $this->configuration[$item];
	        }else
	        {
	            return NULL;
	        }
    	}
    	else {
    		if ( isset($this->configurationCustom[$customGroup]) ) {
    			if ( $item != '' ) {
    				if ( isset($this->configurationCustom[$customGroup][$item]) ) {
    					return  $this->configurationCustom[$customGroup][$item];
    				}
    				else {
    					return NULL;
    				} 
    			}	// item given
    			else {
    				return $this->configurationCustom[$customGroup];
    			}
    		}	// custom group exists
    		else {
    			return NULL;
    		}
    	}
    }	// getConfigurationFor


    /**
     * Fetches row as numeric array (first value at 0, 2nd value at 1, etc).
     * @param null|row $result
     */
    public function fetchRow($result)
    {
    	return mysqli_fetch_row($result);
    }
    
    /**
     * Fetches row as associative array with column names as keys.
     * @param null|row $result
     */
    
    public function fetchAssoc($result)
    {
    	return mysqli_fetch_assoc($result);
    }
    
    /**
     * Fetches row as both
     * associative array with column names as keys and
     * numeric array.
     * @param null|row $result
     */
    public function fetchArray($result)
    {
    	return mysqli_fetch_array($result);
    }
    
    
    public function getNumRows($result)
    {
    	return mysqli_num_rows($result);
    }

    public function getOriginalPostValues() {
    	return $this->originalPostValues;
    }
    
    public function getOriginalFilesValues() {
    	return $this->originalFilesValues;
    }

    /***************************************************************************/
    //
    /***************************************************************************/
    
    // returns true or false
    public function sendPHPMailerMail(
    		$p_options_array,			// Associative array with the following keys:
    		// FROM (mandatory), REPLY_TO, SMTP_HOST, SMTP_PORT, SMTP_AUTH (bool),
    		// SMTP_USERNAME, SMTP_PASSWORD, SMTP_SECURE (absent or 'tls' or 'ssl'),
    		// WORD_WRAP (word wrap after that many chars), SMTP_DEBUG
    		$p_addresses_array,		// Associative array:
    		// TO (mandatory) => <e-mail> or array( <e-mail>, <display_name> )
    		// CC => as TO, BCC as TO
    		$p_subject,
    		$p_html_message,
    		$p_text_message,
    		$p_attachments,				// array of filenames to send as attachments: <filename> or array( filename, display_name )
    		&$po_error_message
    )
    {
    	// http://phpmailer.worxware.com/?pg=properties
    	// http://phpmailer.github.io/PHPMailer/index.html
    
    	$place = __CLASS__ . '::' . __FUNCTION__;
    	//$this->debug( "$place" );
    
    	if ( $p_options_array === false or !is_array($p_options_array) or count($p_options_array) == 0 ) {
    		$po_error_message = "$place: No options were provided.";
    		return false;
    	}
    	
    	if ( $p_addresses_array === false or !is_array($p_addresses_array) or count($p_addresses_array) == 0  ) {
    		$po_error_message = "$place: No p_addresses_array were provided.";
    		return false;
    	}
    	 
    	if ( !isset($p_addresses_array['TO']) or !is_array($p_addresses_array['TO']) or count($p_addresses_array['TO']) == 0 ) {
    		$po_error_message = "$place: No TO addresses were provided.";
    		return false;
    	}
    	
    	if ( !isset($p_options_array['FROM']) ) {
    		$po_error_message = "$place: No FROM WAS provided.";
    		return false;
    	}
    
    	require_once $this->getConfigurationFor('siteBasePath') . $this->getConfigurationFor('wooofClassesPath') . 'thirdParty/PHPMailer/PHPMailerAutoload.php';
    
    	$mail = new PHPMailer;
    	$mail->CharSet 	= 'UTF-8';
    	$mail->Encoding	= 'base64';
    	 
    	if ( isset($p_options_array['SMTP_HOST']) ) {
    		$mail->isSMTP();
    		$mail->Host 		= $p_options_array['SMTP_HOST'];
    		$mail->SMTPAuth 	= $p_options_array['SMTP_AUTH'];
    
    	    if ( isset($p_options_array['SMTP_PORT']) ) {
    			$mail->Port = $p_options_array['SMTP_PORT'];
    		}
    
    	    if ( isset($p_options_array['DEBUG_LEVEL']) ) {
    			$mail->SMTPDebug = $p_options_array['DEBUG_LEVEL'];
    		}
    
    		if ( $mail->SMTPAuth ) {
    			$mail->Username = $p_options_array['SMTP_USERNAME']; 		// 'apapanto@twinnet.gr';    // SMTP username
    			$mail->Password = $p_options_array['SMTP_PASSWORD'];    // SMTP password
    			if ( isset($p_options_array['SMTP_SECURE']) ) {
    				$mail->SMTPSecure = $p_options_array['SMTP_SECURE'];	// 'tls', Enable encryption, 'ssl' also accepted
    			}
    		}
    	}	// SMTP
    
    	$mail->From = $p_options_array['FROM']; //'apapanto@twinnet.gr';
    	if ( isset($p_options_array['FROM_NAME']) ) {
    		$mail->FromName = $p_options_array['FROM_NAME'];
    	}
		else {
			$mail->FromName = $p_options_array['FROM'];
		}    
		
    	if ( isset($p_options_array['REPLY_TO']) ) {
    		$mail->addReplyTo($p_options_array['REPLY_TO']);
    	}
    
    	// Add rescipients
    	foreach( $p_addresses_array['TO'] as $anAddress ) {
    		if ( is_array($anAddress) and isset($anAddress[0]) and isset($anAddress[1]) ) {
    			$mail->addAddress( $anAddress[0], $anAddress[1] );
    		}
    		else {
    			$addresses = explode( ',', $anAddress );
    			foreach( $addresses as $a_singleAddress ) {
    				$mail->addAddress( trim($a_singleAddress) );
    				//var_dump(trim($a_singleAddress));
    			}
    		}
    	}
    
    	// Add CC
    	if ( isset( $p_addresses_array['CC'] ) and is_array($p_addresses_array['CC']) and count($p_addresses_array['CC']) > 0 ) {
    		foreach( $p_addresses_array['CC'] as $anAddress ) {
    			if ( is_array($anAddress) and isset($anAddress[0]) and isset($anAddress[1]) ) {
    				$mail->addCC( $anAddress[0], $anAddress[1] );
    			}
    			else {
    				$addresses = explode( ',', $anAddress );
    				foreach( $addresses as $a_singleAddress ) { $mail->addCC( trim($a_singleAddress) ); }
    			}
    		}
    	}
    
    	// Add BCC
    	if ( isset( $p_addresses_array['BCC'] ) and is_array($p_addresses_array['BCC']) and count($p_addresses_array['BCC']) > 0 ) {
    		foreach( $p_addresses_array['BCC'] as $anAddress ) {
    			if ( is_array($anAddress) and isset($anAddress[0]) and isset($anAddress[1]) ) {
    				$mail->addBCC( $anAddress[0], $anAddress[1] );
    			}
    			else {
    				$addresses = explode( ',', $anAddress );
    				foreach( $addresses as $a_singleAddress ) { $mail->addBCC( trim($a_singleAddress) ); }
    			}
    		}
    	}
    
    	if ( isset($p_options_array['WORD_WRAP']) ) {
    		$mail->WordWrap = $p_options_array['WORD_WRAP'];                         // Set word wrap to 50 characters
    	}
    
    	// Add attachments
    	if ( isset( $p_attachments ) and is_array($p_attachments) and count($p_attachments) > 0 ) {
    		foreach( $p_attachments as $anAttachment ) {
    			if ( is_array($anAttachment) and isset($anAttachment[0]) and isset($anAttachment[1]) ) {
    				$mail->addAttachment( $anAttachment[0], $anAttachment[1] );
    			}
    			else {
    				$mail->addAttachment( $anAttachment );
    			}
    		}
    	}
    
    	if ( WOOOF::hasContent($p_html_message) ) {
    		$mail->isHTML(true);
    		$mail->Body    = $p_html_message;
    		$mail->AltBody = $p_text_message;
    	}
    	else {
    		$mail->isHTML(false);
    		$mail->Body    = $p_text_message;
    	}
    		
    
    	$mail->Subject = $p_subject;
    
    	if( !$mail->send() ) {
    		$po_error_message = "$place: Mailer Error: $mail->ErrorInfo";
    		return false;
    	}
    
    	return true;
    }	// sendPHPMailerMail
    
    /**
    * checkFileUpload checks whether file upload succeeded or not.
    *
    * @param string $postName the key of the $_FILES array to check
    * @param string $errorCode the error code to display if something has gone wrong
    *
    * @return boolean/String TRUE if successful FALSE if not, 'INDF'.
    */
    function checkFileUpload($postName, $errorCode)
    {
    	$uploadError = TRUE;
        if (!isset($_FILES[$postName]))
        {
        	WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_NOTICE, 'Case 1 No file uploaded for '. $postName);
            return 'INDF';
        }
        
        if ($_FILES[$postName]['error']!=UPLOAD_ERR_OK)
        {
            $uploadErrors = array(
                UPLOAD_ERR_OK=>'UPLOAD_ERR_OK There is no error, the file uploaded with success',
                UPLOAD_ERR_INI_SIZE=>'UPLOAD_ERR_INI_SIZE The uploaded file exceeds the upload_max_filesize directive in php.ini',
                UPLOAD_ERR_FORM_SIZE=>'UPLOAD_ERR_FORM_SIZE The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                UPLOAD_ERR_PARTIAL=>'UPLOAD_ERR_PARTIAL The uploaded file was only partially uploaded',
                UPLOAD_ERR_NO_FILE=>'UPLOAD_ERR_NO_FILE No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR=>'UPLOAD_ERR_NO_TMP_DIR Missing a temporary folder',
                UPLOAD_ERR_CANT_WRITE=>'UPLOAD_ERR_CANT_WRITE Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'UPLOAD_ERR_EXTENSION File upload stopped by extension'
            ); 
            if (isset($uploadErrors[$_FILES[$postName]['error']]))
            {
                $uploadError = $uploadErrors[$_FILES[$postName]['error']];
            }else
            {
                $uploadError = 'UNKNOWN ERROR occured. This version of WOOOF doesn\'t recognize the returned error code';
            }
            
        }
        
        if ($_FILES[$postName]['error']==UPLOAD_ERR_NO_FILE)
        {
             WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_NOTICE, 'No file uploaded for '. $postName);
             return 'INDF';
        }elseif ($uploadError!==TRUE)
        {
            WOOOF::$instance->logError($errorCode.' Error in File Upload process. '. $uploadError);
            return FALSE;
        }

        return TRUE;
    }
    
}	// class WOOOF

/*
 * WOOOF_dataBase
 * 
 * represents a data base connection to a specific database.
 * It is the only sanctioned gateway to the MySQL backend when using WOOOF.
 * Logs queries into a db table and/or a file if requested.
 * 
 */

class WOOOF_dataBase
{
	const _ECP = 'WDB';	// Error Code Prefix
	
    private $connection;
    private $logToFile;
    private $logFileName;
    private $logFileHandle;
    private $logToDatabase;
    private $logTable;
    private $dataBaseName;
    private $dataBaseHost;
    private $fileLoggingMode;
    private $dataBaseLoggingMode;
    private $myConnectionSerialNumber;
    private $logConnection;
    private $lastQueryInfo;
    private $logForFlushingToFile;
    private $currentMicroTime;
    private $options;
    private $refinedOptions;
    
    public static $howManyConnections=0;
    
    public $constructedOk = true;	// check this after a new to ensure object was created successfully

    public $resultActive;
    public $resultRows;
    public $lastDbResult;
    
    // new in version 0.12.54. 
    // Relevant only for statements executed through the 'query' method.
    // No of Rows in result for SELECTs, No of Affected rows for DML statements.
    public $affectedRows;	
    
    public function __construct($currentMicroTime, $host='localhost', $userName='', $password='', $dbName='') 
    {
    	$wo = WOOOF::$instance;
    	
        $databaseName = $wo->getConfigurationFor('databaseName');
        $databaseUser = $wo->getConfigurationFor('databaseUser');
        $databasePass = $wo->getConfigurationFor('databasePass');
        $databaseHost = $wo->getConfigurationFor('databaseHost');

        $this->resultActive = FALSE;
        
        $this->currentMicroTime = $currentMicroTime;
        
        if ($dbName=='')
        {
            $this->myConnectionSerialNumber = WOOOF_dataBase::$howManyConnections;
            WOOOF_dataBase::$howManyConnections ++;
            $host = $databaseHost[$this->myConnectionSerialNumber];
            $userName = $databaseUser[$this->myConnectionSerialNumber];
            $password = $databasePass[$this->myConnectionSerialNumber];
            $dbName = $databaseName[$this->myConnectionSerialNumber];
        }
        $this->dataBaseHost = $host;
        $this->logFileHandle='';
        $this->connection = mysqli_connect($host, $userName, $password);
        // we are always requesting a new connection so as to avoid two objects having the same actual connection,
        // as this would probably mess query logging, error/exception handling etc
        
        if ($this->connection === FALSE)
        {
        	$this->constructedOk = false;
            die('Database connection failed with MySQL error :<br/>'. mysqli_connect_error ());
        }
        
        $this->logConnection=  $this->connection;
        if ($this->selectDataBase($dbName)===FALSE)
        {
        	$this->constructedOk = false;
        	die('Data base selection failed.');
        }
        
        $this->query('set names utf8');
        
        $autoCommit = $wo->getConfigurationFor('databaseAutoCommit')[$this->myConnectionSerialNumber];
        $this->query("SET autocommit=" . ( $autoCommit ? '1' : '0' ) );	// to show in SQL Log
        $this->connection->autocommit($autoCommit);		// to properly inform possible replication etc services
        
        $sqlMode 	= $wo->getConfigurationFor('databaseSQLMode')[$this->myConnectionSerialNumber];
		if ( WOOOF::hasContent($sqlMode) ) {
			// dev.mysql.com/doc/refman/5.0/en/sql-mode.html
	        $succ = $this->query("SET sql_mode='" . $sqlMode . "'" );
		    if (!$succ) {
	        	$this->constructedOk = false;
    	        die("Failed to set sql_mode [$sqlMode]<br/>". $this->error());
        	}
		}
        
        $optionsCheck = $this->query('SELECT * FROM information_schema.tables WHERE table_schema = \''. $dbName .'\' AND table_name = \'__options\' LIMIT 1');
        if (mysqli_num_rows($optionsCheck))
        {
            $optionsR =  $this->query('select * From __options');
            while($o = $this->fetchAssoc($optionsR))
            {
                $this->options[$o['id']] = $o;
                $this->refinedOptions[$o['optionName']] = $o['optionValue'];
            }

            // TODO: Check and uncomment!
            /*
            $metaDataDBVersion = WOOOF_MetaData::versionReadFromDB($wo, $this);
        	if ( $metaDataDBVersion !== NULL ) {
        		if ( substr($metaDataDBVersion,0,2) < substr(WOOOF_MetaData::$version,0,2) ) {
        			die("Need to upgradre DB MetaData: DB version [$metaDataDBVersion] is behind Code Version [". WOOOF_MetaData::$version. "]");
        		}
        	}
        	*/
        }	// options found in DB
        
        $this->constructedOk = true;
        
    }	// __construct

    
    public function getOptions()
    {   
        return $this->options;
    }

    public function escape($string)
    {
        return mysqli_real_escape_string($this->connection, $string);
    }

    public function getRefinedOptions()
    {   
        return $this->refinedOptions;
    }

    public function loggingToFile($activate,$filePath='')
    {
    	$wo = WOOOF::$instance;
        $logFilePath = $wo->getConfigurationFor('logFilePath');
        global $__isSiteBuilderPage;

        if ($activate)
        {
            if ($filePath == '')
            {
                $this->logFileName = $logFilePath[$this->myConnectionSerialNumber];
            }else
            {
                $this->logFileName = $filePath;
            }

            if ($__isSiteBuilderPage)
            {
                $this->logFileName .= 'dbManager_';
            }
            
            $this->logFileName .= $this->dataBaseName .'_log.sql';
            
            if ($this->logFileHandle!='')
            {
                fclose($this->logFileHandle);
            }
            $this->logFileHandle = fopen($this->logFileName, 'a');
            if ($this->logFileHandle === FALSE)
            {
                $wo->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0010 " . 'Database Log File Opening Failed! Requested file was '. $this->logFileName);
                $this->logToFile = FALSE;
                return FALSE;
            }else
            {
                $this->logToFile = TRUE;
            }
        }else
        {
            $this->logToFile = FALSE;
        }
        return TRUE;
    }
    
    function __destruct() 
    {
        if ($this->logToFile)
        {
            //echo 'inDestructor!<br/>';
            fputs($this->logFileHandle, $this->logForFlushingToFile, mb_strlen($this->logForFlushingToFile));
        }
    }
    
    public function loggingToDatabase($activate,$table='')
    {
        $logTable = WOOOF::$instance->getConfigurationFor('logTable');
        if ($activate)
        {
            if ($table == '')
            {
                $this->logTable = $logTable[$this->myConnectionSerialNumber];
            }else
            {
                $this->logTable = $table;
            }
            $this->logToDatabase = TRUE;
        }else
        {
            $this->logToDatabase = FALSE;
        }
    }
    
    public function selectDataBase($newDataBaseName)
    {
        if ($newDataBaseName!=$this->dataBaseName)
        {
            if (mysqli_select_db($this->connection, $newDataBaseName) === FALSE)
            {
                WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0020 " . 'Could not select requested database ('. $newDataBaseName .'). Mysql returned : '. $this->error());
                return FALSE;
            }
            $this->dataBaseName = $newDataBaseName;
           
            // reactivating file and database logging if already activated should 
            // be called here so as to make sure logging facilities are present and available
            // if not an exception will be thrown and no queries will be executed without logging 
            if ($this->logToFile)
            {
                $this->loggingToFile(TRUE);
            }
            
            if ($this->logToDatabase)
            {
                $this->loggingToDatabase(TRUE);
            }
        }
        
        return TRUE;
    }

    public function commit() {
    	//return mysqli_commit($this->connection);
    	return $this->query('commit');
    }
    
    public function rollback() {
    	//return mysqli_rollback($this->connection);
    	return $this->query('rollback');
    }
    

    /**
     * 
     * @param string $sanitizedQueryText
     * @return boolean|mysqli_result // FALSE (on failure), mysqli_result (for select,show,describe,explain), or TRUE (for other queries)
     */
    public function query($sanitizedQueryText)
    {
    	$wo = WOOOF::$instance;
    	
    	$this->affectedRows = null;
    	
        $result = mysqli_query($this->connection, $sanitizedQueryText);
        
        $this->affectedRows = mysqli_affected_rows ($this->connection);
        
        if ($this->error() != '')
        {
            $debugInfo = debug_backtrace();
            $lastLevel = count($debugInfo)-1;
            //$wo->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, 
            //    "Query Failed ! at ". $debugInfo[$lastLevel]['file'] ." on line ". $debugInfo[$lastLevel]['line'] ." Query text: \n\n$sanitizedQueryText\n\nMysql Error:".  $this->error());
            $wo->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, 
                self::_ECP."0030 Mysql Error: ".  $this->error());
            if ($wo->getConfigurationFor('displayDatabaseErrors') && !$wo->isAjax)
            {
                echo '<br><span style="font-size:14px;color:red;background-color:yellow;">' . nl2br("Query Failed ! at ". $debugInfo[0]['file'] ." on line ". $debugInfo[0]['line'] ."</font> Query text: \n\n$sanitizedQueryText\n\nMysql Error:".  $this->error() ."\n". /* print_r($debugInfo,TRUE) .*/ '</span><br>' );
            }
            return FALSE;
        }
        $this->lastQueryInfo = mysqli_info($this->connection);
        if (!$this->lastQueryInfo && $result!==TRUE)
        {
            if (@mysqli_num_rows($result))
            {
                $this->lastQueryInfo = "Returned Rows: ". mysqli_affected_rows($this->connection);
            }elseif (mysqli_affected_rows($this->connection))
            {
                $this->lastQueryInfo = "Affected Rows: ". mysqli_affected_rows($this->connection);
            }
        }
        
        $displaySqlLevel = $wo->getConfigurationFor('displaySQLStatementsLevel');
        if ( !( $displaySqlLevel == 0 || ( $displaySqlLevel == 1 && strpos($sanitizedQueryText, '__') !== FALSE ) ) ) {
	        echo $sanitizedQueryText .PHP_EOL.'<br/>';
        }
        
        //always call logging, better safe than sorry
        $this->logQuery($sanitizedQueryText);
        
        return $result;
    }
    
    /**
     * 
     * @param string[] $sanitizedQueriesTextsArray	// dml and/or ddl queries. 
     * @return boolean|mysqli_result // FALSE (on failure), mysqli_result (for select,show,describe,explain), or TRUE (for other queries)
     */
    public function queryMultiple($sanitizedQueriesTextsArray)
    {
    	$wo = WOOOF::$instance;
    	    	
    	if ( !is_array($sanitizedQueriesTextsArray) or count($sanitizedQueriesTextsArray) == 0 ) {
    		$wo->logError(self::_ECP."0520 Array of at least one query string(s) must be passed in 'queryMultiple'");
    		return false;
    	}
    	
		foreach( $sanitizedQueriesTextsArray as $aQuery ) {
			$res = $this->query($aQuery);
			if ( $res === FALSE ) { return FALSE; }
		}    	
    	
		return true;
    }	// queryMultiple
    
    /**
     * 
     * @param string $sanitizedQueryText
     * @return boolean
     */
    private function logQuery($sanitizedQueryText)
    {
        global $userData;
        global $__isSiteBuilderPage;
                
        $doLog 	= true;
        $retVal = true;
        
        $wo = WOOOF::$instance;
        
        $sqtSingleLine = trim(preg_replace("/(\r\n|\n|\r|\t| )+/", ' ', $sanitizedQueryText));
        
        $debugSqlLevel = $wo->getConfigurationFor('debugSQLStatementsLevel');
        if ( !( $debugSqlLevel == 0 || ( $debugSqlLevel == 1 && ( strpos($sanitizedQueryText, '__') !== FALSE && strpos($sanitizedQueryText, '__externalFiles') === FALSE ) ) ) ) {
        	$wo->debug('SQL: ' . $sqtSingleLine);
        }
        
        
        if ($this->logToFile)
        {
            //echo $this->fileLoggingMode .'<br>';
            if ($this->fileLoggingMode == WOOOF_databaseLoggingModes::doNotLogSelects)
            {
                if (substr(strtolower(trim($sanitizedQueryText)),0,6) == 'select')
                {
                    $doLog=FALSE;
                }
            }else if ($this->fileLoggingMode == WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow)
            {
                if (substr(strtolower(trim($sanitizedQueryText)),0,6) == 'select' || substr(strtolower(trim($sanitizedQueryText)),0,4) == 'desc' || substr(strtolower(trim($sanitizedQueryText)),0,4) == 'show')
                {
                    $doLog=FALSE;
                }
            }

            if ($doLog)
            {
            	// Exclude for dbManager (most) irrelevant queries
            	//
            	$sqtSingleLineUpper = strtoupper($sqtSingleLine);
            	if ( 
            		!$__isSiteBuilderPage or 
            		strpos($sqtSingleLine,'__columnMetaData') !== FALSE or 
            		strpos($sqtSingleLine,'__tableMetaData') !== FALSE or 
            		strpos($sqtSingleLineUpper,'ALTER ') !== FALSE or 
            		strpos($sqtSingleLineUpper,'CREATE ') !== FALSE 
            	) {
	                $strToWrite = 
	                	'-- ' . // start with a MySQL Comment string
	                	$wo->sid . ' | ' .
	                	$wo->currentGMTDateTimeAndXSeconds() . ' | ' .
	                	$wo->userData['id'] . ' | ' .
	                	$_SERVER['PHP_SELF']
	                ;
	                
	                $this->logForFlushingToFile .= $strToWrite . PHP_EOL . $sqtSingleLine .";" . PHP_EOL;
            	}
            }
        }
        
        if ($this->logToDatabase)
        {
            $doLog=TRUE;
            if ($this->dataBaseLoggingMode == WOOOF_databaseLoggingModes::doNotLogSelects)
            {
                if (substr(strtolower(trim($sanitizedQueryText)),0,6)=='select')
                {
                    $doLog = FALSE;
                }
            }else if ($this->dataBaseLoggingMode == WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow)
            {
                if (substr(strtolower(trim($sanitizedQueryText)),0,6) == 'select' || substr(strtolower(trim($sanitizedQueryText)),0,4) == 'desc' || substr(strtolower(trim($sanitizedQueryText)),0,4) == 'show')
                {
                    $doLog=FALSE;
                }
            }
            if ($doLog)
            {
                $query = 'insert into '. $this->logTable .' (executionTime,queryText) values ('. $this->currentMicroTime .',\''. $this->escape($sanitizedQueryText) .'\')';
                mysqli_query($this->logConnection, $query);
                if ($this->error()!='')
                {
                	$retVal = false;
                    $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0040 Log Query Failed ! Query text: \r$query\r\rMysql Error:".  $this->error());
                }
            }
        }
        
        return $retVal;
    }	// logQuery
    
    
    public function setLoggingType($newLogToDBType,$newLogToFileType)
    {
        if ($newLogToDBType == WOOOF_databaseLoggingModes::doNotLogSelects)
        {
            $this->dataBaseLoggingMode = WOOOF_databaseLoggingModes::doNotLogSelects;
        }else if ($newLogToDBType == WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow)
        {
            $this->dataBaseLoggingMode = WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow;
        }else
        {
            $this->dataBaseLoggingMode = WOOOF_databaseLoggingModes::logAllQueries;
        }
        
        if ($newLogToFileType == WOOOF_databaseLoggingModes::doNotLogSelects)
        {
            $this->fileLoggingMode = WOOOF_databaseLoggingModes::doNotLogSelects;
        }else if ($newLogToFileType == WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow)
        {
            $this->fileLoggingMode = WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow;
        }else
        {
            $this->fileLoggingMode = WOOOF_databaseLoggingModes::logAllQueries;
        }
    }	// setLoggingType
    
    
    public function setLogConnection($connectionHandle,$logTable='')
    {
        $this->logConnection=$connectionHandle;
        if ($logTable!='')
        {
            $this->logTable=$logTable;
        }
        $this->loggingToDatabase(TRUE, $this->logTable);
    }	// setLogConnection
    
    /**
     * 
     * @param string $location
     * @param string $userId
     * @return false|array[string => boolean]	// string denotes an action
     */
    public function getSecurityPermitionsForLocationAndUser($location, $userId)
    {
        $aggressiveSecurity = WOOOF::$instance->getConfigurationFor('aggressiveSecurity');

        // Antonis, rightsCache
        // echo "-- getSecurityPermitionsForLocationAndUser [$location] [$userId] [$aggressiveSecurity] <br>";	// antonis
        /*
        $wo = WOOOF::$instance; // antonis
        if ( isset($wo->rightsCache[$userId]) && isset($wo->rightsCache[$userId][$location]) ) {
        	echo '  -- from cache<br>';
        	return $wo->rightsCache[$userId][$location];
        }
        */
        
        $relationR = $this->query('select * from __userRoleRelation where userId=\''. WOOOF::$instance->cleanUserInput($userId) .'\'');
        if ( $relationR === FALSE ) {
			return FALSE;        	
        }
        $returnArray = array();
        while ($relation = $this->fetchAssoc($relationR))
        {
            //echo 'userId -> '. $userId .' role -> "'. $relation['roleId'] .'" location -> '. $location .' <br/>';
            $resultArray = $this->getSecurityPermitionsForLocationAndRole($location, $relation['roleId']);
            if (is_array($resultArray))
            {
                while(list($key,$val) = each($resultArray))
                {
                    if (isset($returnArray[$key]))
                    {
                        if ($val != $returnArray[$key])
                        {
                            if ($aggressiveSecurity == TRUE)
                            {
                                if ($returnArray[$key] == TRUE)
                                {
                                    if ($val != TRUE)
                                    {
                                        $returnArray[$key] = FALSE;
                                    }
                                }
                            }else
                            {
                                if ($returnArray[$key] != TRUE)
                                {
                                    if ($val == TRUE)
                                    {
                                        $returnArray[$key] = TRUE;
                                    }
                                }
                            }
                        }
                    }else   
                    {
                        $returnArray[$key] = $val;
                    }
                }
            }
        }
		// Antonis, rightsCache
		/*
        $wo->rightsCache[$userId][$location] = $returnArray;
        
        //echo '<br>-------------<br>';
        //var_dump( $returnArray );
        //echo '<br>-------------<br>';
         */

        return $returnArray;
    }	// getSecurityPermitionsForLocationAndUser
    
    /**
     * 
     * @param string $location
     * @param string $roleId
     * @return false|array[string => boolean]	// string denotes an action
     */
    public function getSecurityPermitionsForLocationAndRole($location, $roleId)
    {
        $locationPieces = explode('_', $location);
        $numberOfPieces = count($locationPieces);
        
        $currentLocation='';
        $permitions='';
        
        // antonis
        $s1 = $s2 = '';
        foreach( $locationPieces as $aLocationPiece ) {
        	$s1 .= $this->escape($aLocationPiece);
        	$s2 .= "'" . $s1 . "'" . ',';
        	$s1 .= '_';
        }
        $s2 = substr($s2, 0, strlen($s2)-1);
        // var_dump( $location, $s2 ); echo "<br>";
        
        /*
        for($c = 0; $c < $numberOfPieces ; $c++)
        {
            if ($currentLocation!='')
            {
                $currentLocation.='_';
            }
            $currentLocation.=$locationPieces[$c];
            $result = $this->query('SELECT * FROM __lrbs where location=\''. $this->escape($currentLocation) .'\' and role=\''. $this->escape($roleId) .'\'');
            while($p = $this->fetchAssoc($result))
            {
                if ($p['allowed'] == '1')
                {
                    $permitions[$p['action']]=TRUE;
                    //echo 'role-> '. $roleId .' location -> '. $currentLocation .' action -> '. $p['action'] .' TRUE <br/>' ;
                }else
                {
                    $permitions[$p['action']]=FALSE;
                    //echo 'role-> '. $roleId .' location -> '. $currentLocation .' action -> '. $p['action'] .' FALSE <br/>' ;
                }
            }
        }
        */
        $result = $this->query('SELECT allowed, action FROM __lrbs where location in ('. $s2 .') and role=\''. $this->escape($roleId) .'\'');
        if ( $result === FALSE ) { return FALSE; } 
        
        while($p = $this->fetchRow($result))
        {
        	if ($p[0] == '1')
        	{
        		$permitions[$p[1]]=TRUE;
        		//echo 'role-> '. $roleId .' location -> '. $currentLocation .' action -> '. $p['action'] .' TRUE <br/>' ;
        	}else
        	{
        		$permitions[$p[1]]=FALSE;
        		//echo 'role-> '. $roleId .' location -> '. $currentLocation .' action -> '. $p['action'] .' FALSE <br/>' ;
        	}
        }
        
        
        return $permitions;
    }
    
    public function getDataBaseName()
    {
        return $this->dataBaseName;
    }
    
    public function getDataBaseHost()
    {
        return $this->dataBaseHost;
    }
    
    public function getLastQueryInfo()
    {
        return $this->lastQueryInfo;
    }
    
    /**
     * 
     * @param string $targetTable
     * @param string $desiredId	// optional. Must be 10 chars if set
     * @return false|string // the new id
     */
    public function getNewId($targetTable, $desiredId='')
    {
        $goOn = FALSE;
        do
        {
        	if ( $desiredId!='' && strlen($desiredId) == 10 ) {
        		$id = $desiredId;
        		$desiredId = '';
        	}
        	else {
	            $id = WOOOF::randomString(10);
        	}
        	
            $testR=$this->query('select id from '. $this->escape($targetTable) .' where id=\''. $id .'\'');
            if ( $testR === FALSE ) { return FALSE; }
            if (!mysqli_num_rows($testR) && $testR!==FALSE) $goOn=1;
        }while (!$goOn);
        return $id;
    }
    
    public function getEmptyTable()
    {
        return new WOOOF_dataBaseTable($this, '');
    }
    
    /*
    getAliasArray. Returns a Look up array out of a DB table.
    $tableName has the name of the table
    $whereClause has the where portion of the query (default is nothing)
    $valueColumn has the name of the column to be used as value (default is 'id')
    $descriptionColumn has the name of the column to be used as <option>'s descriptive text (default is 'name')
    */
    /**
     * 
     * @param string $tableName
     * @param string $whereClause
     * @param string $valueColumn
     * @param string $descriptionColumn
     * @return false|array( value => description )
     */

    public function getAliasArray($tableName, $whereClause='', $valueColumn='id', $descriptionColumn='name')
    {
        $array='';
        $result=$this->query('select '. $valueColumn .', '. $descriptionColumn .' from '. $tableName .' '. $whereClause);
        if ( $result === FALSE ) { return FALSE; }
        
        while ($row=$this->fetchRow($result))
        {
                $array[$row[0]]= $row[1];
        }
        return $array;
    }

    /*
    getDropList. Returns an html droplist out of a DB table.
    $tableName has the name of the table
    $selectName has the name to be given to the select tag
    $whereClause has the where portion of the query (default is nothing)
    $tagClass has the name of the css to apply to the tag
    $valueColumn has the name of the column to be used as value (default is 'id')
    $descriptionColumn has the name of the column to be used as <option>'s descriptive text (default is 'name')
    */
    public function getDropList($tableName, $selectName, $whereClause='', $tagClass=NULL, $valueColumn='id', $descriptionColumn='name', $orderBy='')
    {
        $cssForFormItem = WOOOF::$instance->getConfigurationFor('cssForFormItem');
        $templatesRepository = WOOOF::$instance->getConfigurationFor('templatesRepository');
        
        require($templatesRepository.'wooof_getDropList_1.activeTemplate.php');

        $tag = $selectHead;

        if ($orderBy!='')
        {
            $orderBy = ' ORDER BY '. $orderBy;
        }
        $query = 'select '. $valueColumn .', '. $descriptionColumn .' from '. $tableName .' '. $whereClause . $orderBy;

        $result=$this->query($query);
        $descriptionColumns = explode(',', $descriptionColumn);
        while ($row=$this->fetchRow($result))
        {
            require($templatesRepository.'wooof_getDropList_2.activeTemplate.php');
        }
        $tag .= $selectTail;

        return $tag;
    }
    
    /////// TODO CREATE ANOTHER METHOD THAT DOES THE SAME FROM EXISTING RESULTS 
    /**
     * 
     * @param string $tableName
     * @param string $radioName
     * @param string $isHorizontal
     * @param string $whereClause
     * @param string $tagClass
     * @param string $valueColumn
     * @param string $descriptionColumn
     * @param string $selectColumn
     * @param string $selectValue
     * @return false|string
     */
    public function getRadio($tableName, $radioName, $isHorizontal = FALSE ,$whereClause='', $tagClass=NULL, $valueColumn='id', $descriptionColumn='name', $selectColumn='', $selectValue='')
    {
        $cssForFormItem = WOOOF::$instance->getConfigurationFor('cssForFormItem');
        $templatesRepository = WOOOF::$instance->getConfigurationFor('templatesRepository');

        $tag='';

        if($tagClass===NULL)
        {
            $cTag = '';
        }elseif ($tagClass=='')
        {
            $cTag = 'class="'. $cssForFormItem['dropList'] .'"';
        }else
        {
            $cTag = 'class="'. $tagClass .'"';
        }

        $result=$this->query('select * from '. $tableName .' '. $whereClause);
        if ( $result === FALSE ) { return FALSE; }
        
        while ($row=$this->fetchAssoc($result))
        {
            if ($selectColumn!='')
            {
                if (isset($row[$selectColumn]) && $row[$selectColumn]== $selectValue)
                {
                    $selectedOption=' checked';
                }else   
                {
                    $selectedOption='';
                }
            }else
            {
                $selectedOption='';
            }
            
            require($templatesRepository.'wooof_getRadio_1.activeTemplate.php');
        }
        return $tag;
    }

    /*
    getDropListSelected. Returns an html droplist out of a DB table.
    $tableName has the name of the table
    $selectName has the name to be given to the select tag
    $whereClause has the where portion of the query (default is nothing)
    $tagClass has the name of the css to apply to the tag
    $valueColumn has the name of the column to be used as value (default is 'id')
    $descriptionColumn has the name of the column to be used as <option>'s descriptive text (default is 'name')
    */
    /**
     * 
     * @param string $tableName
     * @param string $selectName
     * @param string $whereClause
     * @param string $tagClass
     * @param string $valueColumn
     * @param string $descriptionColumn
     * @param string $columnToSearch
     * @param string $valueToSearch
     * @return boolean|string
     */
    public function getDropListSelected($tableName, $selectName, $whereClause='', $tagClass=NULL, $valueColumn='id', $descriptionColumn='name', $columnToSearch='', $valueToSearch='')
    {
        $cssForFormItem = WOOOF::$instance->getConfigurationFor('cssForFormItem');
    	$templatesRepository = WOOOF::$instance->getConfigurationFor('templatesRepository');

        require($templatesRepository.'wooof_getDropListSelected_1.activeTemplate.php');

        $tag = $selectHead;

        $result=$this->query('select '. $valueColumn .', '. $descriptionColumn .', '. $columnToSearch .' from '. $tableName .' '. $whereClause);
        if ( $result === FALSE ) { return FALSE; }
        
        $descriptionColumns = explode(',', $descriptionColumn);

        while ($row=$this->fetchRow($result))
        {
            //echo $columnToSearch .' - '. $row[2] .' - '. $valueToSearch .'<br>';
                if ($row[2]==$valueToSearch)
                {
                        $selected=' selected';
                }else
                {
                        $selected='';
                }
                require($templatesRepository.'wooof_getDropListSelected_2.activeTemplate.php');
        }
        $tag .= $selectTail;

        return $tag;
    }

    /**
     * 
     * @param string $table
     * @param string $rowId
     * @return false|null|row table as array
     */
    public function getRow($table, $rowId)
    {
        $r = $this->query('SELECT * FROM '. $table .' WHERE id=\''. $this->escape($rowId) .'\'');
        if ( $r === FALSE ) { return FALSE; }
        return $this->fetchAssoc($r);
    }

    /**
     * 
     * @param string $table
     * @param string $columnName
     * @param string $value
     * @param string $order
     * @return false|null|array // row table as associative array
     */
    public function getRowByColumn($table, $columnName, $value, $order='')
    {
        if ($order!='')
        {
            $order = ' ORDER BY '. $order;
        }
        $r = $this->query('SELECT * FROM `'. $this->escape($table) .'` WHERE `'. $this->escape($columnName) .'`=\''. $this->escape($value) .'\''. $order);
        if ( $r === FALSE ) { return FALSE; }
        return $this->fetchAssoc($r);
    }
    
    
	/**
	 * 
	 * @param string $query
	 * @param string $serialRows
	 * @param string $associativeRows
	 * @return boolean	// also fills-in $this->resultRows
	 */
    public function getResultByQuery($query, $serialRows = TRUE, $associativeRows = TRUE,  $deletePreviousResults = TRUE)
    {
        $this->lastDbResult = $this->query($query);
        if ( $this->lastDbResult === FALSE ) { return FALSE; }
        
        if ($deletePreviousResults)
        {
            $this->resultRows = array();
        }
        
        $this->resultActive = TRUE;
        $i = 0;
        while($row = $this->fetchAssoc($this->lastDbResult))
        {
            if ($associativeRows && isset($row['id']))
            {
                $this->resultRows[$row['id']] = $row;
            }
            if ($serialRows)
            {
                $this->resultRows[$i] = $row;
                $i++;
            }
        }
        
        return true;
    }
    
    public function getLastInsertId()
    {
        return mysqli_insert_id($this->connection);
    }
    
    // TODO: Fill-in with actual code
    public function getSecretSauce()
    {
        global $userData;
        
        $sSR = $this->query('select * from __secretSauce where userId=\''. $userData['id'] .'\'');
        
    }
    
    public function invalidateSecretSauce($sauce)
    {
        global $userData;
        
        $this->cleanSecretSauces();
        $sSR = $this->query('select * from __secretSauce where userId=\''. $userData['id'] .'\' and sauce=\''. WOOOF::$instance->cleanUserInput($sauce) .'\'');
        if ( $sSR === FALSE ) { return FALSE; }
        if (mysqli_num_rows($sSR))
        {
            return $this->query('delete from __secretSauce where userId=\''. $userData['id'] .'\' and sauce=\''. WOOOF::$instance->cleanUserInput($sauce) .'\'');
        }else
        {
            return false;
        }
    }
    
    private function cleanSecretSauces()
    {
        global $userData;
        $dR=  $this->query('delete from __secretSauce where userId=\''. $userData['id'] .'\' and entryDate<\''. date('YmdHis', strtotime('-1 week')) .'\'');
        if ($dR===FALSE)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function error()
    {
        return mysqli_error($this->connection);
    }

    /**
     * Fetches row as numeric array (first value at 0, 2nd value at 1, etc).
     * @param null|row $result
     */
    public function fetchRow($result)
    {
        return mysqli_fetch_row($result);
    }

	/**
	 * Fetches row as associative array with column names as keys.
	 * @param null|row $result
	 */
    
    public function fetchAssoc($result)
    {
        return mysqli_fetch_assoc($result);
    }

	/**
	 * Fetches row as both
	 * associative array with column names as keys and
	 * numeric array.
	 * @param null|row $result
	 */
    public function fetchArray($result)
    {
        return mysqli_fetch_array($result);
    }

    
    public function affectedRows()
    {
        return mysqli_affected_rows($this->connection);
    }
    
    public function getNumRows($result)
    {
        return mysqli_num_rows($result);
    }
    
    
    // Antonis
    // return fetched value/null or === FALSE
    /**
     * 
     * @param safe sql string $p_sql
     * @param bool $p_fail_if_not_single_result_bool. Optional, default true.
     * @param bool $p_fail_if_no_result_bool. Optional, default false.
     * @return false|NULL|string
     */
    public
    function getSingleValueResult (
    	$p_sql,
    	$p_fail_if_not_single_result_bool = true,
    	$p_fail_if_no_result_bool = false
    )
    {
    	$wo = WOOOF::$instance;
    	 
    	$place = __CLASS__ . '::' . __FUNCTION__;
    	//$wo->debug( "$place: " );
    	$l_ret = '';
    
    	$l_res = $this->query($p_sql);
    	if ( $l_res === FALSE ) {
    		return false;
    	}
    
    	$l_count = 0;
    	while( ($row = $this->fetchRow($l_res)) !== NULL ) {
    		$l_count++;
    		if ( $l_count > 1 ) { break; }
    		$l_ret = $row[0];
    	}  // foreach query
    
    	if ( $l_count == 0 ) {
    		if ( $p_fail_if_no_result_bool ) {
    			$wo->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0050 $place: SQL returned no records" );
    			return false;
    		}
    		else {
    			return null;
    		}
    	}  // no records found
    
    	if ( $l_count > 1 ) {
    		if ( $p_fail_if_not_single_result_bool ) {
    			$wo->log(WOOOF_loggingLevels::WOOOF_CRITICAL_ERROR, self::_ECP."0060 $place: SQL returned more than one records" );
    			return false;
    		}
    		else {
    			return $l_ret;
    		}
    	}
    
    	return $l_ret;
    }  // getSingleValueResult
    
    /**
     * 
     * @param string $table_name
     * @param string $column_name
     * @param string $column_value
     * @return false|number
     */
    public
    function getRecordsCount( $table_name, $column_name, $column_value ) {
    	$sql = "SELECT COUNT(*) FROM $table_name WHERE $column_name = '$column_value'";
    	if( ($res = $this->query($sql)) === FALSE ) { return FALSE; }
    
    	$row = $this->fetchRow($res);
    	return (int) $row[0];
    }  // getRecordsCount
    
}


/*
 * WOOOF_dataBaseTable
 * 
 * Stores all the meta data for each table.
 * Retrieves Rows based requested properties.
 * Can also commit updates to the database
 * if the user has enough privileges (per table and per column).
 */

class WOOOF_dataBaseTable
{
	const _ECP = 'WDT';	// Error Code Prefix
	
	private $dataBase;
    private $tableName;
    private $description;
    private $subtableDescription;
    private $tableId;
    private $orderingColumnForListings;
    private $appearsInAdminMenu;
    private $adminPresentation;
    private $adminItemsPerPage;
    private $adminListMarkingCondition;
    private $adminListMarkedStyle;
    private $groupedByTable;
    private $remoteGroupColumn;
    private $localGroupColumn;
    private $tablesGroupedByThis;
    private $hasActivationFlag;
    private $availableForSearching;
    private $hasGhostTable;
    private $hasDeletedColumn;
    private $currentUserCanEdit;
    private $currentUserCanRead;
    private $currentUserCanChangeProperties;
    private $hasEmbededPictures;
    private $columnForMultipleTemplates;
    private $showIdInAdminLists;
    private $showIdInAdminForms;
    private $isView;
    private $viewDefinition;
    private $dbEngine;
    
    static private $deleteRowFilesToRemove;	// array( id =>isImage, ... )
    static private $deleteRowRowIds;		// array( table => array( rowId, ... ), ... ) 
    
    public $columns;
    public $resultActive;
    public $resultRows;
    public $lastDbResult;
    public $isResultSerial;
    public $isResultAssociative;
    
    public $constructedOk = true;	// check this after a new to ensure object was created successfully

    public function __construct($dataBaseObject,$tableName,$tableId='')
    {
        $this->dataBase = $dataBaseObject;
        
        $this->resultActive = FALSE;
        
        if ($tableName=='' && $tableId=='')
        {
            $this->tableName='';
            $this->tableId='';
            $this->currentUserCanChangeProperties = TRUE;
            $this->currentUserCanRead = FALSE;
            $this->currentUserCanEdit = FALSE;
        }else
        {
            $result=null;
            if ($tableName!='')
            {
                $result = $this->dataBase->query('select * from __tableMetaData where tableName=\''. $this->dataBase->escape($tableName) .'\'');
            }else
            {
                $result = $this->dataBase->query('select * from __tableMetaData where id=\''. $this->dataBase->escape($tableId) .'\'');
            }
            if ( $result === FALSE ) {
            	$this->constructedOk = FALSE;
            	return;
            }
            if (!mysqli_num_rows($result))
            {
                if ($this->dataBase->error()=='')
                {
                    WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0070 " . 'Requested table ['.$tableName.'] doesn\'t have metadata stored!');
                }
                $this->constructedOk = FALSE;
                return;
            }

            $metaData = $this->dataBase->fetchAssoc($result);

            $columnsR = $this->dataBase->query('select * from __columnMetaData where tableId=\''. $metaData['id'] .'\' order by ordering');
            
            if ( $columnsR === FALSE ) {
            	$this->constructedOk = FALSE;
            	return;
            }
            
            $i=0;
            while($column = $this->dataBase->fetchAssoc($columnsR))
            {
                $newDatabaseColumn = WOOOF_dataBaseColumn::fromMetaRow($this->dataBase,$column, ($metaData['isView']=='1') );
                if ( $newDatabaseColumn === FALSE ) { $this->constructedOk = FALSE; return; }
                $this->columns[$column['name']] = $newDatabaseColumn;
                $this->columns[$i++] = $newDatabaseColumn;
            }
            $this->tableName = $metaData['tableName'];
            $this->description = $metaData['description'];
            $this->subtableDescription = $metaData['subtableDescription'];
            $this->tableId = $metaData['id'];
            $this->orderingColumnForListings = $metaData['orderingColumnForListings'];
            $this->appearsInAdminMenu = $metaData['appearsInAdminMenu'];
            $this->adminPresentation = $metaData['adminPresentation'];
            $this->adminItemsPerPage = $metaData['adminItemsPerPage'];
            $this->adminListMarkingCondition = $metaData['adminListMarkingCondition'];
            $this->adminListMarkedStyle = $metaData['adminListMarkedStyle'];
            $this->groupedByTable = $metaData['groupedByTable'];
            $this->remoteGroupColumn = $metaData['remoteGroupColumn'];
            $this->localGroupColumn = $metaData['localGroupColumn'];
            $this->tablesGroupedByThis = $metaData['tablesGroupedByThis'];
            $this->hasActivationFlag = $metaData['hasActivationFlag'];
            $this->availableForSearching = $metaData['availableForSearching'];
            $this->hasGhostTable = $metaData['hasGhostTable'];
            $this->hasDeletedColumn = $metaData['hasDeletedColumn'];
            $this->hasEmbededPictures = $metaData['hasEmbededPictures'];
            $this->columnForMultipleTemplates = $metaData['columnForMultipleTemplates'];
            $this->showIdInAdminLists = $metaData['showIdInAdminLists'];
            $this->showIdInAdminForms = $metaData['showIdInAdminForms'];
            $this->isView = (isset($metaData['isView']) ? $metaData['isView'] : '0');
            $this->viewDefinition = (isset($metaData['viewDefinition']) ? $metaData['viewDefinition'] : '');
            $this->dbEngine = (isset($metaData['dbEngine']) ? $metaData['dbEngine'] : '');
            
            $this->currentUserCanChangeProperties = TRUE;
            $this->currentUserCanRead = FALSE;
            $this->currentUserCanEdit = FALSE;
        }
    }	//	_construct
    
    // DONE: recheck why mysql_real_escape string and not WOOOF::cleanUserInput ??
    
    // DONE: check security implications for removed mysql_real escape string in key
    /**
     * 
     * @param string[] $whereClauses	// array( 'Column' => 'value', ... )
     * @param string $orderBy
     * @param int $limitStart
     * @param int $limitHowMany
     * @param string $extraWhereClause	// a sanitized arbitrary SQL clause to be ANDed at the end
     * @return false|int['totalRows'=> , 'rowsFetched'=> ]	// Also fills-in $this->resultRows. 
     */
    public function getResult($whereClauses, $orderBy='', $limitStart='', $limitHowMany='', $extraWhereClause='', $serialRows = TRUE, $associativeRows = TRUE, $deletePreviousResults = TRUE)
    {
        if ($orderBy!='')
        {
            $orderBy=' ORDER BY '. $orderBy;
        }

        if (is_array($whereClauses) && count($whereClauses)>0)
        {
            $where = ' WHERE ';
            while(list($key,$val)=each($whereClauses))
            {
                if ($where != ' WHERE ')
                {
                    $where .= ' AND ';
                }
                $where .= $key .'= \''. $this->dataBase->escape($val) .'\'';
            }
        } else
        {
            $where = '';
        }
        
        if ( $extraWhereClause != '' ) {
        	if ($where != '') { $where .= ' AND '; }
        	else { $where .= ' WHERE '; }
        	// TODO: Security Risk!!!
        	$where .= '(' . $extraWhereClause .')';
        }
        
        if ($limitStart != '' || $limitHowMany != '')
        {
            $limit = ' LIMIT ';
            if ($limitStart != '')
            {
                $limit .= $this->dataBase->escape($limitStart).',';
            }
            
            if ($limitHowMany != '')
            {
                $limit .= $this->dataBase->escape($limitHowMany);
            }
        }else
        {
            $limit='';
        }
        
        if ($deletePreviousResults)
        {
            $this->resultRows = array();
        }
        
        $this->lastDbResult = $this->dataBase->query('SELECT * FROM '. $this->tableName .' '. $where .' '. $this->dataBase->escape($orderBy) .' '. $limit);
        
        if ( $this->lastDbResult === FALSE ) {
        	return FALSE;
        }
        
        //echo 'SELECT * FROM '. $this->tableName .' '. $where .' '. $this->dataBase->escape($orderBy) .' '. $limit .'<br/>';
        if ($limit != '')
        {
            $numR = $this->dataBase->query('SELECT COUNT(*) FROM '. $this->tableName .' '. $where);
            if ( $numR === FALSE ) { return FALSE; }
            $number = $this->dataBase->fetchRow($numR);
            $howManyRows['totalRows'] = $number[0];
        }else
        {
            $howManyRows['totalRows'] = mysqli_num_rows($this->lastDbResult);
        }

        $howManyRows['rowsFetched'] = mysqli_num_rows($this->lastDbResult);
        if ($this->lastDbResult===FALSE)
        {
            return FALSE;
        }
        $this->resultActive = TRUE;
		$i = 0;
        while($row = $this->dataBase->fetchAssoc($this->lastDbResult))
        {
            if ($associativeRows && isset($row['id']))
            {
                $this->resultRows[$row['id']] = $row;
            }
            if ($serialRows)
            {
            	// Use an explicit counter as numeric index, 
            	// because a manually inserted id (eg 1111111111) may cause problems
            	// with just $this->resultRows[] = $row;
                $this->resultRows[$i] = $row;
                $i++;
            }
        }
        
        $this->isResultSerial 		= $serialRows;
        $this->isResultAssociative 	= $associativeRows;
        
        return $howManyRows;
    }

    /**
     * 
     * @param string $tables
     * @param string $whereClause
     * @param string $orderBy
     * @param string $limitStart
     * @param string $limitHowMany
     * @return boolean
     */
    public function getResultJoin($tables, $whereClause, $orderBy='', $limitStart='', $limitHowMany='', $serialRows = TRUE, $associativeRows = TRUE, $deletePreviousResults = TRUE)
    {
        if ($orderBy!='')
        {
            $orderBy=' ORDER BY '. $orderBy;
        }
        if ($whereClause!='')
        {
            $where = ' WHERE '. $whereClause;
        } else
        {
            $where = '';
        }
        
        if ($limitStart != '' || $limitHowMany != '')
        {
            $limit = ' LIMIT ';
            if ($limitStart != '')
            {
                $limit .= $this->dataBase->escape($limitStart).',';
            }
            
            if ($limitHowMany != '')
            {
                $limit .= $this->dataBase->escape($limitHowMany);
            }
        }else
        {
            $limit='';
        }
        $this->lastDbResult = $this->dataBase->query('SELECT DISTINCT '. $this->tableName .'.* FROM '. $this->tableName .', '. $tables .' '. $where .' '. $this->dataBase->escape($orderBy) .' '. $limit);
        
        if ($this->lastDbResult===FALSE)
        {
            return FALSE;
        }
        
        $this->resultActive = TRUE;
                
        if ($deletePreviousResults)
        {
            $this->resultRows = array();
        }
        
        $i = 0;
        while($row = $this->dataBase->fetchAssoc($this->lastDbResult))
        {
            if ($associativeRows && isset($row['id']))
            {
                $this->resultRows[$row['id']] = $row;
            }
            if ($serialRows)
            {
                $this->resultRows[$i] = $row;
                $i++;
            }
        }
        
        $this->isResultSerial 		= $serialRows;
        $this->isResultAssociative 	= $associativeRows;
        
        return TRUE;
    }
    
    public function getResultByQuery($query,$serialRows = TRUE, $associativeRows = TRUE, $deletePreviousResults = TRUE)
    {
        $result = $this->dataBase->query($query);
        if ( $result === FALSE ) { return FALSE; }
        
        if ($deletePreviousResults)
        {
            $this->resultRows = array();
        }
        
        $i = 0;
        while($row = $this->dataBase->fetchAssoc($result))
        {
            if ($associativeRows) {
            	if ( isset($row['id']) ) { 
	                $this->resultRows[$row['id']] = $row;
            	}
	            else {
	            	$associativeRows = false;
	            }
            }
            if ($serialRows)
            {
                $this->resultRows[$i] = $row;
                $i++;
            }
        }
        
        $this->isResultSerial 		= $serialRows;
        $this->isResultAssociative 	= $associativeRows;
        
        return TRUE;
    }
    
    /**
     * 
     * @param string $rowId
     * @param boolean $serialRow
     * @param boolean $associativeRow
     * @param boolean $deletePreviousResults
     * @return false|null|array of rows // serial and/or associative by id
     */
    public function getRow($rowId,$serialRow = TRUE, $associativeRow = TRUE, $deletePreviousResults = TRUE)
    {
        $r = $this->dataBase->query('SELECT * FROM '. $this->tableName .' WHERE id=\''. $this->dataBase->escape($rowId) .'\'');
        if ( $r === FALSE ) { return FALSE; }
        $row = $this->dataBase->fetchAssoc($r);
        
        if ($deletePreviousResults)
        {
            $this->resultRows = array();
        }
        
        if ( $row !== NULL ) 
        {
            if ($associativeRow && isset($row['id']))
            {
                $this->resultRows[$row['id']] = $row;
            }
            if ($serialRow)
            {
                $this->resultRows[] = $row;
            }
        }
        return $row;
    }

    public function getTableId()
    {
        return $this->tableId;
    }
    
    public function getAdminPresentation()
    {
    	return $this->adminPresentation;
    }
    
    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function getTableDescription()
    {
        return $this->description;
    }

    public function getSubTableDescription()
    {
        return $this->subtableDescription;
    }
    
    public function getOrderingColumnForListings()
    {
            return $this->orderingColumnForListings;
    }
    
    public function getAppearsInAdminMenu()
    {
            return $this->appearsInAdminMenu;
    }
    
    public function getAdminItemsPerPage()
    {
            return $this->adminItemsPerPage;
    }
    
    public function getAdminListMarkingCondition()
    {
            return $this->adminListMarkingCondition;
    }
    
    public function getAdminListMarkedStyle()
    {
            return $this->adminListMarkedStyle;
    }
    
    public function getGroupedByTable()
    {
            return $this->groupedByTable;
    }
    
    public function getRemoteGroupColumn()
    {
            return $this->remoteGroupColumn;
    }
    
    public function getLocalGroupColumn()
    {
            return $this->localGroupColumn;
    }
    
    public function getTablesGroupedByThis()
    {
            return $this->tablesGroupedByThis;
    }
    
    public function getHasActivationFlag()
    {
            return $this->hasActivationFlag;
    }
    
    public function getAvailableForSearching()
    {
            return $this->availableForSearching;
    }
    
    public function getHasGhostTable()
    {
            return $this->hasGhostTable;
    }
    
    public function getHasDeletedColumn()
    {
            return $this->hasDeletedColumn;
    }

    public function getHasEmbededPictures()
    {
            return $this->hasEmbededPictures;
    }

    public function getColumnForMultipleTemplates()
    {
            return $this->columnForMultipleTemplates;
    }

    public function getShowIdInAdminForms()
    {
            return $this->showIdInAdminForms;
    }

    public function getShowIdInAdminLists()
    {
            return $this->showIdInAdminLists;
    }

    public function getIsView()
    {
            return $this->isView;
    }

 public function getViewDefinition()
    {
            return $this->viewDefinition;
    }
    
 public function getDbEngine()
    {
            return $this->dbEngine;
    }
    
    

    public function updateMetaDataFromPost()
    {
        if ($this->currentUserCanChangeProperties)
        {
            if ( !isset($_POST['appearsInAdminMenu']) ) { $_POST['appearsInAdminMenu'] = ''; }
            if ( !isset($_POST['hasActivationFlag']) ) { $_POST['hasActivationFlag'] = ''; }
            if ( !isset($_POST['availableForSearching']) ) { $_POST['availableForSearching'] = ''; }
            if ( !isset($_POST['hasGhostTable']) ) { $_POST['hasGhostTable'] = ''; }
            if ( !isset($_POST['hasEmbededPictures']) ) { $_POST['hasEmbededPictures'] = ''; }
            if ( !isset($_POST['hasDeletedColumn']) ) { $_POST['hasDeletedColumn'] = ''; }
            if ( !isset($_POST['showIdInAdminLists']) ) { $_POST['showIdInAdminLists'] = ''; }
            if ( !isset($_POST['showIdInAdminForms']) ) { $_POST['showIdInAdminForms'] = ''; }
            
            $extraColumns = '';        	            

            if ($this->tableId=='')
            {
                $newTableId = $this->dataBase->getNewId('__tableMetaData');
                $query='insert into __tableMetaData set id=\''. $newTableId .'\',';
                if (trim($_POST['orderingColumnForListings'])!='')
                {
                    $pieces = explode(' ', trim($_POST['orderingColumnForListings']));
                    $actualColumn = $pieces[0]; // remember this is used for filtering out desc and asc 
                    $extraColumns.=' '. $actualColumn .' int unsigned not null default \'0\', ';
                }
                if (trim($_POST['hasActivationFlag'])=='1')
                {
                    $extraColumns.=' active char(1) not null default \'0\',';
                }
                $dbEngine =  $this->dataBase->escape(trim($_POST['dbEngine']));
                if ($dbEngine == '' or $dbEngine == NULL ) { $dbEngine = 'InnoDB'; }
                $creationQuery = 'CREATE TABLE `'. $this->dataBase->escape(trim($_POST['tableName'])) .'` (id CHAR(10) COLLATE ascii_bin not NULL, isDeleted CHAR(1) NOT NULL DEFAULT \'0\', '. $extraColumns .' PRIMARY KEY(id), KEY(isDeleted)) ENGINE='.$dbEngine.' DEFAULT CHARSET=utf8';
            }else
            {
                $query='update __tableMetaData set';
            }
            
            $query.='
tableName=\''. $this->dataBase->escape(trim($_POST['tableName'])) .'\',
description=\''. $this->dataBase->escape(trim($_POST['description'])) .'\',
subtableDescription=\''. $this->dataBase->escape(trim($_POST['subtableDescription'])) .'\',
orderingColumnForListings=\''. $this->dataBase->escape(trim($_POST['orderingColumnForListings'])) .'\',
appearsInAdminMenu=\''. $this->dataBase->escape(trim($_POST['appearsInAdminMenu'])) .'\',
adminPresentation=\''. $this->dataBase->escape(trim($_POST['adminPresentation'])) .'\',
adminItemsPerPage=\''. $this->dataBase->escape(trim($_POST['adminItemsPerPage'])) .'\',
adminListMarkingCondition=\''. $this->dataBase->escape(trim($_POST['adminListMarkingCondition'])) .'\',
adminListMarkedStyle=\''. $this->dataBase->escape(trim($_POST['adminListMarkedStyle'])) .'\',
groupedByTable=\''. $this->dataBase->escape(trim($_POST['groupedByTable'])) .'\',
remoteGroupColumn=\''. $this->dataBase->escape(trim($_POST['remoteGroupColumn'])) .'\',
localGroupColumn=\''. $this->dataBase->escape(trim($_POST['localGroupColumn'])) .'\',
tablesGroupedByThis=\''. $this->dataBase->escape(trim($_POST['tablesGroupedByThis'])) .'\',
hasActivationFlag=\''. $this->dataBase->escape(trim($_POST['hasActivationFlag'])) .'\',
availableForSearching=\''. $this->dataBase->escape(trim($_POST['availableForSearching'])) .'\',
hasGhostTable=\''. $this->dataBase->escape(trim($_POST['hasGhostTable'])) .'\',
hasEmbededPictures = \''. $this->dataBase->escape(trim($_POST['hasEmbededPictures'])) .'\',
hasDeletedColumn=\''. $this->dataBase->escape(trim($_POST['hasDeletedColumn'])) .'\',
columnForMultipleTemplates=\''. $this->dataBase->escape(trim($_POST['columnForMultipleTemplates'])) .'\',
showIdInAdminLists=\''. $this->dataBase->escape(trim($_POST['showIdInAdminLists'])) .'\',
showIdInAdminForms=\''. $this->dataBase->escape(trim($_POST['showIdInAdminForms'])) .'\',
dbEngine=\''. $this->dataBase->escape(trim($_POST['dbEngine'])) .'\'
';

            if ($this->tableId!='')
            {
                $query.=' where id=\''. $this->tableId .'\'';
                if ($this->tableName != $this->dataBase->escape(trim($_POST['tableName'])))
                {
                    $succ = $this->dataBase->query('RENAME TABLE `'. $this->tableName .'` TO `'. $this->dataBase->escape(trim($_POST['tableName'])).'`');
                }
                if ($this->getDbEngine() != $this->dataBase->escape(trim($_POST['dbEngine'])))
                {
                    $succ = $this->dataBase->query('ALTER TABLE `'. $this->tableName .'` ENGINE= '. $this->dataBase->escape(trim($_POST['dbEngine'])));
                }
                if ( $succ === FALSE ) { return FALSE; }
            }
            else {
	            if ($this->dataBase->query($creationQuery)===FALSE)
	            {
	            	return FALSE;
	            }
            }

            
            if ($this->dataBase->query($query)===FALSE)
            {
                return FALSE;
            }



            if (trim($_POST['orderingColumnForListings'])!='' && $this->tableId=='')
                {
                    $succ = $this->dataBase->query('insert into __columnMetaData set 
                    id=\''. $this->dataBase->getNewId('__columnMetaData') .'\',
                    tableId=\''. $newTableId .'\',
                    name=\''. $actualColumn .'\',
                    description=\'Σειρά Εμφάνισης\',
                    type=\'1\',
                    length=\'\',
                    presentationType=\'1\',
                    isReadOnly=\'0\',
                    notNull=\'0\',
                    isInvisible=\'0\',
                    appearsInLists=\'0\',
                    isASearchableProperty=\'0\',
                    isReadOnlyAfterFirstUpdate=\'0\',
                    isForeignKey=\'0\',
                    presentationParameters=\'\',
                    valuesTable=\'\',
                    columnToShow=\'\',
                    columnToStore=\'\',
                    defaultValue=\'0\',
                    orderingMirror=\'\',
                    searchingMirror=\'\',
                    resizeWidth=\'\',
                    resizeHeight=\'\',
                    thumbnailWidth=\'\',
                    thumbnailHeight=\'\',
                    midSizeWidth=\'\',
                    midSizeHeight=\'\',
                    thumbnailColumn=\'\',
                    midSizeColumn=\'\',
                    ordering=\'9998\'');
                    if ( $succ === FALSE ) { return FALSE; }
                }
                
                if (trim($_POST['hasActivationFlag'])=='1' && $this->tableId=='')
                {
                    $succ = $this->dataBase->query('insert into __columnMetaData set
                    id=\''. $this->dataBase->getNewId('__columnMetaData') .'\',
                    tableId=\''. $newTableId .'\',
                    name=\'active\',
                    description=\'Ενεργό\',
                    type=\'1\',
                    length=\'\',
                    presentationType=\'5\',
                    isReadOnly=\'0\',
                    notNull=\'0\',
                    isInvisible=\'0\',
                    appearsInLists=\'0\',
                    isASearchableProperty=\'0\',
                    isReadOnlyAfterFirstUpdate=\'0\',
                    isForeignKey=\'0\',
                    presentationParameters=\'\',
                    valuesTable=\'\',
                    columnToShow=\'\',
                    columnToStore=\'\',
                    defaultValue=\'0\',
                    orderingMirror=\'\',
                    searchingMirror=\'\',
                    resizeWidth=\'\',
                    resizeHeight=\'\',
                    thumbnailWidth=\'\',
                    thumbnailHeight=\'\',
                    midSizeWidth=\'\',
                    midSizeHeight=\'\',
                    thumbnailColumn=\'\',
                    midSizeColumn=\'\',
                    ordering=\'9999\'');
                    if ( $succ === FALSE ) { return FALSE; }
                }
        }
        
        return TRUE;
    }	// updateMetaDataFromPost
    
    /**
     * 
     * @param string $postName
     * @return false|string	// "" for no file
     */
    public function handleFileUpload($postName='fileName', $existingExternalFilesId = '')
    {
    	return WOOOF::$instance->handleFileUpload($postName, $existingExternalFilesId);
    }


    public function handlePictureUpload($columnName, $rowId, $postName = '')
    {
        $metaData = $this->columns[$columnName]->getColumnMetaData();
        if ($postName=='')
        {
            $postName = $metaData['name'];
        }

        $uploadSucceded = WOOOF::$instance->checkFileUpload($postName, self::_ECP.'9145');
 
        if ($uploadSucceded!==TRUE && $uploadSucceded!=='INDF')
        {
            return FALSE;
        }elseif ($uploadSucceded==='INDF')
        {
            return TRUE;
        }

        if (WOOOF::$instance->hasContent($metaData['presentationParameters']))
        {
            $outputPath = WOOOF::$instance->getConfigurationFor('siteBasePath') . $metaData['presentationParameters'];
        }else
        {
            $outputPath = WOOOF::$instance->getConfigurationFor('siteBasePath') . WOOOF::$instance->getConfigurationFor('publicSite') . WOOOF::$instance->getConfigurationFor('imagesRelativePath');
        }

        $fromFile = $this->tableId .'_'. $metaData['columnId'] .'_'. $rowId .'_'. $_FILES[$postName]['name'];
        $currentRow = $this->dataBase->getRow($this->tableName, $rowId);
        if ($fromFile == $currentRow[$metaData['name']])
        {
            $fromFile = $this->tableId .'_'. $metaData['columnId'] .'_'. $rowId .'_'. WOOOF::$instance->randomString(3) . $_FILES[$postName]['name'];
        }

        $mvResult = move_uploaded_file($_FILES[$postName]['tmp_name'], $outputPath . $fromFile);
        
        if ($mvResult)
        {
            $columnsToUpdate[$metaData['name']] = $fromFile;

            if (WOOOF::$instance->hasContent($metaData['resizeWidth']) && WOOOF::$instance->hasContent($metaData['resizeHeight']))
            {
                $choppedFile='';
                $filePieces = explode('.', $_FILES[$postName]['name']);
                for($b=0; $b<(count($filePieces)-1); $b++)
                {
                    $choppedFile.=$filePieces[$b].'.';
                }

                $choppedFile.='jpg';
                $resizedFilename = $this->tableId .'_'. $metaData['columnId'] .'_'. $rowId .'_r'. $choppedFile;
                
                if (WOOOF::resizePicture($outputPath . $fromFile, $outputPath . $resizedFilename, $metaData['resizeWidth'], $metaData['resizeHeight'])===FALSE)
                {
                    unlink($outputPath . $fromFile);
                    return FALSE;
                }
                unlink($outputPath . $fromFile);
                $fromFile = $resizedFilename;
                $columnsToUpdate[$metaData['name']] = $resizedFilename;
            }
            
            if (WOOOF::$instance->hasContent($metaData['thumbnailColumn']) && WOOOF::$instance->hasContent($metaData['thumbnailWidth']) && WOOOF::$instance->hasContent($metaData['thumbnailHeight']))
            {
                if (WOOOF::resizePicture($outputPath . $fromFile, $outputPath . 'thumb_'. $fromFile, $metaData['thumbnailWidth'], $metaData['thumbnailHeight'])==FALSE)
                {
                    unlink($outputPath . $fromFile);
                    return FALSE;
                }

                $columnsToUpdate[$metaData['thumbnailColumn']] = 'thumb_'. $fromFile;
            }

            if (WOOOF::$instance->hasContent($metaData['midSizeColumn']) && WOOOF::$instance->hasContent($metaData['midSizeWidth']) && WOOOF::$instance->hasContent($metaData['midSizeHeight']))
            {
                if (WOOOF::resizePicture($outputPath . $fromFile, $outputPath . 'mid_' .$fromFile, $metaData['midSizeWidth'], $metaData['midSizeHeight'])==FALSE)
                {
                    foreach ($columnsToUpdate as $key => $value) 
                    {
                        unlink($outputPath . $value);
                    }
                    return FALSE;
                }
                $columnsToUpdate[$metaData['midSizeColumn']] = 'mid_'. $fromFile;
            }
            $query = 'update '. $this->tableName .' set ';
            $first = TRUE;
            foreach ($columnsToUpdate as $key => $value) 
            {
                if ($first)
                {
                    $first = FALSE;
                }else
                {
                    $query.=', ';
                }
                $query .= '`'. $key .'`=\''. $this->dataBase->escape($value) .'\'';
                $query.=' where id=\''. $this->dataBase->escape($rowId) .'\'';
                if ($this->dataBase->query($query)===FALSE)
                {
                    foreach($columnsToUpdate as $key => $value) 
                    {
                        unlink($outputPath . $value);
                    }
                    return FALSE;
                }else
                {
                    $this->dataBase->query('commit');
                    unlink($outputPath . $currentRow[$metaData['name']]);
                    return TRUE;
                }
            }
        }else
        {
            WOOOf::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."9130 " . 'File upload error for \''. $metaData['name'] .'\': File was uploaded but move failed to the designated directory.');
            return FALSE;
        }
    }

    /**
     * 
     * @param string $rowId
     * @return boolean
     */
    public function updateRowFromResults($rowId)
    {
        if (isset($this->resultRows[$rowId]))
        {
            $result = $this->updateRowFromArray($rowId, $this->resultRows[$rowId]);
            return $result;
        }else
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0100 " . 'Row Update Failure ! Requested row id not found in result rows!');
            return FALSE;
        }
    }

    /**
     * 
     * @param string $rowId
     * @param array(column=>value) $dataArray
     * @return boolean
     */

    public function updateRowFromArraySimple($dataArray)
    {
        if (!isset($dataArray['id']))
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0105 " . 'Row Update Failure ! No id specified in dataArray. Cannot update row without id.');
            return FALSE;
        }
        return $this->updateRowFromArray($dataArray['id'], $dataArray);
    }

    public function updateRowFromArray($rowId,$dataArray)
    {
        $query ='update '. $this->tableName .' set ';
        $first = TRUE;

        foreach ($dataArray as $key => $value) 
        {
            if ($key == 'id' || $key == 'isDeleted')
            {
                continue;
            }

            if ( $this->columns[$key]->checkValue($value) === FALSE ) {
                return FALSE;
            }

            if (!$first)
            {
                $query .= ', ';
            }else
            {
                $first = FALSE;
            }

            $query .= '`'. WOOOF::$instance->cleanUserInput($key) .'`=\''. WOOOF::$instance->cleanUserInput($value) .'\'';
        }

        $query .= ' where id=\''. WOOOF::$instance->cleanUserInput($rowId) .'\'';

        $result = $this->dataBase->query($query);
        if ( $result === FALSE ) { return FALSE; }
        
        return true;
    }

    public function insertRowFromArraySimple($dataArray)
    {
        $query ='insert into '. $this->tableName .' set ';
        $first = TRUE;
        foreach ($dataArray as $key => $value) 
        {
            if ($key == 'id' || $key == 'isDeleted')
            {
                continue;
            }
			WOOOF::$instance->debug( "[Column: $key] ");
        	if ( $this->columns[$key]->checkValue($value) === FALSE ) {
                return FALSE;
            }
            
            if (!$first)
            {
                $query .= ', ';
            }else
            {
                $first = FALSE;
            }

            $query .= '`'. WOOOF::$instance->cleanUserInput($key) .'`=\''. WOOOF::$instance->cleanUserInput($value) .'\'';
        }

        if (!$first)
        {
            $query.=', ';
        }
        $newId = $this->dataBase->getNewId($this->tableName);
        $query .= ' id=\''. $newId .'\'';

        $result = $this->dataBase->query($query);
        if ( $result === FALSE ) { return FALSE; }

        return $newId;
    }


    // TODO: to refactor
    //

    /**
     * 
     * @param string $rowId
     * @param string[] $columnsToFill
     * @return boolean
     */
    public function updateRowFromPost($rowId,$columnsToFill)
    {
    	$siteBasePath = WOOOF::$instance->getConfigurationFor('siteBasePath');
        $imagesRelativePath = WOOOF::$instance->getConfigurationFor('imagesRelativePath');
    	global $__isAdminPage;
        
        if (!is_array($columnsToFill))
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0110 " . 'Update from post failed as no array with columns to update was provided!');
            return FALSE;
        }

        $query1 ='update '. $this->tableName .' set';
        $query = ''; 

        $error='';
        $columnsToFill = array_values($columnsToFill);
        $pleaseNoComma = false;
        for($q = 0; $q < count($columnsToFill); $q++)
        {
        	$succ = TRUE;
        	
        	if( !isset($_POST[$columnsToFill[$q]]) && !isset($_POST[$columnsToFill[$q].'4']) && !isset($_POST[$columnsToFill[$q].'1']) && !isset($_FILES[$columnsToFill[$q]]) ) {
        		WOOOF::$instance->debug( "Warning in updateRowFromPost: Column to fill [{$columnsToFill[$q]}] does not appear in POST or FILES." );
        		continue;
        	} 

        	// antonis
        	$skipColumn = array();
        	
            if ($columnsToFill[$q]!='id' && isset($this->columns[$columnsToFill[$q]]))
            {
                if (isset($skipColumn[$columnsToFill[$q]]) && $skipColumn[$columnsToFill[$q]] == TRUE) 
                {
                    continue;
                }
                if ($query!='')
                {
                	if ( $pleaseNoComma ) { $pleaseNoComma = false; }
                    else { $query.=','; }
                }
                $metaData = $this->columns[$columnsToFill[$q]]->getColumnMetaData();
                $trimmedOrderingColumn = trim(str_replace('desc', '', $this->getOrderingColumnForListings()));
                $trimmedOrderingColumn = trim(str_replace('asc', '', $trimmedOrderingColumn));
                if ( $trimmedOrderingColumn == $columnsToFill[$q] && (isset($_POST[$columnsToFill[$q]]) && (trim($_POST[$columnsToFill[$q]]) == '0' || trim($_POST[$columnsToFill[$q]])=='')) && $metaData['type']== WOOOF_dataBaseColumnTypes::int)
                {
                    $oR = $this->dataBase->query('select max('. $trimmedOrderingColumn .') as maxOrd from '. $this->tableName);
                    if ($oR!==FALSE && $this->dataBase->getNumRows($oR)>0)
                    {
                        $o = $this->dataBase->fetchAssoc($oR);
                        $_POST[$columnsToFill[$q]] = $o['maxOrd'] + 10;
                    }else
                    {
                        WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, 'No maximum '. $trimmedOrderingColumn .' was returned from database uppon insert of new row.');
                    }
                }
                if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::file)
                {
                    $externalFileId=$this->handleFileUpload($columnsToFill[$q]);
                    if ($externalFileId===FALSE)
                    {
                        //die('File Upload Failure!');
                        WOOOf::$instance->log(WOOOF_loggingLevels::WOOOF_NOTICE, self::_ECP."0120 " .  'No file uploaded or file upload error for \''. $columnsToFill[$q] .'\'.');
                        $pleaseNoComma = true;
                    }else
                    {
                        $query.=' '. $columnsToFill[$q] .'=\''. $externalFileId .'\'';
                    }
                }elseif ($metaData['presentationType'] == WOOOF_columnPresentationTypes::picture && isset($_FILES[$columnsToFill[$q]]))
                {
                    if (trim($metaData['presentationParameters']) != '')
                    {
                        $outputPath = $siteBasePath . $metaData['presentationParameters'];
                    }else
                    {
                        $outputPath = $siteBasePath . $imagesRelativePath;
                    }

                    $fromFile = $outputPath. WOOOF::randomString(10) .'_'. $_FILES[$columnsToFill[$q]]['name'];
                    
                    $mvResult = move_uploaded_file($_FILES[$columnsToFill[$q]]['tmp_name'], $fromFile);
                    if ($mvResult)
                    {
                        if ($metaData['resizeWidth']!='')
                        {
                            $choppedFile='';
                            $filePieces = explode('.', $_FILES[$columnsToFill[$q]]['name']);
                            for($b=0; $b<(count($filePieces)-1); $b++)
                            {
                                $choppedFile.=$filePieces[$b].'.';
                            }

                            $choppedFile.='jpg';
                            $targetFilename = $this->tableId .'_'. $metaData['columnId'] .'_'. $rowId .'_'. $choppedFile;
                            
                            WOOOF::resizePicture($fromFile, $outputPath . $targetFilename, $metaData['resizeWidth'], $metaData['resizeHeight']);
                            $query.=' '. $columnsToFill[$q] .'=\''. WOOOF::$instance->cleanUserInput($targetFilename) .'\'';
                            if ($metaData['thumbnailWidth']!='')
                            {
                                WOOOF::resizePicture($fromFile, $outputPath . 'thumb_' .$targetFilename, $metaData['thumbnailWidth'], $metaData['thumbnailHeight']);
                                if ($metaData['thumbnailColumn']!='')
                                {
                                    $this->dataBase->query('update '. $this->tableName .' set '. $metaData['thumbnailColumn'] .'=\''. 'thumb_' .$targetFilename .'\' where id=\''. $rowId .'\'');
                                }
                            }
                            if ($metaData['midSizeWidth']!='')
                            {
                                WOOOF::resizePicture($fromFile, $outputPath . 'mid_' .$targetFilename, $metaData['midSizeWidth'], $metaData['midSizeHeight']);
                                if ($metaData['thumbnailColumn']!='')
                                {
                                    $this->dataBase->query('update '. $this->tableName .' set '. $metaData['midSizeColumn'] .'=\''. 'mid_' .$targetFilename .'\' where id=\''. $rowId .'\'');
                                }
                            }
                            unlink($fromFile);
                        }else
                        {
                            //echo basename(WOOOF::$instance->cleanUserInput($fromFile));
                            $query.=' '. $columnsToFill[$q] .'=\''. basename(WOOOF::$instance->cleanUserInput($fromFile)) .'\'';
                            //exit;
                        }
                    }else
                    {
                        WOOOf::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0130 " . 'File upload error for \''. $columnsToFill[$q] .'\': File was uploaded but move failed to the designated directory.');
                        $query.=' '. $columnsToFill[$q] .'='. $columnsToFill[$q];
                    }
                }elseif ($metaData['presentationType'] == WOOOF_columnPresentationTypes::htmlText)
                {
                    if (!$__isAdminPage)
                    {
                        require_once 'HTMLPurifier.standalone.php';
                        $config = HTMLPurifier_Config::createDefault();
                        $purifier = new HTMLPurifier($config);
                        if (!is_object($purifier))
                        {
                            WOOOf::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0140 " . 'Html purification for \''. $columnsToFill[$q] .'\' failed. Object was not initialized. Posted information was not entered in the database for security reasons.');
                            return FALSE;
                        }else
                        {
                            $query.=' '. $columnsToFill[$q] .'=\''. $this->dataBase->escape($purifier->purify($_POST[$columnsToFill[$q]])) .'\'';
                        }
                    }else
                    {
                        $query.=' '. $columnsToFill[$q] .'=\''. $this->dataBase->escape($_POST[$columnsToFill[$q]]) .'\'';
                    }
                    
                }elseif ($metaData['presentationType'] == WOOOF_columnPresentationTypes::date || $metaData['presentationType'] == WOOOF_columnPresentationTypes::time || $metaData['presentationType'] == WOOOF_columnPresentationTypes::dateAndTime )
                {
                    if ($metaData['isReadOnly'] || $metaData['isReadOnlyAfterFirstUpdate'] )
                    {
                    	$pleaseNoComma = true;
                    	continue;
                    }

                    $tempDate = WOOOF::buildDateTimeFromAdminPost($columnsToFill[$q], $metaData['presentationType'] );
                    if ($this->columns[$columnsToFill[$q]]->checkValue($tempDate) === FALSE) { return FALSE; }
                    $query.=' '. $columnsToFill[$q] .'=\''. WOOOF::$instance->cleanUserInput($tempDate) .'\'';
                }else
                {
                    if ($this->columns[$columnsToFill[$q]]->checkValue($_POST[$columnsToFill[$q]]) === FALSE) {
                    	return FALSE;
                    }
                    $query.=' '. $columnsToFill[$q] .'=\''. WOOOF::$instance->cleanUserInput($_POST[$columnsToFill[$q]]) .'\'';
                    
                    //   $succ = $this->columns[$columnsToFill[$q]]->checkValue($_POST[$columnsToFill[$q]]);
                    
                    if (trim($metaData['orderingMirror'])!='')
                    {
                        $query.= ', '. $metaData['orderingMirror'] .' = \''. WOOOF::customOrderTranslation(WOOOF::$instance->cleanUserInput($_POST[$columnsToFill[$q]])) .'\'';
                        $skipColumn[$metaData['orderingMirror']]=TRUE;
                    }
                }
            }
        }
        if ($succ === FALSE )
        {
        	return FALSE;
        }
        
        if ( trim($query) == '' ) {
        	WOOOf::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0150 " . 'No columns to update.');
        	return FALSE;
        }
        
        $query = $query1 . $query .' where id=\''. WOOOF::$instance->cleanUserInput($rowId) .'\'';
        //echo $query;

        $result = $this->dataBase->query($query);

        return ( $result===FALSE ? FALSE : TRUE );
    }	// updateRowFromPost


    /**
     * 
     * @param string $htmlFragment
     * @param string $locationBase
     * @param string $user
     * @param string $role
     * @param string $requestedAction
     * @param string[] $multiParamsArray
     * @return false|string
     */
    private function presentResultsWithSecurityAux($htmlFragment, $locationBase, $user, $role, $requestedAction, $highlightRowId, $highlightExtraClass, $multiParamsArray=NULL)
    {
    	$needSecurity 	= ( $user != '' || $role != '' );
		$needHighlight	= ( $highlightRowId != '' );
		    	
        $rowsHtml='';
        if ( $this->isResultSerial ) {
	        for($k = 0; $k<count($this->resultRows)/2; $k++)
	        {
	        	$rowId = $this->resultRows[$k]['id'];
	        	if ( $needSecurity ) {
		        	if ( $role != '' ) {
			            $permitions = $this->dataBase->getSecurityPermitionsForLocationAndRole($locationBase . $rowId, $role);
		        	}
		        	else {
		        		$permitions = $this->dataBase->getSecurityPermitionsForLocationAndUser($locationBase . $rowId, $user);
		        	}
		            if ( $permitions === FALSE ) { return FALSE; }
	        	}
	        			            
	            if (!$needSecurity or (isset($permitions[$requestedAction]) && $permitions[$requestedAction]===TRUE) )
	            {
	            	$paramsArray = NULL;
	        		if ( $multiParamsArray !== NULL ) {
		        		$paramsArray = WOOOF::$instance->getFromArray($multiParamsArray, $k);
	        		}

	        		if ( $needHighlight ) {
		        		if ($rowId == $highlightRowId)
		        		{
		        			$htmlFragment=str_replace('@@@extraClass@@@', ' '. $highlightExtraClass, $htmlFragment);
		        		}else
		        		{
		        			$htmlFragment=str_replace('@@@extraClass@@@', ' ', $htmlFragment);
		        		}
	        		}	// highlight
	        			        		 
	        		$tmp = $this->presentRowReadOnly($rowId,$htmlFragment,$paramsArray);
	            	if ( $tmp === FALSE ) { return FALSE; }

	            	$rowsHtml .= $tmp;
	            }	// permissions ok
	        }	// foreach result row
        }	// serial
        else {
            foreach( $this->resultRows as $aKey => $aRow ) {
	        	if ( $needSecurity ) {
	            	if ( $role != '' ) {
		        		$permitions = $this->dataBase->getSecurityPermitionsForLocationAndRole($locationBase . $aKey, $role);
		        	}
		        	else {
		        		$permitions = $this->dataBase->getSecurityPermitionsForLocationAndUser($locationBase . $aKey, $user);
		        	}
		        	if ( $permitions === FALSE ) { return FALSE; }
	        	}
	        		        	 
	        	if (!$needSecurity or ( isset($permitions[$requestedAction]) && $permitions[$requestedAction]===TRUE) )
	        	{
	        		$paramsArray = NULL;
	        		if ( $multiParamsArray !== NULL ) {
	        			$paramsArray = WOOOF::$instance->getFromArray($multiParamsArray, $aKey);
	        		}

	        		if ( $needHighlight ) {
		        		if ($aKey == $highlightRowId)
		        		{
		        			$htmlFragment=str_replace('@@@extraClass@@@', ' '. $highlightExtraClass, $htmlFragment);
		        		}else
		        		{
		        			$htmlFragment=str_replace('@@@extraClass@@@', ' ', $htmlFragment);
		        		}
	        		}	// highlight
	        			        		 
	        		$tmp = $this->presentRowReadOnly($aKey,$htmlFragment,$paramsArray);
	            	if ( $tmp === FALSE ) { return FALSE; }

	            	$rowsHtml .= $tmp;
	        	}	// permissions ok
            }	// foreach result 
        }	// serial or associative result
        
        return $rowsHtml;
    }
    
    /**
     * 
     * @param string $htmlFragment
     * @param string $locationBase
     * @param string $role
     * @param string $requestedAction
     * @param string[] $multiParamsArray // optional. Keys are 0 to n for isResultSerial, rowIds for isResultAssociative. Values are associative arrays with Keys as parameter names, values as the values to show
     * @return false|string
     */
    public function presentResultsWithSecurityRole($htmlFragment, $locationBase, $role, $requestedAction, $multiParamsArray=NULL)
    {
    	return $this->presentResultsWithSecurityAux($htmlFragment, $locationBase, '', $role, $requestedAction, '', '', $multiParamsArray );
    }
    
    
    /**
     * 
     * @param string $htmlFragment
     * @param string $locationBase
     * @param string $user
     * @param string $requestedAction
     * @param string[] $multiParamsArray // optional. Keys are 0 to n for isResultSerial, rowIds for isResultAssociative. Values are associative arrays with Keys as parameter names, values as the values to show
     * @return false|string
     */
    public function presentResultsWithSecurityUser($htmlFragment, $locationBase, $user, $requestedAction, $multiParamsArray=NULL)
    {
    	return $this->presentResultsWithSecurityAux($htmlFragment, $locationBase, $user, '', $requestedAction, '', '', $multiParamsArray );
   	}
    
    /**
     * 
     * @param string $htmlFragment		 // can be ''
     * @param string[] $multiParamsArray // optional. Keys are 0 to n for isResultSerial, rowIds for isResultAssociative. Values are associative arrays with Keys as parameter names, values as the values to show
     * @return false|string
     */
    public function presentResults($htmlFragment, $multiParamsArray=NULL)
    {
    	return $this->presentResultsWithSecurityAux($htmlFragment, '', '', '', '', '', '', $multiParamsArray );
    }
    
    /**
     * 
     * @param string $htmlFragment		 // must be provided
     * @param string[] $multiParamsArray // optional. Keys are 0 to n (as in re. Values are associative arrays with Keys are parameter names, values are the values to show
     * @return false|string
     */
    public function presentResultsWithRowHighlight($htmlFragment, $rowId, $extraClass, $multiParamsArray=NULL)
    {
    	return $this->presentResultsWithSecurityAux($htmlFragment, '', '', '', '', '', '', $rowId, $extraClass, $multiParamsArray );
   	}
    
    /**
     * 
     * @param string $rowId
     * @param string $htmlFragment	// optional
     * @param string[] $paramsArray // optional. Keys are parameter names, values are the values to show
     * @return false|string
     */
    public function presentRowReadOnly($rowId,$htmlFragment='',$paramsArray=null)
    {
        return $this->presentRow($rowId, 'read', $htmlFragment, $paramsArray);
    }
    
    /**
     * 
     * @param string $htmlFragment	// optional
     * @param string[] $paramsArray // optional. Keys are parameter names, values are the values to show
     * @return false|string
     */
    public function presentRowForInsert($htmlFragment='', $paramsArray=null)
    {
        return $this->presentRow('', 'insert', $htmlFragment, $paramsArray);
    }
    
    /**
     * 
     * @param string $rowId
     * @param string $htmlFragment 	// optional
     * @param string[] $paramsArray	// optional. Keys are parameter names, values are the values to show
     * @return false|string
     */
    public function presentRowForUpdate($rowId,$htmlFragment='',$paramsArray=NULL)
    {
        return $this->presentRow($rowId, 'update', $htmlFragment, $paramsArray);
    }
    
    /**
     * 
     * @param string $rowId
     * @param string $type
     * @param string $htmlFragment 	// optional
     * @param string[] $paramsArray // optional. Keys are parameter names, values are the values to show
     * @return false|string
     */
    private function presentRow($rowId, $type, $htmlFragment='', $paramsArray=NULL)
    {
        global $__isAdminPage;
        global $__isSiteBuilderPage;
        
        // WOOOF::$instance->debug( $this->tableName . ' presentRow: ' . print_r($paramsArray,true) );

        $buildFromScratch = FALSE;
        
        if ($rowId != '')
        {
            if (isset($this->resultRows[$rowId]))
            {
                $row = $this->resultRows[$rowId];
            }else
            {
                $result = $this->dataBase->query('select * from '. $this->tableName .' where id=\''. $rowId .'\'');
                if ($result===FALSE) { return FALSE; }
                
                $row = $this->dataBase->fetchAssoc($result);
                if ( $row === NULL ) {
                	WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0160 " . 'Requested update for non-existing row ID !!! @ presentRow');
                	return FALSE;
                }
            }
        }else if ($rowId == '' && $type=='update')
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0170 " . 'Requested update with empty row ID !!! @ presentRow');
            return FALSE;
        }else if ($rowId == '' && $type=='read')
        {
            WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0180 " . 'Requested row presentation with empty row ID !!! @ presentRow');
            return FALSE;
        }
        
        if ($htmlFragment == '')
        {
            $buildFromScratch = TRUE;
        	require(WOOOF::$instance->getConfigurationFor('templatesRepository') .'wooof_presentRow_1.activeTemplate.php');
            $htmlFragment=$defaultFragmentHead;
        }
        
        if ( $buildFromScratch && isset($row['id']) ) {
            require(WOOOF::$instance->getConfigurationFor('templatesRepository') .'wooof_presentRow_1.5.activeTemplate.php');
       	}
        
        if (($this->showIdInAdminForms && $type=='update') && $__isAdminPage)
        {
            require(WOOOF::$instance->getConfigurationFor('templatesRepository') .'wooof_presentRow_1.6.activeTemplate.php');
        }

        $fromStrings = array();
        $toStrings   = array();
        
        for($counter=0; $counter<count($this->columns)/2; $counter++)
        {
        	$metaData = $this->columns[$counter]->getColumnMetaData();
        	
        	// TODO: CAUTION: antonis, check side effects
        	if ( $__isSiteBuilderPage ) {
	        	$metaData['isInvisible']='0';
	        }

            if ($metaData['isInvisible']=='1' && ($metaData['name']!=$this->orderingColumnForListings || !$__isAdminPage ))
            {
                continue;
            }
            //print_r($metaData);
            //echo '<br>';
            $value='';
            if ($type == 'read')
            {
                $value = $this->columns[$counter]->renderReadOnly($row[$metaData['name']], $row['id']);
            }else if ($type == 'insert')
            {
                $value = $this->columns[$counter]->renderForInsert();
            }else if ($type == 'update')
            {
                $value = $this->columns[$counter]->renderForEdit($row[$metaData['name']],$rowId);
                //echo 'value -> '. $row[$metaData['name']] .' '. $value .'<br>';
            }
            if ($metaData['isInvisible']=='1')
            {
                $htmlFragment.=$value;
            }else
            {
                if ($buildFromScratch)
                {
                    require(WOOOF::$instance->getConfigurationFor('templatesRepository') .'wooof_presentRow_2.activeTemplate.php');
                }else
                {
                	//$htmlFragment = str_replace('@@@'. $metaData['name'] .'@@@', $value, $htmlFragment);                	
	       			$fromStrings[] = '@@@' . $metaData['name'] . '@@@';
	       			$toStrings[]    = $value;
                }
            }             
        }	// for all columns
        
        if ($buildFromScratch)
        {
            $htmlFragment.=$defaultFragmentTail;
        }
        
        if (isset($row['id']))
        {
            // $htmlFragment = str_replace('@@@id@@@', $row['id'], $htmlFragment);
   			$fromStrings[] 	= '@@@id@@@';
   			$toStrings[]	= $row['id'];
        }
        
   		// Do the substitutions
   		// Comment following line to see actual fragments' contents!
	    $htmlFragment = str_replace($fromStrings, $toStrings, $htmlFragment);
	    
	    
        // Substitute non-data / custom entries in the fragment
		if ( $paramsArray !== NULL ) {
	        $fromStrings = array();
	       	$toStrings   = array();
	       	foreach( $paramsArray as $aKey => $aVal ) {
	       		$fromStrings[] = "@@@$aKey@@@";
	       		$toStrings[]    = $aVal;
	       	}
	       	if ( isset($fromStrings[0]) ) {
   				// Comment following line to see actual fragments' contents!
	       		$htmlFragment = str_replace($fromStrings, $toStrings, $htmlFragment);
	       	}
		}
		
        return $htmlFragment;
    }

    public function constructAdministrationFragment()
    {
        $siteBaseURL = WOOOF::$instance->getConfigurationFor('siteBaseURL');
        $cssFileNameForTinyMCE = WOOOF::$instance->getConfigurationFor('cssFileNameForTinyMCE');
        $templatesRepository = WOOOF::$instance->getConfigurationFor('templatesRepository');

        require($templatesRepository.'wooof_constructAdministrationFragment_1.activeTemplate.php');

        $output='';
        $richColumns='';
        foreach ($this->columns as $column) 
        {
            $columnDescr = $column->getColumnMetaData();
            if ($columnDescr['isInvisible']!='1' && !isset($output[$columnDescr['ordering']]))
            {
                if ($columnDescr['presentationType']=='13')
                {
                    $headClass = 'editorDescription';
                    $itemClass = 'thumbLink';
                    $formFieldsClass = 'formFields';
                }else if ($columnDescr['presentationType']!='3' && $columnDescr['presentationType']!='4')
                {
                    $headClass = 'itemDescription';
                    $itemClass = 'itemValue';
                    $formFieldsClass = 'formFields';
                }else
                {
                    $headClass = 'editorDescription';
                    $itemClass = 'editor';
                    if ( $columnDescr['presentationType']=='4')
                    {
                        if ($richColumns!='') $richColumns.=', ';
                        $richColumns .= $columnDescr['name'];
                    }
                    $formFieldsClass = 'editorformFields';
                }
                require($templatesRepository.'wooof_constructAdministrationFragment_2.activeTemplate.php');
            }
        }
        ksort($output);
        $returnFragment[0] = implode("\n", $output);
        if ($richColumns!='')
        {
            $returnFragment[1] = str_replace('@@@columns@@@', $richColumns, $editorScripts);
        }

        return $returnFragment;
    }

    public function getInsertableColumns()
    {
        $output='';
        // TODO: Check that contents are double.
        foreach ($this->columns as $column) 
        {
            $columnDescr = $column->getColumnMetaData();
            if ($columnDescr['isInvisible']!='1' || ($this->getOrderingColumnForListings() == $columnDescr['name']))
            {
                // TODO: Check above condition.
                // TODO: Might miss columns because of ordering values!!!!!
                // TODO: Better to simply: $output[] = ...;
                $output[$columnDescr['ordering']-1] = $columnDescr['name'];
            }
        }
        return $output;
    }

    /**
     * 
     * @param string[] $columnsToFill
     * @param [] $mandatory
     * @param int $howManyRepetitions
     * @return boolean|string[]	// array of ids
     */
    public function handleMultiInsertFromPost($columnsToFill,$mandatory,$howManyRepetitions)
    {
        for($c=0;$c<$howManyRepetitions;$c++)
        {
            $insertItems = array();
            foreach ($columnsToFill as $value) 
            {
                if (isset($_POST[$value][$c]))
                {
                    $insertItems[$value] = $_POST[$value][$c];
                }elseif (is_uploaded_file($_FILES[$value]['tmp_name'][$c]))
                {
                    $insertItems[$value] = '';
                }elseif (isset($mandatory[$value]) && !isset($_POST[$value][$c]))
                {
                    WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0190 " . 'At Multi insert from post, row number '. ($c+1) .' of '. $howManyRepetitions .', MANDATORY column '. $value .' has no value posted!');
                    return FALSE;
                }else
                {
                    WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_NOTICE, self::_ECP."0200 " . 'At Multi insert from post, row number '. ($c+1) .' of '. $howManyRepetitions .', column '. $value .' has no value posted!');
                }
                $tmpId = $this->insertFromArray($insertItems);
                if ($tmpId===FALSE)
                {
                    return FALSE;
                }
                $ids[] = $tmpId;
            }
        }
        if ($result===FALSE)
        {
            return FALSE;
        }else
        {
            return $ids;
        }
    }

    /**
     * 
     * @param unknown $columnsToFill
     * @return false|string	// new id
     */
    public function insertFromArray($columnsToFill)
    {
        $imagesRelativePath = WOOOF::$instance->getConfigurationFor('imagesRelativePath');
        $siteBasePath = WOOOF::$instance->getConfigurationFor('siteBasePath');
        global $__isAdminPage;
        
        $insertId=$this->dataBase->getNewId($this->tableName);
        
        $query='insert into '. $this->tableName .' set';
        
        $defferedQueries = array();
        
        if (is_array($columnsToFill))
        {
            foreach($columnsToFill as $column => $value)
            {
                if ($column!='id' && $column!='isDeleted')
                {
                	$metaData = $this->columns[$column]->getColumnMetaData();
                    $trimmedOrderingColumn = trim(str_replace(' desc', '', $this->getOrderingColumnForListings()));
                    if ( $trimmedOrderingColumn == $column && (!isset($value) || (trim($value) == '0' || trim($value)=='')) && $metaData['type']== WOOOF_dataBaseColumnTypes::int)
                    {
                        $oR = $this->dataBase->query('select max('. $trimmedOrderingColumn .') as maxOrd from '. $this->tableName);
                        $o = $this->dataBase->fetchAssoc($oR);
                        $value = $o['maxOrd'] + 10;
                    }elseif( !isset($this->columns[$column]) ) {
                        WOOOF::$instance->debug( "Warning in insertFromArray: Column to fill [{$column}] is not a column in the table." );
                        continue;
                    } 
                    if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::file)
                    {
                        if( isset($_FILES[$column]) && is_uploaded_file($_FILES[$column]['tmp_name']))
                        {
                            $externalFileId=$this->handleFileUpload($column);
                            if ($externalFileId===FALSE)
                            {
                                die('File Upload Failure!');
                            }else
                            {
                                $query.=' '. $column .'=\''. $externalFileId .'\',';
                            }
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::picture)
                    {
                        if (isset($_FILES[$column]))
                        {
                            if (trim($metaData['presentationParameters']) != '')
                            {
                                $outputPath = $siteBasePath . $metaData['presentationParameters'];
                            }else
                            {
                                $outputPath = $siteBasePath . $imagesRelativePath;
                            }

                            $fromFile = $outputPath. WOOOF::randomString(10) .'_'. $_FILES[$column]['name'];
                            //echo $fromFile .' <- is the new filename <br>';
                            $mvResult = move_uploaded_file($_FILES[$column]['tmp_name'], $fromFile);

                            if ($mvResult)
                            {

                                if ($metaData['resizeWidth']!='')
                                {
                                    $choppedFile='';
                                    $filePieces = explode('.', $_FILES[$column]['name']);
                                    for($b=0; $b<(count($filePieces)-1); $b++)
                                    {
                                        $choppedFile.=$filePieces[$b].'.';
                                    }
                                    
                                    $choppedFile.='jpg';
                                    $targetFilename = $this->tableId .'_'. $metaData['columnId'] .'_'. $insertId .'_'. $choppedFile;
                                    
                                    WOOOF::resizePicture($fromFile, $outputPath . $targetFilename, $metaData['resizeWidth'], $metaData['resizeHeight']);
                                    $query.=' '. $column .'=\''. WOOOF::$instance->cleanUserInput($targetFilename) .'\', ';
                                    if ($metaData['thumbnailWidth']!='')
                                    {
                                        WOOOF::resizePicture($fromFile, $outputPath . 'thumb_' .$targetFilename, $metaData['thumbnailWidth'], $metaData['thumbnailHeight']);
                                        if ($metaData['thumbnailColumn']!='')
                                        {
                                            $defferedQueries[]='update '. $this->tableName .' set '. $metaData['thumbnailColumn'] .'=\''. 'thumb_' .$targetFilename .'\' where id=\''. $insertId .'\'';
                                        }
                                    }
                                    if ($metaData['midSizeWidth']!='')
                                    {
                                        WOOOF::resizePicture($fromFile, $outputPath . 'mid_' .$targetFilename, $metaData['midSizeWidth'], $metaData['midSizeHeight']);
                                        if ($metaData['thumbnailColumn']!='')
                                        {
                                            $defferedQueries[]='update '. $this->tableName .' set '. $metaData['midSizeColumn'] .'=\''. 'mid_' .$targetFilename .'\' where id=\''. $insertId .'\'';
                                        }
                                    }
                                    unlink($fromFile);
                                }else
                                {
                                    //echo basename(WOOOF::$instance->cleanUserInput($fromFile)) .'<br>';
                                    $query.=' '. $column .'=\''. basename(WOOOF::$instance->cleanUserInput($fromFile)) .'\',';
                                    //exit;
                                }
                            }else
                            {
                                $query.=' '. $column .'='. $column .', ';
                            }
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::htmlText)
                    {
                        if (!$__isAdminPage)
                        {
                            require_once 'HTMLPurifier.standalone.php';
                            $config = HTMLPurifier_Config::createDefault();
                            $purifier = new HTMLPurifier($config);
                            $query.=' '. $column .'=\''. $this->dataBase->escape($purifier->purify($value)) .'\',';
                        }else
                        {
                            $query.=' '. $column .'=\''. $this->dataBase->escape($value) .'\',';
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::date || $metaData['presentationType'] == WOOOF_columnPresentationTypes::time || $metaData['presentationType'] == WOOOF_columnPresentationTypes::dateAndTime && isset($_POST[$column.'1']))
                    {
                        if ($metaData['isReadOnly'] || ($value == ''))
                        {
                            $tempDate = WOOOF::getCurrentDateTime();
                        }else
                        {
                            $tempDate = $value;
                        }
                        if ($this->columns[$column]->checkValue($tempDate) === TRUE)
                        {
                            $query.=' '. $column .'=\''. WOOOF::$instance->cleanUserInput($tempDate) .'\',';
                        }
                    }else
                    {
                        if(!isset($value))
                        {
                            $value='';
                        }
                        $query.=' '. $column .'=\''. WOOOF::$instance->cleanUserInput($value) .'\',';
                    }
                }
            }
        }
        
        $query.=' id=\''. $insertId .'\'';


        $res = $this->dataBase->query($query);
        if ( $res === FALSE ) { return FALSE; }
        
        for($dC=0; $dC<count($defferedQueries);$dC++)
        {
            $res = $this->dataBase->query($defferedQueries[$dC]);
            if ( $res === FALSE ) { return FALSE; }
        }
        
        return $insertId;
    }
    
    
	/// TODO(?) add mandatory columns (?) 
	/**
	 * 
	 * @param string[] $columnsToFill
	 * @return false|string // new id
	 */
    public function handleInsertFromPost($columnsToFill)
    {
        $imagesRelativePath = WOOOF::$instance->getConfigurationFor('imagesRelativePath');
        $siteBasePath = WOOOF::$instance->getConfigurationFor('siteBasePath');
        global $__isAdminPage;
        
        $insertId=$this->dataBase->getNewId($this->tableName);
        
        if ( $insertId === FALSE ) { return FALSE; }
        
        $defferedQueries = array();

        $query='insert into '. $this->tableName .' set';
        
        if (is_array($columnsToFill))
        {
            foreach($columnsToFill as $column)
            {
                if ($column!='id')
                {
                	$metaData = $this->columns[$column]->getColumnMetaData();
                    $trimmedOrderingColumn = trim(str_replace(' desc', '', $this->getOrderingColumnForListings()));
                    if ( $trimmedOrderingColumn == $column && (!isset($_POST[$column]) || trim($_POST[$column]) == '0' || trim($_POST[$column])=='') && $metaData['type']== WOOOF_dataBaseColumnTypes::int)
                    {
                        $oR = $this->dataBase->query('select max('. $trimmedOrderingColumn .') as maxOrd from '. $this->tableName);
                        if ( $oR === FALSE ) { return FALSE; }
                        $o = $this->dataBase->fetchAssoc($oR);
                        $_POST[$column] = $o['maxOrd'] + 10;
                    }else if( !isset($_POST[$column]) && !isset($_POST[$column.'1']) && !isset($_POST[$column.'4']) && !isset($_FILES[$column]) ) {
                        WOOOF::$instance->debug( "Warning in handleInsertFromPost: Column to fill [{$column}] does not appear in POST or FILES." );
                        continue;
                    } 
                    if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::file)
                    {
                        if(is_uploaded_file($_FILES[$column]['tmp_name']))
                        {
                            $externalFileId=$this->handleFileUpload($column);
                            if ($externalFileId===FALSE)
                            {
                                die('File Upload Failure!'); // TODO: backfix that and remove the die!
                                return FALSE;
                            }else
                            {
                                $query.=' '. $column .'=\''. $externalFileId .'\',';
                            }
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::picture)
                    {
                        if (isset($_FILES[$column]))
                        {
                            if (trim($metaData['presentationParameters']) != '')
                            {
                                $outputPath = $siteBasePath . $metaData['presentationParameters'];
                            }else
                            {
                                $outputPath = $siteBasePath . $imagesRelativePath;
                            }

                            $fromFile = $outputPath. WOOOF::randomString(10) .'_'. $_FILES[$column]['name'];
                            //echo $fromFile .' <- is the new filename <br>';
                            $mvResult = move_uploaded_file($_FILES[$column]['tmp_name'], $fromFile);

                            if ($mvResult)
                            {

                                if ($metaData['resizeWidth']!='')
                                {
                                    $choppedFile='';
                                    $filePieces = explode('.', $_FILES[$column]['name']);
                                    for($b=0; $b<(count($filePieces)-1); $b++)
                                    {
                                        $choppedFile.=$filePieces[$b].'.';
                                    }
                                    
                                    $choppedFile.='jpg';
                                    $targetFilename = $this->tableId .'_'. $metaData['columnId'] .'_'. $insertId .'_'. $choppedFile;
                                    
                                    WOOOF::resizePicture($fromFile, $outputPath . $targetFilename, $metaData['resizeWidth'], $metaData['resizeHeight']);
                                    $query.=' '. $column .'=\''. WOOOF::$instance->cleanUserInput($targetFilename) .'\', ';
                                    if ($metaData['thumbnailWidth']!='')
                                    {
                                        WOOOF::resizePicture($fromFile, $outputPath . 'thumb_' .$targetFilename, $metaData['thumbnailWidth'], $metaData['thumbnailHeight']);
                                        if ($metaData['thumbnailColumn']!='')
                                        {
                                            $defferedQueries[]='update '. $this->tableName .' set '. $metaData['thumbnailColumn'] .'=\''. 'thumb_' .$targetFilename .'\' where id=\''. $insertId .'\'';
                                        }
                                    }
                                    if ($metaData['midSizeWidth']!='')
                                    {
                                        WOOOF::resizePicture($fromFile, $outputPath . 'mid_' .$targetFilename, $metaData['midSizeWidth'], $metaData['midSizeHeight']);
                                        if ($metaData['thumbnailColumn']!='')
                                        {
                                            $defferedQueries[]='update '. $this->tableName .' set '. $metaData['midSizeColumn'] .'=\''. 'mid_' .$targetFilename .'\' where id=\''. $insertId .'\'';
                                        }
                                    }
                                    unlink($fromFile);
                                }else
                                {
                                    //echo basename(WOOOF::$instance->cleanUserInput($fromFile)) .'<br>';
                                    $query.=' '. $column .'=\''. basename(WOOOF::$instance->cleanUserInput($fromFile)) .'\',';
                                    //exit;
                                }
                            }else
                            {
                                $query.=' '. $column .'='. $column .', ';
                            }
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::htmlText)
                    {
                        if (!$__isAdminPage)
                        {
                            require_once 'HTMLPurifier.standalone.php';
                            $config = HTMLPurifier_Config::createDefault();
                            $purifier = new HTMLPurifier($config);
                            $query.=' '. $column .'=\''. $this->dataBase->escape($purifier->purify($_POST[$column])) .'\',';
                        }else
                        {
                            $query.=' '. $column .'=\''. $this->dataBase->escape($_POST[$column]) .'\',';
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::date || $metaData['presentationType'] == WOOOF_columnPresentationTypes::time || $metaData['presentationType'] == WOOOF_columnPresentationTypes::dateAndTime && isset($_POST[$column.'1']))
                    {
                        if ( $metaData['notNull'] == '1' &&
                        	 ( $metaData['isReadOnly'] || (
                        		(!isset($_POST[$column.'1']) ||trim($_POST[$column.'1']) == '')
                        		&& (!isset($_POST[$column.'4']) || $_POST[$column.'4'] == '' )
                        		) 
                        	 )
                          )
                        {
                        	//WOOOF::$instance->debug("$column in isReadOnly or empty");
                            $tempDate = WOOOF::getCurrentDateTime();
                        }else
                        {
                            $tempDate = WOOOF::buildDateTimeFromAdminPost($column, $metaData['presentationType'] );
                        }
                         if ($this->columns[$column]->checkValue($tempDate) === FALSE) { return FALSE; }
                         $query.=' '. $column .'=\''. WOOOF::$instance->cleanUserInput($tempDate) .'\',';
                    }else
                    {
                        if ( !$this->columns[$column]->checkValue($_POST[$column]) ) { return FALSE; }
                    	if(!isset($_POST[$column]))
                        {
                            $_POST[$column]='';
                        }
                        $query.=' '. $column .'=\''. WOOOF::$instance->cleanUserInput($_POST[$column]) .'\',';
                    }
                }
            }
        }
        
        $query.=' id=\''. $insertId .'\'';

        $res = $this->dataBase->query($query);

        if ( $res === FALSE ) { return FALSE; }
        
        for($dC=0; $dC<count($defferedQueries);$dC++)
        {
            $res = $this->dataBase->query($defferedQueries[$dC]);
            if ( $res === FALSE ) { return FALSE; }
        }
        
        return $insertId;
    }
    
       
    			
	/**
	 * 
	 * @param array|null $row
	 * @param string $subTableName
	 * @param string $optionsTableName
	 * @param int $itemsPerRow
	 * @param string $className
	 * @return boolean|string
	 */
    public function renderSubtableAsCheckBoxes($row, $subTableName, $optionsTableName, $itemsPerRow, $className='')
    {
        $cssForFormItem = WOOOF::$instance->getConfigurationFor('cssForFormItem');
        $templatesRepository = WOOOF::$instance->getConfigurationFor('templatesRepository');

        require($templatesRepository.'wooof_renderSubtableAsCheckBoxes_1.activeTemplate.php');

        $output = $headHtml;
        //echo '1<br/>';

        $subTable = new WOOOF_dataBaseTable($this->dataBase, $subTableName);
        if ( $subTable->constructedOk === FALSE ) { return FALSE; }
        //echo '2<br/>';

        $optionsTable = new WOOOF_dataBaseTable($this->dataBase, $optionsTableName);
        if ( $optionsTable->constructedOk === FALSE ) { return FALSE; }
        //echo '3<br/>';

        $res = $optionsTable->getResult('',$optionsTable->getOrderingColumnForListings());
        if ( $res === FALSE ) { return FALSE; }
        //echo $subTable->getOrderingColumnForListings() .'<br/>';

        if (is_array($row))
        {
            $whereClauses[$subTable->getLocalGroupColumn()] = $row[$subTable->getRemoteGroupColumn()];           
            $res = $subTable->getResult($whereClauses);
        }else
        {
            $res = true;
        }
        if ( $res === FALSE ) { return FALSE; }
        
        $howManyItems = count($optionsTable->resultRows)/2;
        $howManyRows = ceil($howManyItems / $itemsPerRow);
        $itemsOut = 0;
        $howManyItemsSub = count($subTable->resultRows)/2;
        $checkBoxName = $subTable->getTableId();        
        
        for($n = 0; $n < count($subTable->columns)/2; $n++)
        {
            $columnMetaData = $subTable->columns[$n]->getColumnMetaData();
            if ($columnMetaData['valuesTable']==$optionsTable->getTableName())
            {
                $presentationColumn = $columnMetaData['name'];
                $presentationValueColumn = $columnMetaData['columnToStore']; 
                $presentationShowColumn = $columnMetaData['columnToShow'];
            }
        }
        
        if ($className=='')
        {
            $className = $cssForFormItem['checkBox'];
        }
        
        for($q = 0; $q < $howManyRows; $q++)
        {
            $output .= $lineHeadHtml;
            for($z = 0; $z < $itemsPerRow; $z++)
            {
                if ($itemsOut<$howManyItems)
                {
                    $output .= $cellHeadHtml;
                    $isChecked = FALSE;
                    for($n = 0; $n < $howManyItemsSub; $n++)
                    {
                        if ($subTable->resultRows[$n][$presentationColumn]==$optionsTable->resultRows[$itemsOut][$presentationValueColumn])
                        {
                            $isChecked = TRUE;
                        }
                    }
                    if ($isChecked)
                    {
                        $checked=' checked';
                    }else
                    {
                        $checked='';
                    }
                    require($templatesRepository.'wooof_renderSubtableAsCheckBoxes_2.activeTemplate.php');
                    $output .= $cellTailHtml;
                }else
                {
                    $output .= $emptyCellHtml;
                }
                $itemsOut++;
            }
            $output .= $lineTailHtml;
        }
        $output .= $tailHtml;
        return $output;
    }	// renderSubtableAsCheckBoxes
    

    /**
     * 
     * @param array $row
     * @param string $subTableName
     * @param string $optionsTableName
     * @param string $className
     * @param string $separator
     * @return false|string
     */
	public function renderSubtableReadOnly($row, $subTableName, $optionsTableName, $className='', $separator=NULL)
    {
        $cssForFormItem = WOOOF::$instance->getConfigurationFor('cssForFormItem');
        $templatesRepository = WOOOF::$instance->getConfigurationFor('templatesRepository');

        $output = '';
        $itemsPerRow = 99999;
        
        require($templatesRepository.'wooof_renderSubtableReadOnly_1.activeTemplate.php');

        $subTable = new WOOOF_dataBaseTable($this->dataBase, $subTableName);
        $optionsTable = new WOOOF_dataBaseTable($this->dataBase, $optionsTableName);
        
        if ( $subTable->constructedOk === FALSE || $optionsTable->constructedOk === FALSE ) {
        	return FALSE;
        }
        
        $res = $optionsTable->getResult('',$optionsTable->getOrderingColumnForListings());
        if ( $res === FALSE ) { return FALSE; }
        
        $whereClauses[$subTable->getLocalGroupColumn()] = $row[$subTable->getRemoteGroupColumn()];
        
        $subTable->getResult($whereClauses);
        if ( $subTable === FALSE ) { return FALSE; }
        
        $howManyItems = count($optionsTable->resultRows)/2;
        $howManyRows = ceil($howManyItems / $itemsPerRow);
        $itemsOut = 0;
        $howManyItemsSub = count($subTable->resultRows)/2;
        $checkBoxName = $subTable->getTableId();        
        
        for($n = 0; $n < count($subTable->columns)/2; $n++)
        {
            $columnMetaData = $subTable->columns[$n]->getColumnMetaData();
            if ($columnMetaData['valuesTable']==$optionsTable->getTableName())
            {
                $presentationColumn = $columnMetaData['name'];
                $presentationValueColumn = $columnMetaData['columnToStore']; 
                $presentationShowColumn = $columnMetaData['columnToShow'];
            }
        }
        
        if ($className=='')
        {
            $className = $cssForFormItem['checkBox'];
        }
        
        for($q = 0; $q < $howManyRows; $q++)
        {
            for($z = 0; $z < $itemsPerRow; $z++)
            {
                if ($itemsOut<$howManyItems)
                {
                    $isChecked = FALSE;
                    for($n = 0; $n < $howManyItemsSub; $n++)
                    {
                        if ($subTable->resultRows[$n][$presentationColumn]==$optionsTable->resultRows[$itemsOut][$presentationValueColumn])
                        {
                            $isChecked = TRUE;
                        }
                    }
                    if ($isChecked)
                    {
                        require($templatesRepository.'wooof_renderSubtableReadOnly_2.activeTemplate.php');
                    }
                }
                $itemsOut++;
            }

        }
        return $output . $outputTail;
    }
    
    /**
     * 
     * @param array $row
     * @param string $subtableName
     * @param string $optionsTable
     * @return boolean
     */
    public function updateSubtableFromPostCheckBoxes($row,$subtableName, $optionsTable)
    {
        $sT = new WOOOF_dataBaseTable($this->dataBase, $subtableName);
        if ( $sT->constructedOk === FALSE ) { return FALSE; }
        
        $sTId = $sT->getTableId();
        
        $res = $this->dataBase->query('delete from '. $sT->getTableName() .' where '. $sT->getLocalGroupColumn() .'=\''. $row[$sT->getRemoteGroupColumn()] .'\'');
        if ( $res === FALSE ) { return FALSE; }

        if (isset($_POST[$sTId]))
        {
            $oT = new WOOOF_dataBaseTable($this->dataBase, $optionsTable);
            $oT->getResult('');
            
            for($n = 0; $n < count($sT->columns)/2; $n++)
            {
                $columnMetaData = $sT->columns[$n]->getColumnMetaData();
                if ($columnMetaData['valuesTable']==$oT->getTableName())
                {
                    $presentationColumn = $columnMetaData['name'];
                }
            }
            
            while(list($key,$val) = each($_POST[$sTId]))
            {
                $res = $this->dataBase->query('insert into '. WOOOF::$instance->cleanUserInput($sT->getTableName()) .' (id,'. WOOOF::$instance->cleanUserInput($sT->getLocalGroupColumn()) .','. WOOOF::$instance->cleanUserInput($presentationColumn) .') values (\''. $this->dataBase->getNewId($sT->getTableName()) .'\',\''. WOOOF::$instance->cleanUserInput($row[$sT->getRemoteGroupColumn()]) .'\',\''. WOOOF::$instance->cleanUserInput($val) .'\')');
                if ( $res === FALSE ) { return FALSE; }
            }
        }
        
        return TRUE;
    }	// updateSubtableFromPostCheckBoxes

	
	/***************************************************************************/
	/***************************************************************************/
	
    // TODO: Add error handling, doc
    public function presentTree($columnsToShow,$htmlFragment,$rowId=null,$onClass='on',$offClass='off')
    {
        $whereClauses[$this->getLocalGroupColumn()]='-1';
        $this->getResult($whereClauses,$this->getOrderingColumnForListings());

        require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_presentTree_1.activeTemplate.php');

        $output = $treeHead;

        for ($i=0; $i < count($this->resultRows)/2; $i++) 
        { 
            require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_presentTree_2.activeTemplate.php');
            if ($this->hasActivationFlag)
            {
                if ($this->resultRows[$i]['active']=='1')
                {
                    $activation = $isActive;
                }else
                {
                    $activation = $isNotActive;
                }
            }else
            {
                $activation = '';
            }
            if (trim($this->getOrderingColumnForListings()!=''))
            {
                $upDown = $upDownActions;
            }else
            {
                $upDown = '';
            }
            $tmp = str_replace('@@@'. $columnsToShow .'@@@', $this->resultRows[$i][$columnsToShow], $htmlFragment);
            $tmp = str_replace('@@@id@@@', $this->resultRows[$i]['id'], $tmp);
            $tmp = str_replace('@@@tableId@@@', $this->tableId, $tmp);
            if ($rowId==$this->resultRows[$i]['id'])
            {
                $tmp = str_replace('@@@level@@@', 1 .' articleMenuSelected', $tmp);
            }else
            {
                $tmp = str_replace('@@@level@@@', 1, $tmp);
            }
            $tmp = str_replace('@@@activation@@@', $activation, $tmp);
            $tmp = str_replace('@@@upDown@@@', $upDown, $tmp);
            $tmp = str_replace('@@@subItems@@@', $this->presentTreeNode($this->resultRows[$i]['id'],$columnsToShow,2,$htmlFragment,$rowId,$onClass,$offClass), $tmp);

            $output .= $tmp;
            /*
            $output.='<li class="treeItemLevel1">'. $this->resultRows[$i][$columnsToShow] .' &nbsp; '. $activation .' <a href="administration.php?__address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'&action=edit"><img border="0" align="top" alt="edit" src="images/edit.png"></a> &nbsp; <a href="javascript:confirmDelete(\'administration.php?__address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'&action=delete\');"><img border="0" align="top" alt="Delete" src="images/delete.png"></a>
          '. $this->presentTreeNode($this->resultRows[$i]['id'],$columnsToShow,2,$htmlFragment) .'</li>
';*/
        }
        $output.=$treeTail;

        return $output;
    }

    // TODO: Add error handling, doc
    private function presentTreeNode($nodeId, $columnsToShow,$level,$htmlFragment,$rowId=null,$onClass='on',$offClass='off')
    {
        if (trim($this->getOrderingColumnForListings())!='')
        {
            $order=' order by '. $this->getOrderingColumnForListings();
        }else
        {
            $order='';
        }
        $result = $this->dataBase->query('select * from '. $this->getTableName() .' where '. $this->getLocalGroupColumn() .' = \''. $nodeId .'\''. $order);
        if (!mysqli_num_rows($result))
        {
            return '';
        }

        $output = $treeHead;

        while ($row = $this->dataBase->fetchAssoc($result)) 
        {
            if ($this->hasActivationFlag)
            {
                if ($row['active']=='1')
                {
                    $activation = $isActive;
                }else
                {
                    $activation = $isNotActive;
                }
            }else
            {
                $activation = '';
            }
            if (trim($this->getOrderingColumnForListings()!=''))
            {
                $upDown = $upDownActions;
            }else 
            {
                $upDown = '';
            }
            $tmp = str_replace('@@@'. $columnsToShow .'@@@', $row[$columnsToShow], $htmlFragment);
            $tmp = str_replace('@@@id@@@', $row['id'], $tmp);
            $tmp = str_replace('@@@tableId@@@', $this->tableId, $tmp);
            if ($rowId==$row['id'])
            {
                $tmp = str_replace('@@@level@@@',  $level .' articleSubMenuSelected', $tmp);
            }else
            {
                $tmp = str_replace('@@@level@@@',  $level, $tmp);
            }
            $tmp = str_replace('@@@activation@@@', $activation, $tmp);
            $tmp = str_replace('@@@upDown@@@', $upDown, $tmp);
            $tmp = str_replace('@@@subItems@@@', $this->presentTreeNode($row['id'],$columnsToShow,($level+1),$htmlFragment), $tmp);
            $output .= $tmp;
/*
            $output .= '<li class="treeItemLevel'. $level .'">'. $row[$columnsToShow] .' &nbsp; '. $activation .' <a href="administration.php?__address=1_'. $this->tableId .'_'. $row['id'] .'&action=edit"><img border="0" align="top" alt="edit" src="images/edit.png"></a> &nbsp; <a href="javascript:confirmDelete(\'administration.php?__address=1_'. $this->tableId .'_'. $row['id'] .'&action=delete\');"><img border="0" align="top" alt="Delete" src="images/delete.png"></a>
          '. $this->presentTreeNode($row['id'],$columnsToShow,($level+1)) .'</li>
';*/
        }
        $output .= $treeTail;
        return $output;
    }

	
	/***************************************************************************/
	/***************************************************************************/
	
    /**
     * 
     * @param string $rowId
     * @return boolean
     */
    public function deleteRow($rowId, $callLevel=1)
    {
    	// CAUTION: infinite recursion is possible!
    	
    	// Static properties:
    	// $deleteRowFilesToRemove;	// array( id =>isImage, ... )
    	// $deleteRowRowIds;		// array( table => array( rowId, ... ), ... )
    	
    	$wo = WOOOF::$instance;

    	$wo->debug("deleteRow: {$this->tableName} [$rowId] [$callLevel]");
    	
        if ( $callLevel == 1 ) {
    		$this::$deleteRowFilesToRemove	= array();
    		$this::$deleteRowRowIds			= array();
    	}

    	if ( isset($this::$deleteRowRowIds[$this->tableName][$rowId]) ) {
    		$wo->logError(self::_ECP."0530 deleteRow: Detected cycle on {$this->tableName}.$rowId");
    		return true;	// break the cycle
    	}

    	// add to memory
    	$this::$deleteRowRowIds[ $this->tableName ] = $rowId;
    	 
    	
        $theRow = $this->getRow($rowId);
        if ( $theRow === FALSE )  { return FALSE; }
        if ( $theRow === NULL ) {
        	$wo->logError(self::_ECP."0540 Delete row [$rowId] not found");
        	return false;
        }
        
        if($this->hasDeletedColumn)
        {
            $res = $this->dataBase->query('update '. $this->tableName .' set isDeleted=\'1\' where id=\''. WOOOF::$instance->cleanUserInput($rowId) .'\'');
            if ( $res === FALSE ) { return FALSE; }
        }else
        {
            if ($this->hasGhostTable)
            {
                //TODO: ghost table stuf goes here
            }
            
	        // antonis
	        // Handle possible File (externalFiles entries) and Picture fields 
	       	// Remove any relevant external files as well.
        	for($i=0; $i<count($this->columns)/2; $i++ ) {
        		$metadata = $this->columns[$i]->getColumnMetaData();
        		if ( $metadata['presentationType'] == WOOOF_columnPresentationTypes::picture or $metadata['presentationType'] == WOOOF_columnPresentationTypes::file) {
		        	WOOOF_Util::do_dump($metadata['name']);

		        	// add to memory
		        	$this::$deleteRowFilesToRemove[] = 
		        		array( 
		        			$theRow[$metadata['name']], 
		        			( $metadata['presentationType'] == WOOOF_columnPresentationTypes::picture )
		        		);
        		}	// file or image field
        	}	// for all columns

            $res = $this->dataBase->query('delete from '. $this->tableName .' where id=\''. WOOOF::$instance->cleanUserInput($rowId) .'\'');
            if ( $res === FALSE ) { return FALSE; }
        }

        // antonis
        // Handle possible Detail records
        //
        // CAUTION: tablesGroupedByThis should be the space separated list of all tables with groupedByTable == this.table
		// CAUTION: this is manually done in dbManager
		//
        if ( $wo->hasContent($this->tablesGroupedByThis) ) {
			$subTableNames = explode(' ', $this->tablesGroupedByThis);
			foreach ($subTableNames as $aSubTableName ) {
				$subTable = new WOOOF_dataBaseTable($wo->db, $aSubTableName);
				if ( !$subTable->constructedOk ) { return FALSE; }

				if ( $subTable->groupedByTable != $this->tableName ) {
					$wo->logError(self::_ECP."0550 SubTable name mismatch!");
					return FALSE;
				}
				
				$res = $subTable->getResult( array( $subTable->localGroupColumn => $rowId ), null, null, null, null, true, false );
				if ( $res === FALSE ) { return FALSE; }
				
				foreach( $subTable->resultRows as $aSubTableRow ) {
					$succ = $subTable->deleteRow($aSubTableRow['id'], $callLevel+1);
					if ( $succ === FALSE ) { return FALSE; }
				}
			}	// foreach subTable
        }	// there exist sub tables
        
        if (isset($this->resultRows[$rowId]))
        {
            foreach ($this->resultRows as $key => $value) 
            {
                if ($value['id']==$rowId)
                {
                    unset($this->resultRows[$key]);
                }
            }
        }
        
        if ( $callLevel == 1 ) {
        	$wo->debug("deleteRow: Completed for all cases.");

			foreach( $this::$deleteRowFilesToRemove as $aFileData ) {
				$succ = WOOOF_ExternalFiles::deleteExternalFile($wo, $aFileData[0], $aFileData[1] );
				// ???ignore false results (like failing to delete row from externaal file.
			}
			//debug
        	//echo WOOOF_Util::do_dump($this::$deleteRowFilesToRemove);
        	//echo WOOOF_Util::do_dump($this::$deleteRowRowIds);
        }
        
        return TRUE;
        
    }	// deleteRow

	
	/***************************************************************************/
	/***************************************************************************/
	
    // TODO: Add error handling, doc
    public function getAdminListRows($headers,$presentation,$displayActivation,$displayPreview,$displayUpDown)
    {
        $siteBaseURL = WOOOF::$instance->getConfigurationFor('siteBaseURL');

        $tplDir = WOOOF::$instance->getConfigurationFor('templatesRepository');
        
        $max = count($headers);
        if ($displayActivation)
        {
            $max++;
        }
        $rowClass = '';
        $output = '';
        $extraURLBit = '';
        if (!count($this->resultRows)) return '';
        foreach( $this->resultRows as $row ) 
        {
            require($tplDir.'wooof_getAdminListRows_1.activeTemplate.php');
            $template = $templateHead;
            $z=0;
            foreach($row as $item => $value) 
            {
                if (($item!='id' || $this->showIdInAdminLists=='1') && $item!='active')
                {
                	require($tplDir.'wooof_getAdminListRows_2.activeTemplate.php');
                    $z++;
                }
            }                

            if ($displayActivation)
            {

                if ($row['active']=='1')
                {
                    $template .= $templateItemIsActive;
                }else
                {
                    $template .= $templateItemIsInactive;
                }
            }
            $template .= $templateEditItem;
            if ($displayUpDown!==false)
            {
                $template .= $templateUpDown;
            }
            if ($displayPreview!==false)
            {

                $template .= $templatePreview;
            }
            $template .= $templateDeleteItem;
            $template .= $templateTail;
            
            
            $output .= $this->presentRowReadOnly($row['id'], $template);
            
        }
        
        return $output;
    }
}

/*
 * WOOOF_dataBaseColumn
 * 
 * Stores all the meta data for a column
 * Can also commit updates to the database
 * if the user has enough privileges. 
 */

class WOOOF_dataBaseColumn
{
	const _ECP = 'WDC';	// Error Code Prefix
	
	private $dataBase;
    private $tableId;
    private $columnId;
    private $name;
    private $description;
    private $type;
    private $presentationType;
    private $length;
    private $isReadOnly;
    private $isInvisible;
    private $appearsInLists;
    private $isASearchableProperty;
    private $isReadOnlyAfterFirstUpdate;
    private $isForeignKey;
    private $presentationParameters;
    private $valuesTable;
    private $columnToShow;
    private $columnToStore;
    private $defaultValue;
    private $orderingMirror;
    private $searchingMirror;
    private $currentUserCanEdit;
    private $currentUserCanRead;
    private $currentUserCanChangeProperties;
    private $thumbnailWidth;
    private $thumbnailHeight;
    private $resizeWidth;
    private $resizeHeight;
    private $thumbnailColumn;
    private $midSizeWidth;
    private $midSizeHeight;
    private $midSizeColumn;
    private $ordering;
    private $adminCSS;
    private $notNull;
    private $indexParticipation;
    private $colCollation;
    
    private $belongsToView;	// comes from table's definition
    
    public function __construct($dataBaseObject) 
    {
        $this->dataBase = $dataBaseObject;
        $this->currentUserCanEdit=FALSE;
        $this->currentUserCanRead=FALSE;
        $this->currentUserCanChangeProperties=FALSE;
    }
    
    /**
     * 
     * @param string $dataBaseObject
     * @param string[] $theMetaRow
     * @return false|WOOOF_dataBaseColumn
     */
    public static function fromMetaRow($dataBaseObject,$theMetaRow,$belongsToView=false)
    {
        $newDbColumn = new WOOOF_dataBaseColumn($dataBaseObject);
        $newDbColumn->tableId = $theMetaRow['tableId'];
        $newDbColumn->columnId = $theMetaRow['id'];
        $newDbColumn->name = $theMetaRow['name'];
        $newDbColumn->description = $theMetaRow['description'];
        $newDbColumn->type = $theMetaRow['type'];
        $newDbColumn->presentationType = $theMetaRow['presentationType'];
        $newDbColumn->length = $theMetaRow['length'];
        $newDbColumn->isReadOnly = $theMetaRow['isReadOnly'];
        $newDbColumn->isInvisible = $theMetaRow['isInvisible'];
        $newDbColumn->appearsInLists = $theMetaRow['appearsInLists'];
        $newDbColumn->isASearchableProperty = $theMetaRow['isASearchableProperty'];
        $newDbColumn->isReadOnlyAfterFirstUpdate = $theMetaRow['isReadOnlyAfterFirstUpdate'];
        $newDbColumn->isForeignKey = $theMetaRow['isForeignKey'];
        $newDbColumn->presentationParameters = $theMetaRow['presentationParameters'];
        $newDbColumn->valuesTable = $theMetaRow['valuesTable'];
        $newDbColumn->columnToShow = $theMetaRow['columnToShow'];
        $newDbColumn->columnToStore = $theMetaRow['columnToStore'];
        $newDbColumn->defaultValue = $theMetaRow['defaultValue'];
        $newDbColumn->orderingMirror = $theMetaRow['orderingMirror'];
        $newDbColumn->thumbnailWidth = $theMetaRow['thumbnailWidth'];
        $newDbColumn->thumbnailHeight = $theMetaRow['thumbnailHeight'];
        $newDbColumn->resizeWidth = $theMetaRow['resizeWidth'];
        $newDbColumn->resizeHeight = $theMetaRow['resizeHeight'];
        $newDbColumn->searchingMirror = $theMetaRow['searchingMirror'];
        $newDbColumn->thumbnailColumn = $theMetaRow['thumbnailColumn'];
        $newDbColumn->midSizeWidth = $theMetaRow['midSizeWidth'];
        $newDbColumn->midSizeHeight = $theMetaRow['midSizeHeight'];
        $newDbColumn->midSizeColumn = $theMetaRow['midSizeColumn'];
        $newDbColumn->ordering = $theMetaRow['ordering'];
        $newDbColumn->adminCSS = $theMetaRow['adminCSS'];
        $newDbColumn->notNull = $theMetaRow['notNull'];

        if ( isset($theMetaRow['indexParticipation']) ) {
	        $newDbColumn->indexParticipation = $theMetaRow['indexParticipation'];
        }
        if ( isset($theMetaRow['colCollation']) ) {
	        $newDbColumn->colCollation = $theMetaRow['colCollation'];
        }
        
        $newDbColumn->belongsToView = $belongsToView;
        
        // TODO: CAUTION: antonis: disable call to updateSecurity for regular site pages
        global $__isSiteBuilderPage, $__isAdminPage;
		if ( $__isSiteBuilderPage or $__isAdminPage ) {
        	$res = $newDbColumn->updateSecurity();
        	if ( $res === FALSE ) { return FALSE; }
		}
		
        return $newDbColumn;
    }
    
    /**
     * 
     * @return boolean	// fills-in currentUserCanEdit/Read/ChangeProperties
     */
    public function updateSecurity()
    {
        global $userData;
        
        $permitions = $this->dataBase->getSecurityPermitionsForLocationAndUser('1_'. $this->tableId .'_'. $this->columnId, $userData['id']);
        if ( $permitions === FALSE ) { return FALSE; }
        
        if (isset($permitions['edit']) && $permitions['edit'] === TRUE)
        {
            $this->currentUserCanEdit = TRUE;
        }else
        {
            $this->currentUserCanEdit = FALSE;
        }
        
        if (isset($permitions['read']) && $permitions['read'] === TRUE)
        {
            $this->currentUserCanRead = TRUE;
        }else
        {
            $this->currentUserCanRead = FALSE;
        }
        
        if (isset($permitions['modifyProperties']) && $permitions['modifyProperties'] === TRUE)
        {
            $this->currentUserCanChangeProperties = TRUE;
        }else
        {
            $this->currentUserCanChangeProperties = FALSE;
        }
        
        return TRUE;
    }
    
    public function getColumnMetaData()
    {
        $columnMetaData='';
        $columnMetaData['type'] = $this->type;
        $columnMetaData['length'] = $this->length;
        $columnMetaData['isReadOnly'] = $this->isReadOnly;
        $columnMetaData['isInvisible'] = $this->isInvisible;
        $columnMetaData['appearsInLists'] = $this->appearsInLists;
        $columnMetaData['isASearchableProperty'] = $this->isASearchableProperty;
        $columnMetaData['isReadOnlyAfterFirstUpdate'] = $this->isReadOnlyAfterFirstUpdate;
        $columnMetaData['isForeignKey'] = $this->isForeignKey;
        $columnMetaData['presentationParameters'] = $this->presentationParameters;
        $columnMetaData['valuesTable'] = $this->valuesTable;
        $columnMetaData['columnToShow'] = $this->columnToShow;
        $columnMetaData['columnToStore'] = $this->columnToStore;
        $columnMetaData['defaultValue'] = $this->defaultValue;
        $columnMetaData['orderingMirror'] = $this->orderingMirror;
        $columnMetaData['searchingMirror'] = $this->searchingMirror;
        $columnMetaData['tableId'] = $this->tableId;
        $columnMetaData['columnId'] = $this->columnId;
        $columnMetaData['name'] = $this->name;
        $columnMetaData['description'] = $this->description;
        $columnMetaData['presentationType'] = $this->presentationType;
        $columnMetaData['resizeWidth'] = $this->resizeWidth;
        $columnMetaData['resizeHeight'] = $this->resizeHeight;
        $columnMetaData['thumbnailWidth'] = $this->thumbnailWidth;
        $columnMetaData['thumbnailHeight'] = $this->thumbnailHeight;
        $columnMetaData['thumbnailColumn'] = $this->thumbnailColumn;
        $columnMetaData['midSizeWidth'] = $this->midSizeWidth;
        $columnMetaData['midSizeHeight'] = $this->midSizeHeight;
        $columnMetaData['midSizeColumn'] = $this->midSizeColumn;
        $columnMetaData['ordering'] = $this->ordering;
        $columnMetaData['adminCSS'] = $this->adminCSS;
        $columnMetaData['notNull'] = $this->notNull;
        $columnMetaData['indexParticipation'] = $this->indexParticipation;
        $columnMetaData['colCollation'] = $this->colCollation;
        
        return $columnMetaData;
    }

    public function getAppearsInLists()
    {
        return $this->appearsInLists;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getId()
    {
        return $this->columnId;
    }
    
    // TODO: Add error handling, doc
    public function updateMetaDataFromPost()
    {
    	$c = '';	// TODO: What was $c ????
    	
        if ($this->currentUserCanChangeProperties)
        {
            if (!isset($_POST['notNull']) || $_POST['notNull'] !='1')
            {
                $_POST['notNull']='0';
            }
            if (!isset($_POST['isReadOnly']) || $_POST['isReadOnly'] !='1')
            {
                $_POST['isReadOnly']='0';
            }
            if (!isset($_POST['isInvisible']) || $_POST['isInvisible'] !='1')
            {
                $_POST['isInvisible']='0';
            }
            if (!isset($_POST['isASearchableProperty']) || $_POST['isASearchableProperty'] !='1')
            {
                $_POST['isASearchableProperty']='0';
            }
            if (!isset($_POST['isReadOnlyAfterFirstUpdate']) || $_POST['isReadOnlyAfterFirstUpdate'] !='1')
            {
                $_POST['isReadOnlyAfterFirstUpdate']='0';
            }
            if (!isset($_POST['isForeignKey']) || $_POST['isForeignKey'] !='1')
            {
                $_POST['isForeignKey']='0';
            }
            if (!isset($_POST['appearsInLists']) || $_POST['appearsInLists'] !='1')
            {
                $_POST['appearsInLists']='0';
            }
            $query='update __columnMetaData set
name=\''. $this->dataBase->escape(trim($_POST['name'])) .'\',
description=\''. $this->dataBase->escape(trim($_POST['description'])) .'\',
type=\''. $this->dataBase->escape(trim($_POST['type'])) .'\',
length=\''. $this->dataBase->escape(trim($_POST['length'])) .'\',
presentationType=\''. $this->dataBase->escape(trim($_POST['presentationType'])) .'\',
isReadOnly=\''. $this->dataBase->escape(trim($_POST['isReadOnly'])) .'\',
notNull=\''. $this->dataBase->escape(trim($_POST['notNull'])) .'\',
isInvisible=\''. $this->dataBase->escape(trim($_POST['isInvisible'])) .'\',
appearsInLists=\''. $this->dataBase->escape(trim($_POST['appearsInLists'])) .'\',
isASearchableProperty=\''. $this->dataBase->escape(trim($_POST['isASearchableProperty'])) .'\',
isReadOnlyAfterFirstUpdate=\''. $this->dataBase->escape(trim($_POST['isReadOnlyAfterFirstUpdate'])) .'\',
isForeignKey=\''. $this->dataBase->escape(trim($_POST['isForeignKey'])) .'\',
presentationParameters=\''. $this->dataBase->escape(trim($_POST['presentationParameters'])) .'\',
valuesTable=\''. $this->dataBase->escape(trim($_POST['valuesTable'])) .'\',
columnToShow=\''. $this->dataBase->escape(trim($_POST['columnToShow'])) .'\',
columnToStore=\''. $this->dataBase->escape(trim($_POST['columnToStore'])) .'\',
defaultValue=\''. $this->dataBase->escape(trim($_POST['defaultValue'])) .'\',
orderingMirror=\''. $this->dataBase->escape(trim($_POST['orderingMirror'])) .'\',
searchingMirror=\''. $this->dataBase->escape(trim($_POST['searchingMirror'])) .'\',
resizeWidth=\''. $this->dataBase->escape(trim($_POST['resizeWidth'])) .'\',
resizeHeight=\''. $this->dataBase->escape(trim($_POST['resizeHeight'])) .'\',
thumbnailWidth=\''. $this->dataBase->escape(trim($_POST['thumbnailWidth'])) .'\',
thumbnailHeight=\''. $this->dataBase->escape(trim($_POST['thumbnailHeight'])) .'\',
midSizeColumn=\''. $this->dataBase->escape(trim($_POST['midSizeColumn'.$c])) .'\',
midSizeWidth=\''. $this->dataBase->escape(trim($_POST['midSizeWidth'.$c])) .'\',
midSizeHeight=\''. $this->dataBase->escape(trim($_POST['midSizeHeight'.$c])) .'\',
thumbnailColumn=\''. $this->dataBase->escape(trim($_POST['thumbnailColumn'])) .'\',
ordering=\''. $this->dataBase->escape(trim($_POST['ordering'])) .'\',
adminCSS=\''. $this->dataBase->escape(trim($_POST['adminCSS'])) .'\',
indexParticipation=\''. $this->dataBase->escape(trim($_POST['indexParticipation'])) .'\',
colCollation=\''. $this->dataBase->escape(trim($_POST['colCollation'])) .'\'
where id=\''. $this->columnId .'\'';
            
            $succ = $this->dataBase->query($query);
            if ( $succ === FALSE ) { return FALSE; }
            
            $result = $this->dataBase->query('select tableName from __tableMetaData where id=\''. $this->tableId .'\'');
            $temp = $this->dataBase->fetchRow($result);
            $tableName = $temp[0];
            
            if ($_POST['isForeignKey'] == '1')
            {
                $foreignKeyExists = FALSE;
                $result = $this->dataBase->query('SHOW INDEX FROM '. $tableName);
                while($row = $this->dataBase->fetchAssoc($result))
                {
                    if ($row['Key_name'] == 'FK_'. $tableName .'_'. $this->name)
                    {
                        $foreignKeyExists = TRUE;
                    }
                }
                if ($foreignKeyExists)
                {
                    $this->dataBase->query('DROP FOREIGN KEY FK_'. $tableName .'_'. $this->name);
                }
                $this->dataBase->query('ALTER TABLE '. $tableName .' ADD FOREIGN KEY FK_'. $tableName .'_'. $this->dataBase->escape(trim($_POST['name'])).
                ' REFERENCES '. $this->dataBase->escape(trim($_POST['valuesTable'])) .' ('. $this->dataBase->escape(trim($_POST['columnToStore'])) .')
    ON DELETE RESTRICT
    ON UPDATE CASCADE');
            }
            
            if ( !$this->belongsToView ) {
	            $query='ALTER TABLE '. $tableName .' CHANGE COLUMN '. $this->name .' '. $this->dataBase->escape(trim($_POST['name'])) .' '. WOOOF_dataBaseColumnTypes::getColumnTypeLiteral($this->dataBase->escape(trim($_POST['type'])));
	            if ($this->dataBase->escape(trim($_POST['length']))!='')
	            {
	                $query.='('.$this->dataBase->escape(trim($_POST['length'])).')';
	            }
	            if ($this->dataBase->escape(trim($_POST['notNull'])) == '1')
	            {
	                $query .= ' NOT NULL ';
	            }
	            if (WOOOF::$instance->hasContent($this->dataBase->escape(trim($_POST['defaultValue']))))
	            {
	                $query .= ' DEFAULT \''. $this->dataBase->escape(trim($_POST['defaultValue'])) .'\'';
	            }
	            if ($this->dataBase->escape(trim($_POST['colCollation'])))
	            {
	                $query .= ' COLLATE \''. $this->dataBase->escape(trim($_POST['colCollation'])) .'\'';
	            }
	            $succ = $this->dataBase->query($query);
                if ( $succ === FALSE ) { return FALSE; }
            }	// normal table column
        }else
        {
            echo 'FAILED !!! You don\'t have the required rights!';
            exit;
        }
        
        return TRUE;
    }	// updateMetaDataFromPost

    public function renderReadOnly($value, $rowId)
    {
        $siteBaseURL = WOOOF::$instance->getConfigurationFor('siteBaseURL');
        $imagesRelativePath = WOOOF::$instance->getConfigurationFor('imagesRelativePath');
        global $__isAdminPage;
		global $__isSiteBuilderPage;
        
        if ( $__isSiteBuilderPage ) {
	       	$this->isInvisible=false;
	    }

        if ( $this->isInvisible)
        {
            return '';
        }
        switch ($this->presentationType) 
        {
            case WOOOF_columnPresentationTypes::checkBox :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderReadOnly_1.activeTemplate.php');
                if ($value=='1')
                {
                    $product = $checkboxActive;
                }else
                {
                    $product = $checkboxInActive;
                }
                break;
            case WOOOF_columnPresentationTypes::date :
                $product = WOOOF::decodeDate($value);
                break;
            case WOOOF_columnPresentationTypes::time :
                $product = WOOOF::decodeTime($value);
                break;
            case WOOOF_columnPresentationTypes::dateAndTime :
                $product = WOOOF::decodeDateTime($value,'/',TRUE);
                break;
            case WOOOF_columnPresentationTypes::autoComplete :
            case WOOOF_columnPresentationTypes::dropList :    
            case WOOOF_columnPresentationTypes::radioHoriz :    
            case WOOOF_columnPresentationTypes::radioVert :
                $result=$this->dataBase->query('select ' . $this->columnToShow . ' from '. $this->valuesTable .' where '. $this->columnToStore .'=\''. $value .'\'');
                if ( $result === FALSE ) { return FALSE; }
                $row = $this->dataBase->fetchAssoc($result);
                $product = $row[$this->columnToShow];
                
                //TODO: Antonis Temporary change
                ///*
                if ( $value != '' && $__isSiteBuilderPage && $this->presentationType == WOOOF_columnPresentationTypes::dropList && substr($this->valuesTable,0,2) != '__'  && class_exists('Generic') ) {
                	$product = Generic::showLinkToRecord($this->valuesTable, $value, 'view', $product ); 
                }
                //*/
                break;
            case WOOOF_columnPresentationTypes::textBox :
                $product = $value;
                break;
            case WOOOF_columnPresentationTypes::htmlText :
                $product = $value;
                if ($__isAdminPage)
                {
                    $product = strip_tags($product);
                    if ($product=='') $product = ' &nbsp; ';
                }
                break;
            case WOOOF_columnPresentationTypes::textArea :
            default:
                $value = nl2br($value);
                if (strlen($value)>6000)
                {
                    $product = substr($value, 0, 60) .'...';
                }else
                {
                    $product = $value;
                }
                break;
            case WOOOF_columnPresentationTypes::file :
            	if ( !WOOOF::$instance->hasContent($value) ) {
            		$product = '';
            	}
            	else {
	                $fR = $this->dataBase->query('select * from __externalFiles where id=\''. $value .'\'');
	                if ( $fR === FALSE ) { return FALSE; }
	                $f=$this->dataBase->fetchAssoc($fR);
	                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderReadOnly_2.activeTemplate.php');
	                if (isset($f['id']))
	                {
	                    $product = $actualFileLink;
	                }else
	                {
	                	WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP."0210 File [$value] was not found in __externalFiles"  );
	                    $product = 'File not found';
	                }
            	}
                break;
            case WOOOF_columnPresentationTypes::picture :
                if (trim($this->presentationParameters)=='')
                {
                    $prefix=$imagesRelativePath;
                }else
                {
                    $prefix=trim($this->presentationParameters);
                }
                if (!$__isAdminPage) {
                	if ( $value == '' ) {
                		$product = $imagesRelativePath . 'spacer.gif';
                	}
                	else {
                		$product = $prefix . $value ;
                	}
                }
                else
                {
                	require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderReadOnly_3.activeTemplate.php');
                	if ($value!='')
                    {
                        $product = $imageLink;
                    }else 
                    {
                        $product = $noImageAvailableLink;
                    }
                }
                break;
        }

        if ($product=='' && $__isAdminPage)
        {
            require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderReadOnly_4.activeTemplate.php');
                
            $product = $emptyImage;
        }
        
        return $product;
    }
    
    /**
     * 
     * @param string $className
     * @return string|unknown
     */
    public function renderForInsert($className='')
    {
        $cssForFormItem = WOOOF::$instance->getConfigurationFor('cssForFormItem');
        global $__isAdminPage;
        global $__isSiteBuilderPage;
        
        if ( ($this->isInvisible || $this->isReadOnly) && !$__isSiteBuilderPage )
        {
            return '';
        }
        if ($className=='' && $this->presentationType!=4)
        {
            $className = $cssForFormItem[WOOOF_columnPresentationTypes::getColumnPresentationLiteral($this->presentationType)];
        }

        switch ($this->presentationType) 
        {
            case WOOOF_columnPresentationTypes::checkBox :
            require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForInsert_1.activeTemplate.php');
                if ($this->defaultValue=='1')
                {
                    $product = $checkBoxChecked;
                }else
                {
                    $product = $checkBoxNormal; 
                }
                break;
            case WOOOF_columnPresentationTypes::date :
                $date = $this->prepareDefaultDateTime();
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForInsert_2.activeTemplate.php');
                $product = $dateInsert;
                break;
            case WOOOF_columnPresentationTypes::time :
                $date = $this->prepareDefaultDateTime();
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForInsert_3.activeTemplate.php');
                $product = $timeInsert;
                break;
            case WOOOF_columnPresentationTypes::dateAndTime :
                $date = $this->prepareDefaultDateTime();
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForInsert_4.activeTemplate.php');
                $product = $dateTimeInsert;
                break;
            case WOOOF_columnPresentationTypes::autoComplete :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForInsert_5.activeTemplate.php');
                $product = $autoCompleteInsert;
                break;
            case WOOOF_columnPresentationTypes::dropList :    
                if ($__isAdminPage && isset($_GET['wooofParent']) && $_GET['wooofParent']!='')
                {
                    $value = WOOOF::$instance->cleanUserInput($_GET['wooofParent']);
                }else
                {
                    $value='';
                }
                $product=$this->dataBase->getDropListSelected($this->valuesTable, $this->name, '' , $className, $this->columnToStore, $this->columnToShow, $this->columnToStore, $value);
                break;
            case WOOOF_columnPresentationTypes::radioHoriz : 
                $product=$this->dataBase->getRadio($this->valuesTable, $this->name, TRUE, '', $className, $this->columnToStore, $this->columnToShow);
                break;
            case WOOOF_columnPresentationTypes::radioVert:    
                $product=$this->dataBase->getRadio($this->valuesTable, $this->name, FALSE,'', $className, $this->columnToStore, $this->columnToShow);
                break;
            case WOOOF_columnPresentationTypes::textBox :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForInsert_6.activeTemplate.php');
                $product = $textBoxInsert;
                break;
            case WOOOF_columnPresentationTypes::htmlText :
                $product = '^@^ html tags Place Holder ^@^';
            case WOOOF_columnPresentationTypes::textArea :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForInsert_7.activeTemplate.php');
                $product = $textAreaInsert;
                break;
            case WOOOF_columnPresentationTypes::file :
            case WOOOF_columnPresentationTypes::picture :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForInsert_8.activeTemplate.php');
                $product = $fileInsert;
                break;
        }
        return $product;
    }
    
    private function prepareDefaultDateTime()
    {
        if ($this->defaultValue=='@@@empty@@@')
        {
            $date['year']='';
            $date['month']='';
            $date['day']='';
            $date['hour']='';
            $date['minute']='';
            $date['second']=''; 
        }else if ($this->defaultValue=='0' || $this->defaultValue=='00000000000000')
        {
        	// Antonis
            $date['year']='';
            $date['month']='';
            $date['day']='';
            $date['hour']='';
            $date['minute']='';
            $date['second']=''; 
        	//$date = WOOOF::breakDateTime($this->defaultValue);
        }else
        {
            $this->defaultValue = WOOOF::getCurrentDateTime();
            $date = WOOOF::breakDateTime($this->defaultValue);                   
        }
        return $date;       
    }
    
    public function getDataBaseEntry($value)
    {
    	$output='';
    	
    	switch ($this->presentationType)
    	{
    		case WOOOF_columnPresentationTypes::file:
    			$externalFileId=$this->handleFileUpload($columnsToFill[$q]);
    			if ($externalFileId===FALSE)
    			{
    				WOOOf::$instance->log(WOOOF_loggingLevels::WOOOF_NOTICE, self::_ECP."0220 " . 'No file uploaded or file upload error for \''. $columnsToFill[$q] .'\'.');
    				$pleaseNoComma = true;
    			}else
    			{
    				$query.=' '. $columnsToFill[$q] .'=\''. $externalFileId .'\'';
    			}
    			break;
    		case '':
    			break;
    		default:
    			break;
    	}
    	
    	return $output;
    }
    
    public function renderForEdit($value,$rowId,$className='')
    {
        $cssForFormItem = WOOOF::$instance->getConfigurationFor('cssForFormItem');
        $imagesRelativePath = WOOOF::$instance->getConfigurationFor('imagesRelativePath');
        $siteBaseURL = WOOOF::$instance->getConfigurationFor('siteBaseURL');
        global $__isAdminPage;
        global $wooofParent;

        require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_1.activeTemplate.php');
                
        if ($className=='')
        {
            $className = $cssForFormItem[WOOOF_columnPresentationTypes::getColumnPresentationLiteral($this->presentationType)];
        }

        if ($this->isInvisible && !$__isAdminPage)
        {
            return '';
        }else if($this->isInvisible)
        {
            return $hidenColumnValue;
        }

        if (($this->isReadOnly || $this->isReadOnlyAfterFirstUpdate) && !$__isAdminPage)
        {
            return $this->renderReadOnly($value,$rowId);
        }else if ($this->isReadOnly || $this->isReadOnlyAfterFirstUpdate)
        {
            return $this->renderReadOnly($value,$rowId) . $hidenColumnValue;
        }
        $product = 'renderForEdit empty product';	// TODO: debug
        
        switch ($this->presentationType) {
            case WOOOF_columnPresentationTypes::checkBox :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_2.activeTemplate.php');
                if ($value=='1')
                {
                    $product = $checkBoxEditChecked;
                }else
                {
                    $product = $checkBoxEdit; 
                }
                break;
            case WOOOF_columnPresentationTypes::date :
                $date = WOOOF::breakDateTime($value);
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_3.activeTemplate.php');
                $product = $dateEdit;
                break;
            case WOOOF_columnPresentationTypes::time :
                $date = WOOOF::breakDateTime($value);
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_4.activeTemplate.php');                
                $product = $timeEdit;
                break;
            case WOOOF_columnPresentationTypes::dateAndTime :
                $date = WOOOF::breakDateTime($value);
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_5.activeTemplate.php');                
                $product = $dateTimeEdit;
                break;
            case WOOOF_columnPresentationTypes::autoComplete :
                $result=$this->dataBase->query('select * from '. $this->valuesTable .' where '. $this->columnToShow .'=\''. WOOOF::$instance->cleanUserInput($value) .'\'');
                $row = $this->dataBase->fetchAssoc($result);
                $aliasValue = $row[$this->columnToShow];
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_6.activeTemplate.php');
                $product = $autoCompleteEdit;
                break;
            case WOOOF_columnPresentationTypes::dropList :
                $tableMetaData = $this->dataBase->getRowByColumn('__tableMetaData', 'tableName', $this->valuesTable);
                if (trim($tableMetaData['orderingColumnForListings'])!='')
                {
                    $orderBy=' order by '.$tableMetaData['orderingColumnForListings'];
                }else 
                {
                    $orderBy='';
                }
                $product=$this->dataBase->getDropListSelected($this->valuesTable, $this->name, $orderBy , $className, $this->columnToStore, $this->columnToShow, $this->columnToStore, $value);
                break;
            case WOOOF_columnPresentationTypes::radioHoriz : 
                $product=$this->dataBase->getRadio($this->valuesTable, $this->name, TRUE, '', $className, $this->columnToStore, $this->columnToShow, $this->columnToStore, $value);
                break;
            case WOOOF_columnPresentationTypes::radioVert:    
                $product=$this->dataBase->getRadio($this->valuesTable, $this->name, FALSE,'', $className, $this->columnToStore, $this->columnToShow, $this->columnToStore, $value);
                break;
            case WOOOF_columnPresentationTypes::textBox :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_7.activeTemplate.php');                
                $product = $textBoxEdit;
                break;
            case WOOOF_columnPresentationTypes::htmlText :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_8.activeTemplate.php');                
                $product = $htmlText;
                break;
            case WOOOF_columnPresentationTypes::textArea :
                require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_9.activeTemplate.php');                
                $product = $textAreaEdit;
                break;
            case WOOOF_columnPresentationTypes::file :                
                $fR = $this->dataBase->query('select * from __externalFiles where id=\''. $value .'\'');
                $f = $this->dataBase->fetchAssoc($fR);

                if (isset($f['id']))
                {
                    require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_10.activeTemplate.php');                
                    $product = $fileEdit;
                }else
                {
                    require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_12.activeTemplate.php');
                    $product = $fileEntry;
                }
                break;
            case WOOOF_columnPresentationTypes::picture :
                if (trim($this->presentationParameters)=='')
                {
                    $prefix=$imagesRelativePath;
                }else
                {
                    $prefix=trim($this->presentationParameters);
                }
            	require(WOOOF::$instance->getConfigurationFor('templatesRepository').'wooof_renderForEdit_11.activeTemplate.php');
                $product = $pictureEdit;
                break;
        }

        return $product;

    }
    
    // TODO: Add error handling, doc
    public function drop()
    {
        if ($this->currentUserCanEdit)
        {
            $table = $this->dataBase->getRow('__tableMetaData',  $this->dataBase->escape(trim($this->tableId)));
            
            $this->dataBase->query('alter table '. $table['tableName'] .' drop column '. $this->name);
            $this->dataBase->query('delete from __columnMetaData where id=\''. $this->columnId .'\'');
        }
    }
    
    public function checkValue($val)
    {
        // TODO: Actual checks should happen here. For the moment anything goes ...
		
    	/*
    	$wo = WOOOF::$instance;
    	if ( $this->notNull == '1' ) {
    		if ( !$wo->hasContent($val) ) {	// $val == '' might be accepted ??? 
    			$wo->logError(self::_ECP."0800 Empty value for non nullable [" . $this->name . "]" );
    			return false;
    		}
    	}
    	
  		if ( $this->presentationType == WOOOF_columnPresentationTypes::date ) {
  			$dString = substr($val,0,8);
  			$d = DateTime::createFromFormat('Ymd', $dString);
  			if ( $d===FALSE or  $d->format('Ymd') == $dString ) {
  				$wo->logError(self::_ECP."0810 Bad Date string [$val] for [{$this->name}]]" );
  				return false;
  			}  			
  		}  	
    	*/
    	
    	return TRUE;
    }

    ////TODO: concentrate update and insertinos here from table. Each column should be able to handle itself.
    ////TODO: return a string containing the query piece that should be concatenated to the main query to be executed by the table 
    public function handleInsertFromPost($rowId = '')
    {
        if (isset($_POST[$this->name])) 
        {
            $value = $_POST[$this->name];
        }else
        {
            if (WOOOF::$instance->hasContent($this->defaultValue))
            {
                $value = $this->defaultValue;
            }else
            {
                $value = 'NULL';
            }
        }

        switch ($this->presentationType) 
        {
            case WOOOF_columnPresentationTypes::checkBox :
                return $this->handleInsertAsCheckbox($value);
                break;
            case WOOOF_columnPresentationTypes::date :
            case WOOOF_columnPresentationTypes::time :
            case WOOOF_columnPresentationTypes::dateAndTime :
                return $this->handleInsertAsDateTime($value);
                break;
            case WOOOF_columnPresentationTypes::autoComplete :
            case WOOOF_columnPresentationTypes::dropList :    
            case WOOOF_columnPresentationTypes::radioHoriz :    
            case WOOOF_columnPresentationTypes::radioVert :
                return $this->handleInsertAsDropList($value);
                break;
            case WOOOF_columnPresentationTypes::textBox :
            case WOOOF_columnPresentationTypes::textArea :
                return $this->handleInsertAsTextBox($value);
                break;
            case WOOOF_columnPresentationTypes::htmlText :
                return $this->handleInsertAsHTML($value);
                break;
            case WOOOF_columnPresentationTypes::file :
                return $this->handleInsertAsFile($value);
                break;
            case WOOOF_columnPresentationTypes::picture :
                return $this->handleInsertAsPicture($value);
                break;
            default:
                WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_WARNING, self::_ECP.'1000 Unknown presentation type. Unable to prepare SQL fragment for insert.');
                return FALSE;
                break;
        }
    }

    public function handleUpdateFromPost($rowId = '')
    {
        if (isset($_POST[$this->name])) 
        {
            $value = $_POST[$this->name];
        }else
        {
            if (WOOOF::$instance->hasContent($this->defaultValue))
            {
                $value = $this->defaultValue;
            }else
            {
                $value = 'NULL';
            }
        }

        switch ($this->presentationType) 
        {
            case WOOOF_columnPresentationTypes::checkBox :
                return $this->handleUpdateAsCheckbox($value);
                break;
            case WOOOF_columnPresentationTypes::date :
            case WOOOF_columnPresentationTypes::time :
            case WOOOF_columnPresentationTypes::dateAndTime :
                return $this->handleUpdateAsDateTime($value);
                break;
            case WOOOF_columnPresentationTypes::autoComplete :
            case WOOOF_columnPresentationTypes::dropList :    
            case WOOOF_columnPresentationTypes::radioHoriz :    
            case WOOOF_columnPresentationTypes::radioVert :
                return $this->handleUpdateAsDropList($value);
                break;
            case WOOOF_columnPresentationTypes::textBox :
            case WOOOF_columnPresentationTypes::textArea :
                return $this->handleUpdateAsTextBox($value);
                break;
            case WOOOF_columnPresentationTypes::htmlText :
                return $this->handleUpdateAsHTML($value);
                break;
            case WOOOF_columnPresentationTypes::file :
                return $this->handleUpdateAsFile($value);
                break;
            case WOOOF_columnPresentationTypes::picture :
                return $this->handleUpdateAsPicture($value);
                break;
            default:
                WOOOF::$instance->log(WOOOF_loggingLevels::WOOOF_WARNING, self::_ECP.'1001 Unknown presentation type. Unable to prepare SQL fragment for update.');
                return FALSE;
                break;
        }
    }

    private function handleInsertAsCheckbox()
    {

    }

    private function handleInsertAsDateTime()
    {

    }

    private function handleInsertAsDropList()
    {

    }

    private function handleInsertAsTextBox()
    {

    }

    private function handleInsertAsHTML()
    {

    }

    private function handleInsertAsFile()
    {

    }

    private function handleInsertAsPicture()
    {

    }






    private function handleUpdateAsCheckbox($value)
    {
        if ($this->checkValue($value)===FALSE)
        {
            return FALSE;
        }
        if ($value == '1')
        {
            return '`'. $this->name .'`=\'1\'';
        }
    }

    private function handleUpdateAsDateTime($value)
    {

    }

    private function handleUpdateAsDropList($value)
    {

    }

    private function handleUpdateAsTextBox($value)
    {

    }

    private function handleUpdateAsHTML($value)
    {

    }

    private function handleUpdateAsFile($value)
    {

    }

    private function handleUpdateAsPicture($value)
    {

    }

}

?>