<?php
$profile_pic = $row->actor->picture ? file_create_url($row->actor->picture) : "/themes/vibio/images/icons/default_user.png";
$alt = $row->actor->name;

echo "
	<div class='picture'>
		<a href='/user/{$row->actor->uid}'>
			<img src='$profile_pic' alt='$alt' title='$alt' />
		</a>
		<span class='view_activity_timestamp timestamp_uncalculated'>{$row->timestamp}</span>
	</div>
";
?>