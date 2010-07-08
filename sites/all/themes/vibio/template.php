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