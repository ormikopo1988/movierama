<?php
$__isAdminPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction = 'read';
$pageLocation = '1';
$pageTitle = 'Administration Back End';

$wo = new WOOOF();

if (isset($_POST['submit']) && $_POST['submit']=='Ενημέρωση')
{
  $oR = $wo->db->query('select * from __options where optionDisplay!=\'4\' order by id');
  while($o = $wo->db->fetchAssoc($oR))
  {
    if (!isset($_POST['o'.$o['id']]))
    {
      $_POST['o'.$o['id']]='';
    }
      $wo->db->query('update __options set optionValue=\''. $wo->db->escape($_POST['o'.$o['id']]) .'\' where id=\''. $o['id'] .'\'') ;
  }
}

$content='<div id="formHolder">
	<form method="POST" action="optionManagement.php" enctype="multipart/form-data">
';

$oR = $wo->db->query('select * from __options where optionDisplay!=\'4\' order by ord');
while($o = $wo->db->fetchAssoc($oR))
{
      		if ($o['optionDisplay']=='1')
      		{
      			$content.='		<section class="formFields">
      		<div class="itemDescription">'. $o['optionName'] .'</div>
      		<div class="itemValue"><input type="text" name="o'. $o['id'] .'" value="'. $o['optionValue'] .'" size="50">';
      		}else if ($o['optionDisplay']=='2')
      		{
      			if ($o['optionValue']=='1') $selected='checked'; else $selected='';
				$content.='		<section class="formFields">
      		<div class="itemDescription">'. $o['optionName'] .'</div>
      		<div class="itemValue"><input type="checkbox" name="o'. $o['id'] .'" value="1" '. $selected .'>';
      		}else
      		{
      			$content.='<section class="editorformFields">
      		<div class="editorDescription">'. $o['optionName'] .'</div>
	      	<div class="editor"><textarea name="o'. $o['id'] .'" cols="55" rows="15">'. $o['optionValue'] .'</textarea>';
      		}

    $content .='</div>
    	</section>';
}

$content.='	<div class="itemEditForm">
		<section class="formFields">
      		<div class="adminButton"><input type="submit" name="submit" value="Ενημέρωση"></div>
      	</section>
     	</div>
    </form>
</div>';

require_once 'template.php';
?>