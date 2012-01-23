<?php
$date_added = t("Added !date", array("!date" => date("M j, Y", $collection->created)));
$tags = t("Tags: !tags", array("!tags" => $collection->tags));
$num_items = t("!count items", array("!count" => $collection->num_items));
$link = l(t("Check it out"), "collections/{$collection->cid}", array("attributes" => array("class" => "collection_link")));
$collection_image = theme('imagecache', 'collection_fixed_fluid_grid_115', $collection->image, $collection->collection_description, $collection->collection_description, '');
echo "
	<div class='block-wrapper'><h2>Showcase Item</h2><div class='collection_image'>
		<a href='/collections/{$collection->cid}'>
			{$collection_image}
		</a>
	</div>
	<div class='collection_info'>
		<div class='collection_owner'>{$collection->user}</div>
		<div class='collection_title'>{$collection->title}</div>
		<div class='collection_item_count'>{$num_items}</div>
		<div class='collection_description'>{$collection->description}</div>
		<div class='collection_categories'>{$tags}</div>
		<div class='collection_date_added'>{$date_added}</div>
		<div class='collection_link'>$link</div>
	</div></div>
";
?>
