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

$table = new WOOOF_dataBaseTable($wo->db, $_GET['table']);

$fragment = $table->constructAdministrationFragment();

echo '<div class="itemEditForm"><form method="POST" action="administration.php" enc-type="multipart/form-data"><input type="hidden" name="__address" value="1_'. $table->getTableId() .'_@@@id@@@"><input type="hidden" name="action" value="insert">';
echo $fragment[0];
echo '<section class="formFields">
      <div class="adminButton"><input type="submit" name="submit" value="Εισαγωγή"></div>
      </section></form></div>';

echo '<a href="buildTblCode.php?table='.$_GET['table'].'&class=">Get Tbl Code (fill-in class name first)...</a>';

exit;
?>