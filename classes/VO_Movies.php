<?php

class VO_Movies {
	const _ECP = 'MOV';	// Error Code Prefix
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 * 
	 * @param WOOOF $wo
	 * @param VO_TblMovieRamaUserMovies $obj
	 * @param string $creatorId
	 * @return false | id
	 * 
	 */
	public static 
	function save( WOOOF $wo, VO_TblMovieRamaUserMovies &$obj, $creatorId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  " );
		
		if(!$wo->hasContent($creatorId)) {
			$wo->logError(self::_ECP."0654 No value provided for [creatorId]" );
			return false;
		}
		
		$tblMovieRamaUserMovies = new WOOOF_dataBaseTable($wo->db, 'movierama_user_movies');
		if(!$tblMovieRamaUserMovies->constructedOk) { return false; }
		
		if(!$wo->hasContent($obj->id)) {
			//insert
			$obj->publishDateTime = $wo->currentGMTDateTime();
			
			// insert new movie in table
			$newId = $tblMovieRamaUserMovies->insertRowFromArraySimple( $obj->toArray() );
			if ( $newId === FALSE ) { return false; }
			$obj->id = $newId;
		}
		
		else {
			//update
			$updatedId = $tblMovieRamaUserMovies->updateRowFromArraySimple( $obj->toArray() );
			if ( $updatedId === FALSE ) { return false; }
			$obj->id = $updatedId;
		}
	  	
		return $obj->id;
	}	// save
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in
	 * @return false | id
	 */
	public static
	function saveMovie( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  SaveMovie" );
		
		if(!$wo->hasContent($in['id'])) {
			//insert movie
			$tblMovieInsert = new VO_TblMovieRamaUserMovies();
			$tblMovieInsert->title = $in['title'];
			$tblMovieInsert->description = $in['description'];
			$tblMovieInsert->movieramaUserId = $wo->app->userId;
			
			$res = self::save($wo, $tblMovieInsert, $wo->app->userId);
			if($res === FALSE) { return false; }
		} 
		else {
			// update the movie
			$oldMovie = $wo->db->getRow('movierama_user_movies', $in['id']);
			if($oldMovie === FALSE) { return false; }
			if($oldMovie === NULL) {
				$wo->logError(self::_ECP."4441 No movie found to update");
				return false;
			}
			
			$tblMovieUpdate = new VO_TblMovieRamaUserMovies($oldMovie);
			$tblMovieUpdate->title = $in['title'];
			$tblMovieUpdate->description = $in['description'];
			
			$res = self::save($wo, $tblMovieUpdate, $wo->app->userId);
			if($res === FALSE) { return false; }
						
			$res = $in['id'];	// just for uniformity with insert
		}
		
		return $res;
	}	// saveMovie
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param string $movieId
	 * @return false | id
	 */
	public static
	function deleteMovie( WOOOF $wo, $movieId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  deleteMovie" );
	
		if ( !$wo->hasContent($movieId) ) {
			$wo->logError(self::_ECP."6311 No value provided for [deleteId]" );
			return false;
		}
		
		$movieRec = $wo->db->getRow('movierama_user_movies', $movieId);
		if($movieRec === FALSE) { return false; }
		if($movieRec === NULL) {
			$wo->logError(self::_ECP."6312 No movie found to delete with id [$movieId]." );
			return false;
		}
		
		//first delete the votes of the movie (if there are any)
		$tblUserVotes = new WOOOF_dataBaseTable($wo->db, 'movierama_user_movies_opinions');
		if(!$tblUserVotes->constructedOk) { return false; }
		
		$movieVotes = $tblUserVotes->getResult(
			[
				'movieId'   => $movieId,
				'isDeleted'	=> '0'
			],
			'',
			'', '', '',
			true, false
		);
		if ( $movieVotes === FALSE ) { return false; }
		
		$hasVotes = ($movieVotes['totalRows'] != 0);
		
		if($hasVotes) {
			foreach ($tblUserVotes->resultRows as $aVote) {
				$deleteVoteRes = $tblUserVotes->deleteRow($aVote['id']);
				if($deleteVoteRes === FALSE) { return false; }
				if($deleteVoteRes === NULL) { return false; }
			}
		}
		
		//then delete the movie from the table
		$tblUserMovies = new WOOOF_dataBaseTable($wo->db, 'movierama_user_movies');
		if(!$tblUserMovies->constructedOk) { return false; }
	
		$deleteRes = $tblUserMovies->deleteRow($movieId);
		if($deleteRes === FALSE) { return false; }
		if($deleteRes === NULL) { return false; }
	
		return $movieId;
	}	// deleteMovie
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param string $requestorUserId = ''
	 * @param bool $isUserLoggedIn
	 * @param string $sortCriteria = ''
	 * @param string $targetUserId = ''
	 * @return array of movies
	 */
	public static
	function getMovies( WOOOF $wo, $requestorUserId='', $isUserLoggedIn, $sortCriteria='', $targetUserId='' )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  Get Movies" );
		
