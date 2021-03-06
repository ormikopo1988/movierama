<?php

class VO_FlagItem {
	const _ECP = 'FLA';	// Error Code Prefix
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 *
	 * @param WOOOF $wo
	 * @param VO_TblFlagItems $obj
	 * @param char $action
	 * @param bool $fetchBack
	 * @return false | type of reported object
	 * Returns actually saved $obj if $fetchBack is set to true
	 */
	public static
	function save( WOOOF $wo, VO_TblFlagItems &$obj, $action='I', $fetchBack=true )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  " );
			
		if ( !$wo->hasContent($obj->flaggedByUserId) ) {
			$wo->logError(self::_ECP."3011 No value provided for [flaggedByUserId]" );
			return false;
		}
	
		if ( !$wo->hasContent($obj->whatId) ) {
			$wo->logError(self::_ECP."3012 No value provided for [whatId]" );
			return false;
		}
		
		if ( !$wo->hasContent($obj->whatType) ) {
			$wo->logError(self::_ECP."3013 No value provided for [whatType]" );
			return false;
		}
	
		if($obj->flaggedByUserId === $obj->whatId) {
			$wo->logError(self::_ECP."3014 Id's cannot be the same");
			return false;
		}
		
		$tblFlagItems = new WOOOF_dataBaseTable($wo->db, 'flag_items');
		if($tblFlagItems === FALSE) { return false; }
		
		//insert
		if($action === 'I') {
			$obj->flaggedDateTime = WOOOF::currentGMTDateTime();
				
			$newId = $tblFlagItems->insertRowFromArraySimple( $obj->toArray() );
			if ( $newId === FALSE ) { return false; }
		}
		
		//update
		else {
			$obj->flaggedDateTime = $wo->currentGMTDateTime();
				
			$res = $tblFlagItems->updateRowFromArraySimple( $obj->toArray() );
			if ( $res === FALSE ) { return false; }
		}
		
		return $obj->whatType;
		
	}	// save
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in
	 * @return false | flagged item type
	 */
	public static
	function saveFlagItem( WOOOF $wo, $movieramaUserId, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$wo->debug( "$place:  " );
		
		//find if user has already flagged the movie once before
		$tblFlagItems = new WOOOF_dataBaseTable($wo->db, 'flag_items');
		if(!$tblFlagItems->constructedOk) { return false; }
		
		$res = $tblFlagItems->getResult(
			[
				'whatId'          => $in['whatId'],
				'whatType'        => $in['whatType'],
				'flaggedByUserId' => $movieramaUserId,
				'flagStatus'      => 'P',
				'isDeleted'       => '0'
			],
			'', '', '', '', false, true
		);
		
		if ( $res === FALSE ) { return false; }
			
		foreach( $tblFlagItems->resultRows as $aFlagItem ) {
			$tblFlagItemUpdate = new VO_TblFlagItems($aFlagItem);
			$tblFlagItemUpdate->flagText = $in['flagText'];
			
			$res = self::save($wo, $tblFlagItemUpdate, 'U');
			if($res === FALSE) { return false; }
			
			return $res;
		}
		
		$tblFlagItemInsert = new VO_TblFlagItems();
		$tblFlagItemInsert->whatType = $in['whatType'];
		$tblFlagItemInsert->whatId = $in['whatId'];
		$tblFlagItemInsert->flaggedByUserId = $movieramaUserId;
		$tblFlagItemInsert->flagText = $in['flagText'];
		$tblFlagItemInsert->flagStatus = 'P';
	
		$res = self::save($wo, $tblFlagItemInsert, 'I');
		if($res === FALSE) { return false; }
	
		return $res;
	
	}	// saveFlagItem
	
}	// VO_FlagItem