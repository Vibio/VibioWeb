<?php 
/* print '<pre>'. check_plain(print_r($info_split, 1)) .'</pre>'; */ 
//$node = $result[node]; //!!! Different from node-view-flag_featured.tpl.php
		// !!! Also, $search_links is set here, not there.
$flag = flag_create_link('feature', $node->nid);

// Get default image... we'll want to somewhat standardize this,
//  but this might be an exception to standardization.
//  Are there alternative images that might sometimes exist if the main one doesn't?
if ($node->field_main_image[0]['filepath']) {
  //The image needs to be formatted
	$img = theme('imagecache', 'product_fixed_width', $node->field_main_image[0]['filepath']);
} elseif(!empty($img)) {
  //Do nothing; $img is already set
}else{
  //Use default
  $img = theme('imagecache', 'product_fixed_width', "themes/vibio/images/icons/default_item_large.png");
}

// $mine should be true if it's yours,  true or null
// Fires for PRODUCTS (merely searched products too)
//if ($node->thisismine) { $minetext = "It's mine all mine"; }

// $search_links are have and want buttons

// the views-fluidgrid-item class was drive-by-theming, senseless. 
// These things are jammed into columns with no fluid anything.
// Maybe one day a themer may decide to remove the columns.
?>
<div class="views-fluidgrid-item"> 
	<div class="views-fluidgrid-item-inner">
  	<div class="views-field-field-main-image-fid">
  		<a href="<?php print $url; ?>" title="<?php print $title; ?>" alt="<?php print $title; ?>">
  			 <?php print $img; ?>
  		</a>
  	</div>	
			<?php print $minetext; ?>
  		<div class="search-links"><?php print $search_links; ?></div>
  		<div class="views-field-title">
  			<a class="item-title" href="<?php print $url; ?>"><?php print $title; ?></a>
  		</div>   		                     
  	<div class="search-flag"><?php print $flag; ?></div>
	</div>
</div>
