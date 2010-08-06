<?php
function vibio_item_user_inventory_search_results($uid)
{
	$allowed_keys = array(
		"item_status",
	);
	
	$phrase = preg_replace('/(^| )([a-z0-9_]+):(.*)( |$)/i', '', $_POST['phrase']);;
	
	foreach ($allowed_keys as $key)
	{
		if ($_POST[$key])
		{
			$phrase .= " $key:{$_POST[$key]}";
		}
	}
	
	if (module_exists("product"))
	{
		$_GET['page'] = $_POST['page'];
		module_load_include("inc", "product");
		$results = _product_search_user_inventory($phrase, $uid);
	}
	else
	{
		$phrase .= " user:{$uid}";
		$results = _vibio_item_search($phrase);
	}
	
	$output = empty($results) ? t("No results found.") : theme("search_results", $results, "vibio_item");
	exit($output);
}
?>