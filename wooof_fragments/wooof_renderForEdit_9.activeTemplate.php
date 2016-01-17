<?php

if ($this->presentationParameters=='')
{
    $parameters='cols="50" rows="15"';
}else
{
    $parameters=$this->presentationParameters;
}
$textAreaEdit = '<textarea name="'. $this->name .'" id="'. $this->name .'" '. $parameters .' class="'. $className .'">'. $value .'</textarea>
';

?>