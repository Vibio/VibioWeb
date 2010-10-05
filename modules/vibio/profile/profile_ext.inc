<?php
function profile_ext_get_dashboard_notifications($uid, &$tabs)
{
	$num_notifications = 0;
	
	foreach (module_invoke_all("profile_ext_dashboard_notifications", $uid) as $module_name => $data)
	{
		$num_notifications += $data['count'];
		$output .= $data['display'];
	}
	
	$title = t("Notifications !num", array("!num" => $num_notifications > 0 ? "($num_notifications)" : ""));
	$tabs[$title] = $output;
	
	return $num_notifications;
}

function profile_ext_initial_steps($u, $force_show=true)
{
	$steps = array();
	if (module_exists("network") && ($force_show || !count(_network_get_friends($u->uid, 1))))
	{
		$steps[] = array(
			"image" => "/themes/vibio/images/thirdparty/facebook.png",
			"text"	=> t("Find some friends using the !ff", array("!ff" => l(t("Friend Finder"), "friends", array("fragment" => "ui-tabs-1")))),
		);
	}

	if (module_exists("collection"))
	{
		module_load_include("inc", "collection");
		if ($force_show || !count(collection_get_collections($uid)))
		{
			$steps[] = array(
				"image" => "/themes/vibio/images/collections/collections.png",
				"text"	=> t("Build a collection by searching for items using the top search bar"),
			);
		}
	}

	$progress = profile_ext_profile_ext_progress($u->uid);
	$progress = $progress[t("Profile")];

	if ($force_show || !$progress['complete'])
	{
		$steps[] = array(
			"image"	=> "/themes/vibio/images/icons/default_user.png",
			"text"	=> t("Fill out info !about and !demo", array("!about" => l(t("about you"), "user/{$u->uid}/edit/About Me"), "!demo" => l(t("where you're from"), "user/{$u->uid}/edit/Demographics"))),
		);
	}

	return $steps;
}
?>
