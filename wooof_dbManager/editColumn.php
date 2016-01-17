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

if (!isset($_GET['table']) && !isset($_POST['table']))
{
    header("Location: dbManager.php?tm=$tm");
    exit;
}else if ((isset($_GET['table']) && $_GET['table']=='') || (isset($_POST['table']) && $_POST['table']==''))
{
    header("Location: dbManager.php");
    exit;
}

if (isset($_POST['table']))
{
    $_GET['table'] = $_POST['table'];
    $_GET['column'] = $_POST['column'];
}

$t = new WOOOF_dataBaseTable($wo->db, $_GET['table']);

if (isset($_POST['submit']))
{
    if ($_POST['name']=='')
    {
        $t->columns[$_GET['column']]->drop();
    }else
    {
        $succ = $t->columns[$_GET['column']]->updateMetaDataFromPost();
        if ( $succ === FALSE ) { $wo->handleShowStopperError(); }
    }
    $wo->db->commit();
    header("Location: dbManager.php#".$_POST['table']);
    exit;
}

$metaData = $t->columns[$_GET['column']]->getColumnMetaData();
//print_r($metaData);

$metaData['notNull'] = WOOOF::translateCheckBoxValue($metaData['notNull']);
$metaData['isReadOnly'] = WOOOF::translateCheckBoxValue($metaData['isReadOnly']);
$metaData['isReadOnlyAfterFirstUpdate'] = WOOOF::translateCheckBoxValue($metaData['isReadOnlyAfterFirstUpdate']);
$metaData['isInvisible'] = WOOOF::translateCheckBoxValue($metaData['isInvisible']);
$metaData['appearsInLists'] = WOOOF::translateCheckBoxValue($metaData['appearsInLists']);
$metaData['isASearchableProperty'] = WOOOF::translateCheckBoxValue($metaData['isASearchableProperty']);
$metaData['isForeignKey'] = WOOOF::translateCheckBoxValue($metaData['isForeignKey']);
$typeOptions = WOOOF::translateSelectValue(1, 7, $metaData['type']);
$presentationTypeOptions = WOOOF::translateSelectValue(1, 13, $metaData['presentationType']);
//print_r(WOOOF::translateSelectValue(1, 13, $metaData['presentationType']));
$content='';

$content='<br/><a href="dbManager.php?tm='.$tm. '#'.$_GET['table'].'" class="normalTextCyan">Back to Main Page</a>
<br/>
<h3>Edit Column ['.$_GET['column'].'] of Table ['.$_GET['table'] . ']</h3><br>' . '
<form method="post" action="editColumn.php" name="newColumnsForm"><input type="hidden" name="column" value="'. $_GET['column'] .'">
<input type="hidden" name="table" value="'. $_GET['table'] .'">    
<table>
<tbody>
<tr>
<td style="vertical-align: top; width: 203px;">Name: </td>
<td style="vertical-align: top; width: 199px;"><input id="name" name="name" value="'. $metaData['name'] .'" autofocus></td>
<td style="vertical-align: top; width: 203px;">Description: </td>
<td style="vertical-align: top; width: 199px;"><input id="description" name="description" value="'. $metaData['description'] .'"></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Presentation Type:<br>
</td>
<td style="vertical-align: top; width: 199px;">
<select id="presentationType" name="presentationType">
<option value="1"'. $presentationTypeOptions[1] .'>textBox</option>
<option value="2"'. $presentationTypeOptions[2] .'>dropList</option>
<option value="3"'. $presentationTypeOptions[3] .'>textArea</option>
<option value="4"'. $presentationTypeOptions[4] .'>htmlText</option>
<option value="5"'. $presentationTypeOptions[5] .'>checkBox</option>
<option value="6"'. $presentationTypeOptions[6] .'>date</option>
<option value="7"'. $presentationTypeOptions[7] .'>time</option>
<option value="8"'. $presentationTypeOptions[8] .'>dateAndTime</option>
<option value="9"'. $presentationTypeOptions[9] .'>autoComplete</option>
<option value="10"'. $presentationTypeOptions[10] .'>radioHoriz</option>
<option value="11"'. $presentationTypeOptions[11] .'>radioVert</option>
<option value="12"'. $presentationTypeOptions[12] .'>file</option>
<option value="13"'. $presentationTypeOptions[13] .'>picture</option>
</select>
</td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Type:<br>
</td>
<td style="vertical-align: top; width: 199px;">
<select id="type" name="type">
<option value="4"'. $typeOptions[4] .'>varchar</option>
<option value="1"'. $typeOptions[1] .'>int</option>
<option value="2"'. $typeOptions[2] .'>float</option>
<option value="3"'. $typeOptions[3] .'>char</option>
<option value="5"'. $typeOptions[5] .'>decimal</option>
<option value="6"'. $typeOptions[6] .'>medium text</option>
<option value="7"'. $typeOptions[7] .'>long text</option>
</select>
</td>

