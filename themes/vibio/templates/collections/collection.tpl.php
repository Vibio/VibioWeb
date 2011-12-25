<?php
$collection_url = url("collections/{$collection->cid}");
$manage_link = $collection->is_owner ? l(t("Edit this Collection"), "collections/manage/{$collection->cid}") : "";
$total_items = t("!count items", array("!count" => $collection->total_items));
$expand = t("View this collection");
$collection->user = l($collection->user_name, "user/{$collection->uid}");

if ($show_preview)
{
	$preview = "
		<div class='collection_preview'>
			<div class='collection_preview_items'></div>
			<div class='collection_preview_loading'>
				<img src='/themes/vibio/images/ajax-loader.gif' />
			</div>
			<div class='collection_preview_init'>
				<span>$expand</span>
			</div>
		</div>
	";
	$collection->share_html = null;
}
$collection_image = theme('imagecache', 'collection_fixed_fluid_grid_77', $collection->image, $collection->collection_description, $collection->collection_description, '');

echo "
	<div class='collection_list_collection' id='collection_{$collection->cid}'>
		<div class='manage_collection_link'>$manage_link <br /><a href='/collections/new'>Create New Collection</a></div>
		<div class='collection_image'>
			<a href='$collection_url'>
				{$collection_image}
			</a>
		</div>
		<div class='collection_summary'>
			<a href='$collection_url'>
				<h3>{$collection->collection_title}</h3>
			</a>
			<div class='collection_item_count'>Number of items: $total_items</div>
			<div class='collection-user'>Collection creator: {$collection->user}</div>
			<p>{$collection->collection_description}</p>
			<div class='clear'></div>
			{$collection->collection_categories}
			{$collection->share_html}
		</div>
		<div class='clear'></div>
		$preview
	</div>
";
?>