		$movies = [];
		$movieElems = ['MUM_id', 'MUM_movieramaUserId', 'MUM_title', 'MUM_description', 'MUM_publishDateTime', 'MUM_noOfLikes', 'MUM_noOfHates', 'VUS_username', 'VUS_avatarImg', 'PPR_firstName', 'PPR_lastName'];
		
		$tblViewMovieRamaUserMovies = new WOOOF_dataBaseTable($wo->db, 'v_movierama_user_movies');
		if(!$tblViewMovieRamaUserMovies->constructedOk) { return false; }
		
		//movies from all users
		if(!$wo->hasContent($targetUserId)) {
			//all movies without sorting filter
			if(!$wo->hasContent($sortCriteria)) {
				$result = $wo->db->query("SELECT * FROM v_movierama_user_movies");
			}
			
			//all movies with sorting filter
			else {
				$result = $wo->db->query("SELECT * FROM v_movierama_user_movies ORDER BY $sortCriteria DESC");
			}	
		}
		
		//movies from a specific user
		else {
			//all movies from a user without sorting filter
			if(!$wo->hasContent($sortCriteria)) {
				$result = $wo->db->query("SELECT * FROM v_movierama_user_movies WHERE MUM_movieramaUserId='$targetUserId'");
			}
			
			//all movies from a user with sorting filter
			else {
				$result = $wo->db->query("SELECT * FROM v_movierama_user_movies WHERE MUM_movieramaUserId='$targetUserId' ORDER BY $sortCriteria DESC");
			}	
		}
		
		if ( $result === FALSE ) { return false; }
		
		if (!$wo->db->getNumRows($result))
		{
			//no error no results
			return [];
		}
		else
		{
			//no error results
			while($row = $wo->db->fetchAssoc($result))
			{
				$tblViewMovieRamaUserMovies->resultRows[] = $row;
			}
		}
		
		$ids = [];
		
		//not logged in
		if($isUserLoggedIn === 'false') {
			foreach($tblViewMovieRamaUserMovies->resultRows as $v_movie) {
				$movie = [];
				$movieId = $v_movie['MUM_id'];
				WOOOF_Util::filterOnKeys($movie, $movieElems, $v_movie);
				$movie['isType'] = 'MOV';
					
				$movies[$movieId] = $movie;
			}
			
			//convert the associative array to a simple array to come back to the frontend
			$movies = array_values($movies);
			
			return $movies;
		}
		
