<?php
$num_items = t("!num items", array("!num" => $collection->num_items));

echo "
	<div class='collection_sidebar_collection'>
		<div class='collection_sidebar_collection_image'>
			<a href='/collections/{$collection->cid}'>
				<img src='{$collection->image}' />
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