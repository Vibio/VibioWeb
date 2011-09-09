<?php


/*
starts with this code in tpl: theme("product_owners", $type, $data);
$offer2buy used to come from offer2buy/init.tpl.php 

somehow gets here -- found it: product_catalog/product.inc calls the database
  and builds a datastructure with html in it (links, with <br> tags.)

$item['node']['link'] is a link to the item page (alias)
$item['node']['nid] is the item nid.
$item['user']['picture'] -> url includes link, just print it.
$item['user']['link']   // note no uid
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
//dsm($offer2buy); -> this is text, the link to make an offer
print debug_backtrace;
echo "
	<div class='item_owner {$item['user']['highlight']}'>
		{$item['user']['picture']}
		<div class='item_info'>
			{$item['node']['link']} {$offer2buy}
			{$item['user']['link']}
		</div>
	</div>
	<div class='clear'></div>
";

?>
