<?php
/*



*/

if (!isset($table))
{
    $__isAdminPage = true;

    $__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
    $__actualPath = dirname($__actualPath);

    require_once $__actualPath . '/setup.inc.php';

    $requestedAction = 'read';
    $pageLocation = '1';
    $pageTitle = 'Administration Back End';

    $wo = new WOOOF();
    $table = new WOOOF_dataBaseTable($wo->db,'categories');
    $addressItems = explode('_', $wo->cleanUserInput($_GET['__address']));
}

$counter=0;
while($table->columns[$counter]->getAppearsInLists() != '1')
{
    $counter++;
}

$path='';

                foreach ($table->columns as $key => $value)
                {
                    $column = $value->getColumnMetaData();
                    ;
                    if ($column['appearsInLists'])
                    {
                        $columnsToShow = $column['name'];
                    }
                }

if (count($addressItems)==2 || $addressItems[2]=='')
{
    $whereClauses['parent_id'] = '-1';
	$table->getResult($whereClauses,'ord');
    //$innerStuff ='<div id="listTitle"><a href="administration.php?action=edit&__address=1_'. $table->getTableId() .'_&wooofParent=-1">Προσθήκη '. $table->getTableDescription() .'</a></div>';
    $actualRow['name']='Μενού';
    $additionLabel = '<a href="administration.php?action=edit&__address=1_'. $table->getTableId() .'_&wooofParent=-1">Προσθήκη '. $table->getTableDescription() .'</a>';
}else
{
    $actualRow = $wo->db->getRow($table->getTableName(), $addressItems[2]);
    $whereClauses['parent_id'] = $addressItems[2];
    $table->getResult($whereClauses,$table->getOrderingColumnForListings());

    $current_row['parent_id'] =  $addressItems[2];
    $treeLevel = 0;

    do
    {
        $treeLevel++; 
        $current_row = $wo->db->getRow($table->getTableName(), $current_row['parent_id']);
        if ($addressItems[2]==$current_row['id'])
        {
            $path = ' > '. $current_row[$table->columns[$counter]->getName()] . $path;
        }else
        {
            $path = ' > <a href="improvedTree.php?__address=1_'. $table->getTableId() .'_'. $current_row['id'] .'&action=read">'. $current_row[$table->columns[$counter]->getName()] .'</a>'. $path;
        }
    }while($current_row['parent_id']!='-1');

    $path = '<div id="pathDivision"><a href="improvedTree.php?__address=1_'. $table->getTableId() .'&action=read">'. $table->getTableDescription().'</a>'. $path .'</div>';
    $additionLabel = 'Προσθήκη υπομενού στο "'. $actualRow[$columnsToShow] .'"';
}    

