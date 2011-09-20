<h3 style="color: red;">template: node-vibio-product, keep this one</h3>

<?php
$title = "<h3 class='product_title'>".check_plain($node->title)."</h3>";

/* Back link for searches */
if ($_GET['searchcrumb'])
{
	/* stephen: security updtae 20110609 */
	$sanitary = htmlspecialchars ( $_GET['searchcrumb'],   ENT_QUOTES );
  	$searchcrumb = t("Back to search results");
	$searchcrumb = "<a href='{$sanitary}'>$searchcrumb</a><br /> . ";
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

/* stephen: security update 20110609 */
$sanitary = htmlspecialchars ( $_GET['searchcrumb'],   ENT_QUOTES );
$manage_link = theme("product_inventory_manage_link", $node, $sanitary);

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
	$external_it_link =  t("Find it on !external_link.", array("!external_link" => l(t("Amazon"), $node->amazon_data['detailpageurl'], array("absolute" => true))));

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

/*  This section is for the page starting after "People who have...." */
if ($page)
{
	module_load_include("inc", "product");
	
	$product_images = theme("vibio_item_images", product_images($node));
	$any_collectors = ''; // boolean	

	foreach (_product_get_product_owners($node->nid, $user->uid) as $type => $data)
	{
		// $data are [count] => 0 [page] => 0 [results] =>
		if ( $data[count] ) { 
			$any_collectors = 1;
		}
		$type_title = $type == "network" ? t("In your network") : t("On Vibio");
		$product_owners .= "<div class='product_owners_type_container' id='product_type_{$type}'>";
		//$product_owners .= "<h4 class='product_description'>$type_title</h4>";
		$product_owners .= "<div class='product_owners_results'>";
		$product_owners .= theme("product_owners", $type, $data);
		
		$product_owners .= "</div></div>";
	}
	
	// $owners_header	
	$owners_header = '';
	$extra_data = '';
	
	if ( $any_collectors ) {
		$owners_header = t("Collectors who have !title in their inventory", array("!title" => $node->title));
		$extra_data = "
			<div class='product_extra_data'>
				$product_images
				<h3>$owners_header</h3>
				$product_owners
				<p>$external_link</p>
			</div>
		";

	} else {
		// note: if you own it, doesn't seem to show up... so...
		if ( product_user_owns_product($node->nid) ) {
			$non_header = t("Besides yourself, no other Vibio Collectors in your network have this item.");
		} else {
			$non_header = t ("Be the first Vibio Collector in your network with <em>!title</em>", array("!title" => $node->title));
		}
		$extra_data = "
                        <div class='product_extra_data'>
				<h3>$non_header</h3>
				<p>$external_link</p>
			</div>
		";	
	} 



}
else
{
	$extra_data = "";
	if ($node->item)
	{
		$price_image = theme("vibio_item_price_image", $node->item);
	}
}

echo "
	searchcrumb: $searchcrumb
	<div class='external_short_link'>
		$external_it_link
	</div>
	<div class='product_image'>
		$price_image
		$image<br />
		$manage_link
	</div>
	<div class='product_node_data'>
		<a href='/node/{$node->nid}'>
			$title
		</a>
		<h4 class='product_description'>Description</h4>
		$product_content
	</div>
	<div class='clear'></div>
	$extra_data
";
?>