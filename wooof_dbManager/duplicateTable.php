<?php
$__isSiteBuilderPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='edit';
$pageLocation='1';

$wo = new WOOOF();

$result = $wo->db->query("select * from __tableMetaData where tableName='". $wo->cleanUserInput($_GET["table"]) ."'");

// TODO: Update with relevant metadata changes!!!

if (mysqli_num_rows($result))
{
	$row=$wo->db->fetchAssoc($result);
        $tableId = $wo->db->getNewId('__tableMetaData');
	$wo->db->query('insert into __tableMetaData set 
id=\''. $tableId .'\',
tableName=\''. $wo->cleanUserInput($row['tableName']) .'_dup\',
orderingColumnForListings=\''. $wo->cleanUserInput($row['orderingColumnForListings']) .'\',
appearsInAdminMenu=\''. $wo->cleanUserInput($row['appearsInAdminMenu']) .'\',
adminPresentation=\''. $wo->cleanUserInput($row['adminPresentation']) .'\',
adminItemsPerPage=\''. $wo->cleanUserInput($row['adminItemsPerPage']) .'\',
adminListMarkingCondition=\''. $wo->cleanUserInput($row['adminListMarkingCondition']) .'\',
adminListMarkedStyle=\''. $wo->cleanUserInput($row['adminListMarkedStyle']) .'\',
groupedByTable=\''. $wo->cleanUserInput($row['groupedByTable']) .'\',
remoteGroupColumn=\''. $wo->cleanUserInput($row['remoteGroupColumn']) .'\',
localGroupColumn=\''. $wo->cleanUserInput($row['localGroupColumn']) .'\',
tablesGroupedByThis=\''. $wo->cleanUserInput($row['tablesGroupedByThis']) .'\',
hasActivationFlag=\''. $wo->cleanUserInput($row['hasActivationFlag']) .'\',
availableForSearching=\''. $wo->cleanUserInput($row['availableForSearching']) .'\',
hasGhostTable=\''. $wo->cleanUserInput($row['hasGhostTable']) .'\',
hasDeletedColumn=\''. $wo->cleanUserInput($row['hasDeletedColumn']) .'\',
description=\''. $wo->cleanUserInput($row['description']) .'\',
hasEmbededPictures=\''. $wo->cleanUserInput($row['hasEmbededPictures']) .'\',
columnForMultipleTemplates=\''. $wo->cleanUserInput($row['columnForMultipleTemplates']) .'\',
showIdInAdminLists=\''. $wo->cleanUserInput($row['showIdInAdminLists']) .'\',
showIdInAdminForms=\''. $wo->cleanUserInput($row['showIdInAdminForms']) .'\',
dbEngine=\''. $wo->cleanUserInput($row['dbEngine']) .'\'
');
      	echo $wo->db->error();
        $rez = $wo->db->query("show create table ". $wo->cleanUserInput($_GET["table"]));
        $cT = $wo->db->fetchAssoc($rez);
         echo $wo->db->error();
         $ct['Create Table'] = str_replace($wo->cleanUserInput($_GET["table"]), $wo->cleanUserInput($_GET["table"]).'_dup', $cT['Create Table']);
        $wo->db->query($ct['Create Table']);
        echo $wo->db->error();

    $result = $wo->db->query("select * from __columnMetaData where tableId='". $wo->cleanUserInput($row['id']) ."'");
    while ($cD = $wo->db->fetchArray($result)) 
    {
        $wo->db->query('insert into __columnMetaData set
id=\''. $wo->db->getNewId('__columnMetaData') .'\',
tableId=\''. $tableId .'\',
name=\''. $wo->cleanUserInput($cD['name']) .'\',
type=\''. $wo->cleanUserInput($cD['type']) .'\',
length=\''. $wo->cleanUserInput($cD['length']) .'\',
presentationType=\''. $wo->cleanUserInput($cD['presentationType']) .'\',
isReadOnly=\''. $wo->cleanUserInput($cD['isReadOnly']) .'\',
notNull=\''. $wo->cleanUserInput($cD['notNull']) .'\',
isInvisible=\''. $wo->cleanUserInput($cD['isInvisible']) .'\',
appearsInLists=\''. $wo->cleanUserInput($cD['appearsInLists']) .'\',
isASearchableProperty=\''. $wo->cleanUserInput($cD['isASearchableProperty']) .'\',
isReadOnlyAfterFirstUpdate=\''. $wo->cleanUserInput($cD['isReadOnlyAfterFirstUpdate']) .'\',
isForeignKey=\''. $wo->cleanUserInput($cD['isForeignKey']) .'\',
presentationParameters=\''. $wo->cleanUserInput($cD['presentationParameters']) .'\',
valuesTable=\''. $wo->cleanUserInput($cD['valuesTable']) .'\',
columnToShow=\''. $wo->cleanUserInput($cD['columnToShow']) .'\',
columnToStore=\''. $wo->cleanUserInput($cD['columnToStore']) .'\',
defaultValue=\''. $wo->cleanUserInput($cD['defaultValue']) .'\',
orderingMirror=\''. $wo->cleanUserInput($cD['orderingMirror']) .'\',
searchingMirror=\''. $wo->cleanUserInput($cD['searchingMirror']) .'\',
description=\''. $wo->cleanUserInput($cD['description']) .'\',
resizeWidth=\''. $wo->cleanUserInput($cD['resizeWidth']) .'\',
resizeHeight=\''. $wo->cleanUserInput($cD['resizeHeight']) .'\',
thumbnailWidth=\''. $wo->cleanUserInput($cD['thumbnailWidth']) .'\',
thumbnailHeight=\''. $wo->cleanUserInput($cD['thumbnailHeight']) .'\',
thumbnailColumn=\''. $wo->cleanUserInput($cD['thumbnailColumn']) .'\',
midSizeColumn=\''. $wo->cleanUserInput($cD['midSizeColumn']) .'\',
ordering =\''. $wo->cleanUserInput($cD['ordering']) .'\',
adminCSS=\''. $wo->cleanUserInput($cD['adminCSS']) .'\',
midSizeWidth=\''. $wo->cleanUserInput($cD['midSizeWidth']) .'\',
midSizeHeight=\''. $wo->cleanUserInput($cD['midSizeHeight']) .'\',
indexParticipation=\''. $wo->cleanUserInput($cD['indexParticipation']) .'\',
colCollation=\''. $wo->cleanUserInput($cD['colCollation']) .'\'
');
    }
    $wo->db->commit();
}


header("Location: dbManager.php?tm=$tm");
exit;
?>
