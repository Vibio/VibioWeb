<?php
$link_params = array();

if ($searchcrumb)
{
	$link_params["query"] = "searchcrumb=$searchcrumb";
}

$slow_add = l("I have one!", "product/{$nid}/add-to-inventory", $link_params);
$quick_add = l("I have one! (quick add)", "product/{$nid}/add-to-inventory/quick", $link_params);

echo "
	$slow_add<br />
	$quick_add
";
?>