<?php
// Confused: what is actor?  Is this totally custom code?  Doesn't extend
//  well when creating new views
!empty($row->actor->picture) ? $profile_pic = $row->actor->picture : $profile_pic = "themes/vibio/images/icons/default_user_large.png";
// Generate image using imagecache
$picture = theme('imagecache', "tiny_profile_pic", $profile_pic, $alt, $alt, "");
$alt = $row->actor->name;
//dsm($row);
//debug_backtrace());
//why don't any backtrace options work? die(var_dump(debug_backtrace()));
echo "
	<div class='picture'>
		<a href='/user/{$row->actor->uid}'>
			{$picture}
		</a>
		<span class='view_activity_timestamp timestamp_uncalculated'>{$row->timestamp}</span>
	</div>
";
?>
