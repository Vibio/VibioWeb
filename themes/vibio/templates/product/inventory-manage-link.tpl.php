<?php
global $user;
$item_id = product_user_owns_product($product->nid);
$is_product_page = !isset($product->item);

if ($is_product_page && $item_id || // PRODUCT page where user owns the product
	(!$is_product_page && $item_id && $product->item->uid != $user->uid)) // ITEM page where the current user owns the product, but isn't looking at their own item page
{
	$manage_link = l(t("View yours"), "node/$item_id")."<br />";
}
elseif (!$item_id) // user doesn't own this product, always show this.
{
	$manage_link = theme("product_inventory_add", $product->nid, $searchcrumb);
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
?>
