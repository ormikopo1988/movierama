<?php

if($tagClass===NULL)
{
    $cTag = '';
}elseif ($tagClass=='')
{
    $cTag = 'class="'. $cssForFormItem['dropList'] .'"';
}else
{
    $cTag = 'class="'. $tagClass .'"';
}
$selectHead = '<select name="'. $selectName  .'" '. $cTag .'>
';
$selectTail = '</select>
';

?>