<?php

$__isSiteBuilderPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='logIn';
$pageLocation='3_logIn';

$wo = new WOOOF();

$loginResult = $wo->handleLoginFromPost();

if ($loginResult === FALSE || !isset($loginResult['id']))
{
    header('Location: logIn.php?error=1');
    exit;
}

$wo->invalidateSession();
$wo->newSession($loginResult['id']);
$wo->db->commit();

header('Location: dbManager.php');
exit
?>