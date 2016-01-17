<?php
// Initial require before constructing $wo

// Use 'prodXXXXXX' for production enviroments
// A file named 'conf_{WOOOF_ENVIRONMENT}' must be present in WOOOF_CONFIG_PATH.

define( 'WOOOF_ENVIRONMENT', 	'dev' );

if (isset($__actualPath))
{
    define('WOOOF_CONFIG_PATH', $__actualPath .'/config/' );
}else
{
    define('WOOOF_CONFIG_PATH', '../config/' );
}


require_once 'setup.common.inc.php';

/* End of setup.inc.php */