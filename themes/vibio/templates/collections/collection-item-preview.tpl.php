<?php
$updated = t("Updated !date", array("!date" => "<span class='timestamp_uncalculated'>{$item->node_changed}</span>"));
$price_image = theme("vibio_item_price_image", $item, "mini");

if($_GET['debug'] || 1 ) {
	require_once getcwd() . '/sites/all/modules/vshare/vshare_small.php';
	$share_links = vshare_small($item);
} else
	$share_links = "<i><u title='hover gives the Twitter and Facebook icons'>share$a$b</u></i>";


echo "
	<div class='collection_list_image'>
		$price_image
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
		{$share_links}
	</div>
";
?>
