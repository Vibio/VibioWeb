<?php
$busy_indicator = "<td><img class='uri_edit_busy_indicator' src='/themes/vibio/images/ajax-loader.gif' /></td>";
$table = "<table><tr>";
$rows = "";

foreach ($list as $link)
{
	$rows .= "<td>$link</td>";
}

$busy_indicator_align == "before" ? $rows = $busy_indicator.$rows : $rows .= $busy_indicator;

$table .= "$rows</tr></table>";

echo $table;
?>
