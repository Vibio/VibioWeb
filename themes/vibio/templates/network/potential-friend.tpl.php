<?php
echo "
	<div class='potential_friend'>
		$photo
		<div class='potential_friend_summary'>
			<a href='/user/{$friend->info['uid']}'>
				{$friend->info['name']}
			</a><br />
			{$friend_src_link}
		</div>
		<div class='potential_friend_links'>
			{$uri_links}
		</div>
		<div class='clear'></div>
	</div>
";
?>
