<?php
/*
Available variables here:

$cTag (computed/local) contains the computed html class fragment (i.e. ` class="xxxxxx"`). This can be empty (``) if $tagClass is NULL which is the default
$tableName (parameter) the table to be used to get the rows for the radio tag(s)
$radioName (parameter) the name of the radio tag(s) to be created
$isHorizontal = FALSE (parameter) whether the user requests a horizontal set of radio tags 
$whereClause = '' (parameter) a string starting with ` WHERE ` to be appended to the db query 
$tagClass = NULL (parameter) requested tag class by the user
$valueColumn = 'id' (parameter) the column to be used as the values of the radio tags
$descriptionColumn = 'name' (parameter) the column to be used as a textual description for each radio tags
$selectColumn = '' (parameter) a column to evaluate in order to determine the initially selected radio button
$selectValue = ''  (parameter) the value that the $selectColumn must contain the tag to be initially selected 

*/
$tag.='<span '. $cTag .'"><input type="radio" name="'. $radioName .'" value="'. $row[$valueColumn] .'"'. $selectedOption .' id="'. $radioName .'">'. $row[$descriptionColumn] .'</span>&nbsp; ';
            if (!$isHorizontal)
            {
                $tag.='<br/>';
            }
?>