<?php 

	/*******************************************************************/
	// createEvaluation
	//
	$router->map(
		'GET',
		'/createEvaluation/MOV/[a:whatId]',
		function($whatId) use($wo) {
			VO_CtrlEvaluation::createEvaluationForm($wo, 'MOV', $whatId);
		}
		, 'createEvaluation'
	);
	
	// editEvaluation
	//
	$router->map(
		'GET',
		'/evaluation/edit/MOV/[a:evaluationId]',
		function($evaluationId) use($wo) {
			VO_CtrlEvaluation::editEvaluationForm($wo, $evaluationId, 'MOV');
		}
		, 'editEvaluation'
	);
	
	// viewEvaluations
	//
	$router->map(
		'GET',
		'/evaluations/view/MOV/[a:whatId]',
		function($whatId) use($wo) {
			VO_CtrlEvaluation::viewEvaluations($wo, $whatId, 'MOV');
		}
		, 'viewEvaluations'
	);
	
	// viewEvaluations
	//
	$router->map(
		'GET',
		'/evaluation/results/[a:evaluationId]',
		function($evaluationId) use($wo) {
			VO_CtrlEvaluation::viewEvaluationResults($wo, $evaluationId);
		}
		, 'viewEvaluationResults'
	);
	
	// API (JSON)
	//
	
	/********************** API calls *********************************************/
	// Evaluations

	// api/evaluation/create
	$router->map('POST', '/api/evaluation/create', function() use($wo) {
		try {
			$res = VO_CtrlEvaluation::createEvaluation($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// api/evaluation/open
	$router->map('POST', '/api/evaluation/open', function() use($wo) {
		try {
			$res = VO_CtrlEvaluation::openEvaluation($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// api/evaluation/close
	$router->map('POST', '/api/evaluation/close', function() use($wo) {
		try {
			$res = VO_CtrlEvaluation::closeEvaluation($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// api/evaluation/getEvalCriteria
	$router->map('POST', '/api/evaluation/getEvalCriteria', function() use($wo) {
		try {
			$res = VO_CtrlEvaluation::getEvaluationCriteria($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// api/evaluation/submitEvaluation
	$router->map('POST', '/api/evaluation/submitEvaluation', function() use($wo) {
		try {
			$res = VO_CtrlEvaluation::submitEvaluation($wo, json_decode($_POST['data'], true));
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
/* End of file evaluations.php */
	
