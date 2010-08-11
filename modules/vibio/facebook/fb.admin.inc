<?php
function facebook_admin()
{
	return system_settings_form(array(
		"fb_app_id"		=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Application ID"),
			"#default_value"=> variable_get("fb_app_id", ""),
			"#required"		=> true,
		),
		"fb_app_secret"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Application Secret"),
			"#default_value"=> variable_get("fb_app_secret", ""),
			"#required"		=> true,
		),
		"fb_signup_perms"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Sign-Up Extended Permissions"),
			"#description"	=> t("This is a facebook formatted (comma separated) list of extended perms to ask for when creating an account using facebook oauth."),
			"#default_value"=> variable_get("fb_signup_perms", "email"),
		),
	));
}
?>