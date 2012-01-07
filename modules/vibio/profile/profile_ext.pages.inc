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
	$out = "<table class='user_external_accounts'>";
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
		
		$display_func = "{$data['id']}_external_account_display";
		$out .= function_exists($display_func) && ($output = $display_func($uid, $data, $info[$type], $is_admin)) ?
			$output : theme("profile_ext_external_accounts", $data, $info[$type], $is_admin);
	}
	$out .= "</table>";
	
	$title = t("Linked Accounts");
	return "
		<table class='profile_fields'>
			<tr>
				<td class='field_name section_title'>
					$title
				</td>
				<td>$out</td>
			</tr>
		</table>
	";
}

/**
 * Coordinate printing various profile-branch pages, eg
 *  profile/uid/badges
 *

also began here with
function profile_ext_preprocess_user_profile(&$vars){



 /* @return 
 *  theme("profile_ext_profile", $child_page)
 *
 *		- stephen, 20110811 keyword reprofileicious
 */
function TAKE_OTHER_PATHprofile_ext_profile($account, $page_arg = "activity") {
	// model a bit off user_view in user module
	// rewrite that tpl, using all new names to not get entangled.
	$username =   check_plain($account->name);

  // Retrieve all profile fields and attach to $account->content.
  user_build_content($account);
	
	/* copy some of Nelson's code just in case...
		try cutting this out to see what happens when time,
		or see if I need pieces put here and commented
  */	
	drupal_add_js("themes/vibio/js/profile.js");
	module_load_include("inc", "profile_ext");
	//   $steps = module_exists("newuser") ? array() : profile_ext_initial_steps($user);

  return theme("profile_ext_profile", $account, $this_page, $sec_menu);
}
// puts heartbeat in dashboard, and ??
function profile_ext_dashboard($selected_tab=false)
{
	global $user;
	
	drupal_add_js("themes/vibio/js/profile.js");
	drupal_set_title(t("My Dashboard"));
	module_load_include("inc", "profile_ext");
	
	$tabs = array(t("Activity Feed") => views_embed_view("user_hb_incoming_activity"));
	$steps = module_exists("newuser") ? array() : profile_ext_initial_steps($user);

	profile_ext_get_dashboard_notifications($user->uid, $tabs);
	
	if (module_exists("collection"))
	{
		$tabs[t("Inventory")] = views_embed_view("user_collections1", "default", $user->uid, PRIVACY_ONLYME);
	}
	
	foreach (module_invoke_all("profile_ext_dashboard", $user->uid) as $title => $tab)
	{
		$tabs[$title] = $tab;
	}
	
	if ($selected_tab)
	{
		drupal_add_js(array("profile_ext" => array("selected_tab" => str_replace("-", "_", $selected_tab))), "setting");
	}
	
	return theme("profile_ext_dashboard", $tabs, $steps);
}

function profile_ext_change_picture($u)
{
	exit(drupal_get_form("profile_ext_edit_picture", $u));
}

function profile_ext_edit_picture(&$state, $u)
{
	$current = $u->picture ? theme("user_picture", $u) : "";
	$prefix = $u->picture ? "$current<div id='profile_ext_change_picture_form_container'>" : "";
	$suffix = $u->picture ? "</div><div class='clear'></div>" : "";
	
	$form = array(
		"#prefix"		=> $prefix,
		"#suffix"		=> $suffix,
		"#attributes"	=> array(
			"enctype"	=> "multipart/form-data",
		),
		"_account"	=> array(
			"#type"	=> "value",
			"#value"=> $u,
		),
	);
	
	if ($u->picture)
	{
		$form['picture_delete'] = array(
			"#type"			=> "checkbox",
			"#title"		=> t("Delete Picture"),
			"#description"	=> t('Check this box to delete your current picture.'),
		);
	}
	else
	{
		$form['picture_delete'] = array("#type"	=> "hidden");
	}
	
	$form['picture_upload'] = array(
		"#type"			=> "file",
		"#title"		=> t("Upload Picture"),
		"#size"			=> $u->picture ? $form['picture_upload']['#size'] = 15 : 40,
		"#description"	=> t('Your virtual face or picture. Maximum dimensions are %dimensions and the maximum size is %size kB.', array('%dimensions' => variable_get('user_picture_dimensions', '85x85'), '%size' => variable_get('user_picture_file_size', '30'))) .' '. variable_get('user_picture_guidelines', ''),
	);
	
	$form['submit'] = array(
		"#type"	=> "submit",
		"#value"=> t("Save"),
	);
	
	return $form;
}

//modified user_validate_picture
function profile_ext_edit_picture_validate(&$form, &$state)
{
	$vals = $state['values'];
	$u = $vals['_account'];
	
	$validators = array(
		'file_validate_is_image' => array(),
		'file_validate_image_resolution' => array(variable_get('user_picture_dimensions', '85x85')),
		'file_validate_size' => array(variable_get('user_picture_file_size', '30') * 1024),
	);
	
	if ($file = file_save_upload("picture_upload", $validators))
	{
		if (isset($u->picture) && file_exists($u->picture))
		{
			file_delete($u->picture);
		}
		
		$info = image_get_info($file->filepath);
		$destination = variable_get('user_picture_path', 'pictures')."/picture-{$u->uid}.{$info['extension']}";
		
		if (file_copy($file, $destination, FILE_EXISTS_REPLACE))
		{
			$state['values']['picture'] = $file->filepath;
		}
		else
		{
			form_set_error('picture_upload', t("Failed to upload the picture image; the %directory directory doesn't exist or is not writable.", array('%directory' => variable_get('user_picture_path', 'pictures'))));
		}
	}
}

function profile_ext_edit_picture_submit(&$form, &$state)
{
	$vals = $state['values'];
	$u = $vals['_account'];
	
	if (!empty($vals['picture_delete']))
	{
		if ($u->picture && file_exists($u->picture))
		{
			file_delete($u->picture);
		}
		
		$picture = "";
	}
	else
	{
		$picture = $vals['picture'];
	}
	
	$sql = "UPDATE {users}
			SET `picture`='%s'
			WHERE `uid`=%d";
	db_query($sql, $picture, $u->uid);
	
	drupal_set_message(t("Your picture has been saved"));
	drupal_goto("user/{$u->uid}");
}
?>
