<?php
echo "
	<div class='potential_friend'>
		<a href='/user/{$friend->info['uid']}'>
			<img src='{$photo}' />
		</a>
		<a href='/user/{$friend->info['uid']}'>
			{$friend->info['name']}
		</a>
		{$uri_links}
		{$friend_src_link}
	</div>
";
?>