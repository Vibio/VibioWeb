<?php


/*
starts with this code in tpl: theme("product_owners", $type, $data);

Does it run through init.tpl.php ?  I think so.  
	That creates the $offer2buy vaiable, perhaps

somehow gets here:

$item['node']['link'] is a link to the item page (alias)
$item['node']['nid] is the item nid.
$item['user']['picture'] -> url includes link, just print it.
$item['user']['link']   // note no uid

original version creates weird cripple|javascript code:
dpm($offer2buy);
<div class='offer2buy offer2buy_popup'>
	<span class='offer2buy_nid'>19911</span>
	<button class='offer2buy_init'>make offer</button></div>
$offer2buy is the "offer" button 
Need to go up-code to change that.
*/



echo "
	<div class='item_owner'>
		{$item['user']['picture']}
		<div class='item_info'>
			{$item['node']['link']} {$offer2buy}
			{$item['user']['link']}
		</div>
	</div>
	<div class='clear'></div>
";

$echo_vNelson = "
	<div class='item_owner'>
		{$item['user']['picture']}
		<div class='item_info'>
			{$item['node']['link']} {$offer2buy}
			{$item['user']['link']}
		</div>
	</div>
	<div class='clear'></div>
";
?>
