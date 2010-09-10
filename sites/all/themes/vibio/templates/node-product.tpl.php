<?php
if ($_GET['searchcrumb'])
{
	$searchcrumb = t("Back to search results");
	$searchcrumb = "<a href='{$_GET['searchcrumb']}'>$searchcrumb</a><br />";
}
else
{
	$searchcrumb = "";
}

if ($image = _product_get_image($node->nid, true))
{
	$image = "
		<a href='$image' rel='prettyphoto[item_image]'>
			<img src='$image' class='product_main_image' />
		</a>
	";
}

$manage_link = theme("product_inventory_manage_link", $node, $_GET['searchcrumb']);

if (isset($node->amazon_data))
{
	if (empty($node->body))
	{
		$product_content = theme("product_amazon_display", $node, $page);
	}
	else
	{
		$product_content = theme("product_display", $node, $page);
		$product_content .= theme("vibio_amazon_item_details", $node);
	}
	
	$external_link = $page ? t("Get \"!item\" from !external_link.", array("!item" => $node->title, "!external_link" => l(t("Amazon"), $node->amazon_data['detailpageurl'], array("absolute" => true)))) : "";
}
else
{
	$product_content = theme("product_display", $node, $page);
}

// these things will show up, regardless of the product source
if (arg(0) != "product" && arg(2) != "add-to-inventory")
{
	drupal_set_title($node->title);
}

if ($page)
{
	module_load_include("inc", "product");
	
	$product_images = theme("vibio_item_images", product_images($node));
	$owners_header = t("People who have !title in their inventory", array("!title" => $node->title));
	$product_owners = "<h3>$owners_header</h3>";
	
	foreach (_product_get_product_owners($node->nid, $user->uid) as $type => $data)
	{
		$type_title = $type == "network" ? t("In your network") : t("On Vibio");
		$product_owners .= "<div class='product_owners_type_container' id='product_type_{$type}'>";
		$product_owners .= "<h4>$type_title</h4>";
		$product_owners .= "<div class='product_owners_results'>";
		$product_owners .= theme("product_owners", $type, $data);
		
		$product_owners .= "</div></div>";
	}
}

echo "
	$searchcrumb
	$image
	$manage_link
	$product_content
	$product_images
	$product_owners
	<p>$external_link</p>
";
?>