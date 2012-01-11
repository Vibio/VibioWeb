<?php
$collection_image = theme('imagecache', 'collection_sidebar_fixed_25', $collection_item->field_main_image[0]['filepath'], $collection_item->title, $collection_item->title, '');
echo "
	<a href='/node/{$collection_item->nid}'>
		{$collection_image}
	</a>
";
?>
