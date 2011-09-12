<?php
function profile_ext_get_dashboard_notifications($uid, &$tabs)
{
	$num_notifications = 0;
	
	foreach (module_invoke_all("profile_ext_dashboard_notifications", $uid) as $module_name => $data)
	{
		$num_notifications += $data['count'];
		$output .= $data['display'];
	}
	
	$title = t("Invitations !num", array("!num" => $num_notifications > 0 ? "($num_notifications)" : ""));
	$tabs[$title] = $output;
	
	return $num_notifications;
}

function profile_ext_initial_steps($u, $force_show=false)
{
	$steps = array();
	if (module_exists("network") && ($force_show || !count(_network_get_friends($u->uid, 1))))
	{
		$steps[] = array(
			"image" => "/themes/vibio/images/thirdparty/facebook.png",
			"text"	=> t("Expand your circle with the Vibio !ff", array("!ff" => l(t("Connection Finder"), "contacts", array("fragment" => "ui-tabs-1")))),
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

function profile_ext_public_info($uid)
{
	$info = module_invoke_all("profile_ext_public_info", $uid);
	/* this collects info from all over, like:
		function collection_profile_ext_public_info($uid)
		
	*/


	usort($info, function($a, $b)
	{
		return $a['weight'] > $b['weight'] ? 1 : -1;
	});
	$out = "<table class='profile_fields'>";
	foreach ($info as $i)
	{
		$out .= "
			<tr>
				<td class='field_name'>{$i['title']}:</td>
				<td class='field_val'>{$i['data']}</td>
			</tr>
		";
	}
	$out .= "</table>";
	
	return $out;
}
?>
