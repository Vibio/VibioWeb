<?php
$collection_url = url("collections/{$collection->cid}");
$manage_link = $collection->is_owner ? l(t("Manage Collection"), "collections/manage/{$collection->cid}") : "";
$total_items = t("!count items", array("!count" => $collection->total_items));
$expand = t("Expand Item List");

if ($show_preview)
{
	$preview = "
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
	";
}

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
			<span class='collection_item_count'>$total_items</span>
			<span class='manage_collection_link'>$manage_link</span>
			{$collection->share_html}
			<div class='clear'></div>
			<p>{$collection->collection_description}</p>
			{$collection->collection_categories}
		</div>
		<div class='clear'></div>
		$preview
	</div>
";
?>