<?php

if ($separator==NULL) 
{
    $output = '';
    $outputTail ='';
}elseif ($separator=='') 
{
    $output = '<span class="'. $className .'"> | </span>';
    $outputTail ='<span class="'. $className .'"> | </span>';
}else
{
    $output = '<span class="'. $className .'">'. $separator .'</span>';
    $outputTail = '<span class="'. $className .'">'. $separator .'</span>';
}

?>