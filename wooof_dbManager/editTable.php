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

if (isset($_GET['action']) && $_GET['action']=='new')
{
    $action = 'new';
    $_GET['table'] = '';
    $description='';
    $subtableDescription='';
    $presentationDefault[1]='';
    $presentationDefault[2]='';
    $presentationDefault[3]='';
    $presentationDefault[4]='';
    $presentationDefault[5]='';
    $tableName='';
    $orderingColumnForListings='';
    $appearsInAdminMenu='';
    $adminItemsPerPage='';
    $adminListMarkingCondition='';
    $adminListMarkedStyle='';
    $groupedByTable='';
    $remoteGroupColumn='';
    $localGroupColumn='';
    $tablesGroupedByThis='';
    $hasActivationFlag='';
    $availableForSearching='';
    $hasGhostTable='';
    $hasDeletedColumn='';
    $hasEmbededPictures='0';
    $columnForMultipleTemplates='';
    $showIdInAdminLists='';
    $showIdInAdminForms='';
    $dbEngine='';
}else if (isset($_GET['table']))
{
    $table = new WOOOF_dataBaseTable($wo->db, $wo->db->escape(trim($_GET['table'])));
    $action = 'edit';
    $description=$table->getTableDescription();
    $subtableDescription=$table->getSubTableDescription();
    $presentationDefault[1]='';
    $presentationDefault[2]='';
    $presentationDefault[3]='';
    $presentationDefault[4]='';
    $presentationDefault[5]='';
    $presentationDefault[$table->getAdminPresentation()]=' selected';
    $tableName=$table->getTableName();
    $orderingColumnForListings=$table->getOrderingColumnForListings();
    $appearsInAdminMenu=$table->getAppearsInAdminMenu();
    if ($appearsInAdminMenu=='1')
    {
        $appearsInAdminMenu=' checked';
    }else
    {
        $appearsInAdminMenu='';
    }
    $adminItemsPerPage=$table->getAdminItemsPerPage();
    $adminListMarkingCondition=$table->getAdminListMarkingCondition();
    $adminListMarkedStyle=$table->getAdminListMarkedStyle();
    $groupedByTable=$table->getGroupedByTable();
    $remoteGroupColumn=$table->getRemoteGroupColumn();
    $localGroupColumn=$table->getLocalGroupColumn();
    $tablesGroupedByThis=$table->getTablesGroupedByThis();
    $hasActivationFlag=$table->getHasActivationFlag();
    $columnForMultipleTemplates=$table->getColumnForMultipleTemplates();
    $dbEngine=$table->getDbEngine();
    
    if ($hasActivationFlag=='1')
    {
        $hasActivationFlag=' checked';
    }else
    {
        $hasActivationFlag='';
    }
    $availableForSearching=$table->getAvailableForSearching();
    if ($availableForSearching=='1')
    {
        $availableForSearching=' checked';
    }else
    {
        $availableForSearching='';
    }
    $hasGhostTable=$table->getHasGhostTable();
    if ($hasGhostTable=='1')
    {
        $hasGhostTable=' checked';
    }else
    {
        $hasGhostTable='';
    }
    $hasDeletedColumn=$table->getHasDeletedColumn();
    if ($hasDeletedColumn=='1')
    {
        $hasDeletedColumn=' checked';
    }else
    {
        $hasDeletedColumn='';
    }
    $hasEmbededPictures=$table->getHasEmbededPictures();
    if ($hasEmbededPictures=='1')
    {
        $hasEmbededPictures=' checked';
    }else
    {
        $hasEmbededPictures='';
    }
    $showIdInAdminLists=$table->getShowIdInAdminLists();
    if ($showIdInAdminLists=='1')
    {
        $showIdInAdminLists=' checked';
    }else
    {
        $showIdInAdminLists='';
    }
    $showIdInAdminForms=$table->getShowIdInAdminForms();
    if ($showIdInAdminForms=='1')
    {
        $showIdInAdminForms=' checked';
    }else
    {
        $showIdInAdminForms='';
    }
    
}else if (isset($_POST['submit']))
{
    if ($_POST['action']=='new')
    {
        $table = $wo->db->getEmptyTable();
        $succ = $table->updateMetaDataFromPost();
    }elseif ($_POST['action']=='edit')
    {
        $table = new WOOOF_dataBaseTable($wo->db, $_POST['table']);
        $succ = $table->updateMetaDataFromPost();
    }
    if ( $succ === FALSE ) { $wo->db->rollback(); $wo->handleShowStopperError(); }
    
    $wo->db->commit();
    header("Location: dbManager.php?tm=$tm#".$_POST['table']);
    exit;
}
$content = '<br/><a href="dbManager.php?tm='.$tm. '#'.$tableName.'" class="normalTextCyan">Back to Main Page</a><br/><br/><form method="POST" action="editTable.php"><input type="hidden" name="action" value="'. $action .'"><input type="hidden" name="table" value="'. $_GET['table'] .'">
    <table>
        <tr><td align="right">Table Name</td><td align="left"><input type="text" id="tableName" name="tableName" value="'. $tableName .'" autofocus></td></tr>
        <tr><td align="right">Table Description</td><td align="left"><input type="text" id="description" name="description" value="'. $description .'"></td></tr>
        <tr><td align="right">Description as Subtable</td><td align="left"><input type="text" id="subtableDescription" name="subtableDescription" value="'. $subtableDescription .'"></td></tr>
        <tr><td align="right">Column List Order</td><td align="left"><input type="text" id="orderingColumnForListings" name="orderingColumnForListings" value="'. $orderingColumnForListings .'"></td></tr>
        <tr><td align="right">Appears in Menu</td><td align="left"><input type="checkbox" id="appearsInAdminMenu" name="appearsInAdminMenu" value="1"'.$appearsInAdminMenu .'></td></tr>
        <tr><td align="right">Presented As</td><td align="left">
    <select id="adminPresentation" name="adminPresentation">
        <option value="1"'. $presentationDefault[1] .'>List</option>
        <option value="2"'. $presentationDefault[2] .'>Categorized List</option>
        <option value="3"'. $presentationDefault[3] .'>TreeView</option>
        <option value="4"'. $presentationDefault[4] .'>Tree Categorized List</option>
        <option value="5"'. $presentationDefault[5] .'>Composite Tree</option>
    </select>
