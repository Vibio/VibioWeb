<?php
global $user;
$item_id = product_user_owns_product($product->nid);
$is_product_page = !isset($product->item);

if ($is_product_page && $item_id || // PRODUCT page where user owns the product
	(!$is_product_page && $item_id && $product->item->uid != $user->uid)) // ITEM page where the current user owns the product, but isn't looking at their own item page
{
	$manage_link = t("This item is already in your !inventory", array("!inventory" => l(t("inventory"), "node/$item_id")));
}
elseif (!$item_id) // user doesn't own this product, always show this.
{
	$manage_link = theme("product_inventory_add", $product->nid, $searchcrumb);
}

if (!$is_product_page)
{
	$manage_link .= l(t("See who else owns this"), "node/{$product->nid}");
}

echo $manage_link;
?>