<?php
$__isSiteBuilderPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='edit';
$pageLocation='1';

$wo = new WOOOF();

$table = new WOOOF_dataBaseTable($wo->db, $_GET['table']);

if (isset($_GET['submit']) && $_GET['submit']=='Submit')
{
	for($du=0;$du<count($_GET['chk']);$du++)
	{
		$desr = $wo->db->query('select * from __columnMetaData where tableId=\''. $table->getTableId() .'\' and id=\''.  $wo->cleanUserInput($_GET['chk'][$du]) .'\'');
		$de = $wo->db->fetchAssoc($desr);
		$c='';
		//print_r($de);
		//echo 'INSERT INTO __columnMetaData set
		$wo->db->query('INSERT INTO __columnMetaData set 
id=\''. $wo->db->getNewId('__columnMetaData') .'\',
tableId=\''. $table->getTableId() .'\',
name=\''. $wo->db->escape(trim($de['name'.$c] . $_GET['suplec'])) .'\',
description=\''. $wo->db->escape(trim($de['description'.$c]. $_GET['suple'])) .'\',
type=\''. $wo->db->escape(trim($de['type'.$c])) .'\',
length=\''. $wo->db->escape(trim($de['length'.$c])) .'\',
notNull=\''. $wo->db->escape(trim($de['notNull'.$c])) .'\',
presentationType=\''. $wo->db->escape(trim($de['presentationType'.$c])) .'\',
isReadOnly=\''. $wo->db->escape(trim($de['isReadOnly'.$c])) .'\',
isInvisible=\''. $wo->db->escape(trim($de['isInvisible'.$c])) .'\',
appearsInLists=\''. $wo->db->escape(trim($de['appearsInLists'.$c])) .'\',
isASearchableProperty=\''. $wo->db->escape(trim($de['isASearchableProperty'.$c])) .'\',
isReadOnlyAfterFirstUpdate=\''. $wo->db->escape(trim($de['isReadOnlyAfterFirstUpdate'.$c])) .'\',
isForeignKey=\''. $wo->db->escape(trim($de['isForeignKey'.$c])) .'\',
presentationParameters=\''. $wo->db->escape(trim($de['presentationParameters'.$c])) .'\',
valuesTable=\''. $wo->db->escape(trim($de['valuesTable'.$c])) .'\',
columnToShow=\''. $wo->db->escape(trim($de['columnToShow'.$c])) .'\',
columnToStore=\''. $wo->db->escape(trim($de['columnToStore'.$c])) .'\',
defaultValue=\''. $wo->db->escape(trim($de['defaultValue'.$c])) .'\',
orderingMirror=\''. $wo->db->escape(trim($de['orderingMirror'.$c])) .'\',
searchingMirror=\''. $wo->db->escape(trim($de['searchingMirror'.$c])) .'\',
resizeWidth=\''. $wo->db->escape(trim($de['resizeWidth'.$c])) .'\',
resizeHeight=\''. $wo->db->escape(trim($de['resizeHeight'.$c])) .'\',
thumbnailWidth=\''. $wo->db->escape(trim($de['thumbnailWidth'.$c])) .'\',
thumbnailHeight=\''. $wo->db->escape(trim($de['thumbnailHeight'.$c])) .'\',
midSizeColumn=\''. $wo->db->escape(trim($de['midSizeColumn'.$c])) .'\',
midSizeWidth=\''. $wo->db->escape(trim($de['midSizeWidth'.$c])) .'\',
midSizeHeight=\''. $wo->db->escape(trim($de['midSizeHeight'.$c])) .'\',
thumbnailColumn=\''. $wo->db->escape(trim($de['thumbnailColumn'.$c])) .'\',
ordering=\''. $wo->db->escape(trim($de['ordering'.$c])) .'\',
adminCSS=\''. $wo->db->escape(trim($_POST['adminCSS'.$c])) .'\',
indexParticipation=\''. $wo->db->escape(trim($_POST['indexParticipation'.$c])) .'\',
colCollation=\''. $wo->db->escape(trim($_POST['colCollation'.$c])) .'\'
  ');
				
		$query='ALTER TABLE '. $table->getTableName() .' ADD COLUMN '. $wo->db->escape(trim($de['name'] . $_GET['suplec'])) .' '. WOOOF_dataBaseColumnTypes::getColumnTypeLiteral($wo->db->escape(trim($de['type'.$c])));
            if ($wo->db->escape(trim($de['length'.$c]))!='')
            {
                $query.='('.$wo->db->escape(trim($de['length'.$c])).')';
            }
            if ($wo->db->escape(trim($de['notNull'.$c])) == '1')
            {
                $query .= ' NOT NULL ';
            }
            if ($wo->hasContent($wo->db->escape(trim($de['defaultValue'.$c]))))
            {
                $query .= ' DEFAULT \''. $wo->db->escape(trim($de['defaultValue'.$c])) .'\'';
            }

            if ($wo->hasContent($wo->db->escape(trim($de['colCollation'.$c]))))
            {
            	$query .= ' COLLATE \''. $wo->db->escape(trim($de['colCollation'.$c])) .'\'';
            }
            
            //echo $query .'<br/>';
            $wo->db->query($query);
	}
	$wo->db->commit();
	header('Location: dbManager.php');
	exit;
}

$content='<form method="GET" action="'. $_SERVER['PHP_SELF'] .'"><input type="hidden" name="table" value="'. $table->getTableName() .'">
';

$pr = $wo->db->query('select * From __columnMetaData where tableId=\''. $table->getTableId() .'\'');
while($p = $wo->db->fetchAssoc($pr))
{
	$content.='<input type="checkbox" name="chk[]" value="'. $p['id'] .'"><span class="normal_text_white">'. $p['name'] .'</span><br>
';
}
$content.='Description addition : <input type=text name="suple" value=" (Αγγλικά)"> Column name addition : <input type=text name="suplec" value="_en">';
$content.='<br><input type="submit" name="submit" value="Submit">
</form>
';
require_once 'template.php';
?>