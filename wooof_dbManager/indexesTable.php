<?php
$__isSiteBuilderPage = true;

$__actualPath = dirname($_SERVER['SCRIPT_FILENAME']);
$__actualPath = dirname($__actualPath);

require_once $__actualPath . '/setup.inc.php';

$requestedAction='edit';
$pageLocation='1';

$wo = new WOOOF();

$tm = WOOOF::getCurrentDateTime();

$database = $wo->db->getDatabaseName();
$table = trim( $wo->db->escape($_GET["table"]) );

$content = '<br/><a href="dbManager.php#'.$table.'" class="normalTextCyan">Back to Main Page</a><br/><br/>';

$result = $wo->db->query("show indexes from `$table`"  );

$content .= "<h3>Database Indexes for [$table]</h3>";

$content.="<table width=\"100%\" border=\"0\" cellspacing=\"1\" align=\"left\" bgcolor=\"#FFFFFF\"><tr bgcolor=\"#000000\" class=\"normal_text_yellow\"><td>Table</td><td>Non_unique</td><td>Key_name</td><td>Seq_in_index</td><td>Column_name</td><td>Collation</td><td>Cardinality</td><td>Sub_part</td><td>Packed</td><td>Null</td><td>Index_type</td><td>Comment</td></tr>";

while($row = $wo->db->fetchAssoc($result))
{
	$content.="<tr bgcolor=\"#000000\" class=\"normal_text_cyan\"><td>{$row["Table"]}</td><td>{$row["Non_unique"]}</td><td>{$row["Key_name"]}</td><td>{$row["Seq_in_index"]}</td><td>{$row["Column_name"]}</td><td>{$row["Collation"]}</td><td>{$row["Cardinality"]}</td><td>{$row["Sub_part"]}</td><td>{$row["Packed"]}</td><td>{$row["Null"]}</td><td>{$row["Index_type"]}</td><td>{$row["Comment"]}</td></tr>\n";
}
$content.="</table>";


$content.="<br><br><br><br>";

$content .= "<h3>Indexes in MetaData not already in the DB for Table [".$_GET["table"]."]</h3>";
ob_start();
$res2 = WOOOF_MetaData::buildIndexesForTable($wo, $database, $table, false );
if ( $res2 !== FALSE ) {
	$content .= '<br>' . implode("<br>", $res2);
}
else {
	$content .= '<br>' . '<h2>Error</h2>' . $wo->getErrorsAsStringAndClear();
}
ob_end_clean();

$content .= '
		<br><br>
<a href="mdSynch.php?what=buildIndex&param1='.$table.'&param2=1" class="normalTextOrange">Build new Indexes</a>
';


require_once 'template.php';
?>