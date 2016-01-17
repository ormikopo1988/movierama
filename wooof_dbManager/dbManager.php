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

$metaDataDBVersion = WOOOF_MetaData::versionReadFromDB($wo, $wo->db);
if ( $metaDataDBVersion !== NULL ) {
	if ( $metaDataDBVersion === 'UNDEFINED' or substr($metaDataDBVersion,0,2) < substr(WOOOF_MetaData::$version,0,2) ) {
		echo "
			Need to upgradre DB MetaData: DB version [$metaDataDBVersion] is behind Code Version [". WOOOF_MetaData::$version. "]
			<br>
			<a href=\"mdSynch.php?what=selfUpgradeMetaData\">Click here to upgrade right now...</a>
		";
		die();
	}
}

if (!isset($_COOKIE["allTablesVisible"]))
{
	setcookie("allTablesVisible","no");
	header("Location: dbManager.php?tm=". $tm);
	exit;
}

if ($_COOKIE["allTablesVisible"]=="no")
{
    $switchText="Make system tables visible"; 
    $showSystemTables=FALSE;
}else 
{
    $switchText="Hide system tables";
    $showSystemTables=TRUE;
}

$lastQueryInformation = '';
$lastQueryOutput = '';

if (isset($_GET['query']))
{
    $_POST['query'] = $_GET['query'];
    $_POST['Submit'] = '1';
}

if (isset($_POST['Submit']))
{
    $result = $wo->db->query($_POST['query']);
    
    if ( $result === FALSE ) {
    	$lastQueryInformation = "ERRORS: " . $wo->getErrorsAsStringAndClear();
    }
    else {
	    $info = $wo->db->getLastQueryInfo();
	
	    if ($info != FALSE)
	    {
	        $lastQueryInformation = '<br/>'. $info .'<br/>
	';
	    }
	    $result2 = $wo->db->commit();
	    if (@mysqli_num_rows($result))
	    {
	        $firstRow = TRUE;
	        $lastQueryOutput = '<br/><table callpadding="1" cellspacing="1" bgcolor="#CCCCCC" class="normalTextWhite">';
	        while($row = $wo->db->fetchAssoc($result))
	        {
	            if ($firstRow)
	            {
	                $header = '<tr>';
	                $firstTableRow = '<tr>';
	                while(list($key, $val) = each($row))
	                {
	                    $header .= '<td bgcolor="#DDDDDD" class="normalTextBlack">'. $key .'</td>';
	                    $firstTableRow .= '<td bgcolor="#000000">'. $val .'</td>';
	                    $rowKeys[] = $key;
	                }
	                $header .= '</tr>';
	                $firstTableRow .= '</tr>';
	                $lastQueryOutput .= $header ."\r". $firstTableRow ."\r";
	                $firstRow = FALSE;
	            }else
	            {
	                $lastQueryOutput .= '<tr>';
	                $count=count($rowKeys);
	                for($c = 0; $c < $count; $c++)
	                {
	                    $lastQueryOutput .= '<td bgcolor="#000000">'. $row[$rowKeys[$c]] .'</td>';
	                }
	                $lastQueryOutput .= "</tr>\r";
	            }
	        }
	        $lastQueryOutput.='</table>';
	    }
    }
}
else {
	$_POST['query'] = '';
}



$content = '
		<br>        
		<a href="editTable.php?action=new" class="normalTextGreen">New Table</a> | 
        <a href="backUpDataBase.php" class="normalTextCyan">BackUp Database</a> | 
        <a href="switchTableVisibility.php" class="normalTextYellow">'. $switchText .'</a> |  
        <a href="adminMenuManager.php" class="normalTextOrange">Edit BackEnd Menu </a> |
        <a href="logOut.php" class="normalTextWhite">Logout</a>
        <br>
        <a href="mdSynch.php?what=selfUpgradeMetaData" class="normalTextPink">Upgrade Meta Data</a> | 
        <a href="mdSynch.php?what=exportMetaData" class="normalTextRed">Export Meta Data</a> | 
        <a data-toggle="modal" data-target="#modal_1" href="#" class="normalTextCyan">Import and Update Meta Data</a> | 
        <a href="mdSynch.php?what=buildIndexes" class="normalTextWhite">Scripts for Indexes</a> | 
        <a data-toggle="modal" data-target="#modal_3" href="#" class="normalTextGreen">Rev Engineer All Objects </a> | 
   		<a data-toggle="modal" data-target="#modal_2" href="#" class="normalTextYellow">Rev Engineer an Object</a> | 
        <br/>';