<td style="vertical-align: top; width: 203px;">Length:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="length" name="length" value="'. $metaData['length'] .'"></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Not Null:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="notNull" name="notNull" value="1" type="checkbox"'. $metaData['notNull'] .'>
</td>
<td style="vertical-align: top; width: 203px;">Default Value:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="defaultValue" name="defaultValue" value="'. $metaData['defaultValue'] .'"></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Order of Appearence:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="ordering" name="ordering" value="'. $metaData['ordering'] .'"></td>
<td style="vertical-align: top; width: 203px;">Index Participation:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="indexParticipation" name="indexParticipation" value="'. $metaData['indexParticipation'] .'"></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Collation:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="colCollation" name="colCollation" value="'. $metaData['colCollation'] .'" list="colCollationOptions"></td>
		<datalist id="colCollationOptions">
		  <option value="ascii_bin">
		  <option value="utf8_general_ci">
		</datalist>
<td style="vertical-align: top; width: 203px;">Presentation Parameters:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="presentationParameters" name="presentationParameters" value="'. $metaData['presentationParameters'] .'"></td>
</tr>

		
<tr>
<td style="vertical-align: top; width: 203px;">Read Only:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="isReadOnly" name="isReadOnly" value="1" type="checkbox"'. $metaData['isReadOnly'] .'><br>
</td>
<td style="vertical-align: top; width: 203px;">Read Only After Insert:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="isReadOnlyAfterFirstUpdate" name="isReadOnlyAfterFirstUpdate" value="1" type="checkbox"'. $metaData['isReadOnlyAfterFirstUpdate'] .'></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Invisible:</td>
<td style="vertical-align: top; width: 199px;"><input id="isInvisible" name="isInvisible" value="1" type="checkbox"'. $metaData['isInvisible'] .'></td>
<td style="vertical-align: top; width: 203px;">Appears In Lists:</td>
<td style="vertical-align: top; width: 199px;"><input id="appearsInLists" name="appearsInLists" value="1" type="checkbox"'. $metaData['appearsInLists'] .'></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Foreign Table:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="valuesTable" name="valuesTable" value="'. $metaData['valuesTable'] .'"></td>
<td style="vertical-align: top; width: 203px;">Foreign Column
to Show:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="columnToShow" name="columnToShow" value="'. $metaData['columnToShow'] .'"></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Foreign Key Column:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="columnToStore" name="columnToStore" value="'. $metaData['columnToStore'] .'"></td>
<td style="vertical-align: top; width: 203px;">DB Foreign Key:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="isForeignKey" name="isForeignKey" value="1" type="checkbox"'. $metaData['isForeignKey'] .'></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Use in Admin
Searches:</td>
<td style="vertical-align: top; width: 199px;"><input id="isASearchableProperty" name="isASearchableProperty" value="1" type="checkbox"'. $metaData['isASearchableProperty'] .'></td>
<td style="vertical-align: top; width: 203px;">Admin Listing CSS:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="adminCSS" name="adminCSS" value="'. $metaData['adminCSS'] .'"></td>
</tr>
		
<tr>
<td style="vertical-align: top; width: 203px;">Oredering Mirror:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="orderingMirror" name="orderingMirror" value="'. $metaData['orderingMirror'] .'"></td>
<td style="vertical-align: top; width: 203px;">Searching Mirror:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="searchingMirror" name="searchingMirror" value="'. $metaData['searchingMirror'] .'"></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Resize Picture to:<br>
</td>
<td style="vertical-align: top; width: 199px;">W: <input id="resizeWidth" name="resizeWidth" value="'. $metaData['resizeWidth'] .'" size="4">&nbsp;H: <input id="resizeHeight" name="resizeHeight" value="'. $metaData['resizeHeight'] .'" size="4"></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Create Midsize Picture to:<br>
</td>
<td style="vertical-align: top; width: 199px;">W: <input id="midSizeWidth" name="midSizeWidth" value="'. $metaData['midSizeWidth'] .'" size="4">&nbsp;H: <input id="midSizeHeight" name="midSizeHeight" value="'. $metaData['midSizeHeight'] .'" size="4"></td>
<td style="vertical-align: top; width: 203px;">Column to store midsize picture:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="midSizeColumn" name="midSizeColumn" size="30" value="'. $metaData['midSizeColumn'] .'"></td>
</tr>

<tr>
<td style="vertical-align: top; width: 203px;">Create Thumbnail:<br>
</td>
<td style="vertical-align: top; width: 199px;">W: <input id="thumbnailWidth" name="thumbnailWidth" value="'. $metaData['thumbnailWidth'] .'" size="4">&nbsp;H: <input id="thumbnailHeight" name="thumbnailHeight" value="'. $metaData['thumbnailHeight'] .'" size="4"></td>
<td style="vertical-align: top; width: 203px;">Column to store thumbnail:<br>
</td>
<td style="vertical-align: top; width: 199px;"><input id="thumbnailColumn" name="thumbnailColumn" size="30" value="'. $metaData['thumbnailColumn'] .'"></td>
</tr>
	
<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Apply changes to Column"></td></tr>
</tbody>
</table>
</form>
';

$extraDocReadyJS = "
	setupAutoComplete();
";

$fragmentParams = array();
$fragments = $wo->fetchApplicationFragment('tooltipsFragments.php', $fragmentParams );

$content .= $fragments['columnTooltips'];

require_once 'template.php';
?>