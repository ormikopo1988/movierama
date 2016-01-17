<?php

$autoCompleteInsert = '<input type="hidden" name="'. $this->name .'_hidden" id="'. $this->name .'_hidden" value=""><input type="text" name="'. $this->name .'" id="'. $this->name .'" value="" '. $this->presentationParameters .' autocomplete="off" onKeyUp="ajaxShowOptions(this,\'g@e@t^'. $this->tableId .'^'. $this->name .'\',event)">
';
?>