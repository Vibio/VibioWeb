<?php
$date_added = t("Added !date", array("!date" => date("M j, Y", $collection->created)));
$tags = t("Tags: !tags", array("!tags" => $collection->tags));
$num_items = t("!count items", array("!count" => $collection->num_items));
$link = l(t("Check it out"), "collections/{$collection->cid}", array("attributes" => array("class" => "collection_link")));

echo "
	<div class='collection_image'>
		<a href='/collections/{$collection->cid}'>
			<img src='{$collection->image}' />
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
	</div>
";
?>
