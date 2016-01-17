<?php
$__isAdminPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction = 'read';
$pageLocation = '1_roles';
$pageTitle = 'Administration Back End';

$wo = new WOOOF();

$wo->getResultByQuery('select * from __roles', FALSE);

foreach ($wo->resultRows as $value) 
{
	$content.='';
}

require 'template.php';
?>