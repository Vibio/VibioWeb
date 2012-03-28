<?php
/* 
Notes for themer: change 'tiny_profile_pic' to any other imagecache
to change the size

Notes for devel finding code:
20111118: produced here: modules/vibio/vibio_item/product_catalog/product.module
function product_vibio_item_images($item_nid)
calls
modules/vibio/vibio_item/product_catalog/product.inc:function product_images($node, $get_main=false)    returns images as urls
 *
 * additional-images.tpl.php is generated within node-product.tpl.php with
 * $product_images = theme("vibio_item_images", product_images($node));
 */

if (!empty($images)) {
	$header = t("Additional Images");
	$image_html = "";
	$attributes = array(
		'class'=>'node_view_item_image'
		);
	//images come here either as filepaths relative to webroot or absolute filepaths
	foreach ($images as $image)
	{
		$imagecached = theme('imagecache', "tiny_profile_pic", $image, "Additional images of this item", "", $attributes) ;

                //Make sure that we convert absolute filepaths to relative paths that can be used in a <a> tag
		//If the path is relative, this should return something
		$relative_path = strpos($image, 'sites/default/files');
		//If relative_path is false, we need to make the $image into a relative path	
		if ($relative_path === FALSE) {
      //delete: $image_filename = str_replace('/var/www/vibio/uploads/', '', $image);
      $image_pretty = "/sites/default/files/uploads/" . $image_filename;
      //Otherwise, we should have a relative path we can use
    }
    else {
      $image_pretty = url($image);
      // If there are amphersands in the encoded filename, bad stuff'll happen.
      $image_pretty = str_replace('%2526', '&', $image_pretty);
    }
	
		$image_html .= "
			<a href=\"{$image_pretty}\" rel=\"prettyphoto[item_image]\">
				$imagecached
			</a>";
	}
	
	echo "
		<h4>$header</h4>
		$image_html
	";
}
?>
