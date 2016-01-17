<?php
$__isAdminPage = true;
$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction = 'read';
$pageLocation = '1';
$pageTitle = 'Administration Back End';

$tableName = 'pictures';
$columnName = 'picture';
$remoteIdColumn = 'itemId';

$wo = new WOOOF();

if (isset($_GET['itemId']))
{
	$_POST['itemId']=$wo->cleanUserInput($_GET['itemId']);
}else if (isset($_POST['itemId']))
{
	$_POST['itemId']=$wo->cleanUserInput($_POST['itemId']);
}else
{
	die('severe error! no ITEM ID!');
}

if (isset($_GET['table']))
{
	$_POST['table']=$wo->cleanUserInput($_GET['table']);
}else if (isset($_POST['table']))
{
	$_POST['table']=$wo->cleanUserInput($_POST['table']);
}else
{
	die('severe error! no TABLE ID!');
}

if (isset($_GET['__address']))
{
	$address = $wo->cleanUserInput($_GET['__address']);
}elseif (isset($_POST['__address']))
{
	$address = $wo->cleanUserInput($_POST['__address']);
}

if (isset($_GET['action']))
{
	$_GET['action'] = $wo->cleanUserInput($_GET['action']);
}elseif (isset($_POST['action']))
{
	$_GET['action'] = $wo->cleanUserInput($_POST['action']);
}else
{
	$_GET['action']='';
}

$table = new WOOOF_dataBaseTable($wo->db, '', $_POST['table']);
$pictureTable = new WOOOF_dataBaseTable($wo->db,$tableName);

echo '<html>
<head>
<link rel="stylesheet" type="text/css" href="css/admin.css">
      <script src="jquery-1.9.1.js"></script>
      <script src="jquery-ui.js"></script>
      <script src="jquery.bpopup.min.js"></script>
      <script>
