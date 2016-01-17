<?php
$__isAdminPage = true;
$__isSiteBuilderPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction = 'read';
$pageLocation = '1';
$pageTitle = 'Administration Back End';

$wo = new WOOOF();

$in = $wo->getMultipleGetPost( ['table', 'class' ] );

if ( !$wo->hasContent($in['table']) or !$wo->hasContent($in['class']) ) {
	die("You have to provice 'table' and 'class' parameters");
}

$res = WOOOF_MetaData::createTplCodeForTable( $wo, $in['table'], $in['class'] );
$res = '<textarea rows="50" cols="100">'. htmlentities($res) . "</textarea>";

echo $res;
exit;
?>