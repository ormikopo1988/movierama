<?php

die('Currently under implementation...');

$__isSiteBuilderPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='edit';
$pageLocation='1';

$wo = new WOOOF();

$tm = WOOOF::getCurrentDateTime();

$extraContent = '';

if (isset($_GET['newNode'])) 
{
	$_GET['newNode'] = $wo->cleanUserInput($_GET['newNode']);
	if ($_GET['newNode']!='NULL')
	{
		$testR = $wo->query('select * from __administrationMenu where id = '. $_GET['newNode']);
		if (!mysqli_num_rows($testR))
		{
			die('Argh! the parent id selected doesn\'t exist !!!');
		}
	}
	
	$tables = $wo->db->getDropList('__tableMetaData', 'table', 'where appearsInAdminMenu=1', $tagClass='normalText', $valueColumn='id', $descriptionColumn='tableName', $orderBy='tableName');

	$extraContent = '<a href="adminMenuManager.php" class="normalTextOrange">Back to Admin Menu Manager</a> <br/><br/>
<form method="GET" action="adminMenuManager.php"><input type="hidden" name="action" value="insertNode">
	<input type="hidden" name="parentId" value="'. $_GET['newNode'] .'">
	Node label: <input type="text" name="nodeLabel" class="normalText"><br/>
	Node type: <select name="nodeType">
	<option value="1">1. Table List</option>
	<option value="2">2. Table Search</option>
	<option value="3">3. Table insert</option>
	<option value="4">4. Simple Link</option>
</select><br/>
	Relevant Table (types 1-3): '. $tables .'<br/>
	Link (type 4): <input type="text" name="nodeLink"/><br/>
	<input type="submit" name="submit" value="Insert"/>
</form>';
}elseif (isset($_GET['action']) && $_GET['action']=='insertNode')
{
	if ($_GET['parentId']!='NULL')
	{
		$testR = $wo->db->query('select * from __administrationMenu where id = '. $_GET['parentId']);
		if (!mysqli_num_rows($testR))
		{
			die('Argh! the parent id selected doesn\'t exist !!!');
		}
	}
	if ($_GET['nodeLabel']=='' && $_GET['nodeType']!='4') 
	{
		$tableDescription = $wo->db->getRow('__tableMetaData', $wo->cleanUserInput($_GET['table']));
		if ($tableDescription === null) 
		{
			die('Not a valid table!!!!');
		}
	}elseif ($_GET['nodeLabel']=='' && $_GET['nodeType']=='4') 
	{
		die('The label cannot be empty for simple links.');
	}
	
}else
{
	$extraContent = '<br/>
<a href="adminMenuManager.php?newNode=NULL">Insert new root node</a>
<br/>
';
}

$content = '<br/><a href="dbManager.php?tm='.$tm. '" class="normalTextCyan">Back to Main Page</a>
<br/> <br/>
';

$content .= $extraContent;

require_once 'template.php';
?>