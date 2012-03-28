<div class="medium_user_pic">
<?php
$pic = $row->users_picture ? $row->users_picture : "/themes/vibio/images/icons/default_user.png";
//print_r($row);
$alt = $row->users_name . "'s Picture";
$title = ''; // or name, if it’snot printed right below anyway
$attributes = ''; print	theme('imagecache', 'medium_square_standard', $pic, $alt,$title, $attributes); ?>
</div>
