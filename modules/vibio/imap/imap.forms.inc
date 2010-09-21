<?php
function imap_admin()
{
	return system_settings_form(array(
		"imap_serv"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("IMAP Server"),
			"#description"	=> t("Connection string for php's imap_open() function"),
			"#required"		=> true,
			"#default_value"=> variable_get("imap_serv", false),
		),
		"imap_user"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("User"),
			"#required"		=> true,
			"#default_value"=> variable_get("imap_user", false),
		),
		"imap_pass"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Pass"),
			"#required"		=> true,
			"#default_value"=> variable_get("imap_pass", false),
		),
		"imap_files"=> array(
			"#type"			=> "textfield",
			"#title"		=> t("File Location"),
			"#description"	=> t("An absolute location where files will be saved. Omit trailing /"),
			"#required"		=> true,
			"#default_value"=> variable_get("imap_files", false),
		),
	));
}
?>