<?php
global $user;

$default_tab = false;
if (arg(2) == "inventory")
{
	$default_tab = "#user_inventory";
}
elseif (arg(2) == "badges")
{
	$default_tab = "#user_badges";
}

drupal_add_js(array("profile_settings" => array("default_tab" => $default_tab)), "setting");

$access = module_exists("privacy") ? privacy_get_access_level($uid) : 1;
$activity_feed = views_embed_view("user_heartbeat_activity", "block_1", $uid, $access);
$activity_feed_title = t("Activity Feed");
$profile_info = theme("profile_ext_profile_info", $uid, $access);
$profile_info_title = t("Profile Information");
$inventory = views_embed_view("user_collections", "default", $uid, $access);
$inventory_title = t("Inventory");

if (module_exists("badge"))
{
	$badges_title = t("Badges");
	$badges_title = "
		<li>
			<a href='#user_badges'>
				<span class='tab'>$badges_title</span>
			</a>
		</li>
	";
	
	$badges_html = theme("badge_list", $uid);
	$badges_html = "
		<div id='user_badges'>
			$badges_html
		</div>
	";
}

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
				$badges_title
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
			$badges_html
		</div>
	</div>
";
?>
