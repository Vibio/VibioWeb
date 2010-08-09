<?php
global $user;
$image = file_create_url($node->field_main_image[0]['filepath']);

foreach ($node->amazon_data['editorialreviews'] as $review)
{
	if ($review['source'] == "Amazon.com Product Description")
	{
		$review = $review['content'];
	}
}

if (!$description)
{
	$review = $node->amazon_data['editorialreviews'][0]['content'];
}

if (!($description = _vibio_amazon_clean_content($review)))
{
	$description = _vibio_amazon_clean_content_allowhtml($review);
}

$theme = "vibio_amazon_item_"._amazon_clean_type($node->amazon_data['producttypename']);
if ($details = theme($theme, $node->amazon_data))
{
	$details = "
		<h4>Details</h4>
		$details
	";
}

if ($page)
{
	if ($item_id = product_user_owns_product($node->nid))
	{
		$manage_link = t("This item is already in your !inventory", array("!inventory" => l(t("inventory"), "node/$item_id")));
	}
	else
	{
		$manage_link = l("I have one!", "product/{$node->nid}/add-to-inventory");
	}
}
elseif (arg(0) != "product" && arg(2) != "add-to-inventory")
{
	drupal_set_title($node->title);
}

$external_link = $page ? t("Get \"!item\" from !external_link.", array("!item" => $node->title, "!external_link" => l(t("Amazon"), $node->amazon_data['detailpageurl'], array("absolute" => true)))) : "";

if ($page)
{
	module_load_include("inc", "product");
	$owners_header = t("People who have !title in their inventory", array("!title" => $node->title));
	$product_owners = "<h3>$owners_header</h3>";
	foreach (_product_get_product_owners($node->nid, $user->uid) as $type => $items)
	{
		$product_owners .= theme("product_owners", $type, $items);
	}
}

echo "
	<a href='/node/{$node->nid}'><img src='{$image}' style='float: left; padding: 0 10px 10px 0;' /></a>
	$manage_link
	<h4>Description</h4>
	$description
	<div style='clear: left;'></div>
	$details
	$product_owners
	<p>$external_link</p>
";	
?>