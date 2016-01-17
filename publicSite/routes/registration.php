<?php 

	/*******************************************************************/
	// register
	//
	$router->map(
		'GET',
		'/register',
		function() use($wo) {
			VO_CtrlRegistration::register($wo);
		}
		, 'register'
	);
	
	$router->map(
		'GET',
		'/register/verify',
		function() use($wo) {
			VO_CtrlRegistration::registerVerify($wo);
		}
		, 'registerVerify'
	);
	
	/*******************************************************************/
	// login
	//
	$router->map(
		'GET',
		'/login',
		function() use($wo) {
			VO_CtrlSite::login($wo);
		}
		, 'login'
	);
	
	/*******************************************************************/
	// logout
	//
	$router->map(
		'GET',
		'/logout',
		function() use($wo) {
			VO_CtrlSite::logout($wo);
		}
		, 'logout'
	);
	
	// API (JSON)
	//
	
	/********************** API calls *********************************************/
	// Registration
	
	// api/registration/save
	$router->map('POST', '/api/registration/do', function() use($wo) {
		try {
			$res = VO_CtrlRegistration::saveRegistration($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});

	// api/login/do
	$router->map('POST', '/api/login/do', function() use($wo) {
		try {
			$res = VO_CtrlRegistration::loginDo($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// api/password/reset
	$router->map('POST', '/api/password/reset', function() use($wo) {
		try {
			$res = VO_CtrlRegistration::resetPassword($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
		
	// api/verificationToken/resend
	$router->map('POST', '/api/verificationToken/resend', function() use($wo) {
		try {
			$res = VO_CtrlRegistration::resendToken($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
/* End of file registration.php */
	
