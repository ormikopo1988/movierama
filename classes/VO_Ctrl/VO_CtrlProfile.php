<?php



class VO_CtrlProfile {
	const _ECP = 'CPR';	// Error Code Prefix
	
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	public static function profileEdit( WOOOF $wo ) {
	
		$requestedAction='viewUncontroled';	// TODO: should be edit
		$pageLocation='3';
		$browserTitle='MovieRama Edit User Profile';

		$wo = WOOOF::getWOOOF($pageLocation, $requestedAction, $wo);
		if ( $wo === FALSE ) { die('Failed to getWOOOF()'); }
		
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
		
		$data = VO_ProfileData::getMainInfo($wo, $wo->app->userId);
		if ( $data === FALSE ) { $wo->handleShowStopperError(); }
		$data = json_encode( $data );
		
		$content = "
		<div id='content-main'></div>

		<script>
        	var data = $data;
        	
        	ReactDOM.render(
        		React.createElement(
        			ProfileEdit, 
        			{ data: data }
        		), 
        		document.getElementById('content-main')
        	);
		</script>
		";
	
		$tpl = array(
				'browserTitle'	=> $browserTitle,
				'content' 		=> $content,
				'errorMessage'	=> '',
				'message'		=> '',
		);
		
		$wo->fetchApplicationFragment('structural/template.php', [], $tpl);
	}	//profileEdit
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	/**
	 * 
	 * @param WOOOF $wo
	 * @param array $in
	 * @return [ 'saveOk' => bool, 'userId' => id, 'errors' => array ]
	 */
	public static 
	function mainInfoSave( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
		
		$res =  VO_Users::saveMainInfo($wo, $in);
		
		if ( $res === FALSE ) {
			$out = [
				'saveOk' => false,
				'errors' => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
		
		else {
			$out = [
				'saveOk'	=> true,
				'userId'	=> $res
			];
			$wo->db->commit();
		}
		
		return $out;
	}	// mainInfoSave
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in
	 * @return [ 'changeOk' => bool, 'changePass' => boolean, 'errors' => array ]
	 */
	public static
	function changePassword( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
	
		if ( $wo->userData['id'] == '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
	
		$errors = [];
		
		if ( $in['newPass'] !== $in['newPassConfirm'] ) {
			$errors[] = "Passwords given do not match.";
			$out = [
				'changeOk'	 => false,
				'errors'   => $errors
			];
			return $out;
		}
		
		$res = VO_Users::passwordChange($wo, $in);
		
		if ( $res === FALSE ) {
			$out = [
				'changeOk' => false,
				'errors'   => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'changeOk'	 => true,
				'changePass' => $res
			];
			$wo->db->commit();
		}
	
		return $out;
	}	// changePassword
	
}	// VO_CtrlProfile