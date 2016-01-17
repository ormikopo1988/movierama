<?php 

	/*******************************************************************/
	// Movies
	//
	// movie/createForm
	$router->map(
		'GET',
		'/movie/createForm',
		function() use($wo) {
			VO_CtrlMovie::createMovieViewForm($wo);
		}
		, 'createMovieViewForm'
	);
	
	// movie/edit/[a:movieId]
	$router->map(
		'GET',
		'/movie/edit/[a:movieId]',
		function($movieId) use($wo) {
			VO_CtrlMovie::editMovie($wo, $movieId);
		}
		, 'editMovie'
	);
	
	// movies/sort/[a:sortCriteria]
	//
	$router->map(
		'GET',
		'/movies/sort/[a:sortCriteria]',
		function($sortCriteria) use($wo) {
			switch($sortCriteria) {
				case "noOfLikes":
					$criteria = "MUM_noOfLikes";
					break;
				case "noOfHates":
					$criteria = "MUM_noOfHates";
					break;
				case "publishDateTime":
					$criteria = "MUM_publishDateTime";
					break;
				default:
					$wo->handleShowStopperError('505 Unknown sort criteria');
			}
			VO_CtrlSite::home($wo, $criteria);
		}
		, 'sortMoviesByCriteria'
	);
	
	// movies/sort/[a:sortCriteria]
	//
	$router->map(
		'GET',
		'/movies/[a:userId]',
		function($userId) use($wo) {
			VO_CtrlSite::home($wo, '', $userId);
		}
		, 'userMovies'
	);
	
	// movies/sort/[a:sortCriteria]
	//
	$router->map(
		'GET',
		'/movies/[a:userId]/sort/[a:sortCriteria]',
		function($userId, $sortCriteria) use($wo) {
			switch($sortCriteria) {
				case "noOfLikes":
					$criteria = "MUM_noOfLikes";
					break;
				case "noOfHates":
					$criteria = "MUM_noOfHates";
					break;
				case "publishDateTime":
					$criteria = "MUM_publishDateTime";
					break;
				default:
					$wo->handleShowStopperError('505 Unknown sort criteria');
			}
			VO_CtrlSite::home($wo, $criteria, $userId);
		}
		, 'userMoviesSorted'
	);
	
	/******************* API Calls **************************************/
	// api/movie/create
	$router->map('POST', '/api/movie/create', function() use($wo) {
		try {
			$res = VO_CtrlMovie::createMovie($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// /api/movie/like/[a:movieId]
	$router->map('POST', '/api/movie/like/[a:movieId]', function($movieId) use($wo) {
		try {
			$res = VO_CtrlMovie::like($wo, $movieId);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage());
		}
		WOOOF_Util::returnJSON2($wo, $res);
	});
		
	// /api/movie/unlike/[a:opinionId]
	$router->map('POST', '/api/movie/unLike/[a:opinionId]', function($opinionId) use($wo) {
		try {
			$res = VO_CtrlMovie::unLike($wo, $opinionId);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
		
	// /api/movie/hate/[a:movieId]
	$router->map('POST', '/api/movie/hate/[a:movieId]', function($movieId) use($wo) {
		try {
			$res = VO_CtrlMovie::hate($wo, $movieId);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
		
	// /api/movie/unhate/[a:opinionId]
	$router->map('POST', '/api/movie/unHate/[a:opinionId]', function($opinionId) use($wo) {
		try {
			$res = VO_CtrlMovie::unHate($wo, $opinionId);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// /api/movie/getOpinionsInfo -> requestorUserId, $targetIds[]
	$router->map('POST', '/api/movie/getOpinionsInfo', function() use($wo) {
		try {
			$res = VO_CtrlMovie::opinionsInfo($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// /api/movie/getCounters/[a:movieId] -> requestorUserId, $targetIds[]
	$router->map('POST', '/api/movie/getCounters/[a:movieId]', function($movieId) use($wo) {
		try {
			$res = VO_CtrlMovie::countersInfo($wo, $movieId);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// /api/movie/flag
	$router->map('POST', '/api/movie/flag', function() use($wo) {
		try {
			$res = VO_CtrlMovie::flagMovie($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// /api/movie/saveMovie
	$router->map('POST', '/api/movie/saveMovie', function() use($wo) {
		try {
			$res = VO_CtrlMovie::createMovie($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});
	
	// /api/movie/delete
	$router->map('POST', '/api/movie/delete', function() use($wo) {
		try {
			$res = VO_CtrlMovie::deleteMovie($wo, $_POST);
		}
		catch ( Exception $e ) { $res = false; $wo->logError($e->getMessage()); }
		WOOOF_Util::returnJSON2($wo, $res);
	});


/* End of file movies.php */

