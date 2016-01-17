<?php

$fileEdit = '<a href="getFile.php?location='. $this->tableId .'_'. $this->columnId .'_'. $rowId .'" class="'. $className .'" target="_blank">'. $f['originalFileName'] .'</a> <a href="deleteFile.php?location='. $this->tableId .'_'. $this->columnId .'_'. $rowId .'">Delete file.</a><br/><input type="file" name="'. $this->name .'" id="'. $this->name .'" class="'. $className .'">
';

?>