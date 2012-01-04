<?php
//@todo: get all of these variables into preprocess functions
$collection_url = url("collections/{$collection->nid}");
$manage_link = $collection->is_owner ? l(t("Edit this Collection"), "collections/manage/{$collection->nid}") : "";
$add_collection_link = $collection->is_owner ? l(t("Create New Collection"), "collections/new") : "";
$total_items = t("!count items", array("!count" => $collection->total_items));
$expand = t("View this collection");
$collection->user = l($collection->name, "user/{$collection->uid}");

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
$collection_image = theme('imagecache', 'collection_fixed_fluid_grid_77', $collection->image, $collection->body, $collection->body, '');

echo "
	<div class='collection_list_collection' id='collection_{$collection->nid}'>
		<div class='manage_collection_link'>$manage_link <br /> $add_collection_link</div>
		<div class='collection_image'>
			<a href='$collection_url'>
				{$collection_image}
			</a>
		</div>
		<div class='collection_summary'>
			<a href='$collection_url'>
				<h3>{$collection->title}</h3>
			</a>
			<div class='collection_item_count'>Number of items: $total_items</div>
			<div class='collection-user'>Collection creator: {$collection->user}</div>
			<p>{$collection->body}</p>
			<div class='clear'></div>
			{$collection->collection_categories}
			{$collection->share_html}
		</div>
		<div class='clear'></div>
		$preview
	</div>
";
?>
