<?php
$profile_pic = $row->actor->picture ? file_create_url($row->actor->picture) : "/themes/vibio/images/icons/default_user.png";
$alt = $row->actor->name;
$date = date("z") == date("z", $row->timestamp) ? date("g:iA", $row->timestamp) : date("M j", $row->timestamp);

echo "
	<div class='picture'>
		<a href='/user/{$row->actor->uid}'>
			<img src='$profile_pic' alt='$alt' title='$alt' />
		</a>
		<span class='view_activity_timestamp'>$date</span>
	</div>
";
?>