function confirmDelete(url)
        {
          $(\'#titleModal\').html(\'Πρόκειται να γίνει μη αναστρέψιμη διαγραφή!\')
          $(\'#textModal\').html(\'Σίγουρα θέλετε να προχωρήσετε στην διαγραφή του αντικειμένου; Η κίνηση αυτή δεν αντιστρέφεται...\');
          window.confirmedURLToGo = url;
          $(\'#modal\').addClass(\'redBorder\');
          $(\'#modal\').bPopup();
        }
        function closePopup()
        {
          $(\'#modal\').bPopup().close();
        }
        function popUpConfirmed()
        {
          window.location = window.confirmedURLToGo;
        }
      </script>
</head>
<body><div id="modal" style="display: none;">
        
        <span class="titleModal" id="titleModal">Αν θες δίνε και Tίτλο Modal Window </span><br />
        <div id="textModal">Κείμενο μηνύματος βνα ωσ χψβ αξηβσω ξαηβσ χξηαβσ χξηαβχ ηξα κξασ κξαβν σ</div><br />
        <input class="modalButton" type="button" name="button" value="No" onClick="javascript:closePopup();">
     &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; 
        <input class="modalButton" type="button" name="button" value="Yes" onClick="javascript:popUpConfirmed();">        
     </div><div>
';
if (isset ($_FILES['file']))
{
	if ($_FILES['file']['type']=='image/jpeg'
	|| $_FILES['file']['type']=='image/png'
	|| $_FILES['file']['type']=='image/gif'
	|| $_FILES['file']['type']=='application/octet'
	|| $_FILES['file']['type']=='application/pdf'
	|| $_FILES['file']['type']=='application/x-pdf')
	{
		$prefix = $wo->randomString(20);

		$fileInfo = pathinfo($_FILES['file']['name']);
		if (($_FILES['file']['type']=='application/octet' || $_FILES['file']['type']=='application/pdf' || $_FILES['file']['type']=='application/x-pdf') && $fileInfo['extension']!= 'pdf' && $fileInfo['extension']!= 'PDF')
		{
			echo $_FILES['file']['type'] .' '. $fileInfo['extension'] .' BAD file type. File deleted.<br/><br/>';
			unlink($_FILES['file']['tmp_name']);
			exit;
		}
		$insertId = $wo->db->getNewId($tableName);
		
		$maxR = $wo->db->query('select max(ord) from '. $tableName );
		$max = $wo->db->fetchRow($maxR);
		$max = $max[0] + 10;
		if (!isset($_POST['entry_date']))
		{
			$entryDate = WOOOF::getCurrentDateTime();
		}else
		{
			$entryDate = $wo->cleanUserInput($_POST['entry_date']);
		}
		
		if (file_exists($table->getTableName().'.prePictureUploadOperations.inc.php'))
		{
			require $wo->getConfigurationFor($siteBasePath) . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.prePictureUploadOperations.inc.php';
		}

		if ($_FILES['file']['type']=='application/octet' || $_FILES['file']['type']=='application/pdf' || $_FILES['file']['type']=='application/x-pdf')
		{
			$isPDF = ', isPDF=\'1\'';
		}else
		{
			$isPDF = ', isPDF=\'0\'';
		}

		move_uploaded_file($_FILES['file']['tmp_name'], $siteBasePath . $imagesRelativePath . $prefix . $_FILES['file']['name']);
		//WOOOF::cropCenterOfPicture($siteBasePath . $imagesRelativePath . $prefix . $_FILES['file']['name'], $siteBasePath . $imagesRelativePath . $prefix .'thumb_' . $_FILES['file']['name'], 120, 77);
		WOOOF::cropPictureAndResize($siteBasePath . $imagesRelativePath . $prefix . $_FILES['file']['name'], $siteBasePath . $imagesRelativePath . $prefix .'thumb_' . $_FILES['file']['name'], 280, 180);
		$wo->db->query('insert into '. $tableName .' set '. $columnName .'=\''. $wo->db->escape($prefix . $_FILES['file']['name']) .'\', '. $remoteIdColumn .' = \''. $_POST['itemId'] .'\', objectId=\''. $_POST['table'] .'\', id = \''. $insertId .'\', entryDate=\''. $entryDate .'\', description=\''. $wo->cleanUserInput($_POST['description']) .'\', description_en=\''. $wo->cleanUserInput($_POST['description_en']) .'\', ord=\''. $max .'\', thumbnail=\''. $wo->cleanUserInput( $prefix .'thumb_' . $_FILES['file']['name']) .'\' '. $isPDF);
		
		if (file_exists($table->getTableName().'.postPictureUploadOperations.inc.php'))
		{
			require $wo->getConfigurationFor($siteBasePath) . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postPictureUploadOperations.inc.php';
		}
		
		if (!$wo->db->error())
		{
			echo "File Upload OK! <br/><br/>";
			/*if ($fileInfo['extension']=='pdf' || $fileInfo['extension']!= 'PDF')
			{
				echo '<br/><div>images/'. $prefix . $_FILES['file']['name'] .'</div><br/>';
			}else
			{
				echo '<div><img src="images/'. $prefix . $_FILES['file']['name'] .'" width="200" height="150"></div><br/>';
			}*/
		}
	}else if (isset($_FILES['file']['name']))
	{
		echo $_FILES['file']['type'] .' '. $fileInfo['extension'] .' BAD file type. File deleted.<br/><br/>';
		unlink($_FILES['file']['tmp_name']);
	}
}else if ($_GET['action']=='deleteItem')
{
	$addressItems = explode('_', $address);
	$iR = $wo->db->query('select * from '. $tableName .' where id=\''. $wo->cleanUserInput($addressItems[2]) .'\'');
	if (mysqli_num_rows($iR))
	{
		$i = $wo->db->fetchAssoc($iR);
		if (file_exists($siteBasePath . $imagesRelativePath . $i[$columnName]))
		{
			unlink($siteBasePath . $imagesRelativePath .  $i[$columnName]);
		}
		if ($i['thumbnail']!='')
		{
			@unlink($imageAbsolutePath .  $i['thumbnail']);
		}
		$wo->db->query('delete from '. $tableName .' where id=\''. $wo->cleanUserInput($addressItems[2]) .'\'');
	}
}else if ($_GET['action']=='moveUp')
{
	$addressItems = explode('_', $address);
	$targetRow = $wo->db->getRow($tableName, $addressItems[2]);
	//print_r($targetRow);
	$ordering = 'ord';
	$beforeR = $wo->db->query('select * from '. $tableName .' where '. $ordering .' < \''. $wo->db->escape($targetRow[$ordering]) .'\' and objectId=\''. $table->getTableId() .'\' and itemId=\''. $_POST['itemId'] .'\' order by '. $ordering .' desc limit 1');
	if (mysqli_num_rows($beforeR))
	{
		$before = $wo->db->fetchAssoc($beforeR);
		//echo 'inside the swap<br/>'. $targetRow['title'] .'<br/>'. $before['title'] .'<br/>';
		$wo->db->query('update '. $tableName .' set '. $ordering .'=\''. $targetRow[$ordering] .'\' where id=\''. $before['id'] .'\'');
		$wo->db->query('update '. $tableName .' set '. $ordering .'=\''. $before[$ordering] .'\' where id=\''. $targetRow['id'] .'\'');
	}
}else if ($_GET['action']=='moveDown')
{
	$addressItems = explode('_', $address);
	$targetRow = $wo->db->getRow($tableName, $addressItems[2]);
	//print_r($targetRow);
	$ordering = 'ord';
	$beforeR = $wo->db->query('select * from '. $tableName .' where '. $ordering .' > \''. $wo->db->escape($targetRow[$ordering]) .'\' and objectId=\''. $table->getTableId() .'\' and itemId=\''. $_POST['itemId'] .'\' order by '. $ordering .' limit 1');
	if (mysqli_num_rows($beforeR))
	{
		$before = $wo->db->fetchAssoc($beforeR);
		//echo 'inside the swap<br/>'. $targetRow['title'] .'<br/>'. $before['title'] .'<br/>';
		$wo->db->query('update '. $tableName .' set '. $ordering .'=\''. $targetRow[$ordering] .'\' where id=\''. $before['id'] .'\'');
		$wo->db->query('update '. $tableName .' set '. $ordering .'=\''. $before[$ordering] .'\' where id=\''. $targetRow['id'] .'\'');
	}
}else if ($_GET['action']=='activate')
{

}else if ($_GET['action']=='deactivate')
{

}

echo '<form method="POST" action="'. $_SERVER['PHP_SELF'] .'" enctype="multipart/form-data"><input type="file" name="file"><input type="hidden" name="table" value="'. $_POST['table'] .'"><input type="hidden" name="'. $remoteIdColumn .'" value="'. $_POST['itemId'] .'"><br/><input type="text" name="description" value="" placeholder="Περιγραφή"><br/><input type="text" name="description_en" value="" placeholder="Περιγραφή Αγγλικά"><br/><input type="submit" name="submit" value="Upload"></form>';

$sPR = $wo->db->query('select * from '. $tableName .' where '. $remoteIdColumn .'=\''. $_POST['itemId'] .'\' and objectId=\''. $_POST['table'] .'\' order by ord');
while($sP = $wo->db->fetchAssoc($sPR)) 
{
  $fileInfo = pathinfo($sP['picture']);
  if ($fileInfo['extension']=='pdf' || $fileInfo['extension']== 'PDF')
  {
    echo '<br/><div><a href="'. $siteBaseURL . $imagesRelativePath . $sP['picture'] .'" style="font-family: verdana; font-size: 12px; color: #000000;"><img src="../assets/pdfIcon.jpg" width="22" height="22"/>'. substr($sP['picture'], 20) .'</a> <br/>';
  }else
  {
    echo '<div><img src="'. $siteBaseURL . $imagesRelativePath . $sP['picture'] .'" width="88" height="62"> ';
  }
$extraURLBit='&table='. $table->getTableId() .'&'. $remoteIdColumn .'='. $_POST['itemId'];
$template='';
/*
	if ($row['active']=='1')
	{
	    $template.='<a href="'. $_SERVER['PHP_SELF'] .'?action=deactivate&__address=1_'. $pictureTable->getTableName() .'_'. $sP['id'] .'" class="on">Active</a>
	';
	}else
	{
	    $template.='<a href="'. $_SERVER['PHP_SELF'] .'?action=activate&__address=1_'. $pictureTable->getTableName() .'_'. $sP['id'] .'" class="off">Inctive</a>
	';
	}
*/
	$template.='<a href="administration.php?&__address=1_'. $pictureTable->getTableId() .'_'. $sP['id'] .'&action=edit'. $extraURLBit .'" target="_parent"><img src="images/edit.png" border="0" alt="Edit this item."></a>
	';
	$template.='<a href="'. $_SERVER['PHP_SELF'] .'?action=moveUp&__address=1_'. $pictureTable->getTableId() .'_'. $sP['id']. $extraURLBit .'"><img src="images/arrowUp.png" border="0" alt="Up this item in order"></a><a href="'. $_SERVER['PHP_SELF'] .'?action=moveDown&__address=1_'. $pictureTable->getTableName() .'_'. $sP['id'] . $extraURLBit .'"><img src="images/arrowDown.png" border="0" alt="Down this item in order"></a>
	';
	$template.='    <a href="javascript:confirmDelete(\''. $_SERVER['PHP_SELF'] .'?__address=1_'. $pictureTable->getTableId() .'_'. $sP['id']. $extraURLBit .'&action=deleteItem\');"><img src="images/delete.png" border="0" alt="Delete this item"></a>
	    </div>
	';
	echo $template .' </div><br/>';
}
echo '</div></body></html>';

?>