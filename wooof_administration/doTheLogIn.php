<?php
//echo 'here!<br/>';

$__isAdminPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='logIn';
$pageLocation='3_logIn';
//echo 'there!<br/>';
$wo = new WOOOF();


//print_r($wo);

//echo 'nowhere!<br/>';

$loginResult = $wo->handleLoginFromPost();

//print_r($loginResult);
//print_r($_POST);

//echo $wo->db->error();

if ($loginResult === FALSE || !isset($loginResult['id']))
{
    header('Location: logIn.php?error=1');
    exit;
}

$wo->invalidateSession();
$wo->newSession($loginResult['id']);

header('Location: administration.php');
exit
?>