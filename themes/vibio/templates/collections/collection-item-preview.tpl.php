<?php
$updated = t("Updated !date", array("!date" => "<span class='timestamp_uncalculated'>{$item->node_changed}</span>"));
$price_image = theme("vibio_item_price_image", $item, "mini");
$item_image = theme('imagecache', 'collection_fixed_fluid_height_100', $item->image, $item->node_title, $item->node_title, ''); 

echo "<!-- sites/default/themes/vibio/templates/collections/collection-item-preview.tpl.php -->
	<div class='collection_list_image'>
		$price_image
		$item_image
		</a>
	</div>
	<div class='collection_list_item_summary'>
		<a href='/node/{$item->nid}'>
			<h5>{$item->node_title}</h5>
		</a>
		<span class='item_updated'>$updated</span>
		<span class='item_price'>{$item->offer2buy_price}</span>
		<div id='offer-buttons'>$offer_button</div>
		$share_links
	</div>
" 
;
?>
