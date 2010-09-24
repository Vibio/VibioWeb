<?php
global $user;

$access = module_exists("privacy") ? privacy_get_access_level($uid) : 1;
$activity_feed = views_embed_view("user_heartbeat_activity", "block_1", $uid, $access);
$activity_feed_title = t("Activity Feed");
$profile_info = theme("profile_ext_profile_info", $uid, $access);
$profile_info_title = t("Profile Information");
$inventory = views_embed_view("user_collections", "default", $uid, $access);
$inventory_title = t("Inventory");

echo "
	<div id='profile_user_tabs' class='tabs tabs_big'>
		<div class='tabs'>
			<ul class='tabs primary clearfix'>
				<li>
					<a href='#activity_feed'>
						<span class='tab'>$activity_feed_title</span>
					</a>
				</li>
				<li>
					<a href='#user_inventory'>
						<span class='tab'>$inventory_title</span>
					</a>
				</li>
				<li>
					<a href='#profile_info'>
						<span class='tab'>$profile_info_title</span>
					</a>
				</li>
			</ul>
		</div>
		<div class='profile_info_container'>
			<div id='activity_feed'>
				$activity_feed
			</div>
			<div id='profile_info'>
				$profile_info
			</div>
			<div id='user_inventory'>
				$inventory
			</div>
		</div>
	</div>
";
?>