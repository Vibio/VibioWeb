<?php
if ($item_id = product_user_owns_product($node->nid))
{
	$manage_link = t("This item is already in your !inventory", array("!inventory" => l(t("inventory"), "node/$item_id")));
}
else
{
	$manage_link = theme("product_inventory_add", $node->nid);
}

if (isset($node->field_main_image[0]['filepath']))
{
	$image = file_create_url($node->field_main_image[0]['filepath']);
	$image = "<img src='$image' style='float: left; padding: 0 10px 10px 0;' />";
}
else
{
	$image = "";
}

echo "
	<a href='/node/{$node->nid}'>$image</a>
	$manage_link
	<h4>Description</h4>
	{$node->body}
	<div style='clear: left;'></div>
";
?>