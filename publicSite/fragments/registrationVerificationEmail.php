<?php 

extract( $parametersArray, EXTR_PREFIX_ALL, '_param' );	

$_preFragmentsArray =
	array(
	);
		
		
$_fragmentsArray = 
	array(
	'subject' => 'MovieRama Registration'
	,
	'messageText' => <<<EOH
		Thank you for your interest in MovieRama.\n
		In order to verify your e-mail account and complete your Registration,
		please follow the link below\n
		$_param_URL
EOH
	,
	'messageHTML' => <<<EOH
		Thank you for your interest in MovieRama.<br> 
		In order to verify your e-mail account and complete your Registration,
		<a href="$_param_URL">please follow this link</a>.
EOH
	,
);
	
return $_fragmentsArray;