$result = $wo->db->query('show tables');

while ($t = $wo->db->fetchRow($result)) 
{
	$tableFoundInMetaData = $isView = false; $classForTableName = '';
	
    if (substr($t[0],0,2)=='__' && !$showSystemTables)
    {
        continue;
    }
    
    //if (substr($t[0],0,2)!='__')
    //{
	    $sql = "select isView from __tableMetaData where tableName = '$t[0]'";
	    $tmdRes = $wo->db->query($sql);
	    if ( $tmdRes === FALSE ) { die("Failed to query table metadata for {$t[0]}. " . $wo->getErrorsAsStringAndClear() ); }
		$tableFoundInMetaData = ( ( $tmdRow = $wo->fetchAssoc($tmdRes) ) !== NULL );
		$isView = ( $tableFoundInMetaData && $tmdRow['isView'] == '1' );
		$classForTableName = ( !$tableFoundInMetaData ? 'normalTextWhite' : ( $isView ? 'normalTextGrey' : 'normalTextOrange' ) );
	
		$links = '';
		
		if ( $tableFoundInMetaData ) {
			if ( $isView ) {
				$links = '[is a VIEW]&nbsp;';
			}
			else {
	    		$links .= ' 
    	    		<a href="newColumns.php?table='. $t[0] .'" class="normalTextPink">New Columns</a>&nbsp;&nbsp;&nbsp;
        		';
			}
		}
		
		$links .= '
   				<a href="indexesTable.php?tm='. $tm .'&table='. urlencode($t[0]) .'" class="normalTextYellow">[Indexes]</a> &nbsp;
				<a href="dbManager.php?query=select * from `'. $t[0] .'` limit 1000#queryResults" class="normalTextPurple">[SELECT *]</a> &nbsp;&nbsp;
   				<a href="mdSynch.php?what=reverseEngineerObject&param1=' . urlencode($t[0]) .'" class="normalTextYellow">[Rev Engineer]</a> &nbsp;
        		<a href="mdSynch.php?what=exportMetaData&param3='.urlencode($t[0]).'" class="normalTextRed">[Export Meta Data]</a> &nbsp; 
   				<a href="dropTable.php?tm='. $tm .'&table='. urlencode($t[0]) .'" class="normalTextRed">[Drop]</a> &nbsp; 
   				<a href="javascript:areWeSureTruncate(\''. $t[0] .'\')" class="normalTextOrange">[Truncate]</a> &nbsp; 
   		';

		if ( $tableFoundInMetaData ) {
			$links .= '
   				<a href="duplicateTable.php?tm='. $tm .'&table='. urlencode($t[0]) .'" class=normalTextGreen>[Duplicate]</a> &nbsp; 
   				<a href="buildTableForm.php?tm=$tm&table='. urlencode($t[0]) .'" class="normalTextBlue">[Build Form]</a> &nbsp; 
   				<a href="dupCols.php?tm='. $tm .'&table='. urlencode($t[0]) .'" class=normalTextCyan>[Dup cols]</a> &nbsp;
   			';
   			// <a href="copyTable.php?tm='. $tm .'&table='. urlencode($t[0]) .'" class="normalTextWhite">COPY</a> &nbsp;
		}
    //}else
    //{
     //   $links = ' &nbsp;&nbsp;&nbsp; <a href="dbManager.php?query=select * from `'. $t[0] .'` limit 1000#queryResults" class="normalTextPurple">SELECT *</a>';
    //}
    $links = '<div class="showOnHoverDiv">' . $links . '</div>';
    
	// var_dump( $t[0], $tableFoundInMetaData, $isView );
	    
    
    
    $content .= '        <br/><table cellpadding="2" cellspacing="1" bgcolor="#FFFFFF">
            <tr>
    		<td class="normalTextWhite" bgColor="#000000" style="width: 1500px;">
    		<a name="'.$t[0].'"></a>
    		<div class="hoverContainer">'
    ;
	$href = ( $tableFoundInMetaData ? 'editTable.php?table='. $t[0] : '#' );
	$content .= '<a href="'.$href.'" class="'.$classForTableName.'">'. $t[0] .'</a>' .
            '&nbsp;' . $links .'
            </div></td></tr>
            <tr><td class="normalTextCyan" bgColor="#000000">
';
    
    $result2 = $wo->db->query('desc '. $t[0]);
    while($column = $wo->db->fetchRow($result2))
    {
        $href = ( $tableFoundInMetaData ? 'editColumn.php?table='. urlencode($t[0]) .'&column='. $column[0] : '#' );
        $content .= ' <a href="'.$href.'" class="normalTextCyan">'. $column[0] .'</a> -';
    }
    $content .= '</td></tr> 
        </table>
';
}

