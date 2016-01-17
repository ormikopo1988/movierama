<?php
$__isSiteBuilderPage = true;

$tm=date("YmdHis",time());

if ($_COOKIE["allTablesVisible"]=="no")
{
	setcookie("allTablesVisible","yes");
	header("Location: dbManager.php?tm=$tm");
	exit;
}else
{
	setcookie("allTablesVisible","no");
	header("Location: dbManager.php?tm=$tm");
	exit;
}
?>