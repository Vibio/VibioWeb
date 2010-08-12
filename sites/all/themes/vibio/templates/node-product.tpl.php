<?php
if ($_GET['breadcrumb'])
{
	$breadcrumb = t("Back to search results");
	echo "<a href='{$_GET['breadcrumb']}'>$breadcrumb</a><br />";
}

if (isset($node->amazon_data))
{
	echo theme("product_amazon_display", $node, $page);
	$footer_text = $page ? t("Get \"!item\" from !external_link.", array("!item" => $node->title, "!external_link" => l(t("Amazon"), $node->amazon_data['detailpageurl'], array("absolute" => true)))) : "";
}
else
{
	echo theme("product_display", $node, $page);
}

// these things will show up, regardless of the product source
if (arg(0) != "product" && arg(2) != "add-to-inventory")
{
	drupal_set_title($node->title);
}

if ($page)
{
	module_load_include("inc", "product");
	
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
	$product_owners
	<p>$external_link</p>
";
?>