</td></tr>
        <tr><td align="right">Items Per Page</td><td align="left"><input type="text" id="adminItemsPerPage" name="adminItemsPerPage" value="'. $adminItemsPerPage .'"></td></tr>
        <tr><td align="right">Rows Marking Condition</td><td align="left"><textarea cols="30" rows="5" id="adminListMarkingCondition" name="adminListMarkingCondition">'. $adminListMarkingCondition .'</textarea></td></tr>
        <tr><td align="right">Style to use For Marking</td><td align="left"><input type="text" id="adminListMarkedStyle" name="adminListMarkedStyle" value="'. $adminListMarkedStyle .'"></td></tr>
        <tr><td align="right">Sub table of</td><td align="left"><input type="text" id="groupedByTable" name="groupedByTable" value="'. $groupedByTable .'"></td></tr>
        <tr><td align="right">Remote Key column</td><td align="left"><input type="text" id="remoteGroupColumn" name="remoteGroupColumn" value="'. $remoteGroupColumn .'"></td></tr>
        <tr><td align="right">Local Key column</td><td align="left"><input type="text" id="localGroupColumn" name="localGroupColumn" value="'. $localGroupColumn .'"></td></tr>
        <tr><td align="right">Subtables</td><td align="left"><input type="text" id="tablesGroupedByThis" name="tablesGroupedByThis" value="'. $tablesGroupedByThis .'"></td></tr>
        <tr><td align="right">Activation Switch</td><td align="left"><input type="checkbox" id="hasActivationFlag" name="hasActivationFlag" value="1"'. $hasActivationFlag .'></td></tr>
        <tr><td align="right">Admins Can Search</td><td align="left"><input type="checkbox" id="availableForSearching" name="availableForSearching" value="1"'. $availableForSearching .'></td></tr>
        <tr><td align="right">ID should appear in Admin Lists</td><td align="left"><input type="checkbox" id="showIdInAdminLists" name="showIdInAdminLists" value="1"'. $showIdInAdminLists .'></td></tr>
        <tr><td align="right">ID should appear in Admin Forms</td><td align="left"><input type="checkbox" id="showIdInAdminForms" name="showIdInAdminForms" value="1"'. $showIdInAdminForms .'></td></tr>
        <tr><td align="right">Has Shadow table</td><td align="left"><input type="checkbox" id="hasGhostTable" name="hasGhostTable" value="1"'. $hasGhostTable .'></td></tr>
        <tr><td align="right">Has Soft Delete Column</td><td align="left"><input type="checkbox" id="hasDeletedColumn" name="hasDeletedColumn" value="1"'. $hasDeletedColumn .'></td></tr>
        <tr><td align="right">Embed picture/pdf table</td><td align="left"><input type="checkbox" id="hasEmbededPictures" name="hasEmbededPictures" value="1"'. $hasEmbededPictures .'></td></tr>
        <tr><td align="right">Column to use as prefix for multiple template files</td><td align="left"><input type="text" id="columnForMultipleTemplates" name="columnForMultipleTemplates" value="'. $columnForMultipleTemplates .'"></td></tr>
        <tr><td align="right">Database Engine</td><td align="left">
        		<input type="text" id="dbEngine" name="dbEngine" value="'. $dbEngine .'" list="dbEngineOptions">
					<datalist id="dbEngineOptions">
					  <option value="MyISAM">
					  <option value="InnoDB">
					</datalist>
        	</td>
        </tr>
    </table><br/>
    <input type="submit" name="submit" value="Submit">
</form>
';
$fragmentParams = array();
$fragments = $wo->fetchApplicationFragment('tooltipsFragments.php', $fragmentParams );
	
$content .= $fragments['tableTooltips'];

require_once 'template.php';
?>