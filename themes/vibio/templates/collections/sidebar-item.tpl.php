<?php
$collection_image = theme('imagecache', 'collection_sidebar_fixed_25', $collection_item->image, $collection_item->title, $collection_item->title, '');
echo "
	<a href='/node/{$collection_item->item_nid}'>
		{$collection_image}
	</a>
";
?>
