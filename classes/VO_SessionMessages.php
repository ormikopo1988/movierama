<?php

class VO_SessionMessages {
	const _ECP = 'SMG';	// Error Code Prefix
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 * 
	 * @param WOOOF $wo
	 * @param string $messageText
	 * @param string $messageType
	 * @param number $fadeInSeconds
	 * @return bool
	 */
	public static 
	function addMessage( WOOOF $wo, $messageText, $messageType = 'I', $fadeInSeconds = 0 )
	{
		$newId	= $wo->db->getNewId('session_messages');
		$sid	= $wo->sid;
		
		if ( $messageType != 'I' and $messageType != 'E' and $messageType != 'S' and $messageType != 'W' ) {
			$wo->log(WOOOF_loggingLevels::WOOOF_LOG_WARNINGS, "Invalid messageType [$messageType] for Message: [$messageText]" );
			$messageType = 'E';
		}

		$fadeInSeconds = (int) $fadeInSeconds;
		
		$sql = 
			"insert into session_messages( id, sessionId, messageType, messageText, whenMicrotime, fadeInSeconds ) " .
			"values ( '$newId', '$sid', '$messageType', '$messageText', ". microtime(true) .", $fadeInSeconds )"
		;
		
		$succ = $wo->db->query( $sql );
		return $succ;
	}	// addMessage
	

	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 * 
	 * @param WOOOF $wo
	 * @param string $messageType	// I, E, W, S. Optional, default is 'ALL'
	 * @param bool $keepThem		// Optional, default false
	 * @return array				// [ [ messageType => , messageText =>, fadeInSeconds => ], ... ]
	 */
	public static
	function getMessages( WOOOF $wo, $messageType = 'ALL',  $keepThem = false )
	{
		$sid = $wo->sid;
		
		$sql = 
			"select messageText, messageType, fadeInSeconds from session_messages ";
		
		$where = 
			"where sessionId = '$sid' " . 
			( $messageType == 'ALL' ? '' : "messageType = '$messageType' ")
		;
		
		$sql = $sql . $where . ' order by whenMicrotime';
					
		$res = $wo->db->getResultByQuery($sql, true, false);
		if ( $res === FALSE ) { return FALSE; }
		
		$out = $wo->db->resultRows;
		
		if ( !$keepThem ) {
			$sql = "delete from session_messages $where";
			$res = $wo->db->query( $sql );
			if ( $res === FALSE ) { return FALSE; }
		}
			
		return $out;
	}	// getMessages
	
	
	
}	// VO_SessionMessages