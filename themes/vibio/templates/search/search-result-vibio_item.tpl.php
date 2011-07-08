<?php /* print '<pre>'. check_plain(print_r($info_split, 1)) .'</pre>'; */ ?>
<?php
/* this section is a bunch of efforts to get imagecache working.
All failed, I'm in a rush, so leaving notes and moving on...
	print_r($result[node]->field_main_image[0][fid]);
   $file = field_file_load($result[node]->field_main_image[0][fid]); 
   $img = theme('imagecache', 'item_base_square', $file['filepath']); 
print $img;
   $filepath = '/home/ubuntu/www/vibio/src/sites/default/files/uploads/products/B0018C8STO.jpg'; 
//imagecache_image_flush($file);
//$img = more than image theme('imagecache', 'item_base_square', $file); 
  // $output .= theme('imagecache', 'product', $first['filepath'], $first['alt'], $first['title']);
$presetname = 'item_base_square';

$image = theme('imagecache', $presetname, $filepath); //, $alt, $title, $attributes);
$img = imagecache_create_url($presetname, $filepath);
print "image:" . $image;
*/

echo "
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
?>