if (isset($actualRow['id']) && trim($actualRow['categoryHandling'])!='')
{
    $content=$path .'<div class="listTitle">"'. $actualRow[$columnsToShow] .'" είναι σελίδα. Στις σελίδες δεν μπορείτε να προσθέσετε υπομενού ή άρθρα.</div>';
}else
{
    if (count($addressItems)==2)
    {
        $wooofParent='-1';
    }else
    {
        $wooofParent=$addressItems[2];
    }
    $content=$path .'<div class="listTitle"><a href="administration.php?action=edit&__address=1_'. $addressItems[1] .'_&wooofParent='. $wooofParent .'">'. $additionLabel .' &nbsp;<img src="images/add.png" alt="Create new item" border="0" align="top"></a></div>';

                $htmlFragment='<li class="normalTreeItemLevel@@@level@@@">@@@'. $columnsToShow .'@@@ &nbsp;@@@activation@@@<a href="administration.php?__address=1_@@@tableId@@@_@@@id@@@&action=edit"><img border="0" align="top" alt="edit" src="images/edit.png"></a>@@@upDown@@@ &nbsp; <a href="javascript:confirmDelete(\'administration.php?__address=1_@@@tableId@@@_@@@id@@@&action=delete\');"><img border="0" align="top" alt="Delete" src="images/delete.png"></a>
          @@@subItems@@@</li>';

        if (count($addressItems)==2)
        {
            $whereClauses[$table->getLocalGroupColumn()]='-1';
        }else
        {
            $whereClauses[$table->getLocalGroupColumn()]=$addressItems[2];
        }
        $table->getResult($whereClauses,$table->getOrderingColumnForListings());
        $output='<ul class="treeLevel1">
';

        for ($i=0; $i < count($table->resultRows)/2; $i++) 
        { 
            if ($table->getHasActivationFlag())
            {
                if ($table->resultRows[$i]['active']=='1')
                {
                    $activation = '<a href="administration.php?action=deactivate&__address=1_'. $table->getTableId() .'_'. $table->resultRows[$i]['id'] .'" class="catOn">Active</a>';
                }else
                {
                    $activation = '<a href="administration.php?action=activate&__address=1_'. $table->getTableId() .'_'. $table->resultRows[$i]['id'] .'" class="catOff">Inactive</a>';
                }
            }else
            {
                $activation = '';
            }
            if (trim($table->getOrderingColumnForListings()!=''))
            {
                $upDown = ' <a href="administration.php?action=moveUp&__address=1_'. $table->getTableId() .'_'. $table->resultRows[$i]['id'] .'"><img src="images/arrowUp.png" border="0" alt="Up table item in order"></a><a href="administration.php?action=moveDown&__address=1_'. $table->getTableId() .'_'. $table->resultRows[$i]['id'] .'"><img src="images/arrowDown.png" border="0" alt="Down table item in order"></a>';
            }
            $tmp = str_replace('@@@'. $columnsToShow .'@@@', '<a href="administration.php?__address=1_'. $table->getTableId() .'_'. $table->resultRows[$i]['id'] .'&action=edit">'.$table->resultRows[$i][$columnsToShow].'</a>', $htmlFragment);
            $tmp = str_replace('@@@id@@@', $table->resultRows[$i]['id'], $tmp);
            $tmp = str_replace('@@@tableId@@@', $table->getTableId(), $tmp);
            $tmp = str_replace('@@@level@@@', 1, $tmp);
            $tmp = str_replace('@@@activation@@@', $activation, $tmp);
            $tmp = str_replace('@@@upDown@@@', $upDown, $tmp);
            $tmp = str_replace('@@@subItems@@@', '', $tmp);
            
            $output .= $tmp;
            /*
            $output.='<li class="treeItemLevel1">'. $this->resultRows[$i][$columnsToShow] .' &nbsp; '. $activation .' <a href="administration.php?__address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'&action=edit"><img border="0" align="top" alt="edit" src="images/edit.png"></a> &nbsp; <a href="javascript:confirmDelete(\'administration.php?__address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'&action=delete\');"><img border="0" align="top" alt="Delete" src="images/delete.png"></a>
          '. $this->presentTreeNode($this->resultRows[$i]['id'],$columnsToShow,2,$htmlFragment) .'</li>
';*/
        }
        $output.='</ul>
';
        $content.=$output;
/*
$innerStuff = '<br/>
    <div id="listTitle">';
    $innerStuff2 = '';
    $options = $wo->db->getRefinedOptions();


        $subtables = $table->getTablesGroupedByThis();
        $hasChildren = false;
        if (trim($subtables)!='')
        {
            $subtables = explode(' ', $subtables);
            for($sC=0; $sC<count($subtables);$sC++)
            {
                if ($subtables[$sC]!=$table->getTableName())
                {
                    $tableTmp = new WOOOF_dataBaseTable($wo->db, trim($subtables[$sC]));
                    $coR = $wo->db->query('select * from '. trim($subtables[$sC]) .' where '. $tableTmp->getLocalGroupColumn() .' = \''. $actualRow[$tableTmp->getRemoteGroupColumn()] .'\' limit 1');
                    if (mysqli_num_rows($coR))
                    {
                        $hasChildren = true;
                    }
                    $innerStuff2 .= $wo->doTableList($tableTmp, ' where '. $tableTmp->getLocalGroupColumn() .' = \''. $actualRow[$tableTmp->getRemoteGroupColumn()] .'\'','&wooofParent='. $actualRow[$tableTmp->getRemoteGroupColumn()]);
                }
            }
        }
    if ($options[$table->getTableName().'_canHaveSubnodesAndSubtableItems']!='0')
    {
        $displaySubItems = true;
        $displaySubTables = true;
    }else
    {
        $displaySubItems = false;
        $displaySubTables = false;
        if (count($table->resultRows)==0)
        {
            $displaySubTables = true;
        }

        if (!$hasChildren)
        {
            $displaySubItems = true;
        }
    }

    if ($options[$table->getTableName().'_maxTreeLevel']<=$treeLevel)
    {
        $displaySubItems = false;
    }

    if ( !$displaySubTables )
    {
        $innerStuff2 ='';
    }
    if ( $displaySubItems )
    {
        $innerStuff ='<a href="administration.php?action=edit&__address=1_'. $table->getTableId() .'_&wooofParent='. $addressItems[2] .'">Προσθήκη Υπό '. $table->getTableDescription() .' ('. $actualRow[$table->columns[$counter]->getName()] .')</a>
';
    }
    
    $innerStuff.='<br/><br/><a href="administration.php?__address=1_'. $table->getTableId() .'_'. $addressItems[2] .'&action=edit">Επεξεργασία Στοιχείων ('. $actualRow[$table->columns[$counter]->getName()] .')</a>
';
    $innerStuff.='<br/><br/><a href="javascript:confirmDelete(\'administration.php?__address=1_'. $table->getTableId() .'_'. $addressItems[2] .'&action=delete&from=read\');">Διαγραφή επιλογής "'. $actualRow[$table->columns[$counter]->getName()] .'"</a><br/><br/>
';
    

    
    $innerStuff.= $innerStuff2 .'    </div>';
}

$subCategories='<ul class="treeLevel1">
';
$htmlFragment='<li><div class="objectControls">
<a href="administration.php?__address=1_'. $table->getTableId() .'_@@@id@@@&action=edit"><img src="images/edit.png" /></a>
<a href="javascript:confirmDelete(\'administration.php?__address=1_'. $table->getTableId() .'_@@@id@@@&action=delete&from=read\');"><img src="images/delete.png" /></a>
</div>
<a class="treeItemLevel1" href="improvedTree.php?__address=1_'. $table->getTableId() .'_@@@id@@@&action=read">@@@'. $table->columns[$counter]->getName() .'@@@</a> 
          </li>';
$subCategories .= $table->presentResults($htmlFragment);
$subCategories .= '</ul>';
*/


/*$content=$path .'
<div id="treeDiv">'. $subCategories .'</div><div id="listDiv">
				'. $innerStuff .'</div>';
*/
}

require 'template.php';
?>