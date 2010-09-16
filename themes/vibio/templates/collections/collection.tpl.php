<?php
$collection_url = url("collections/{$collection->cid}");
$manage_link = $collection->is_owner ? l(t("Manage Collection"), "collections/manage/{$collection->cid}") : "";
$total_items = t("!count items", array("!count" => $collection->total_items));
$expand = t("Expand Item List");

echo "
	<div class='collection_list_collection' id='collection_{$collection->cid}'>
		<div class='collection_image'>
			<a href='$collection_url'>
				<img src='{$collection->image}' title='{$collection->collection_description}' alt='{$collection->collection_description}' />
			</a>
		</div>
		<div class='collection_summary'>
			<a href='$collection_url'>
				<h3>{$collection->collection_title}</h3>
			</a>
			<span>$total_items</span>
			<p>{$collection->collection_description}</p>
			<small>$manage_link</small>
		</div>
		<div class='clear'></div>
		<div class='collection_preview'>
			<div class='collection_preview_items'></div>
			<div class='collection_preview_loading'>
				<img src='/themes/vibio/images/ajax-loader.gif' />
			</div>
			<div class='collection_preview_init'>
				<img src='/themes/vibio/images/collections/expand.png' />
				<span>$expand</span>
			</div>
		</div>
	</div>
";
?>