$content.='        <br/>
		<hr>
        <form method="POST" action="dbManager.php#queryResults" id="queryForm">
			<table>
				<tr><td>
            		<textarea cols="60" rows="5" name="query" id="queryBox" class="normalTextBlue" >'.$_POST['query'].'</textarea><br/>
        		</td></tr>    
				<tr><td>
					<input type="submit" value="Execute Query" name="Submit" class="normalTextRed" />
            		&nbsp;&nbsp;&nbsp;
					<input type="button" value="Reset" onclick="document.getElementById(\'queryBox\').value = \'\'; return false;">
            	</td></tr>
			</table>
		</form>
';

if ( $lastQueryInformation != '' ) {
	$content .= '<a name="queryResults"></a><div style="padding:10px; background: rgb(224, 224, 186);"><h3>Query Results</h3>';
	$content .= $lastQueryInformation;
	$content .= $lastQueryOutput;
	$content .= '</div>';
}

$content .= '<br><br>*** The End***';


$content .= '
		<!-- modals -->
		
		<div id="modal_1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content" style="padding:20px;"> 
		    	<input type="text" id="param1" name="param1" placeholder="File to import..." style="width:100%" /><br>
				<br><input type="submit" onclick="modalGo('."'file'".')" value="Import Meta Data File and Update" />
			</div>
		  </div>
		</div>
		<div id="modal_2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content" style="padding:20px;"> 
		    	<input type="text" id="param2" name="param2" placeholder="Object to reverse engineer..." style="width:100%" /><br>
				<br><input type="submit" onclick="modalGo('."'object'".')" value="Reverse Engineer Object" />
			</div>
		  </div>
		</div>
		<div id="modal_3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content" style="padding:20px;"> 
		    	<input type="text" id="param3" name="param3" placeholder="Action... (refresh, show, indexes, ascii)" style="width:100%" /><br>
				<br><input type="submit" onclick="modalGo('."'action'".')" value="Reverse Engineer Objects with Action" />
			</div>
		  </div>
		</div>
		
		<script>
			function getParamsFromModal() {
				$("#myModal").modal();
			}
		
			function modalGo(param) {
				if(param == "file") {
					var p1 = $("#param1").val();
						
					if(p1 == "") {
						alert("Please provide the file name to import!");
						return false;	
					}
						
					var params = "param1=" + p1;
					window.location.href = "./mdSynch.php?what=importAndUpdateMetaData&"+params;
				}
				
				else if(param == "object") {
					var p2 = $("#param2").val();
						
					if(p2 == "") {
						alert("Please provide the object name to reverse!");
						return false;	
					}
						
					var params = "param1=" + p2;
					window.location.href = "./mdSynch.php?what=reverseEngineerObject&"+params;	
				}

				else if(param == "action") {
					var p3 = $("#param3").val();
						
					if(p3 == "") {
						alert("Please provide an Action!");
						return false;	
					}
						
					var params = "param1=" + p3;
					window.location.href = "./mdSynch.php?what=reverseEngineerObjects&"+params;	
				}
			}
		</script>';

$extraHeadScripts = '<script>
function areWeSureTruncate(whichTable)
{
    var r = confirm("You are about to TRUNCATE table "+ whichTable +". Go on ?");
    if (r == true) {
        window.location.href = \'dbManager.php?query=truncate \'+ whichTable;
    }
}
</script>';

require_once 'template.php';
?>