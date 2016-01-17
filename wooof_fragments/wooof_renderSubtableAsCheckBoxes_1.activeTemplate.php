<?php

if ( !isset($lineHeadHtml) ) { $lineHeadHtml = ''; }
if ( !isset($cellHeadHtml) ) { $cellHeadHtml = ''; }
if ( !isset($cellTailHtml) ) { $cellTailHtml = ''; }
if ( !isset($emptyCellHtml) ) { $emptyCellHtml = ''; }
if ( !isset($lineTailHtml) ) { $lineTailHtml = ''; }
if ( !isset($tailHtml) ) { $tailHtml = ''; }

$headHtml = '<table cellpadding="2" cellspacing="0" border="0" style="background-color:inherit; font-size: 14px;">
';
$lineHeadHtml .= '<tr style="border-bottom:0px;">';
$cellHeadHtml .= '<td style="margin-top:5px;">';
$cellTailHtml .= '</td>';
$emptyCellHtml .= '<td>&nbsp;</td>';
$lineTailHtml .= '</tr>';
$tailHtml .='</table>';
?>