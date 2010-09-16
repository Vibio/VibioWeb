<?php
$updated = t("Updated !date", array("!date" => date("M j", $item->node_changed)));

echo "
	<a href='/node/{$item->nid}'>
		<img src='{$item->image}' />
	</a>
	<a href='/node/{$item->nid}'>
		<h5>{$item->node_title}</h5>
		<span class='item_updated'>$updated</span>
	</a>
";
?>