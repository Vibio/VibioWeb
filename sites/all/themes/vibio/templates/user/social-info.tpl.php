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
		$view = views_embed_view("user_inventory", "default", $uid, $access);
		break;
	case "activity":
		$view = views_embed_view("user_heartbeat_activity", "block_1", $uid, $access);
		break;
	default:
		$view = $user->uid == $uid ? _vibio_ajax_get_relational_activity($uid, false) : views_embed_view("user_heartbeat_activity", "block_1", $uid, $access);
		break;
}
 
$tabs = array(); //module_invoke_all("user_social_info", $uid); //need to re do how this is done.

$tasks = array(
	"activity"	=> t("My Activity"),
	"friends"	=> t("Friends"),
	"inventory"	=> t("Inventory"),
);

if ($user->uid == $uid)
{
	$tasks = array_merge(array("friend-activity" => t("Friend Activity")), $tasks);
}

$additional_tabs = "";
$additional_divs = "";
$script = "
	<script type='text/javascript'>
		var tab_args = {};
";
foreach ($tabs as $id => $data)
{
	if ($data['custom_ajax'])
	{
		$url = "#{$id}";
		$link_attr = "class='tablink_custom_ajax' id='tablink_{$id}'";
		$additional_divs .= "<div id='{$id}'></div>";
	}
	else
	{
		$url = $data['url'];
		$link_attr = "";
	}
	
	$additional_tabs .= "
		<li>
			<a href='{$url}' {$link_attr}>{$data['title']}</a>
		</li>
	";
	
	if ($data['args'])
	{
		$script .= "tab_args.$id = {$data['args']}";
	}
}
$script .= "</script>";

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