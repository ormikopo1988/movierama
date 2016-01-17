<?php

if ($_GET['action']=='edit')
{
    $extraRequestBit='&from=edit';
}else
{
    $extraRequestBit='&from=read';
}

if ($rowClass=='objectRow')
{
    $rowClass='objectRowDark';
}else
{
    $rowClass='objectRow';
}

$templateHead = '<div class="'. $rowClass .'">
';

$templateTail = '</div>
';

$templateUpDown = '<a href="administration.php?action=moveUp&__address=1_'. $this->tableId .'_'. $row['id'] . $extraRequestBit  .'"><img src="images/arrowUp.png" border="0" alt="Up this item in order"></a><a href="administration.php?action=moveDown&__address=1_'. $this->tableId .'_'. $row['id'] . $extraRequestBit  .'"><img src="images/arrowDown.png" border="0" alt="Down this item in order"></a>
';

$templateEditItem = '<div class="objectControls">
    <a href="administration.php?&__address=1_'. $this->tableId .'_'. $row['id'] .'&action=edit'. $extraURLBit .'"><img src="images/edit.png" border="0" alt="Edit this item."></a>
';

$templatePreview = '    <a href="'. $siteBaseURL . $displayPreview .'"><img src="images/preview.png" border="0" alt="Preview Item In Site" target="_blank"></a>
';

$templateDeleteItem = '    <a href="javascript:confirmDelete(\'administration.php?__address=1_'. $this->tableId .'_'. $row['id'] .'&action=delete'. $extraRequestBit .'\');"><img src="images/delete.png" border="0" alt="Delete this item"></a>
    </div>
';

$templateItemIsActive = '<div class="objectPropertyCellSmall"><a href="administration.php?action=deactivate&__address=1_'. $this->tableId .'_'. $row['id'] . $extraRequestBit .'" class="on">Active</a></div>
';

$templateItemIsInactive = '<div class="objectPropertyCellSmall"><a href="administration.php?action=activate&__address=1_'. $this->tableId .'_'. $row['id'] . $extraRequestBit  .'" class="off">Inctive</a></div>
';
?>