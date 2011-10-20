<?php
// This is sucked out of search-result-vibio_item.tpl.php
// bad coding done fast, redo (figure out all the square displays,
// really wireframing by code right now, then integrate.)
// Crap, fast code.  Figure out what was done with search!
//dsm($node);
//dvm($node);
//print_r($node);
$title = $node->title;
$url = "/node/" . $node->nid;
// not needed with imagecache: $info_split['image'] = $node->field_main_image[0]['filepath'];
//$img = theme('imagecache', 'item_base_square', $node->field_main_image[0]['filepath']);
$img = theme('imagecache', 'product_fixed_width', $node->field_main_image[0]['filepath']);

//print $node->field_main_image[0]['filepath']; // yes, this is right
//cut out, replace with imagecache: <img class='item-square-image' srcimagecache='$img' src='{$info_split['image']}' />
$flag = flag_create_link('feature', $node->nid); 

$justkeepmakingchanges = "
                <div class='item-rectangle'> 
                        <a href='$url' title='$title' alt='$title'>
                               $img
                        </a>
                        <div class='title-item'>
                                <a class='item-title' href='$url'>$title</a><br />
                        </div>
			$flag
                        <div class='search-links'>Search links: $search_links X</div>
                </div>
";


print $justkeepmakingchanges;
?>

