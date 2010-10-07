<?php
$link_params = array();

if ($searchcrumb)
{
	$link_params["query"] = "searchcrumb=$searchcrumb";
}

$slow_add = l("I have one", "product/{$nid}/add-to-inventory", $link_params);
$link_params['attributes']['class'] = "product_quick_add";
$quick_add = l("Quick add", "product/{$nid}/add-to-inventory/quick", $link_params);

echo "
	$slow_add<br />
	$quick_add<br />
";
?>