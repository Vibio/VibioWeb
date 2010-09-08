<?php
/**
 * Implementation of HOOK_theme().
 */
function vibio_theme(&$existing, $type, $theme, $path) {
	$hooks = zen_theme($existing, $type, $theme, $path);

	$hooks = array_merge($hooks, array(
		"user_social_info"	=> array(
			"arguments"	=> array("uid"	=> null),
			"template"	=> "templates/user/social-info",
		),
	));
	
	return $hooks;
}

function vibio_preprocess_search_result(&$vars)
{
	if ($vars['type'] == "user")
	{
		$args = explode("/user/", $vars['result']['link']);
		$account = new stdClass();
		$account->uid = (int) $args[1];
		$account->name = $vars['result']['title'];

		if (module_exists("user_relationship_blocks"))
		{
			/*
			not considering any other roles atm, since 
			$res = db_query('SELECT r.rid, r.name FROM {role} r INNER JOIN {users_roles} ur ON ur.rid = r.rid WHERE ur.uid = %d', $account->uid);
			while ($role = db_fetch_object($res))
			{
				$account->roles[$role->uid] = $role->name;
			}*/
			$account->roles[2] = "authenticated user"; //hack, but every account viewed here MUST be authenticated.
			$vars['ur_actions'] = theme("user_relationships_actions_block", $account);
		}
	}
}

function vibio_preprocess_user_profile(&$vars)
{
	drupal_add_css("sites/all/themes/vibio/css/user.css");

	$vars['profile']['social_info'] = theme("user_social_info", arg(1));
}

function vibio_preprocess_user_social_info(&$vars)
{
	drupal_add_js("sites/all/themes/vibio/js/user.js");
}

function vibio_menu_item_link($link)
{
	if ($link['type'] & MENU_IS_LOCAL_TASK && $link['path'] == "search/node/%")
	{
		return "";
	}
	
	return zen_menu_item_link($link);
}

function vibio_preprocess_page(&$vars, $hook)
{
	zen_preprocess_page($vars, $hook);
	
	$css = "";
	
	foreach (drupal_add_css() as $media => $types)
	{
		$css .= "<style type='text/css' rel='stylesheet' media='$media'>";
		foreach ($types as $type => $files)
		{
			foreach ($files as $file => $preprocess)
			{
				$css .= "@import \"/{$file}\";\n";
			}
		}
		$css .= "</style>";
	}
	
	$vars['styles'] = $css;
}

function phptemplate_user_relationships_pending_request_approve_link($uid, $rid)
{
	return l(
		t("Approve"),
		"relationships/{$uid}/{$rid}/approve",
		array(
			"attributes"	=> array(
				"class"	=> "uri_popup_link",
			),
		)
	);
}

function phptemplate_user_relationships_remove_link($uid, $rid)
{
	return l(
		t("Remove"),
		"relationships/{$uid}/{$rid}/remove",
		array(
			"attributes"	=> array(
				"class"	=> "uri_popup_link",
			),
		)
	);
}