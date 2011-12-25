<?php
global $user;
$item_id = product_user_owns_product($product->nid);
$is_product_page = !isset($product->item);

if ($is_product_page && $item_id || // PRODUCT page where user owns the product
	(!$is_product_page && $item_id && $product->item->uid != $user->uid)) // ITEM page where the current user owns the product, but isn't looking at their own item page
{
	$manage_link = l(t("Edit your item details to:"), "node/$item_id/edit", 
		array(
			'attributes' => array(
				'class' => "local_action_button",),
			'query'=>'manage=1')
		) . '<ul id="product-manage-ul">
		<li>Move this item into different collections</li>
		<li>Remove this item from your collection</li>
		<li>Adjust who can view this item (privacy settings)</li>
		<li>Change status(For Sale/Not For Sale)</li>
		</ul>';  // used to be View yours
		
}
elseif (!$item_id) // user doesn't own this product, always show this.
{
	// originally unsanitized though maybe theme fixed. fix 20110609
	$searchcrumb = htmlentities ( trim ( $searchcrumb ) , ENT_QUOTES )  ;
	$manage_link = theme("product_inventory_add", $product->nid, $searchcrumb);//, $variant); 
}

if (!$is_product_page)
{
	$manage_link .= l(t("View product"), "node/{$product->nid}");
}

$show_share_links = !$is_product_page && $item_id && $product->item->uid == $user->uid; // loooking at your own item on an item page
if ($show_share_links && module_exists("fb"))
{
	$manage_link .= theme("fb_share", $item_id, "node", "button");
}
if ($show_share_links && module_exists("tweetassist"))
{
	$manage_link .= theme("tweetassist_tweet", "node", $item_id);
}

echo $manage_link;
