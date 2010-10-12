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

if (!$is_product_page && $item_id && $product->item->uid == $user->uid && module_exists("fb")) // loooking at your own item
{
	$manage_link .= "<br />".theme("fb_share", $item_id, "node", "button")."<div class='clear'></div>";
}

echo $manage_link;
?>
