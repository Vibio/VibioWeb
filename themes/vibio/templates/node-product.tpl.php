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

//$product_image can be set by vibio_addthis if it has already loaded the image path. It is an absolute path.
if(isset($product_image)){
        $image = $product_image;
}else{
        $image =_product_get_image($node->nid, true);
}

if (isset($image))
{
	$image = file_uncreate_url($image);
	//Looks like: pocket watch.jpg  
	// filesystemhackery 20111121
	if ( !preg_match('/sites\/default\/files/', $image) ) {
		$urlimage = "/sites/default/files/uploads/" . $image;	 // new: 20111121 (erase note)
	} else {
//die($image);
		$urlimage = "/$image"; // hey look, terrible, recode!
	}
		
	$pattern = "/^\//";
	$image = preg_replace($pattern,"",$image);
        $alt = 'Product Image';
        $imgtitle = '';
        $attributes = array('class' => 'product_main_image');
        $imagecached = theme('imagecache', 'product_main_product_page', 
                $image, 
                $alt, $imgtitle, $attributes);
        $image = "
                <a href='$urlimage' rel='prettyphoto[item_image]'>
                        $imagecached
                </a>
        ";
}

/* stephen: security update 20110609 */
$sanitary = htmlspecialchars ( $_GET['searchcrumb'],   ENT_QUOTES );
//$variant = "prod_page";
$manage_link = theme("product_inventory_manage_link", $node, $sanitary);//, $variant); // For example, the "Have" button, now to be "I already have this item"

if (isset($node->amazon_data)) {
	/* bug hunt: nodes with field_amazon_asin are returning false here.
   *  returned by: amazon/vibio_amazon.module
   *    function vibio_amazon_nodeapi ($op = load)
   *     "amazon_data" => array_shift(amazon_item_lookup_from_db($node->field_amazon_asin[0]['asin'])),
   * this is working: dsm(amazon_item_lookup_from_db($node->field_amazon_asin[0]['asin']));
   * and node_load is firing, but somehow that lookup, which works here, isn't
   *  working there.
   * $node->field_amazon_asin[0]['asin'] isn't set at node_api. It is set here.
   *  module weighting problem?   Believe that weighting was the problem/solution.

   */

	if (empty($node->body))
	{
		$product_content = theme("product_amazon_display", $node, $page);
	}
	else
	{
		$product_content = theme("product_display", $node, $page);
		$product_content .= theme("vibio_amazon_item_details", $node);
	}
	
	$external_link = $page ? t("Get \"!item\" from !external_link.", array("!item" => $node->title, "!external_link" => l(t("Amazon"), $node->amazon_data['detailpageurl'], array("absolute" => true,
"attributes" => array()     // javascript ruining this ('target' =>"_amazon" )
)))) : "";
	//$external_link .=  $node->amazon_data['detailpageurl']; This URL is already encoded, when it shouldn't be. i.e.:
	//  encode   key=variable  so the equals sign is gone and it's just text.
	$external_it_link =  t("Find it on !external_link.", array("!external_link" => l(t("Amazon"), $node->amazon_data['detailpageurl'], array("absolute" => true,
		"attributes" => array('target' =>"_amazon" )
		))));

}
else
{
	$product_content = theme("product_display", $node, $page);
$external_link = "We couldn't find this product at Amazon.";
//this is working: dsm(amazon_item_lookup_from_db($node->field_amazon_asin[0]['asin']));
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

	// 20110715 We need to highlight one product based on URL
	// Might also think about adding your product to the list.
	// Nelson's style is to call the database for eachsuch search
	// My pref would be a hashreturned from database.
	// Yeah -- his approach breaks down... every call has to remove
	// the highlighted product, icky. Not going to do that..
	// All this code should be moved (mine, plus what was already here)
	// out of tpl.	

	// deal with [network]->those owners, [vibio]->those owners	
	foreach (_product_get_product_owners($node->nid, $user->uid) as $type => $data)
	{
		// $data are [count] => 0 [page] => 0 [results] =>
		if ( $data[count] ) { 
			$any_collectors = 1;
		}

		// Deal with highlighting
		$uid_highlight=$_GET['highlight']; // no security, passing to boolean ONLY
		// warning: $data is an array, not well crafted object.


		// is Invalid argument supplied for foreach()
		//http://staging.vibio.com/product/hand-made-mens-shoes-designer-driving-moccasin-mens-leather-loafer-slip-model-kenzo-l-110-bl

		// foreach owner you can see...
		if ( !empty($data['results']) ) {
			foreach ( $data['results'] as $i => $result ) {
				//print_r($result);
				if ( $result['user']['uid'] == $uid_highlight ) {
					// !!! cut, and move to top of $data['results']
					$data['results'][$i]["user"]['highlight'] = " highlight";

					$hold = $data['results'][$i];
					unset($data['results'][$i]);
					array_unshift($data['results'], $hold); // work despite non-object?

				}
			}
		}
		$type_title = $type == "network" ? t("In your network") : t("On Vibio");
		$product_owners .= "<div class='product_owners_type_container' id='product_type_{$type}'>";
		//$product_owners .= "<h4 class='product_description'>$type_title</h4>";
		$product_owners .= "<div class='product_owners_results'>";
		// is this really theming a custom, non-node data structure?
		// to make design changes, have to go back to 
		// product_catalog/product.inc which is formating the
		// links, for example.	
		// Also look here list:
		//  modules/vibio/vibio_item/offer_to_buy/offer2buy.module
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
				<img src='/themes/vibio/images/long_border.png' />
				$product_owners
				<p>$external_link</p>
				
			</div>
		";

	} else {
		// note: if you own it, doesn't seem to show up... so...
		if ( product_user_owns_product($node->nid) ) {
			$non_header = t("Besides yourself, no one else in your network has this item.");
		} else {
			$non_header = t ("Be the first in your network with <em>!title</em>", array("!title" => $node->title));
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

/* press design/wireframe team to get a back-to-search into the design
 *  $searchcrumb
 */

echo "
	<h1 id='product_description'>Description</h1>
	<div class='external_short_link'>
       $external_it_link<a id='info-button' class='automodal' href='/product-help'><span class='tab'>Info</span></a>
   </div>
	<div class='product_image'>
		$price_image
		$image
	</div>
	<div class='product_node_data'>
		<a href='/node/{$node->nid}'>
			$title
		</a>
		$product_content
		$links
		$manage_link
	</div>
	<div class='clear'></div>
	$extra_data
";
?>

<?php print flag_create_link('feature', $node -> nid);
	/* could easily do this for items, with $product->nid*/
?>
