<?php
/* stephen: what about a teaser view, similar but cleaner, for new themes? 
 *	It looks like the new themes are product-driven, rather than item-driven
 *	most but not all the time.  vibio_wires 4/11 #11 could use a teaser?
 *  	Confusing because the new designs are product-centric, v1.0 
 *	item-centric.
 */

global $user;
$offer2buy = "";

if ($node->offer2buy['settings']['price'] == 0 && $node->offer2buy['settings']['is_negotiable'])
{
	$offer2buy = t("Price: Best Offer");
}
else
{
	$offer2buy = t("Price: \$!price", array("!price" => $node->offer2buy['settings']['price']));
	
	if ($node->offer2buy['settings']['is_negotiable'])
	{
		$offer2buy .= " (".t("Negotiable").")";
	}
}

$offer2buy = "<div class='node_offer2buy_price'>$offer2buy</div>";

if ($user->uid > 0 && $user->uid != $node->uid)
{
	$offer2buy_extra .= theme("offer2buy_init", $node->nid);
}

if ($node->offer2buy['settings']['allow_offer_views'] || $user->uid == $node->uid)
{
	$popup_content = empty($node->offer2buy['offers']) ? t("There are currently no offers on this item") : theme("offer2buy_offer_list", $node->offer2buy['offers'], $user->uid == $node->uid);
	$offer2buy_extra .= theme("offer2buy_existing_offers_popup", $popup_content);
}

$offer2buy = $offer2buy_extra.$offer2buy;

/* product info may be from an external source like Amazon */
$product = node_load($node->product_nid);
$product->item =& $node;
$product_data = $node->product_nid ? node_view($product) : "";
$user_other_items = theme("vibio_item_user_other_items", $node);

/* $node->title and body are specific to the owner of the item */
echo "
	{$product_data}
	<div class='product_extra_data'>
		<div class='node_vibio_item_title'>
			{$node->title}
		</div>
		<div class='offer2buy_node_display'>
			{$offer2buy}
		</div>
		<div class='clear'></div>
		{$node->body}
		{$item_extra_images}<br />
		{$user_other_items}
	</div>
";
?>
