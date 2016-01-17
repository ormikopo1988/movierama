<?php
/* The MovieRama Application Class */

class VO_App extends VO_TblAbstract {
	const _ECP = 'APP';	// Error Code Prefix
	
    public $userId;				// User Id
    public $personProfileId;	// MovieRama Person Profile Id
    public $userSlug;			// First Name, Last Name
    public $isUserRegistered;
    public $movieRamaPersonRow;		// A v_movierama_persons Row

    public
    function initFor( WOOOF $wo ) {
    	$wooofUserId = $wo->userData['id'];
    	
    	if ( !$wo->hasContent($wooofUserId) ) {
    		$wo->logError(self::_ECP."0010 No value found for 'wooofUserId'");
    		return false;
    	}
    	
    	if ( $wooofUserId === '0123456789' ) {
    		return true;
    	}
    	
    	$movieRamaPersonRow = $wo->db->getRowByColumn('v_movierama_persons', 'VUS_userId', $wooofUserId );
    	if ( $movieRamaPersonRow === FALSE ) { return FALSE; }
    	
    	if ( $movieRamaPersonRow === NULL ) {
    		$wo->logError(self::_ECP."0020 User [$wooofUserId] should not be logged-in");
    		return false;
    	}
    	
    	$this->userId 			  = $movieRamaPersonRow['VUS_id'];
    	$this->personProfileId 	  = $movieRamaPersonRow['VUS_personProfileId'];
    	$this->userSlug			  = $movieRamaPersonRow['PROF_firstName'] . ' ' . $movieRamaPersonRow['PROF_lastName'];
    	$this->movieRamaPersonRow = $movieRamaPersonRow;
    	$this->isUserRegistered = ( $movieRamaPersonRow['VUS_isVerified'] == '1' );
    	
    	return $this->userId;
    		 
    }	// initFor
    
    public static
    function handleClassAutoloader( $className ) 
    {
    	// Looking at:
    	//		siteBasePath/classes/
    	//			VO_Ctrl	for Controllers
    	//			VO_Tbl	for Table Classes
    	$wo = WOOOF::$instance;
    	
        $classesDir = $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('classesPath');
    	
    	if ( substr($className,0,7) == 'VO_Ctrl' ) {
    		require( $classesDir . 'VO_Ctrl/' . $className . '.php' );
    		return true; // ???
    	}
    	
        if ( substr($className,0,6) == 'VO_Tbl' ) {
    		require( $classesDir . 'VO_Tbl/' . $className . '.php' );
    		return true; // ???
    	}
    	
    	$filename = $classesDir . $className . '.php';
    	
    	if ( file_exists($filename) ) {
    		require( $filename );
    	}
    	
    	return false;
    }	// handleClassAutoloader
    

}  // VO_App