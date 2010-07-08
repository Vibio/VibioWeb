<?php
global $user;
$activity = t("Activity");
$friends = t("Friends");
$stuff = t("Stuff");

$activity_feed = views_embed_view("user_heartbeat_activity", "default", $uid);
//test
echo "
	<div id='social_tabs'>
		<ul>
			<li>
				<a href='#user_activity'>$activity</a>
			</li>
			<li>
				<a href='/vibio-ajax/get-friends/$uid'>$friends</a>
			</li>
			<li>
				<a href='/vibio-ajax/get-inventory/$uid'>$stuff</a>
			</li>
		</ul>
		<div id='user_activity'>
			$activity_feed
		</div>
	</div>
";
?>