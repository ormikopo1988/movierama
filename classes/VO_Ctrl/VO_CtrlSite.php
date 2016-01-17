<?php



class VO_CtrlSite {
	const _ECP = 'SIT';	// Error Code Prefix
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 * 
	 * @param WOOOF $wo
	 * @param string $sortCriteria = '' -> criteria to sort the movies
	 * @param string $targetUserId = '' -> userId to see movies from
	 */
	public static 
	function home( WOOOF $wo, $sortCriteria='', $targetUserId='' ) {
		
		$requestedAction='viewUncontroled';
		$pageLocation='3';
		$browserTitle='Welcome to MovieRama!';
		$wo = WOOOF::getWOOOF($pageLocation, $requestedAction, $wo);
		if ( $wo === FALSE ) { die('Failed to getWOOOF()'); }
	
		//not logged in user
		if ( $wo->userData['id'] == '0123456789' ) {
			$userData = [];
			
			$requestorUserId = '';
			
			$isloggedIn = 'false';
		}
		
		//logged in user
		else {
			$userData =  VO_ProfileData::getMainInfo($wo, $wo->app->userId);
			if ( $userData === FALSE ) {
				$wo->handleShowStopperError('500 Failed to get Profile Data');
			}
			
			$requestorUserId = $wo->app->userId;
			
			$isloggedIn = 'true';
		}
		
		$movies = VO_Movies::getMovies($wo, $requestorUserId, $isloggedIn, $sortCriteria, $targetUserId);
		if($movies === FALSE) { return false; }
		
		$userData = json_encode( $userData );
		$movies = json_encode( $movies );
		
		$content = <<<EOH
		<div id='content-main'></div>
	
		<script>
			var movies = $movies;
			var isLoggedIn = $isloggedIn;
			var targetUserId = '$targetUserId';
					
			ReactDOM.render(
				React.createElement(
					ObjectsList,
					{ data: movies, title: 'MovieRama Movies', isType: 'MOV',
					autocompleteUrl: 'movies', isLoggedIn: isLoggedIn, targetUserId: targetUserId }
				),
				document.getElementById('content-main')
			);
		</script>
EOH
		;
		
		$tpl = array(
			'browserTitle'	=> $browserTitle,
			'content' 		=> $content,
			'errorMessage'	=> '',
			'message'		=> '',
		);
		
		
		$wo->fetchApplicationFragment('structural/template.php', [], $tpl);
		
	}	// home
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	public static function login( WOOOF $wo ) {
	
		$requestedAction='viewUncontroled';
		$pageLocation='3';
		$browserTitle='MovieRama Login Page';
		$wo = WOOOF::getWOOOF($pageLocation, $requestedAction, $wo);
		if ( $wo === FALSE ) { die('Failed to getWOOOF()'); }
	
		if ( $wo->userData['id'] != '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
		
		$content = <<<EOH
		<div id='content-main'></div>
				
		<script>
			ReactDOM.render(
				React.createElement(
					LoginBox, 
					{ nextRoute: "" }
				), 
				document.getElementById('content-main')
			);
		</script>
EOH
	;
	
		$tpl = array(
				'browserTitle'	=> $browserTitle,
				'content' 		=> $content,
				'errorMessage'	=> '',
				'message'		=> '',
		);
		
		
		$wo->fetchApplicationFragment('structural/template.php', [], $tpl);
	
	}	//login

	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 */
	public static
	function logout( WOOOF $wo )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		
		$wo->invalidateSession();
		
		header( "Location:  " . $wo->assetsURL );
	}	// logout
	
}	// VO_CtrlSite