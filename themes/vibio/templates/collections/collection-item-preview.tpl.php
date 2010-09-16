<?php
$updated = t("Updated !date", array("!date" => date("M j", $item->node_changed)));

echo "
	<div class='collection_list_image'>
		<a href='/node/{$item->nid}'>
			<img src='{$item->image}' />
		</a>
	</div>
	<div class='collection_list_item_summary'>
		<a href='/node/{$item->nid}'>
			<h5>{$item->node_title}</h5>
		</a>
		<span class='item_updated'>$updated</span>
		<span class='item_price'>{$item->offer2buy_price}</span>
	</div>
";
?>