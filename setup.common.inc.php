<?php

// No need to make any changes here.
// Required by setup.inc.php
// WOOOF_ENVIRONMENT and WOOOF_CONFIG_PATH should be defined before getting here.

if ( !defined('WOOOF_ENVIRONMENT') || !defined('WOOOF_CONFIG_PATH') ) {
	die( "WOOOF Environment was not set. Aborting..." );
}

$confFilePath = WOOOF_CONFIG_PATH . 'conf_'.WOOOF_ENVIRONMENT.'.php';

if ( ! file_exists($confFilePath)) {
	die( "Failed to load config file: [$confFilePath]. Aborting..." );
}

include($confFilePath);		// global array $wooofConfigOptions is defined here.

if (!isset($__isAdminPage))
{
	$__isAdminPage = false;
}

if (!isset($__isSiteBuilderPage))
{
	$__isSiteBuilderPage = false;
}

// This is used to prevent clickJacking.

if (basename($_SERVER["PHP_SELF"])!='handlePictureUpload.php')
{
	header('X-Frame-Options: DENY');
}

if (file_exists('./wooof.php'))
{
	require('./wooof.php');
}elseif (file_exists('../wooof.php'))
{
	require('../wooof.php');
}else
{
	require('../../wooof.php');
}


// Site specific constants, functions and ultility Declarations
// May make use of ENVIRONMENT for specific setups
require_once WOOOF_CONFIG_PATH . 'commonApp.php';

/* End of setup.common.inc.php */