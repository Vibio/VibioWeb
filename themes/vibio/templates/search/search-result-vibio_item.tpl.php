<?php /* print '<pre>'. check_plain(print_r($info_split, 1)) .'</pre>'; */ 
//print $result[node]->nid;
$node = $result[node]; //!!! Different from node-view-flag_featured.tpl.php
		// !!! Also, $search_links is set here, not there.

$flag = flag_create_link('feature', $node->nid);
$img = theme('imagecache', 'product_fixed_width', $node->field_main_image[0]['filepath']);

$second = "
	<div class='item-rectangle'> 
		<a href='$url' title='$title' alt='$title'>
			 $img
		</a>
		<div class='title-item'>
			<a class='item-title' href='$url'>$title</a><br/>
		</div>                        $flag
	<div class='search-links'>$search_links</div>
	</div>
";

$first= "
		<div class='item-square'>
			<a href='$url' title='$title' alt='$title'>
				<img class='item-square-image' srcimagecache='$img' src='{$info_split['image']}' />
			</a>
			<div class='over-item'>
				<a class='item-title' href='$url'>$title</a><br />
			</div>
			<div class='search-links'>$search_links</div>
		</div>
";


print $second;

?>
