<?php


/*
starts with this code in tpl: theme("product_owners", $type, $data);
$offer2buy used to come from offer2buy/init.tpl.php 
function offer2buy_preprocess_product_owner(&$vars)

somehow gets here -- found it: product_catalog/product.inc calls the database
  and builds a datastructure with html in it (links, with <br> tags.)

$item['node']['link'] is a link to the item page (alias)
$item['node']['nid] is the item nid.
$item['user']['picture'] -> url includes link, just print it.
$item['user']['link']   // note no uid... you connect to this user
-- I added uid

original version creates weird cripple|javascript code:
<div class='offer2buy offer2buy_popup'>
	<span class='offer2buy_nid'>19911</span>
	<button class='offer2buy_init'>make offer</button></div>
$offer2buy is the "offer" button 
Need to go up-code to change that.
*/


// why do they all say "item_owner"  as a class?  -> that's the perspective
//  this was written from.  The product is listed above, these are the owners
//  of that product (also, these are the items of that product, not the
//  perspective of the original design)
//dsm($item);
//dsm($offer2buy); -> this is text, the link to make an offer, from offer2buy.module

/* UX problem: $item['node']['link'] has the same text as the Product 
 *
 */



if ( $price ) {
	$price = "<div class='row'><div class='header'>Asking Price:</div> \$$price</div>";
} else {
	$price = '';
}

/* highlight is here:
Emphasises the key user.  Not much though.
themes/vibio/templates/node-product.tpl.php
modules/vibio/vibio_item/vibio_item.module

There's no design/ux yet on how to get item details.

I'm thinking it should pop?
*/
echo "
	<div class='item_owner {$item['user']['highlight']}'>
		{$item['user']['picture']}
		<div class='action'>$offer2buy
		$price</div>
		$usernamelink
		<div class='item_info'>
			$shortlink
{$item['node']['link']}
			<div class='row'>$teaser</div>
			<div class='row'><div class='header'>Connection:</div> {$item['user']['link']}</div>
		</div>
		</div>
	<div class='clear'></div>
";

?>
