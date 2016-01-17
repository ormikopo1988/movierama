<?php

$__isSiteBuilderPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='edit';
$pageLocation='1';

$wo = new WOOOF();

if (!isset($_GET['sure']) || $_GET['sure']!='yes')
{
    $content='You are about to eliminate table <span class="largeTextOrange">'. $_GET['table'] .'</span>.<br/> are you sure ? <a href="dbManager.php" class="largeTextGreen">NO</a> - - - - - <a href="dropTable.php?sure=yes&table='. $_GET['table'] .'" class="largeTextRed">YES</a>';
}else
{
    $result = $wo->db->query('select * from __tableMetaData where tableName=\''. $wo->db->escape($_GET['table']) .'\'');
    $tableMetaData = $wo->db->fetchAssoc($result);
    
    if (isset($tableMetaData['id']))
    {
        $wo->db->query('delete from __columnMetaData where tableId=\''. $tableMetaData['id'] .'\'');
        $wo->db->query('delete from __tableMetaData where id=\''. $tableMetaData['id'] .'\'');
    }
    $wo->db->query('drop '. ($tableMetaData['isView']=='1' ? 'view ' : 'table ') . $wo->db->escape($_GET['table']));
    $wo->db->commit();
    header("Location: dbManager.php");
    exit;
}

require_once 'template.php';
?>