<?php
// This is sucked out of search-result-vibio_item.tpl.php
// bad coding done fast, redo (figure out all the square displays,
// really wireframing by code right now, then integrate.)
// Crap, fast code.  Figure out what was done with search!
dsm($node);
$title = $node->title;
$url = "/node/" . $node->nid;
$info_split['image'] = $node->field_main_image[0]['filepath'];
$file = field_file_load($display_node->field_main_image[0]['fid']);
// why tthis doesn't work I don't know.... it seems to produce
//  the html but not deal with the imge.  Imagecache not set up right?
//$img = theme('imagecache', 'item_base_square', $file['filepath']);


echo "
                <div class='item-square'>
                        <a href='$url' title='$title' alt='$title'>
                                <img class='item-square-image' srcimagecache='$img' src='{$info_split['image']}' />
				$img
                        </a>
                        <div class='over-item'>
                                <a class='item-title' href='$url'>$title</a><br />
                        </div>
                        <div class='search-links'>$search_links</div>
                </div>
";
?>

