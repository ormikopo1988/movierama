<?php
if ($this->presentationParameters=='')
{
    $parameters='cols="50" rows="10"';
}else
{
    $parameters=$this->presentationParameters;
}

$textAreaInsert = '<textarea name="'. $this->name .'" id="'. $this->name .'" '. $parameters .' class="'. $className .'">'. $this->defaultValue .'</textarea>
';

?>