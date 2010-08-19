<?php
if (isset($node->field_main_image[0]['filepath']))
{
	$image = file_create_url($node->field_main_image[0]['filepath']);
	$image = "<img src='$image' style='float: left; padding: 0 10px 10px 0;' />";
}
else
{
	$image = "";
}

$manage_link = theme("product_inventory_manage_link", $node);

echo "
	<a href='/node/{$node->nid}'>$image</a>
	$manage_link
	<h4>Description</h4>
	{$node->body}
	<div style='clear: left;'></div>
";
?>