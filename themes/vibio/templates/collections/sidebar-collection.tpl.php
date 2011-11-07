<?php
$num_items = t("!num items", array("!num" => $collection->num_items));
$collection_image = theme('imagecache', 'collection_fixed_fluid_grid_77', $collection->image, $collection->collection_description, $collection->collection_description, '');

echo "
	<div class='collection_sidebar_collection'>
		<div class='collection_sidebar_collection_image'>
			<a href='/collections/{$collection->cid}'>
				{$collection_image}
			</a>
		</div>
		<div class='collection_sidebar_collection_items'>
			{$item_html}
		</div>
		<div class='clear'></div>
		<a href='/collections/{$collection->cid}'>
			<h5>{$collection->title}</h5>
		</a>
		<span>$num_items</span>
	</div>
";
?>
