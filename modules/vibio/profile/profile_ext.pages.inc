<?php
function profile_ext_view_profile_category($user, $category)
{
	drupal_set_title(_profile_ext_get_title($user, $category));
	
	$res = _profile_get_fields($category);
	$out = "";
	
	while ($row = db_fetch_array($res))
	{
		if (isset($user->$row['name']) && !empty($user->$row['name']))
		{
			$field = is_array($user->$row['name']) ? implode("-", $user->$row['name']) : $user->$row['name'];
			$list = implode(", ", explode("\n", trim($field)));
			$out .= "<strong>{$row['title']}</strong>: {$list}<br />";
		}
	}
	
	return $out ? $out : t("User !user has no information for category !category", array("!user" => $user->name, "!category" => $category));
}

function profile_ext_manage_external_accounts($uid)
{
	global $user;
	
	// profile_ext depends on privacy depends on network
	$info = _network_get_userinfo($uid, false, true);
	$external_accounts = module_invoke_all("profile_ext_external_account_data");
	$is_admin = $user->uid == $uid;
	
	drupal_add_js("var external_accounts = {}", "inline");
	$out = "";
	foreach ($external_accounts as $type => $data)
	{
		if ($data['js'])
		{
			$confirm_params = array(
				"!account_type"	=> $data['name'],
			);
			
			$confirm = t("Are you sure you want to remove !account_type account !account_name from your Vibio account?", $confirm_params);
			$js = "
				external_accounts.{$data['id']} = {
					confirm_text: '$confirm'
				}
			";
			drupal_add_js($js, "inline");
			drupal_add_js($data['js']);
		}
		
		$out .= theme("profile_ext_external_accounts", $data, $info[$type], $is_admin);
	}
	
	return $out;
}

function profile_ext_dashboard($selected_tab=false)
{
	global $user;
	
	drupal_add_js("themes/vibio/js/profile.js");
	drupal_set_title(t("My Dashboard"));
	module_load_include("inc", "profile_ext");
	
	$tabs = array(t("Activity Feed") => views_embed_view("user_hb_incoming_activity"));
	profile_ext_get_dashboard_notifications($user->uid, $tabs);
	
	if (module_exists("collection"))
	{
		$tabs[t("Inventory")] = views_embed_view("user_collections", "default", $user->uid, PRIVACY_ONLYME);
	}
	
	foreach (module_invoke_all("profile_ext_dashboard", $user->uid) as $title => $tab)
	{
		$tabs[$title] = $tab;
	}
	
	if ($selected_tab)
	{
		drupal_add_js(array("profile_ext" => array("selected_tab" => str_replace("-", "_", $selected_tab))), "setting");
	}
	
	return theme("profile_ext_dashboard", $tabs);
}
?>