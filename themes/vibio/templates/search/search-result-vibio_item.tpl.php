<?php /* print '<pre>'. check_plain(print_r($info_split, 1)) .'</pre>'; */ 
$node = $result[node]; //!!! Different from node-view-flag_featured.tpl.php
		// !!! Also, $search_links is set here, not there.
$flag = flag_create_link('feature', $node->nid);
//dsm($node->field_main_image[0]['filepath']);
//Alec hack: turn off imagecache
$img_path = $node->field_main_image[0]['filepath'];
if(strstr($img_path, 'sites') != FALSE){
	//This handles images saved from Amazon, possibly newly uploaded images as well
	$img = theme('image', $img_path, '', '', array('width' => 160), FALSE);
}else{
	//This handles uploaded images (possibly only old ones). I believe
	//these images have file paths like /var/www/..., as per the
	//the old filesystem. Perhaps we need to clear out the old file paths?
	$img_path = 'http://staging.vibio.com/sites/default/files/uploads/' . $img_path; 
	$img = theme('image', $img_path, '', '', array('width' => 160), FALSE);
}
// $mine should be true if it's yours,  true or null
// Fires for PRODUCTS (merely searched products too)
//if ($node->thisismine) { $minetext = "It's mine all mine"; }

// $search_links are have and want buttons

// the views-fluidgrid-item class was drive-by-theming, senseless. 
// These things are jammed into columns with no fluid anything.
// Maybe one day a themer may decide to remove the columns 

$rectangle = "
<div class='views-fluidgrid-item'> 
	<div class='views-fluidgrid-item-inner'>
  	<div class='views-field-field-main-image-fid'>
  		<a href='$url' title='$title' alt='$title'>
  			 $img
  		</a>
  	</div>	
			$minetext
  		<div class='search-links'>$search_links</div>
  		<div class='views-field-title'>
  			<a class='item-title' href='$url'>$title</a>
  		</div>   		                     
  	<div class='search-flag'>$flag</div>
	</div>
</div>
";

print $rectangle;

?>
