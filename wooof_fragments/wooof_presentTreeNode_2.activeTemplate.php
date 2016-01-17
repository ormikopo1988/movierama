<?php

$isActive = '<a href="administration.php?action=deactivate&__address=1_'. $this->tableId .'_'. $row['id'] .'" class="'. $onClass .'">Active</a>
';
$isNotActive = '<a href="administration.php?action=activate&__address=1_'. $this->tableId .'_'. $row['id'] .'" class="'. $offClass .'">Inactive</a>
';
$upDownActions = '<a href="administration.php?action=moveUp&__address=1_'. $this->tableId .'_'. $row['id'] .'"><img src="images/arrowUp.png" border="0" alt="Up this item in order"></a><a href="administration.php?action=moveDown&__address=1_'. $this->tableId .'_'. $row['id'] .'"><img src="images/arrowDown.png" border="0" alt="Down this item in order"></a>
';

?>