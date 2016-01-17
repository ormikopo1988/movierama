<?php

class VO_ProfileData {
	const _ECP = 'PRD';	// Error Code Prefix
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param id $movieRamaUserId
	 * @return false | array[ ]
	 */
	public static
	function getMainInfo( WOOOF $wo, $movieRamaUserId )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  [$movieRamaUserId]" );
	
		$main = [];
		
		if ( $movieRamaUserId == $wo->app->userId ) {
			$ramaUser = $wo->app->movieRamaPersonRow;
			$main['isSelf'] = true;
		}
		else {
			$ramaUser = $wo->db->getRowByColumn('v_movierama_persons', 'VUS_id', $movieRamaUserId);
			if($ramaUser === FALSE || $ramaUser === NULL) { return false; }
			$main['isSelf'] = false;
		}
		
		$main['personProfileId']		= $ramaUser['VUS_personProfileId'];
		$main['movieRamaUserId']		= $ramaUser['VUS_id'];
		$main['isActive']				= $ramaUser['VUS_isActive'];
		
		$main['isLoggedIn'] = true;
		
		$main['isType'] = 'PRS';
		
		$main['avatarImg'] = $ramaUser['VUS_avatarImg'];
		
		$mainElems = ['PROF_firstName', 'PROF_lastName'];
		
		WOOOF_Util::filterOnKeys($main, $mainElems, $ramaUser, 'PROF_');
		
		return $main;
	} //getMainInfo
	
}	// VO_ProfileData