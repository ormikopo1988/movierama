<?php
$__isAdminPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

function doTheExit()
{
    global $wo;
    $wo->db->commit();
    exit;
}

$requestedAction = 'read';
$pageLocation = '1';
$pageTitle = 'Administration Back End';

$wo = new WOOOF();
        
if (isset($_GET['__address']))
{
	$address = $wo->cleanUserInput($_GET['__address']);
}else if (isset($_POST['__address']))
{
	$address = $wo->cleanUserInput($_POST['__address']);
}else
{
	$address='1';
}

if (isset($_GET['action']))
{
	$action = $wo->cleanUserInput($_GET['action']);
}else if (isset($_POST['action']))
{
	$action = $wo->cleanUserInput($_POST['action']);
}else
{
	$action='read';
}

$content = '';
$extraScripts ='';

if ($address!='1')
{
	$security = $wo->db->getSecurityPermitionsForLocationAndUser($address, $userData['id']);
	
	if (!isset($security[$action]) || $security[$action]!=true)
	{
		//die('Δεν έχετε την απαιτούμενη έγκριση ασφάλειας για να προβείτε σε αυτή την ενέργεια. Θα πρέπει να κάνετε login με άλλο λογαριασμό για να προχωρήσετε.<br/>Αυτό το σφάλμα μπορεί να συμβεί επίσης αν ζητήσετε μια ενέργεια που δεν έχει οριστεί.');
		$wo->handleShowStopperError('You are not authorised for this action. Please login with an admin account.');
	}else
	{
		$addressItems = explode('_', $address);
		if (count($addressItems)<2 || $addressItems[0]!='1')
		{
			//$wo->reportError('Η διεύθυνση που δώσατε είναι εσφαλμένη. Δεν βρέθηκε το κατάλληλο descriptor.');
			$wo->handleShowStopperError('Wrong authorisation location.');
		}
		
		$table = new WOOOF_dataBaseTable($wo->db,'',$addressItems[1]);
		if ($action=='read' && count($addressItems)==2) // table listing requested
		{
			if ($table->getAdminPresentation()=='4')
			{
				$table2 = new WOOOF_dataBaseTable($wo->db,$table->getGroupedByTable());

				if (!isset($_GET[$table->getLocalGroupColumn()]) || $_GET[$table->getLocalGroupColumn()]=='')
				{
					if ($table2->getOrderingColumnForListings()!='')
					{
						$orderBy = $table2->getOrderingColumnForListings();
					}else
					{
						$orderBy = 'id';
					}

					$table2->getResult('', $orderBy, '', 1);

					$_GET[$table->getLocalGroupColumn()] = $table2->resultRows[0]['id'];
				}else
				{
					$_GET[$table->getLocalGroupColumn()] = $wo->cleanUserInput($_GET[$table->getLocalGroupColumn()]);
				}

				foreach ($table2->columns as $key => $value)
				{
					$column = $value->getColumnMetaData();
					if ($column['appearsInLists'])
					{
						$columnsToShow = $column['name'];
					}
				}

				$htmlFragment='<li><a class="treeItemLevel@@@level@@@" href="administration.php?__address=1_'. $table->getTableId() .'&'.$table->getLocalGroupColumn().'=@@@'. $table->getRemoteGroupColumn() .'@@@&wooofParent=@@@'. $table->getRemoteGroupColumn() .'@@@&action=read">@@@'. $columnsToShow .'@@@</a>
          @@@subItems@@@</li>';
          		$row = $wo->db->getRow($table2->getTableName(), $_GET[$table->getLocalGroupColumn()]);
                        if ($table->getHasDeletedColumn())
                        {
                            $where=' and isDeleted!=\'1\'';
                        }else
                        {
                            $where='';
                        }
				$content='<div id="treeDiv">'. $table2->presentTree($columnsToShow,$htmlFragment, $row['id']) .'</div><div id="listDiv">
				'. $wo->doTableList($table, ' where '. $table->getLocalGroupColumn() .'=\''. $wo->cleanUserInput($_GET[$table->getLocalGroupColumn()]) .'\' '. $where, $wo->cleanUserInput($_GET[$table->getLocalGroupColumn()])) .'</div>';

			}else if ($table->getAdminPresentation()=='3')
			{
				$content='<div class="listTitle"><a href="administration.php?action=edit&__address=1_'. $addressItems[1] .'_&wooofParent=-1">Προσθήκη Κεντρικού Κόμβου &nbsp;<img src="images/add.png" alt="Create new item" border="0" align="top"></a></div>';
				foreach ($table->columns as $key => $value)
				{
					$column = $value->getColumnMetaData();
					if ($column['appearsInLists'])
					{
						$columnsToShow = $column['name'];
					}
				}
				$htmlFragment='<li class="normalTreeItemLevel@@@level@@@">@@@'. $columnsToShow .'@@@ &nbsp;@@@activation@@@<a href="administration.php?__address=1_@@@tableId@@@_@@@id@@@&action=edit"><img border="0" align="top" alt="edit" src="images/edit.png"></a>@@@upDown@@@ &nbsp; <a href="javascript:confirmDelete(\'administration.php?__address=1_@@@tableId@@@_@@@id@@@&action=delete\');"><img border="0" align="top" alt="Delete" src="images/delete.png"></a>
          @@@subItems@@@</li>';
				$content.=$table->presentTree($columnsToShow,$htmlFragment,null,'catOn','catOff');
			}elseif ($table->getAdminPresentation()=='2')
			{

			}elseif ($table->getAdminPresentation()=='5')
			{
				require_once('improvedTree.php');
			}else
			{
                            if ($table->getHasDeletedColumn())
                            {
                                $where=' where isDeleted!=\'1\'';
                            }else
                            {
                                $where='';
                            }
				//print_r($table->columns);
				$content.= $wo->doTableList($table, $where);
			}

		}else if ($action=='edit' && count($addressItems)==3) // row edit or new row requested
		{
			$before = '';
			$after = '';

			$htmlFragment = $table->constructAdministrationFragment();

			if ($table->getColumnForMultipleTemplates()!='')
			{
				if (trim($addressItems[2])=='')
				{
					$htmlFragment[0] = file_get_contents($table->getTableName().'.template.inc.php');
				}else
				{
					$row = $wo->db->getRow($table->getTableName(),$wo->cleanUserInput($addressItems[2]));
					$htmlFragment[0] = file_get_contents($row[$table->getColumnForMultipleTemplate()] .'_'. $table->getTableName().'.template.inc.php');
				}
			}else if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.template.inc.php'))
			{
				$htmlFragment[0] = file_get_contents($table->getTableName().'.template.inc.php');
			}

				if ($table->getHasEmbededPictures() && trim($addressItems[2])!='')
				{
					$before = '<div id="formHolder">';
					$after = '</div><div id="pictureDiv">
					<iframe id="resultArea" scroll="auto" frameborder="0" seamless="seamless" src="handlePictureUpload.php?table='. $table->getTableId() .'&itemId='. $addressItems[2] .'"></iframe></div>';
				}
				if (trim($table->getAdminListMarkingCondition())!='' && trim($addressItems[2])!='')
				{
					$htmlFragment[0] = '<div class="previewEye">
					<a href="'. $siteBaseURL . $table->getAdminListMarkingCondition() .'">
					<img border="0" align="top" alt="Preview item." src="images/preview.png">
					</a>
					</div>'. $htmlFragment[0];
				}
			
			if (trim($addressItems[2])=='')
			{
				$htmlFragment[0] = str_replace('@@@id@@@', '', $htmlFragment[0]);
				if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.prePresentInsert.inc.php')) 
				{
					require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.prePresentInsert.inc.php';
				}
				$content = $table->presentRowForInsert($htmlFragment[0]);
				if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postPresentInsert.inc.php')) 
				{
					require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.prePresentInsert.inc.php';
				}
				$action='insert';
				$buttonValue='Εισαγωγή';
			}else
			{
				$htmlFragment[0] = str_replace('@@@id@@@', $addressItems[2], $htmlFragment[0]);
				if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.prePresentUpdate.inc.php')) 
				{
					require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.prePresentUpdate.inc.php';
				}
				$content = $table->presentRowForUpdate($addressItems[2],$htmlFragment[0]);
				if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postPresentUpdate.inc.php')) 
				{
					require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postPresentUpdate.inc.php';
				}
				$action='update';
				$buttonValue='Ενημέρωση';
			}
			if (isset($_GET['wooofParent']))
			{
				$extraHidden='<input type="hidden" name="wooofParent" value="'. $wo->cleanUserInput($_GET['wooofParent']) .'"/>';
			}elseif (isset($_GET['table']))
			{
				$extraHidden='<input type="hidden" name="table" value="'. $wo->cleanUserInput($_GET['table']) .'"/><input type="hidden" name="itemId" value="'. $wo->cleanUserInput($_GET['itemId']) .'"/>';
			}else
			{
				$extraHidden='';
			}
			
			if (isset($htmlFragment[1]) && $htmlFragment[1]!='')
			{
				$extraScripts .= $htmlFragment[1];
			}

			if (trim($table->getTablesGroupedByThis())!='' && trim($addressItems[2])!='')
			{
				$rowToEdit = $wo->db->getRow($table->getTableName(), trim($addressItems[2]));
				$pieces = explode(' ', $table->getTablesGroupedByThis());
				foreach ($pieces as $piece) 
				{
					$tableTmp = new WOOOF_dataBaseTable($wo->db,$piece);
					//$content .='<section class="formFields">'; //<div class="listTitle">'. $tableTmp->getTableDescription() .' &nbsp; &nbsp; <a href="administration.php?action=edit&__address=1_'. $tableTmp->getTableId() .'_&wooofParent='. $rowToEdit[$tableTmp->getRemoteGroupColumn()] .'">Προσθήκη &nbsp;<img src="images/add.png" alt="Create new item" border="0" align="top"></a></div>';
					if ($tableTmp->getHasDeletedColumn())
                                        {
                                            $where=' and isDeleted!=\'1\'';
                                        }else
                                        {
                                            $where='';
                                        }
                                        $content .= $wo->doTableList($tableTmp, ' where '. $tableTmp->getLocalGroupColumn() .' = \''. $rowToEdit[$tableTmp->getRemoteGroupColumn()] .'\''. $where, $rowToEdit[$tableTmp->getRemoteGroupColumn()]);
					//$content .= '</section>';
				}
			}

			$content = $before . '<form method="POST" action="administration.php" enctype="multipart/form-data"><div class="itemEditForm"><input type="hidden" name="__address" value="1_'. $addressItems[1] .'_'. $addressItems[2] .'"><input type="hidden" name="action" value="'. $action .'">'. $extraHidden .
			'</div>'. $content .'<div class="itemEditForm"><section class="formFields">
      <div class="adminButton"><input type="submit" name="submit" value="'. $buttonValue .'"></div>
      </section></div></form>';
      		$content.= $after;
		}else if ($action=='insert' && count($addressItems)==3) // saving of new row requested
		{
			$insertColumns = $table->getInsertableColumns();
			if ($table->getGroupedByTable()!='')
			{
				if (isset($_POST['wooofParent']))
				{
					$_POST[$table->getLocalGroupColumn()] = $_POST['wooofParent'];
				}
			}
			if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.preDoInsert.inc.php')) 
			{
				require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.preDoInsert.inc.php';

			}

			$theNewInsertId = $table->handleInsertFromPost($insertColumns);

			$targetRow = $wo->db->getRow($table->getTableName(), $theNewInsertId);

			if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postDoInsert.inc.php')) 
			{
				require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postDoInsert.inc.php';
			}

			if ($table->getGroupedByTable()!='' || (isset($_POST['table']) && isset($_POST['itemId'])))
			{
				if (isset($_POST['table']))
				{
					$parentTable = new WOOOF_dataBaseTable($wo->db, '', $wo->cleanUserInput($_POST['table']));
					$subtableMulti = 1;
				}else
				{
					$parentTable = new WOOOF_dataBaseTable($wo->db, $table->getGroupedByTable());
					$subtableMulti = 0;
				}
				if (stripos($parentTable->getTablesGroupedByThis(), $table->getTableName())!==false  && $_POST[$table->getLocalGroupColumn()] != '-1')
				{
					header('Location: administration.php?action=edit&__address=1_'. $parentTable->getTableId() .'_'. $_POST[$table->getLocalGroupColumn()]);
				}else if ($subtableMulti)
				{
					header('Location: administration.php?action=edit&__address=1_'. $parentTable->getTableId() .'_'.$_POST['itemId']);
				}else
				{
					header('Location: administration.php?action=read&__address=1_'. $table->getTableId() .'&wooofParent5='. $targetRow[$table->getLocalGroupColumn()] .'&'. $table->getLocalGroupColumn() .'='. $targetRow[$table->getLocalGroupColumn()]);
				}
			}else
			{
				header('Location: administration.php?action=read&__address=1_'. $table->getTableId());
			}
			doTheExit();
		}else if ($action=='update' && count($addressItems)==3) // saving of existing row requested
		{
			$insertColumns = $table->getInsertableColumns();
			if ($table->getGroupedByTable()!='')
			{
				if (isset($_POST['wooofParent']))
				{
					$_POST[$table->getLocalGroupColumn()] = $_POST['wooofParent'];
				}
			}
                        
                        $rowBeforeUpdate = $table->getRow($addressItems[2]);
                        
                        if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.preDoUpdate.inc.php')) 
			{
				require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.preDoUpdate.inc.php';
			}
                        
			$table->updateRowFromPost($addressItems[2],$insertColumns);
			
			$targetRow = $wo->db->getRow($table->getTableName(), $addressItems[2]);
			
			if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postDoUpdate.inc.php')) 
			{
				require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postDoUpdate.inc.php';
			}

			
			if ($table->getGroupedByTable()!='' || (isset($_POST['table']) && isset($_POST['itemId'])))
			{
				if (isset($_POST['table']))
				{
					$parentTable = new WOOOF_dataBaseTable($wo->db, '', $wo->cleanUserInput($_POST['table']));
					$subtableMulti = 1;
				}else
				{
					$parentTable = new WOOOF_dataBaseTable($wo->db, $table->getGroupedByTable());
					$subtableMulti = 0;
				}
				
				if (stripos($parentTable->getTablesGroupedByThis(), $table->getTableName())!==false && $_POST[$table->getLocalGroupColumn()] != '-1')
				{
					header('Location: administration.php?action=edit&__address=1_'. $parentTable->getTableId() .'_'. $_POST[$table->getLocalGroupColumn()]);
				}else if ($subtableMulti)
				{
					header('Location: administration.php?action=edit&__address=1_'. $parentTable->getTableId() .'_'.$_POST['itemId']);
				}else
				{
					header('Location: administration.php?action=read&__address=1_'. $table->getTableId() .'&wooofParent6='. $targetRow[$table->getLocalGroupColumn()] .'&'. $table->getLocalGroupColumn() .'='. $targetRow[$table->getLocalGroupColumn()]);
				}
			}else
			{
				header('Location: administration.php?action=read&__address=1_'. $table->getTableId());
			}
			doTheExit();
		}else if ($action=='delete' && count($addressItems)>2) // row(s) deletion requested
		{
			for($q=2; $q<count($addressItems); $q++)
			{ //TODO: check for permitions to delete multiple items here
				$targetRow = $wo->db->getRow($table->getTableName(), $addressItems[$q]);

				if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.preDelete.inc.php')) 
				{
					require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.preDelete.inc.php';
				}

				$table->deleteRow($addressItems[$q]);
				
				if (file_exists($wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postDelete.inc.php')) 
				{
					require $wo->getConfigurationFor('siteBasePath') . $wo->getConfigurationFor('adminIncludesDirectory') . $table->getTableName().'.postDelete.inc.php';
				}
			}
			if ($table->getGroupedByTable()!='')
			{
				$table2 = new WOOOF_dataBaseTable($wo->db,$table->getGroupedByTable());
				if (stripos($table2->getTablesGroupedByThis(), $table->getTableName())!==null && $_GET['from']=='edit')
				{
					header('Location: administration.php?action=edit&__address=1_'. $table2->getTableId().'_'. $targetRow[$table->getLocalGroupColumn()]);
				}else
				{
					if ($table->getGroupedByTable()!='')
					{
						$extraURLBit='&wooofParent='. $targetRow[$table->getLocalGroupColumn()] .'&'. $table->getLocalGroupColumn() .'='. $targetRow[$table->getLocalGroupColumn()];
					}else
					{
						$extraURLBit='';
					}
					header('Location: administration.php?action=read&__address=1_'. $table->getTableId().$extraURLBit);
				}
			}else
			{
				header('Location: administration.php?action=read&__address=1_'. $table->getTableId());
			}
			doTheExit();
		}else if ($action=='activate' && count($addressItems)>2) // row(s) activation requested
		{
			if ($table->getHasActivationFlag())
			{
				for($q=2; $q<count($addressItems); $q++)
				{
					$wo->db->query('update '. $table->getTableName() .' set active=\'1\' where id=\''. $addressItems[$q] .'\'');
				}
			}
			$targetRow = $wo->db->getRow($table->getTableName(), $addressItems[2]);

			if ($table->getGroupedByTable()!='')
			{
				$table2 = new WOOOF_dataBaseTable($wo->db,$table->getGroupedByTable());
				if ($table2->getAdminPresentation()==WOOOF_tablePresentationTypes::CompositeTree)
				{
					if ($targetRow[$table->getLocalGroupColumn()]!='-1')
					{
						header('Location: administration.php?action=edit&__address=1_'. $table2->getTableId() .'_'. $targetRow[$table->getLocalGroupColumn()] .'&parentNode='. $targetRow[$table->getLocalGroupColumn()]);
					}else
					{
						header('Location: improvedTree.php?action=read&__address=1_'. $table2->getTableId() .'&parentNode='. $targetRow[$table->getLocalGroupColumn()]);
					}
					doTheExit();
				}else if (stripos($table2->getTablesGroupedByThis(), $table->getTableName())!==null && $_GET['from']=='edit')
				{
					header('Location: administration.php?action=edit&__address=1_'. $table2->getTableId().'_'. $targetRow[$table->getLocalGroupColumn()]);
				}else
				{
					if ($table->getGroupedByTable()!='')
					{
						$extraURLBit='&wooofParent='. $targetRow[$table->getLocalGroupColumn()] .'&'. $table->getLocalGroupColumn() .'='. $targetRow[$table->getLocalGroupColumn()];
					}else
					{
						$extraURLBit='';
					}
					header('Location: administration.php?action=read&__address=1_'. $table->getTableId().$extraURLBit);
				}
			}else
			{
				header('Location: administration.php?action=read&__address=1_'. $table->getTableId());
			}
			doTheExit();
		}else if ($action=='deactivate' && count($addressItems)>2) // row(s) edit requested
		{
			if ($table->getHasActivationFlag())
			{
				for($q=2; $q<count($addressItems); $q++)
				{ //TODO: check for permitions to deactivate multiple items here
					$wo->db->query('update '. $table->getTableName() .' set active=\'0\' where id=\''. $addressItems[$q] .'\'');
				}
			}
			$targetRow = $wo->db->getRow($table->getTableName(), $addressItems[2]);
			if ($table->getGroupedByTable()!='')
			{
				$table2 = new WOOOF_dataBaseTable($wo->db,$table->getGroupedByTable());
				if ($table2->getAdminPresentation()==WOOOF_tablePresentationTypes::CompositeTree)
				{
					if ($targetRow[$table->getLocalGroupColumn()]!='-1')
					{
						header('Location: administration.php?action=edit&__address=1_'. $table2->getTableId() .'_'. $targetRow[$table->getLocalGroupColumn()] .'&parentNode='. $targetRow[$table->getLocalGroupColumn()]);
					}else
					{
						header('Location: improvedTree.php?action=read&__address=1_'. $table2->getTableId() .'&parentNode='. $targetRow[$table->getLocalGroupColumn()]);
					}
					doTheExit();
				}else if (stripos($table2->getTablesGroupedByThis(), $table->getTableName())!==null && $_GET['from']=='edit')
				{
					header('Location: administration.php?action=edit&__address=1_'. $table2->getTableId().'_'. $targetRow[$table->getLocalGroupColumn()]);
				}else
				{
					if ($table->getGroupedByTable()!='')
					{
						$extraURLBit='&wooofParent='. $targetRow[$table->getLocalGroupColumn()] .'&'. $table->getLocalGroupColumn() .'='. $targetRow[$table->getLocalGroupColumn()];
					}else
					{
						$extraURLBit='';
					}
					header('Location: administration.php?action=read&__address=1_'. $table->getTableId().$extraURLBit);
				}
			}else
			{
				header('Location: administration.php?action=read&__address=1_'. $table->getTableId());
			}
			doTheExit();
		}else if ($action=='moveUp' && count($addressItems)==3) // row move up in order requested
		{
			$targetRow = $wo->db->getRow($table->getTableName(),$addressItems[2]);
			$ordering = $table->getOrderingColumnForListings();

			if ($table->getGroupedByTable()!='')
			{
				$table2 = new WOOOF_dataBaseTable($wo->db,$table->getGroupedByTable());
				if ($table2->getAdminPresentation()==WOOOF_tablePresentationTypes::CompositeTree)
				{
					if ($targetRow[$table->getLocalGroupColumn()]!='-1')
					{
						$header = 'Location: administration.php?action=edit&__address=1_'. $table2->getTableId() .'_'. $targetRow[$table->getLocalGroupColumn()] .'&parentNode='. $targetRow[$table->getLocalGroupColumn()];
					}else
					{
						$header = 'Location: improvedTree.php?action=read&__address=1_'. $table2->getTableId() .'&parentNode='. $targetRow[$table->getLocalGroupColumn()];
					}
				}else if (stripos($table2->getTablesGroupedByThis(), $table->getTableName())!==null && $_GET['from']=='edit')
				{
					$header = 'Location: administration.php?action=edit&__address=1_'. $table2->getTableId().'_'. $targetRow[$table->getLocalGroupColumn()];
				}else
				{
					$header = '';
				}
				$extraURLBit='&wooofParent='. $targetRow[$table->getLocalGroupColumn()] .'&'. $table->getLocalGroupColumn() .'='. $targetRow[$table->getLocalGroupColumn()];
				$extraQueryBit = ' and '. $table->getLocalGroupColumn() .'=\''. $targetRow[$table->getLocalGroupColumn()] .'\' ';
			}else
			{
				$header='';
				$extraURLBit='';
				$extraQueryBit = '';
			}

			if (stripos($ordering, 'desc'))
			{
				//echo 'has DESC<br/>';
				$ordering2 = trim(str_replace('desc', '', $ordering));
				$beforeR = $wo->db->query('select * from '. $table->getTableName() .' where '. $ordering2 .' > \''. $wo->db->escape($targetRow[$ordering2]) .'\' '. $extraQueryBit .' order by '. $ordering2 .' limit 1');
				//echo 'select * from '. $table->getTableName() .' where '. $ordering2 .' > \''. $wo->db->escape($targetRow[$ordering2]) .'\' '. $extraQueryBit .' order by '. $ordering .' limit 1<br/>';
				$ordering = $ordering2;
			}else
			{
				$beforeR = $wo->db->query('select * from '. $table->getTableName() .' where '. $ordering .' < \''. $wo->db->escape($targetRow[$ordering]) .'\' '. $extraQueryBit .' order by '. $ordering .' desc limit 1');
			}
			if (mysqli_num_rows($beforeR))
			{
				$before = $wo->db->fetchAssoc($beforeR);
				//echo $targetRow['title'] .'<br/>'. $before['title'] .'<br/>';
				$wo->db->query('update '. $table->getTableName() .' set '. $ordering .'=\''. $targetRow[$ordering] .'\' where id=\''. $before['id'] .'\'');
				$wo->db->query('update '. $table->getTableName() .' set '. $ordering .'=\''. $before[$ordering] .'\' where id=\''. $targetRow['id'] .'\'');
			}
			if ($header!='')
			{
				header($header);
			}else
			{
				header('Location: administration.php?action=read&__address=1_'. $table->getTableId() . $extraURLBit);
			}
			doTheExit();
		}else if ($action=='moveDown' && count($addressItems)==3) // row move down in order requested
		{
			$targetRow = $wo->db->getRow($table->getTableName(),$addressItems[2]);
			$ordering = $table->getOrderingColumnForListings();
			
			if ($table->getGroupedByTable()!='')
			{
				$table2 = new WOOOF_dataBaseTable($wo->db,$table->getGroupedByTable());
				if ($table2->getAdminPresentation()==WOOOF_tablePresentationTypes::CompositeTree)
				{
					if ($targetRow[$table->getLocalGroupColumn()]!='-1')
					{
						$header = 'Location: administration.php?action=edit&__address=1_'. $table2->getTableId() .'_'. $targetRow[$table->getLocalGroupColumn()] .'&parentNode='. $targetRow[$table->getLocalGroupColumn()];
					}else
					{
						$header = 'Location: improvedTree.php?action=read&__address=1_'. $table2->getTableId() .'&parentNode='. $targetRow[$table->getLocalGroupColumn()];
					}
				}else if (stripos($table2->getTablesGroupedByThis(), $table->getTableName())!==null && $_GET['from']=='edit')
				{
					$header = 'Location: administration.php?action=edit&__address=1_'. $table2->getTableId().'_'. $targetRow[$table->getLocalGroupColumn()];
				}else
				{
					$header = '';
				}
				$extraURLBit = '&wooofParent='. $targetRow[$table->getLocalGroupColumn()] .'&'. $table->getLocalGroupColumn() .'='. $targetRow[$table->getLocalGroupColumn()];
				$extraQueryBit = ' and '. $table->getLocalGroupColumn() .'=\''. $targetRow[$table->getLocalGroupColumn()] .'\' ';
			}else
			{
				$extraURLBit = '';
				$extraQueryBit = '';
				$header='';
			}

			if (stripos($ordering, 'desc'))
			{
				//echo 'has DESC<br/>';
				$ordering2 = trim(str_replace('desc', '', $ordering));
				$beforeR = $wo->db->query('select * from '. $table->getTableName() .' where '. $ordering2 .' < \''. $wo->db->escape($targetRow[$ordering2]) .'\' '. $extraQueryBit .' order by '. $ordering .' limit 1');
				$ordering = $ordering2;
			}else
			{
				//echo 'has NO desc<br/>';
				$beforeR = $wo->db->query('select * from '. $table->getTableName() .' where '. $ordering .' > \''. $wo->db->escape($targetRow[$ordering]) .'\' '. $extraQueryBit .' order by '. $ordering .' limit 1');
			}
			
			if (mysqli_num_rows($beforeR))
			{
				$before = $wo->db->fetchAssoc($beforeR);
				//echo 'inside the swap<br/>'. $targetRow['title'] .'<br/>'. $before['title'];
				$wo->db->query('update '. $table->getTableName() .' set '. $ordering .'=\''. $targetRow[$ordering] .'\' where id=\''. $before['id'] .'\'');
				$wo->db->query('update '. $table->getTableName() .' set '. $ordering .'=\''. $before[$ordering] .'\' where id=\''. $targetRow['id'] .'\'');
			}
			
			if ($header!='')
			{
				header($header);
			}else
			{
				header('Location: administration.php?action=read&__address=1_'. $table->getTableId(). $extraURLBit);
			}
			doTheExit();
		}else if ($action=='emptyPicture' && count($addressItems)==4)
		{

		}else
		{
			require('firstPage.php');
		}
	}

}else
{
	require('firstPage.php');
}

if (isset($_GET['noPageWarper']) && $_GET['noPageWarper']=='true')
{
	echo $content;
	doTheExit();
}else
{
	require 'template.php';
}

?>