<?php

$autoCompleteEdit = '<input type="hidden" name="'. $this->name .'_hidden" id="'. $this->name .'_hidden" value="'. $value .'"><input type="text" name="'. $this->name .'" id="'. $this->name .'" value="'. $aliasValue .'" '. $this->presentationParameters .' autocomplete="off" onKeyUp="ajaxShowOptions(this,\'g@e@t^'. $this->tableId .'^'. $this->name .'\',event)" class="'. $className .'">
';

?>