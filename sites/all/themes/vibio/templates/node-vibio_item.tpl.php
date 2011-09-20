<h3 style="color: red;">template: node-vibio-item, eventually to be removed</h3>
<?php
/* stephen: what about a teaser view, similar but cleaner, for new themes? 
 *	It looks like the new themes are product-driven, rather than item-driven
 *	most but not all the time.  vibio_wires 4/11 #11 could use a teaser?
 *  	Confusing because the new designs are product-centric, v1.0 
 *	item-centric.
 *
 * $links was removed from this.  
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

/* if it's not yours, and you're not anonymous, make an offer */
if ($user->uid > 0 && $user->uid != $node->uid)
{
	/* aksi called by vibio_item/offer_to_buy/offer2buy.module,
		that one wants a button 
	 * offer2buy.module defines offer2buy_init to the template,
	 *  and assumes that it should be "new" ... which seems like an error,
	 *  like it doesn't let you edit your offer?  correct...
	 *  "You already have a pending offer on this item. The details of that offer are shown below. Any edits will replace your original offer.
	 * Somewhere in the process, it sends the requested offer amount 
	 *  must be via nid)
	 * This creates the offer form that is called up by this button:
	 *  function offer2buy_offer_form(&$state, $nid, $referrer)
	 * It won't stop getting weirder: this produces the links,
	 *  some other code produces "make offer" button!

	 * old make-offer button, removed by stephen:
	 $offer2buy_extra .= theme("offer2buy_init", $node->nid);
         */ 

	// The v1.1 offer button, only on the item page!!!! Shit. Looks
	//  so similar to the product page I don't know where I am,
	//  
	// add or edit?
	if ( $offer_id == 'not yet written: = offer_already($user->uid, $node->nid)' ) {
		// edit_link	
		$offer2buy_extra .= "<a class='offer2buy_init'
			href='the edit link for $offer_id'>edit your offer</a>";
	} else {
		$nid = $node->nid;
		// if we ever give up on popups, use destination=this url
		$offer2buy_extra .= "<a class='offer2buy_init popup'
			href='add/offer/$nid'>offer popup</a>";	
	}
	
} /* !!! else, if you're anonymous, sign in to make an offer?? */

if ($node->offer2buy['settings']['allow_offer_views'] || $user->uid == $node->uid)
{
	$popup_content = empty($node->offer2buy['offers']) ? t("There are currently no offers on this item") : theme("offer2buy_offer_list", $node->offer2buy['offers'], $user->uid == $node->uid);
	$offer2buy_extra .= theme("offer2buy_existing_offers_popup", $popup_content);
}

$offer2buy = $offer2buy_extra.$offer2buy;

/* product info may be from an external source like Amazon,
	stephen: terrible confusion between items and products, change this */
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