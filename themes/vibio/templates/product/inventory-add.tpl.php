<?php
$link_params = array("attributes" => array("class" => "inventory_add", "id" => "inventory_add_{$nid}"));

if ($searchcrumb)
{
	$link_params["query"] = "searchcrumb=$searchcrumb";
}

$add = l("I have one", "product/{$nid}/add-to-inventory", $link_params);

echo "$add<br />";
?>