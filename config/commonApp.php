<?php

// Application specific options
//
$wooofConfigCustomOptions = array(
    // Required for allowing debugging on all or specific sessions
    //
	'debug' =>
		array( 
			'sessions' => array ( 
				'ALL', 
				'---A9YkAyYmIjFk8qNvVy0mOjD7MdQeKqQvGyQeA90g' 
			)	
		),
    // Required for using the WOOOF::sendMail routine
    //
	'email_smtp' =>
		array(
			'SMTP_HOST'		=> 'smtp.gmail.com',
			'SMTP_PORT'		=> 587,		
			'SMTP_AUTH' 	=> true,
			'SMTP_USERNAME'	=> 'innovvoice@gmail.com',
			'SMTP_PASSWORD'	=> '1qa@WS3ed$RF',
			'SMTP_SECURE'	=> 'tls',  // optional, can be commented out
			//'WORD_WRAP'	=> 80,
			'SMTP_DEBUG'	=> false
		),
    // Useful for providing a common/default 'FROM:' email address (from_general).
    // Other addresses are up to the configurer.
    //
	'email_addresses' =>
		array(
			'from_general'		=> 'movierama@gmail.com'
		),

    // Custom/Application configuration options
    //
    
    /*
    // E.g. for bank payments
    'cardProcessor' => 
     	array(
	        'cardProcessorActionURL'    =>  'https://alpha.test.modirum.com/vpos/shophandlermpi',
            'cardProcessorMID'          =>  '0000000000',
            'cardProcessorSecret'       =>  'Cardlink',
            'cardProcessorSuccess'      =>  'http://xxxxxx.gr/....../handlePaymentResult.php',
            'cardProcessorFailure'      =>  'http://xxxxxx.gr/....../handlePaymentResult.php',
     ),
     */
);


// Application specific requirements, autoloaders, etc

// Custom 'showStopperErrorRoutine' routines

function  showStopperErrorRoutineDev( WOOOF $wo, $errorInput, $uri = NULL ) {
	var_dump($errorInput, $_SERVER['REQUEST_URI']); // die();
	if ( !$wo->hasContent($uri) ) {
		$uri = $_SERVER['REQUEST_URI'];
	}
	
	showStopperErrorRoutineProd( $wo, $errorInput, $uri );
	die();
}

function  showStopperErrorRoutineProd( WOOOF $wo, $errorInput, $uri = NULL ) {
	//var_dump($errorInput); die();
	$err = ( is_array($errorInput) ? $errorInput[0] : $errorInput );
	$code = mb_substr($err, 0, 3);
	$message = mb_substr($err, 4);
	header('Location: '. $wo->assetsURL . "error.php?c=$code&m=$message&u=".urlencode($uri) );
	die();
}


// A custom 'initApplicationRoutine' routine

function initAppMOVIERAMA( WOOOF $wo ) {
	$wo->debug("Initialising MOVIERAMA App...");
	
	$appObject = new VO_App();
	$userId = $appObject->initFor($wo );
	if ( $userId === FALSE ) { return FALSE; }
	
	$wo->app = $appObject;
	
	spl_autoload_register(
		function($className) {
			VO_App::handleClassAutoloader($className, WOOOF::$instance );
		}
	);

	return TRUE;
}

function wooofTimerStart( $name, $description='' ) {
	global $executionTimers;
	$executionTimers[$name][0] = microtime(true);
	$executionTimers[$name][2] = $description;
}

function wooofTimerStop( $name ) {
	global $executionTimers;
	$executionTimers[$name][1] = microtime(true);
}

function wooofTimersShow() {
	global $executionTimers;
	foreach( $executionTimers as $aName => $aStartEnd ) {
		if ( !isset($aStartEnd[0]) ) { continue; } // TODO: issue a warning
		$wasStopped = true;
		if ( !isset($aStartEnd[2]) ) { $aStartEnd[2] = ''; }
		if ( !isset($aStartEnd[1]) ) { $aStartEnd[1] = microtime(true); $wasStopped = false; }
		echo "$aName ({$aStartEnd[2]}): " . round( ($aStartEnd[1] - $aStartEnd[0] )*1000 )  . ' ms'.
		( !$wasStopped ? ' (stopped at the end!)' : '<br>' )
		;
	}
	
}