		//logged in
		else {
			foreach($tblViewMovieRamaUserMovies->resultRows as $v_movie) {
				$movie = [];
				$movieId = $v_movie['MUM_id'];
				WOOOF_Util::filterOnKeys($movie, $movieElems, $v_movie);
				$movie['isType'] = 'MOV';
					
				$isUserMovieCreator = self::isUserMovieCreator($wo, $wo->app->userId, $v_movie['MUM_id']);
				if($isUserMovieCreator === FALSE) { return false; }
				
				if($isUserMovieCreator === '1') {
					$movie['isSelfMovieCreator'] = true;
				} else {
					$movie['isSelfMovieCreator'] = false;
				}
				
				$hasRunningEvals = VO_Evaluation::hasRunningEvaluations($wo, $movieId, 'MOV', $wo->app->userId);
				if($hasRunningEvals === FALSE) { return false; }
				
				$runningEvalId = '';
				if($hasRunningEvals === 1) {
					//go get last running evaluation id
					$runningEvalId = VO_Evaluation::getLastRunningEvaluation($wo, $movieId, 'MOV', $wo->app->userId);
					if($runningEvalId === FALSE) { return false; }
				}
				
				$movie['hasRunningEvals'] = $hasRunningEvals;
				$movie['runningEvalId'] = $runningEvalId;
				
				$movies[$movieId] = $movie;
			}
			
			//pass the keys from $movies to $ids
			$ids = array_keys($movies);
			
			//make the opinions array
			$opinionInfoArray = self::getOpinionsInfo($wo, $wo->app->userId, $ids);
			if($opinionInfoArray === false) { return false; }
			
			//merge the movies array (with main info) with the opinions array
			foreach($movies as $aKey => &$aMovie) {
				$aMovie['opinions'] = $opinionInfoArray[$aKey];
			}
			
			//convert the associative array to a simple array to come back to the frontend
			$movieOpinions = array_values($movies);
				
			//return $movieOpinions;
			return $movieOpinions;
		}
	}	// getMovies
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param string $movieramaUserId
	 * @param string $movieId
	 * @return false | 0 or 1
	 */
	public static
	function isUserMovieCreator( WOOOF $wo, $movieramaUserId, $movieId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$sql = "SELECT 1 FROM movierama_user_movies WHERE id='$movieId' and isDeleted='0' and movieramaUserId='$movieramaUserId'";
		$res = $wo->db->query($sql);
		if ( $res === FALSE ) { return FALSE; }
		
		return ($wo->db->getNumRows($res) === 0 ? '0' : '1');
	}	// isUserMovieCreator
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $requestorUserId
	 * @param array $targetIds
	 * @return false | array[ id, ... , ]
	 */
	public static
	function getOpinionsInfo( WOOOF $wo, $requestorUserId, $targetIds )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  $requestorUserId" );
	
		if ( $requestorUserId === NULL ) {
			// possible for API calls
			return [];
		}
		
		if(!$wo->hasContent($requestorUserId)) {
			$wo->logError(self::_ECP."2285 You must provide a [requestorUserId]");
			return false;
		}
	
		if(!$wo->hasContent($targetIds)) {
			$wo->logError(self::_ECP."2286 You must provide [targetIds]");
			return false;
		}
	
		$movieramaUserOpinionsStats = new WOOOF_dataBaseTable($wo->db, 'movierama_user_movies_opinions');
		if ( !$movieramaUserOpinionsStats->constructedOk ) { return false; }
	
		$statuses = [];
	
		foreach ($targetIds as $aTargetId) {
			$status = [];
			
			//if you are the owner you have no options available to vote your own movies
			$isUserMovieCreator = self::isUserMovieCreator($wo, $requestorUserId, $aTargetId);
			if($isUserMovieCreator === FALSE) { return false; }
				
			if($isUserMovieCreator === '1') {
				$statuses[$aTargetId] = [];
				continue;
			}
			
			// BUILD opinionStatus array
			
			// First check if the requestor has already gave his/her opinion for the target movie
			$hasGivenOpinion = $movieramaUserOpinionsStats->getResult(
				[
					'movieramaUserId' => $requestorUserId,
					'movieId'	      => $aTargetId,
					'isDeleted'       => '0'
				],
				'', '', '', '', true, false
			);
			
			if ( $hasGivenOpinion === FALSE ) { return false; }
			
			$requestorHasGivenOpinion = $hasGivenOpinion['totalRows'] != 0;
			
			//no opinion at all
			if(!$requestorHasGivenOpinion) {
				$status['opinionStatus'] = 'N';
				$status['likeLink'] = 'api/movie/like/'.$aTargetId;
				$status['hateLink'] = 'api/movie/hate/'.$aTargetId;
			}
			
			//has given an opinion already
			else {
				$opinionId = $movieramaUserOpinionsStats->resultRows[0]['id'];
				
				$opinion = $movieramaUserOpinionsStats->resultRows[0]['isPositive'];
				
				//case 1: user likes movie
				if($opinion === '1') {
					$status['opinionStatus'] = 'L';
					$status['opinionLink'] = 'api/movie/unLike/'.$opinionId;
					$status['hateLink'] = 'api/movie/hate/'.$aTargetId;
				}
				//case 2: user hates movie
				else if($opinion === '0') {
					$status['opinionStatus'] = 'H';
					$status['opinionLink'] = 'api/movie/unHate/'.$opinionId;
					$status['likeLink'] = 'api/movie/like/'.$aTargetId;
				}
				
			}
				
			$status['targetId'] = $aTargetId;
			
			$statuses[$aTargetId] = $status;
		}
	
		return $statuses;
	} //getOpinionsInfo
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param string $movieId
	 * @return false | array
	 */
	public static
	function getCountersInfo( WOOOF $wo, $movieId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  Get Counters Info" );
	
		if ( $movieId === NULL ) {
			// possible for API calls
			return [];
		}
	
		if(!$wo->hasContent($movieId)) {
			$wo->logError(self::_ECP."2295 You must provide a [movieId]");
			return false;
		}
	
		$movieRec = $wo->db->getRow('movierama_user_movies', $movieId);
		if($movieRec === FALSE) { return false; }
		
		$movieVotes = [
			'likes' => $movieRec['noOfLikes'],
			'hates' => $movieRec['noOfHates']
		];
			
		return $movieVotes;
	} //getCountersInfo
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $fromUserId
	 * @param id $movieId
	 * @return false | id
	 */
	public static
	function likeMovie( WOOOF $wo, $fromUserId, $movieId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  Like Movie" );
		
		//First check if the movie is hated to revert the change
		$tblUserOpinions = new WOOOF_dataBaseTable($wo->db, 'movierama_user_movies_opinions');
		if(!$tblUserOpinions->constructedOk) { return false; }
		
		$res = $tblUserOpinions->getResult(
			[
				'movieramaUserId' => $fromUserId,
				'movieId'         => $movieId,
				'isPositive'      => '0',
				'isDeleted'       => '0'
			],
			'', '', '', '', false, true
		);

		if ( $res === FALSE ) { return false; }
		
		foreach( $tblUserOpinions->resultRows as $aRow ) {
			//delete hate
			$deleteHate = $tblUserOpinions->deleteRow($aRow['id']);
			if($deleteHate === FALSE) { return false; }
			
			//update counter
			$decreaseHate = self::updateCounter($wo, $movieId, 'noOfHates', '-1');
			if($decreaseHate === FALSE) { return false; }
		}
	
		$tblUserLikeInsert = new VO_TblMovieRamaUserMoviesOpinions();
		$tblUserLikeInsert->movieramaUserId = $fromUserId;
		$tblUserLikeInsert->movieId = $movieId;
		$tblUserLikeInsert->isPositive = '1';
	
		$res = self::saveUserOpinion($wo, $tblUserLikeInsert);
		if($res === FALSE) { return false; }
		
		$increaseLike = self::updateCounter($wo, $movieId, 'noOfLikes', '+1');
		if($increaseLike === FALSE) { return false; }
	
		return $res;
	
	}	// likeMovie
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $fromUserId
	 * @param id $movieId
	 * @return false | id
	 */
	public static
	function hateMovie( WOOOF $wo, $fromUserId, $movieId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  Hate Movie" );
		
		//First check if the movie is liked to revert the change
		$tblUserOpinions = new WOOOF_dataBaseTable($wo->db, 'movierama_user_movies_opinions');
		if(!$tblUserOpinions->constructedOk) { return false; }
		
		$res = $tblUserOpinions->getResult(
			[
				'movieramaUserId' => $fromUserId,
				'movieId'         => $movieId,
				'isPositive'      => '1',
				'isDeleted'       => '0'
			],
			'', '', '', '', false, true
		);
		
		if ( $res === FALSE ) { return false; }
		
		foreach( $tblUserOpinions->resultRows as $aRow ) {
			//delete like
			$deleteLike = $tblUserOpinions->deleteRow($aRow['id']);
			if($deleteLike === FALSE) { return false; }
				
			//update counter
			$decreaseLike = self::updateCounter($wo, $movieId, 'noOfLikes', '-1');
			if($decreaseLike === FALSE) { return false; }
		}
	
		$tblUserHateInsert = new VO_TblMovieRamaUserMoviesOpinions();
		$tblUserHateInsert->movieramaUserId = $fromUserId;
		$tblUserHateInsert->movieId = $movieId;
		$tblUserHateInsert->isPositive = '0';
	
		$res = self::saveUserOpinion($wo, $tblUserHateInsert);
		if($res === FALSE) { return false; }
	
		$increaseHate = self::updateCounter($wo, $movieId, 'noOfHates', '+1');
		if($increaseHate === FALSE) { return false; }
		
		return $res;
	
	}	// hateMovie
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param VO_TblMovieRamaUserMoviesOpinions $obj
	 * @return false | liked movie id
	 */
	public static
	function saveUserOpinion( WOOOF $wo, VO_TblMovieRamaUserMoviesOpinions &$obj )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  " );
			
		if ( !$wo->hasContent($obj->movieramaUserId) ) {
			$wo->logError(self::_ECP."3001 No value provided for [movieramaUserId]" );
			return false;
		}
	
		if ( !$wo->hasContent($obj->movieId) ) {
			$wo->logError(self::_ECP."3002 No value provided for [movieId]" );
			return false;
		}
	
		if($obj->movieramaUserId === $obj->movieId) {
			$wo->logError(self::_ECP."3003 Id's cannot be the same");
			return false;
		}
		
		if ( !$wo->hasContent($obj->isPositive) ) {
			$wo->logError(self::_ECP."3004 No value provided for [isPositive]" );
			return false;
		}
	
		$tblUserMovieOpinions = new WOOOF_dataBaseTable($wo->db, 'movierama_user_movies_opinions');
		if(!$tblUserMovieOpinions->constructedOk) { return false; }
	
		// insert
		$newId = $tblUserMovieOpinions->insertRowFromArraySimple( $obj->toArray() );
		if ( $newId === FALSE ) { return false; }
			
		return $obj->movieId;
	
	}	// saveUserOpinion
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param string $id for record to be deleted
	 * @param string $action ('unLike' | 'unHate')
	 * @return id of row deleted
	 */
	public static
	function deleteOpinion( WOOOF $wo, $id, $action )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  Delete Movie Opinion" );
			
		if ( !$wo->hasContent($id) ) {
			$wo->logError(self::_ECP."1909 No value provided for [id]" );
			return false;
		}
	
		$tblUserMovieOpinions = new WOOOF_dataBaseTable($wo->db, 'movierama_user_movies_opinions');
		if(!$tblUserMovieOpinions->constructedOk) { return false; }
		
		//find movie id to update counter
		$movieOpinionRec = $wo->db->getRow('movierama_user_movies_opinions', $id);
		if($movieOpinionRec === FALSE) { return false; }
	
		$res = $tblUserMovieOpinions->deleteRow($id);
		if($res === FALSE) { return false; }
		
		if($action === 'unLike') {
			$decreaseLike = self::updateCounter($wo, $movieOpinionRec['movieId'], 'noOfLikes', '-1');
			if($decreaseLike === FALSE) { return false; }		
		} else if($action === 'unHate') {
			$decreaseHate = self::updateCounter($wo, $movieOpinionRec['movieId'], 'noOfHates', '-1');
			if($decreaseHate === FALSE) { return false; }		
		} 
			
		return $id;
	
	}	// deleteOpinion
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	public static
	function updateCounter( WOOOF $wo, $movieId, $columnToUpdate, $updateAction )
	{
		$sql =  "update movierama_user_movies ";
		$sql .=	"set $columnToUpdate=$columnToUpdate$updateAction ";
		$sql .= "where id='$movieId' and isDeleted='0'";
	
		$res = $wo->db->getResultByQuery($sql, true, false);
		if ( $res === FALSE ) { return FALSE; }
	
		return true;
	}	// updateCounter
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
}	// VO_Movies