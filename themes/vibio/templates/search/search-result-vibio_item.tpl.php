<?php /* print '<pre>'. check_plain(print_r($info_split, 1)) .'</pre>'; */ 
$node = $result[node]; //!!! Different from node-view-flag_featured.tpl.php
		// !!! Also, $search_links is set here, not there.
$flag = flag_create_link('feature', $node->nid);
$img = theme('imagecache', 'product_fixed_width', $node->field_main_image[0]['filepath']);

// $search_links are have and want buttons

$rectangle = "
	<div class='item-rectangle col_$zebra'> 
		<a href='$url' title='$title' alt='$title'>
			 $img
		</a>
		<div class='title-item'>
			<a class='item-title' href='$url'>$title</a><br/>
		</div>                        
	<div class='search-flag'>$flag</div>
	<div class='search-links'>$search_links</div>
	</div>
";

print $rectangle;

?>
