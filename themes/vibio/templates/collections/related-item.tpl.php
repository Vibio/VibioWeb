<?php
$image = field_file_load($item->node_data_field_main_image_field_main_image_fid);
$link = l(t("Check it out"), "node/{$item->nid}", array("attributes" => array("class" => "collection_link")));
if(empty($image['filename'])){
  //Use the default image
  theme('imagecache', 'collection_fixed_fluid_grid_115',
          'themes/vibio/images/icons/default_item_large.png', $item->node_title, $item->node_title, '');
}else{
  //Imagecache is probably unnecessary, consdering that views should be 
  //giving us an imagecached file path...
  $collection_image = theme('imagecache', 'collection_fixed_fluid_grid_115', 
          $image['filepath'], $item->node_title, $item->node_title, '');
}
echo "
	<div class='block-wrapper'>
	<h2>Showcase Item</h2><div class='collection_image'>
		<a href='/node/{$item->nid}'>
			{$collection_image}
		</a>
	</div>
	<div class='collection_info'>
	<div class='collection_title'>{$item->node_title}</div>
	</div></div>
";
  /*<div class='collection_owner'>{$item->users_name}</div>*/
?>

