<?php

class VO_CtrlMovie {
	const _ECP = 'CMO';	// Error Code Prefix
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in
	 * @return false | [ 'movieDeletedOk' => bool, 'movieDeletedId' => string ]
	 */
	
	public static function deleteMovie( WOOOF $wo, $in ) {
	
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		if(!$wo->hasContent($in['id']))  {
			$wo->logError(self::_ECP."5185 You must provide a id for the movie to delete.");
			return false;
		}
		
		$res =  VO_Movies::deleteMovie($wo, $in['id']);
	
		if ( $res === FALSE ) {
			$out = [
				'movieDeletedOk' => false,
				'errors' 		 => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'movieDeletedOk'	=> true,
				'movieDeletedId'	=> $res
			];
			$wo->db->commit();
		}
	
		return $out;
	
	}	//createGroup
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 * 
	 * @param WOOOF $wo
	 * @param array $in
	 * @return false | [ 'movieCreatedOk' => bool, 'movieCreatedId' => string ]
	 */
	
	public static function createMovie( WOOOF $wo, $in ) {
		
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		$errors = [];
		if(!$wo->hasContent($in['title']))  {
			$errors[] = "You must provide a title for the movie.";
		}
		
		if(!$wo->hasContent($in['description'])) {
			$errors[] = "You must provide a description for the movie.";
		}
		
		if($wo->hasContent($errors)) {
			$out = [
				'movieCreatedOk' => false,
				'errors' 		 => $errors
			];
			return $out;
		}
	
		$res =  VO_Movies::saveMovie($wo, $in);
	
		if ( $res === FALSE ) {
			$out = [
				'movieCreatedOk' => false,
				'errors' 		 => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'movieCreatedOk'	=> true,
				'movieCreatedId'	=> $res
			];
			$wo->db->commit();
		}
	
		return $out;

	}	//createGroup
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	public static function createMovieViewForm( WOOOF $wo ) {
	
		$requestedAction='viewUncontroled';
		$pageLocation='3';
		$browserTitle='MovieRama Movie Creation Page';
		$wo = WOOOF::getWOOOF($pageLocation, $requestedAction, $wo);
		if ( $wo === FALSE ) { die('Failed to getWOOOF()'); }
		
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		$userId = $wo->app->userId;
		
		$content = <<<EOH
		<div id='content-main'></div>
				
		<script>
			ReactDOM.render(
				React.createElement(
					CreateMovieForm,
					{ data: '$userId' }
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
	
	}	//createMovieViewForm
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param string $movieId
	 * @return false | edit movie page
	 */
	public static function editMovie( WOOOF $wo, $movieId ) {
	
		$requestedAction='viewUncontroled';
		$pageLocation='3';
		$browserTitle='MovieRama Movie Edit Page';
		$wo = WOOOF::getWOOOF($pageLocation, $requestedAction, $wo);
		if ( $wo === FALSE ) { die('Failed to getWOOOF()'); }
		
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		$requestedMovie = $wo->db->getRow('movierama_user_movies', $movieId);
		if($requestedMovie === FALSE) { return false; }
		if($requestedMovie === NULL) {
			$wo->logError(self::_ECP."5085 No row with id [$movieId] found in movies table!");
			return false;
		}
		
		//check if you really have the edit privileges
		$isSelfMovieOwner = VO_Movies::isUserMovieCreator($wo, $wo->app->userId, $movieId);
		if($isSelfMovieOwner === FALSE) { return false; }
		if($isSelfMovieOwner === '0') {
			$wo->logError(self::_ECP."5087 I am sorry you cannot edit this movie you are not the creator.");
			return false;
		}
		
		$list = json_encode($requestedMovie);
	
		$movieTitle = json_encode($requestedMovie['title']);
	
		$content = <<<EOH
		<div id='content-main'></div>
	
		<script>
			var list = $list;
			var movieTitle = $movieTitle;
			ReactDOM.render(
				React.createElement(
					MovieEdit,
					{ data: list, title: movieTitle }
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
	
	}	//editGroup
	
	/***************************************************************************/
	
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $movieId (movie id to like)
	 * @return false | [ 'likeOk' => bool, 'likedRecId' => id, 'errors' => array ]
	 */
	public static
	function like( WOOOF $wo, $movieId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		if( !$wo->hasContent($movieId) ) {
			$wo->logError(self::_ECP."4102 You must provide a movie ID to like!");
			return false;
		}
	
		//CHECK IF THE MOVIE IS THE USER'S
		$isUserMovieCreator = VO_Movies::isUserMovieCreator($wo, $wo->app->userId, $movieId);
		
		if($isUserMovieCreator === '1') {
			$out = [
				'likeOk' => false,
				'errors' => 'You can not like your own movie'
			];
			return $out;
		}
	
		$res = VO_Movies::likeMovie($wo, $wo->app->userId, $movieId);
		
		if ( $res === FALSE ) {
			$out = [
				'likeOk' => false,
				'errors' => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
		
		else {
			$out = [
				'likeOk'		=> true,
				'likedRecId'	=> $res
			];
			$wo->db->commit();
		}
		
		return $out;
		
	}	// like
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $opinionId (opinion id to delete)
	 * @return false | [ 'unLikeOk' => bool, 'unLikeRecId' => id, 'errors' => array ]
	 */
	public static
	function unLike( WOOOF $wo, $opinionId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
		
		if( !$wo->hasContent($opinionId) ) {
			$wo->logError(self::_ECP."4112 You must provide an [opinionId] to delete!");
			return false;
		}
		
		$res = VO_Movies::deleteOpinion($wo, $opinionId, 'unLike');
		
		if ( $res === FALSE ) {
			$out = [
				'unLikeOk' => false,
				'errors' => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'unLikeOk'			=> true,
				'unLikeRecId'	=> $res
			];
			$wo->db->commit();
		}
	
		return $out;
	
	}	// unLike
	
	/***************************************************************************/
	
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $movieId (movie id to hate)
	 * @return false | [ 'hateOk' => bool, 'hatedRecId' => id, 'errors' => array ]
	 */
	public static
	function hate( WOOOF $wo, $movieId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		if( !$wo->hasContent($movieId) ) {
			$wo->logError(self::_ECP."4112 You must provide a movie ID to hate!");
			return false;
		}
	
		//CHECK IF THE MOVIE IS THE USER'S
		$isUserMovieCreator = VO_Movies::isUserMovieCreator($wo, $wo->app->userId, $movieId);
	
		if($isUserMovieCreator === '1') {
			$out = [
				'hateOk' => false,
				'errors' => 'You can not hate your own movie'
			];
			return $out;
		}
	
		$res = VO_Movies::hateMovie($wo, $wo->app->userId, $movieId);
	
		if ( $res === FALSE ) {
			$out = [
				'hateOk' => false,
				'errors' => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'hateOk'		=> true,
				'hatedRecId'	=> $res
			];
			$wo->db->commit();
		}
	
		return $out;
	
	}	// hate
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $opinionId (opinion id to delete)
	 * @return false | [ 'unHateOk' => bool, 'unHateRecId' => id, 'errors' => array ]
	 */
	public static
	function unHate( WOOOF $wo, $opinionId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		if( !$wo->hasContent($opinionId) ) {
			$wo->logError(self::_ECP."4116 You must provide an [opinionId] to delete!");
			return false;
		}
	
		$res = VO_Movies::deleteOpinion($wo, $opinionId, 'unHate');
	
		if ( $res === FALSE ) {
			$out = [
				'unHateOk' => false,
				'errors' => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'unHateOk'			=> true,
				'unHateRecId'	=> $res
			];
			$wo->db->commit();
		}
	
		return $out;
	
	}	// unHate
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param $in: 
	 * -> id $requestorUserId (id of user that requested to see his/her opinions with other movies)
	 * -> array $targetIds (ids of movies to see the opinion status)
	 * @return [ 'getOpinionsOk' => bool, 'errors' => array, 'getOpinions' => array ]
	 */
	public static
	function opinionsInfo( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
		
		if( !$wo->hasContent($in['requestorId']) ) {
			$wo->logError(self::_ECP."4126 You must provide a [requestorId].");
			return false;
		}
		
		if( !$wo->hasContent($in['targetIds']) ) {
			$wo->logError(self::_ECP."4136 You must provide [targetIds].");
			return false;
		}
	
		$res = VO_Movies::getOpinionsInfo($wo, $in['requestorId'], $in['targetIds']);
	
		if ( $res === FALSE ) {
			$out = [
				'getOpinionsOk' => false,
				'errors' => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'getOpinionsOk'	=> true,
				'getOpinions'	=> array_values($res)
			];
			$wo->db->commit();
		}
	
		return $out;
	}	// opinionsInfo
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param string $movieId
	 * @return [ 'getCountersOk' => bool, 'errors' => array, 'getCounters' => array ]
	 */
	public static
	function countersInfo( WOOOF $wo, $movieId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
		
		if( !$wo->hasContent($movieId) ) {
			$wo->logError(self::_ECP."4166 You must provide [movieId] to compute its votes.");
			return false;
		}
	
		$res = VO_Movies::getCountersInfo($wo, $movieId);
	
		if ( $res === FALSE ) {
			$out = [
				'getCountersOk' => false,
				'errors' => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'getCountersOk'	=> true,
				'getCounters'	=> $res
			];
			$wo->db->commit();
		}
	
		return $out;
	}	// countersInfo
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in
	 * @return false | [ 'flagItemOk' => bool, 'flagItemRecType' => string, 'errors' => array ]
	 */
	public static
	function flagMovie( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		if( !$wo->hasContent($in['whatId']) ) {
			$wo->logError(self::_ECP."4196 You must provide a movie ID to report!");
			return false;
		}
	
		if( !$wo->hasContent($in['whatType']) ) {
			$wo->logError(self::_ECP."4197 You must provide a target type to report!");
			return false;
		}
	
		if( !$wo->hasContent($in['flagText']) ) {
			$wo->logError(self::_ECP."4198 You must provide flag text!");
			return false;
		}
	
		$res = VO_FlagItem::saveFlagItem($wo, $wo->app->userId, $in);
	
		if ( $res === FALSE ) {
			$out = [
				'flagItemOk' => false,
				'errors' => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'flagItemOk'	  => true,
				'flagItemRecType' => $res
			];
			$wo->db->commit();
		}
	
		return $out;
	
	}	// flagMovie
	
}	// VO_CtrlMovie