<?php 

	/*******************************************************************/
	// Profile
	//
	// profile/edit
	$router->map(
		'GET',
		'/profile/edit',
		function() use($wo) {
			VO_CtrlProfile::profileEdit($wo);
		}
		, 'profileEdit'
	);
	
	/******************* API Calls **************************************/
	// Profile (MovieRama User)
	//
	// Gets ==========================================================

	// /api/profile/getMainInfo
	$router->map('GET', '/api/profile/getMainInfo', function() use($wo) {
		try {
			$res = VO_ProfileData::getMainInfo($wo, $wo->app->userId);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// api/profile/changePassword
	$router->map('POST', '/api/profile/changePassword', function() use($wo) {
		try {
			$res = VO_CtrlProfile::changePassword($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// api/profile/mainInfoSave
	$router->map('POST', '/api/profile/mainInfoSave', function() use($wo) {
		try {
			$res = VO_CtrlProfile::mainInfoSave($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});


/* End of file profile.php */

