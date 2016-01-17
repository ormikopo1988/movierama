<?php 

	// index.php
	
	$executionTimers['wooof_1'][0] = microtime(true);

	require_once '../setup.inc.php';
	
	$wo = new WOOOF(true, null, null, false);
  	if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }

  	wooofTimerStop('wooof_1');
  	
	$router = new AltoRouter();
	$router->setBasePath( substr( $wo->getConfigurationFor('siteBaseURL') . $wo->getConfigurationFor('publicSite'), 0, -1 ) );
	
	/*******************************************************************/
	
	// the start/home
	//
	$router->map(
		'GET',
		'/',
		function() use($wo) {
			VO_CtrlSite::home($wo);
		}
		, 'home'
	);
	
	require_once 'routes/movies.php';
	require_once 'routes/profile.php';
	require_once 'routes/registration.php';
	require_once 'routes/evaluations.php';
	
	/*
	 ==========================================================================
	 */

	// Find and follow route based on URL
	// Handling of no matches, etc. inside the 'run' function.
	 
	$router->run($wo);

/* End of file index.php */

