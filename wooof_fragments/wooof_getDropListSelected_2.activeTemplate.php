<?php

                $tag.='<option value="'. $row[0] .'"'. $selected .'>';
                for($dCounter = 1; $dCounter<=count($descriptionColumns); $dCounter++)
                {
                    $tag.= $row[$dCounter] .' ';
                }
                $tag.= '</option>
';

?>