<?php



class VO_CtrlRegistration {
	const _ECP = 'REG';	// Error Code Prefix
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	public static function register( WOOOF $wo ) {
		
		$requestedAction='viewUncontroled';	// register
		$pageLocation='3';
		$browserTitle='MovieRama Registration Page';
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
					RegisterBox, 
					{}
				), 
				document.getElementById('content-main')
			);
			/*
			ReactDOM.render(
				<RegisterBox />,
				document.getElementById('content-main')
			);
			*/
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

	}	//register
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 * 
	 * @param WOOOF $wo
	 * @param array $in	// [ 'email', 'password', 'passwordConfirm', 'firstName', 'lastName' ]
	 * @return array [ 'registerOK', 'errors' ] 
	 */
	public static
	function saveRegistration( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		//$wo->debug( "$place:  [$in]" );

		if ( $wo->userData['id'] != '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
		
		$res = VO_Registration::registerUser($wo, $in, $errors);
		
		if ( $res === FALSE ) {
			$out = [
				'registerOK' => false,
				'errors' => $errors
			];
			$wo->db->rollback();
		}
		
		else {
			$out = [
				'registerOK'	=> true,
				'userId'		=> $res
			];
			VO_SessionMessages::addMessage($wo, 'Thank you for Registering to MovieRama! Please check your email and follow the link
					that has been given to you in order to verify your email address and be able to log in into the system...', 'S');
			$wo->db->commit();
		}
		
		return $out;
	
	}	// saveRegistration
	
	/***************************************************************************/
	//
	/***************************************************************************/
  
	public static function registerVerify( WOOOF $wo ) {
		
		$requestedAction='viewUncontroled';	// register
		$pageLocation='3';
		$browserTitle='MovieRama Registration Verification Page';
		$wo = WOOOF::getWOOOF($pageLocation, $requestedAction, $wo);
		if ( $wo === FALSE ) { die('Failed to getWOOOF()'); }

		if ( $wo->userData['id'] != '0123456789' ) {
			$wo->handleShowStopperError('505');
		}
		
		$paramNames = array(
			'token'
		);
		
		$in = $wo->getMultipleGetPost( $paramNames );
		
		if ( !$wo->hasContent($in['token']) ) {
			$wo->handleShowStopperError('No token was provided for verification.');
		}
		
		$vusRec = $wo->db->getRowByColumn( 'movierama_users', 'verificationToken', $in['token'] );
		if ( $vusRec === FALSE ) { $wo->handleShowStopperError('505'); }
		if ( $vusRec === NULL ) {
			$wo->handleShowStopperError('505 Token not found.');
		}
		
		// We seem to be ok
		//
		
		$res = $wo->db->query(
			"update movierama_users set isVerified = '1', verificationToken = null where id = '".$vusRec['id']."'"
		);
		if ( $res === FALSE ) { return FALSE; }

		VO_SessionMessages::addMessage($wo, 
			'Thank you. You e-mail is now verified and you can proceed to Logging in to MovieRama.',
			'S'
		);
		
		$wo->db->commit();
		
		header( "Location:  " . $wo->assetsURL . 'login' );
		exit();
				
	}	// registerVerify
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in	// [ 'email', 'password' ]
	 * @return array [ 'loginOK', 'errors' ]
	 */
	public static
	function loginDo( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		//$wo->debug( "$place:  [$in]" );
	
		$requestedAction='logIn';
		$pageLocation='3';
		$browserTitle='Logging in MovieRama';
		$wo = WOOOF::getWOOOF($pageLocation, $requestedAction, $wo);
		if ( $wo === FALSE ) { die('Failed to getWOOOF()'); }
	
		if ( $wo->userData['id'] != '0123456789' ) {
			$wo->handleShowStopperError("505 $place: " . $wo->userData['id']);
		}
	
		return VO_Registration::loginDo($wo, $in);
	}	// loginDo
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in	// [ 'email' ]
	 * @return array [ 'resetOk', 'errors' ]
	 */
	public static
	function resetPassword( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$requestedAction='resetPassword';
		$pageLocation='3';
		$browserTitle='MovieRama User Password Reset';
	
		if ( $wo->userData['id'] != '0123456789' ) {
			$wo->handleShowStopperError("505 $place: " . $wo->userData['id']);
		}
	
		if ( !$wo->hasContent($in['email']) ) {
			$wo->logError(self::_ECP."2349 You must provide your email in order to reset your password.");
			return false;
		}
	
		$movieramaUserRec = $wo->db->getRowByColumn('movierama_users', 'username', $in['email']);
		if($movieramaUserRec === FALSE) { return false; }
		if($movieramaUserRec === NULL) {
			$wo->logError(self::_ECP."2350 I am sorry it seems you are not a registered MovieRama user.");
			return false;
		}
	
		$res = VO_Registration::passwordReset($wo, $in);
	
		if ( $res === FALSE ) {
			$out = [
				'resetOk' => false,
				'errors'  => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'resetOk' => true,
				'reset'   => $res,
			];
			$wo->db->commit();
		}
	
		return $out;
	}	// resetPassword
	
	/***************************************************************************/
	//
	/***************************************************************************/
	
	/**
	 *
	 * @param WOOOF $wo
	 * @param array $in	// [ 'email' ]
	 * @return array [ 'resendOk', 'errors' ]
	 */
	public static
	function resendToken( WOOOF $wo, $in )
	{
		$place = __CLASS__ . '::' . __FUNCTION__;
		$requestedAction='resendToken';
		$pageLocation='3';
		$browserTitle='MovieRama User Verification Token Resend';
	
		if ( $wo->userData['id'] != '0123456789' ) {
			$wo->handleShowStopperError("505 $place: " . $wo->userData['id']);
		}
	
		if ( !$wo->hasContent($in['email']) ) {
			$wo->logError(self::_ECP."2359 You must provide your email in order to resend your verification token.");
			return false;
		}
	
		$movieramaUserRec = $wo->db->getRowByColumn('movierama_users', 'username', $in['email']);
		if($movieramaUserRec === FALSE) { return false; }
		if($movieramaUserRec === NULL) {
			$wo->logError(self::_ECP."2360 I am sorry it seems you are not a registered MovieRama user.");
			return false;
		}
	
		$res = VO_Registration::tokenResend($wo, $in);
	
		if ( $res === FALSE ) {
			$out = [
				'resendOk' => false,
				'errors'   => $wo->getErrorsAsArrayAndClear()
			];
			$wo->db->rollback();
		}
	
		else {
			$out = [
				'resendOk' => true,
				'resend'   => $res,
			];
			$wo->db->commit();
		}
	
		return $out;
	}	// resendToken

}	// VO_CtrlRegistration