<?php

class VO_SysParams {
	const _ECP = 'SPA';	// Error Code Prefix
	
	// Should be private... ??
	public static $params = array();	// array( 'PARAM_NAME' => array( param_value, param_datatype, ),  .... )
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	// Params are assigned to VO_SysParams::params
	// returns true or FALSE
	public static 
	function loadAll( WOOOF $wo )
	{
	  $place = __CLASS__ . '::' . __FUNCTION__;
	  $wo->debug( "$place:  " );
	  $po_error_message = ''; $l_error_message = '';
	  
	  $t1 = new WOOOF_dataBaseTable($wo->db, 'sys_params');
	  if ( !$t1->constructedOk ) { return false; }
	  
	  $res = $t1->getResult( ['isdeleted'=>0] , 'code' );
	  if ( $res === FALSE ) { return false; }
	  
	  foreach( $t1->resultRows as $aRow ) {
	  	$l_res[strtoupper($aRow['code'])] =
	  		array( self::convertToType($aRow['paramValue'], $aRow['paramDataType']), $aRow['paramDataType'] );
	  }
	  
	  self::$params = $l_res;
	  
	  return true;
	}	// loadAll
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 * 
	 * @param unknown string
	 * @param unknown string
	 * @return NULL|number|string
	 */
	
	private static 
	function convertToType(
		$p_param_value,
		$p_param_datatype
	)
	{
		if ( $p_param_value == '' ) {
			return null;
		}
		
		if ( $p_param_datatype == 'INT' ) {
			return (int) $p_param_value;
		}
		
		if ( $p_param_datatype == 'FLOAT' ) {
			return (float) $p_param_value;
		}
		
		return $p_param_value;  
	}	// convertToType
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	// returns param's value (from Cache or DB) or False
	public static 
	function get( WOOOF $wo, $paramCode, $evenDeleted=false )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		  
		$p_param = trim(strtoupper($paramCode));
		
		if ( isset( self::$params[$paramCode] ) ) {
			return self::$params[$paramCode][0];
		}
		  
		
		$t1 = new WOOOF_dataBaseTable($wo->db, 'sys_params');
		if ( !$t1->constructedOk ) { return false; }

		$wheres = [ 'code' => $paramCode ];
		if ( !$evenDeleted ) { $wheres['isDeleted'] = 0; }
		$res = $t1->getResult( $wheres , 'code' );
		if ( $res === FALSE ) { return false; }
		  
		if ( $res['rowsFetched'] !== 1 ) {
			$wo->logError(self::_ECP."0010 {$res['rowsFetched']} records found for [$paramCode]" );
			return false;
		}
		  
		$paramRec = $t1->resultRows[0];
		  
		$l_val = self::convertToType($paramRec['paramValue'], $paramRec['paramDataType']);
		  
		self::$params[$paramCode] = array( $l_val, $paramRec['paramDataType'] );
		  
		return $l_val;
	  
	}	// get
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	// returns param's value always from DB or throws Exception
	public static 
	function getFreshValue( WOOOF $wo, $paramCode	)
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$l_error_message = '';
		  
		$p_param = strtoupper($paramCode);
		
		if ( isset( self::$params[$paramCode] ) ) {
			unset( self::$params[$paramCode] );
		}
		  
		return self::get($wo, $paramCode);
	}	// getFreshValue
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	// TODO: Change for WOOOF. Do not use as is!!!!
	// update only!
	// returns true/false
	public static 
	function save(
	  stcmt_db $p_dbconn,
	  System_paramsEnt $p_paramEnt,
		&$po_error_message
	)
	{
	  $place = __CLASS__ . '::' . __FUNCTION__;
	  tp_debug( "$place: " );
		$l_error_message = '';
		
	  if ( !hasContent($p_paramEnt->ID) or !myIsInt($p_paramEnt->ID ) ) {
	  	$po_error_message = "$place: Bad Param Id [{$p_paramEnt->ID}]";
	  	return false;
	  }
	  
	  $l_val = $p_paramEnt->VALUE;
	
	  if ( !hasContent($l_val) and $p_paramEnt->NULLABLE_FLAG == '0' ) {
	  	$po_error_message = "$place: Parameter must have a value";
	  	return false;
	  }
	  
	  if ( hasContent($l_val) and hasContent($p_paramEnt->PARAM_DATATYPE) ) {
	  	$l_datatypeOk = true;
	  
	  	switch ( $p_paramEnt->PARAM_DATATYPE ) {
	  		case 'INT':
	  			$l_datatypeOk = myIsInt( $l_val );
	  			break;
	  		case 'FLOAT':
	  			$l_datatypeOk = my_numeric_float( $l_val, $p_paramEnt->input_decimal_chars );
	  			break;
	  		default:
	  			break;
	  	}	// which type
	
	  	if ( !$l_datatypeOk) {
	  		$po_error_message = "$place: Value provided [$l_val] is not of the correct type [{$p_paramEnt->PARAM_DATATYPE}]";
	  		return false;
	  	}
	  }	// value and datatype are given
	  
	  // TODO: Check min / max values. Must be done according to type...
	  
	  $p_paramEnt->LAST_UPDATE_UTC_DATETIME = CS_Util::nowUTC( $p_paramEnt->input_dateFormat );
	  
	  $l_succ = $p_paramEnt->update();
	  if ( !$l_succ ) {
	  	$po_error_message = "$place: Failed to update: " . $p_paramEnt->error_message;
	  	return false;
	  }
	  
	  return true;
	}	// save

}	// VO_SysParams