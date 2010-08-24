<?php
global $user;
$access = module_exists("privacy") ? privacy_get_access_level($uid) : 1;

$tab = arg(2);

switch ($tab)
{
	case "friend-activity":
		$view = _vibio_ajax_get_relational_activity($uid, false);
		break;
	case "friends":
		$view = views_embed_view("user_relationships_browser", "default", $uid);
		break;
	case "inventory":
		$view = module_exists("collection") ? views_embed_view("user_collections", "default", $uid, $access) : views_embed_view("user_inventory", "default", $uid, $access);
		//$view = views_embed_view("user_inventory", "default", $uid, $access);
		break;
	case "activity":
		$view = views_embed_view("user_heartbeat_activity", "block_1", $uid, $access);
		break;
	default:
		$func = strtolower(str_replace("-", "_", $tab))."_user_data";
		if (function_exists($func))
		{
			$view = $func(arg(1));
		}
		else
		{
			$view = $user->uid == $uid ?
				_vibio_ajax_get_relational_activity($uid, false) :
				views_embed_view("user_heartbeat_activity", "block_1", $uid, $access);
		}
		break;
}
 
$tabs = module_invoke_all("user_social_info", $uid); //need to re do how this is done.

$tasks = array(
	"activity"	=> t("My Activity"),
	"friends"	=> t("Friends"),
	"inventory"	=> t("Inventory"),
);

if ($user->uid == $uid)
{
	$tasks = array_merge(array("friend-activity" => t("Friend Activity")), $tasks);
}

foreach ($tabs as $data)
{
	$tasks[$data['url']] = $data['title'];
}

$additional_tabs = "";

if ($user->uid == $uid)
{
	$relational_header = t("Relational Activity");
	$relational_tab = "<li><a href='/vibio-ajax/get-relational-activity/$uid'>$relational_header</a></li>";
}

echo "<ul id='social_tabs'>";
foreach ($tasks as $url => $title)
{
	echo "<li><a href='/user/{$uid}/{$url}'>$title</a></li>";
}
echo "<li><img id='social_loading_div' src='/sites/all/themes/vibio/images/ajax-loader.gif' /></li>";
echo "</ul><div style='clear: both;'></div>";

echo $view;
?>