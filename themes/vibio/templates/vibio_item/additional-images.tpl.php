<?php
/* 
Notes for themer: change 'tiny_profile_pic' to any other imagecache
to change the size

Notes for devel finding code:
20111118: produced here: modules/vibio/vibio_item/product_catalog/product.module
function product_vibio_item_images($item_nid)
calls
modules/vibio/vibio_item/product_catalog/product.inc:function product_images($node, $get_main=false)    returns images as urls

 */
if (!empty($images))
{
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
		if ( !strpos('sites/default/files', $image) ) {
                        $image_filename = str_replace('/var/www/vibio/uploads/', '', $image);
                        $image_pretty = "/sites/default/files/uploads/" . $image_filename;
                } else {
                        $image_pretty = $image;
                        die($image);
                }
	
		$image_html .= "
			<a href='$image_pretty' rel='prettyphoto[item_image]'>
				$imagecached
			</a>";
	}
	
	echo "
		<h4>$header</h4>
		$image_html
	";
}
?>
