<?php
$__isAdminPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='signOut';
$pageLocation='1';
$pageTitle='Log out.';

$wo = new WOOOF(FALSE);
$wo->invalidateSession();
$wo->db->commit();

header('Location: index.php?'. $wo->getCurrentDateTime());
exit;
?>