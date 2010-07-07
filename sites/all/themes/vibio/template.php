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

function vibio_preprocess_user_profile(&$vars)
{
	drupal_add_css("sites/all/themes/vibio/css/user.css");

	$vars['profile']['social_info'] = theme("user_social_info", arg(1));
}

function vibio_preprocess_user_social_info(&$vars)
{
	drupal_add_js("sites/all/themes/vibio/js/user.js");
}