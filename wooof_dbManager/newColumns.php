<?php

$__isSiteBuilderPage = true;
$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='edit';
$pageLocation='1';

$wo = new WOOOF();
if ( !$wo->constructedOk ) { $wo->handleShowStopperError( "1000 Failed to init WOOOF." ); }

$tm = WOOOF::getCurrentDateTime();

$content='';

if (!isset($_GET['table']) && !isset($_POST['table']))
{
    header("Location: dbManager.php?tm=$tm");
    exit;
}else if ((isset($_GET['table']) && $_GET['table']=='') || (isset($_POST['table']) && $_POST['table']==''))
{
    header("Location: dbManager.php?tm=$tm");
    exit;
}

if (isset($_POST['table']))
{
    $_GET['table'] = $_POST['table'];
}

//used in order to be sure that the requested table exists
$t = new WOOOF_dataBaseTable($wo->db, $_GET['table']);

if ( $t->getIsView() == '1' ) {
	header("Location: dbManager.php?tm=$tm");
	exit;
}

$noOfColumns = 9;	// must be < 10

if (isset($_POST['submit']) && $_POST['submit']=='create')
{
    
    for($c=1; $c <= $noOfColumns; $c++)
    {
        if ($_POST['name'.$c] != '')
        {
            if (!isset($_POST['notNull'.$c]) || $_POST['notNull'.$c] !='1')
            {
                $_POST['notNull'.$c]='0';
            }
            if (!isset($_POST['isReadOnly'.$c]) || $_POST['isReadOnly'.$c] !='1')
            {
                $_POST['isReadOnly'.$c]='0';
            }
            if (!isset($_POST['isInvisible'.$c]) || $_POST['isInvisible'.$c] !='1')
            {
                $_POST['isInvisible'.$c]='0';
            }
            if (!isset($_POST['isASearchableProperty'.$c]) || $_POST['isASearchableProperty'.$c] !='1')
            {
                $_POST['isASearchableProperty'.$c]='0';
            }
            if (!isset($_POST['isReadOnlyAfterFirstUpdate'.$c]) || $_POST['isReadOnlyAfterFirstUpdate'.$c] !='1')
            {
                $_POST['isReadOnlyAfterFirstUpdate'.$c]='0';
            }
            if (!isset($_POST['isForeignKey'.$c]) || $_POST['isForeignKey'.$c] !='1')
            {
                $_POST['isForeignKey'.$c]='0';
            }
            if (!isset($_POST['appearsInLists'.$c]) || $_POST['appearsInLists'.$c] !='1')
            {
                $_POST['appearsInLists'.$c]='0';
            }
            if ($_POST['ordering'.$c]=='' || $_POST['ordering'.$c]=='0')
            {
                $oR = $wo->db->query('select max(ordering) from __columnMetaData where tableId=\''. $t->getTableId() .'\'');
                $o = mysqli_fetch_row($oR);
                $_POST['ordering'.$c] = (int)$o[0]+10;
            }
            $succ = $wo->db->query('INSERT INTO __columnMetaData set 
id=\''. $wo->db->getNewId('__columnMetaData') .'\',
tableId=\''. $t->getTableId() .'\',
name=\''. $wo->db->escape(trim($_POST['name'.$c])) .'\',
description=\''. $wo->db->escape(trim($_POST['description'.$c])) .'\',
type=\''. $wo->db->escape(trim($_POST['type'.$c])) .'\',
length=\''. $wo->db->escape(trim($_POST['length'.$c])) .'\',
notNull=\''. $wo->db->escape(trim($_POST['notNull'.$c])) .'\',
presentationType=\''. $wo->db->escape(trim($_POST['presentationType'.$c])) .'\',
isReadOnly=\''. $wo->db->escape(trim($_POST['isReadOnly'.$c])) .'\',
isInvisible=\''. $wo->db->escape(trim($_POST['isInvisible'.$c])) .'\',
appearsInLists=\''. $wo->db->escape(trim($_POST['appearsInLists'.$c])) .'\',
isASearchableProperty=\''. $wo->db->escape(trim($_POST['isASearchableProperty'.$c])) .'\',
isReadOnlyAfterFirstUpdate=\''. $wo->db->escape(trim($_POST['isReadOnlyAfterFirstUpdate'.$c])) .'\',
isForeignKey=\''. $wo->db->escape(trim($_POST['isForeignKey'.$c])) .'\',
presentationParameters=\''. $wo->db->escape(trim($_POST['presentationParameters'.$c])) .'\',
valuesTable=\''. $wo->db->escape(trim($_POST['valuesTable'.$c])) .'\',
columnToShow=\''. $wo->db->escape(trim($_POST['columnToShow'.$c])) .'\',
columnToStore=\''. $wo->db->escape(trim($_POST['columnToStore'.$c])) .'\',
defaultValue=\''. $wo->db->escape(trim($_POST['defaultValue'.$c])) .'\',
orderingMirror=\''. $wo->db->escape(trim($_POST['orderingMirror'.$c])) .'\',
searchingMirror=\''. $wo->db->escape(trim($_POST['searchingMirror'.$c])) .'\',
resizeWidth=\''. $wo->db->escape(trim($_POST['resizeWidth'.$c])) .'\',
resizeHeight=\''. $wo->db->escape(trim($_POST['resizeHeight'.$c])) .'\',
thumbnailWidth=\''. $wo->db->escape(trim($_POST['thumbnailWidth'.$c])) .'\',
thumbnailHeight=\''. $wo->db->escape(trim($_POST['thumbnailHeight'.$c])) .'\',
midSizeColumn=\''. $wo->db->escape(trim($_POST['midSizeColumn'.$c])) .'\',
midSizeWidth=\''. $wo->db->escape(trim($_POST['midSizeWidth'.$c])) .'\',
midSizeHeight=\''. $wo->db->escape(trim($_POST['midSizeHeight'.$c])) .'\',
thumbnailColumn=\''. $wo->db->escape(trim($_POST['thumbnailColumn'.$c])) .'\',
ordering=\''. $wo->db->escape(trim($_POST['ordering'.$c])) .'\',
adminCSS=\''. $wo->db->escape(trim($_POST['adminCSS'.$c])) .'\',
indexParticipation=\''. $wo->db->escape(trim($_POST['indexParticipation'.$c])) .'\',
colCollation=\''. $wo->db->escape(trim($_POST['colCollation'.$c])) .'\'
  ');
            if ( $succ === FALSE ) { $wo->handleShowStopperError(); }
            if ($_POST['isForeignKey'.$c] == '1')
            {
                $foreignKeyExists = FALSE;
                $result = $wo->db->query('SHOW INDEX FROM '. $t->getTableName());
                while($row = $wo->db->fetchAssoc($result))
                {
                    if ($row['Key_name'] == 'FK_'. $t->getTableName() .'_'. $wo->db->escape(trim($_POST['name'.$c])))
                    {
                        $foreignKeyExists = TRUE;
                    }
                }
                if ($foreignKeyExists)
                {
                    $wo->db->query('DROP FOREIGN KEY FK_'. $t->getTableName() .'_'. $wo->db->escape(trim($_POST['name'.$c])));
                }
                $succ = $wo->db->query('ALTER TABLE '. $t->getTableName() .' ADD FOREIGN KEY FK_'. $t->getTableName() .'_'. $wo->db->escape(trim($_POST['name'.$c])).
                ' REFERENCES '. $wo->db->escape(trim($_POST['valuesTable'.$c])) .' ('. $wo->db->escape(trim($_POST['columnToStore'.$c])) .')
    ON DELETE RESTRICT
    ON UPDATE CASCADE');
            }
            if ( $succ === FALSE ) { $wo->handleShowStopperError(); }
            
            $query='ALTER TABLE '. $t->getTableName() .' ADD COLUMN '. $wo->db->escape(trim($_POST['name'.$c])) .' '. WOOOF_dataBaseColumnTypes::getColumnTypeLiteral($wo->db->escape(trim($_POST['type'.$c])));
            if ($wo->db->escape(trim($_POST['length'.$c]))!='')
            {
                $query.='('.$wo->db->escape(trim($_POST['length'.$c])).')';
            }
            if ($wo->db->escape(trim($_POST['notNull'.$c])) == '1')
            {
                $query .= ' NOT NULL ';
            }
            if ($wo->hasContent($wo->db->escape(trim($_POST['defaultValue'.$c]))))
            {
                $query .= ' DEFAULT \''. $wo->db->escape(trim($_POST['defaultValue'.$c])) .'\'';
            }
            if ($wo->hasContent($wo->db->escape(trim($_POST['colCollation'.$c]))))
            {
                $query .= ' COLLATE \''. $wo->db->escape(trim($_POST['colCollation'.$c])) .'\'';
            }
            $succ = $wo->db->query($query);
            if ( $succ === FALSE ) { $wo->handleShowStopperError(); }
            
        }
    }
    $wo->db->commit();
 
    header("Location: dbManager.php?tm=$tm#".$_GET['table']);
    exit;
}

$content=
'<br/><a href="dbManager.php?tm='.$tm. '#'.$_GET['table'].'" class="normalTextCyan">Back to Main Page</a>
<br/><br/>
<h3>Add Columns to Table ['.$_GET['table'] . ']</h3>' .
'<form method="post" action="newColumns.php" name="newColumnsForm"><input Type="hidden" name="table" value="'. $_GET['table'] .'">
<table style="text-align: left; width: 100%;" border="1px" cellpadding="2" cellspacing="1">
<tbody><tr>
<td style="vertical-align: top; width: 185px;">Name: </td>';

for($i=1; $i<$noOfColumns; $i++) {
	$autofocus = ( $i==1 ? 'autofocus' : '' );
	$content .= '<td style="vertical-align: top;"><input id="name'.$i.'" name="name'.$i.'" '.$autofocus.' ></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Description: </td>';
for($i=1; $i<10; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="description'.$i.'" name="description'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Presentation
Type:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;">
				 	<select id="presentationType'.$i.'" name="presentationType'.$i.'">
				 		<option value="1">textBox</option>
						<option value="2">dropList</option>
						<option value="3">textArea</option>
						<option value="4">htmlText</option>
						<option value="5">checkBox</option>
						<option value="6">date</option>
						<option value="7">time</option>
						<option value="8">dateAndTime</option>
						<option value="9">autoComplete</option>
						<option value="10">radioHoriz</option>
						<option value="11">radioVert</option>
						<option value="12">file</option>
						<option value="13">picture</option>
				 	</select>
				 </td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Type:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;">
				 	<select id="type'.$i.'" name="type'.$i.'">
						<option value="4">varchar</option>
				 		<option value="1">int</option>
						<option value="2">float</option>
						<option value="3">char</option>
						<option value="5">decimal</option>
						<option value="6">medium text</option>
						<option value="7">long text</option>
				 	</select>
				 </td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Length:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="length'.$i.'" name="length'.$i.'" value="255"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Not Null:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="notNull'.$i.'" name="notNull'.$i.'" value="1" type="checkbox"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Default Value:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="defaultValue'.$i.'" name="defaultValue'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Order of appearence:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="ordering'.$i.'" name="ordering'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Index Participation:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="indexParticipation'.$i.'" name="indexParticipation'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Collation:<br>
</td>
		<datalist id="colCollationOptions">
		  <option value="ascii_bin">
		  <option value="utf8_general_ci">
		</datalist>
';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="colCollation'.$i.'" name="colCollation'.$i.'" list="colCollationOptions"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Read Only:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="isReadOnly'.$i.'" name="isReadOnly'.$i.'" value="1" type="checkbox"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Read Only After Insert:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="isReadOnlyAfterFirstUpdate'.$i.'" name="isReadOnlyAfterFirstUpdate'.$i.'" value="1" type="checkbox"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Invisible:</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="isInvisible'.$i.'" name="isInvisible'.$i.'" value="1" type="checkbox"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Appears In Lists:</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="appearsInLists'.$i.'" name="appearsInLists'.$i.'" value="1" type="checkbox"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Foreign Table:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="valuesTable'.$i.'" name="valuesTable'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Foreign Column
to Show:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="columnToShow'.$i.'" name="columnToShow'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Foreign Key
Column:<br>
</td>';
for($i=1; $i<=$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="columnToStore'.$i.'" name="columnToStore'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">DB Foreign Key:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="isForeignKey'.$i.'" name="isForeignKey'.$i.'" value="1" type="checkbox"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Presentation
Parameters:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="presentationParameters'.$i.'" name="presentationParameters'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Use in Admin
Searches:</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="isASearchableProperty'.$i.'" name="isASearchableProperty'.$i.'" value="1" type="checkbox"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 203px;">Admin Listing CSS:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;" width: 199px;"><input id="adminCSS'.$i.'" name="adminCSS'.$i.'" value="objectPropertyCellMedium"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Oredering Mirror:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="orderingMirror'.$i.'" name="orderingMirror'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 185px;">Searching Mirror:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;"><input id="searchingMirror'.$i.'" name="searchingMirror'.$i.'"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 203px;">Resize Picture to:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top; width: 199px;">Width : <input id="resizeWidth'.$i.'" name="resizeWidth'.$i.'" size="10">
			<br> 
			Height: <input id="resizeHeight'.$i.'" name="resizeHeight'.$i.'" size="10"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 203px;">Create MidSize picture:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top; width: 199px;">Width : <input id="midSizeWidth'.$i.'" name="midSizeWidth'.$i.'" size="10"> 
			<br>
			Height: <input id="midSizeHeight'.$i.'" name="midSizeHeight'.$i.'" size="10"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 203px;">Column to store MidSize Picture:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;" width: 199px;"><input id="midSizeColumn'.$i.'" name="midSizeColumn'.$i.'" ></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 203px;">Create Thumbnail:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;" width: 199px;">Width : <input id="thumbnailWidth'.$i.'" name="thumbnailWidth'.$i.'" size="10"> 
			<br>
			Height: <input id="thumbnailHeight'.$i.'" name="thumbnailHeight'.$i.'" size="10"></td>';
}
$content .= '</tr>
<tr>
<td style="vertical-align: top; width: 203px;">Column to store thumbnail:<br>
</td>';
for($i=1; $i<$noOfColumns; $i++) {
	$content .= '<td style="vertical-align: top;" width: 199px;"><input id="thumbnailColumn'.$i.'" name="thumbnailColumn'.$i.'" ></td>';
}
$content .= '</tr>
<tr>
<td colspan="11" align="center"><input type="submit" name="submit" value="create"></td></tr>
</tbody>
</table>
</form>';

$extraDocReadyJS = "
	setupAutoComplete();
";

$fragmentParams = array();
$fragments = $wo->fetchApplicationFragment('tooltipsFragments.php', $fragmentParams );

$content .= $fragments['columnTooltips'];

require_once 'template.php';
?>