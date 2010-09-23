<?php
function imap_fetch_admin()
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
		"imap_max_images"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Max Images Saved"),
			"#required"		=> true,
			"#description"	=> t("The maximum number of images saved per run. The interval at which the process is run is determined by a cron fetch to imap/fetch"),
			"#default_value"=> variable_get("imap_max_images", false),
		),
	));
}

function imap_fetch_delete_form(&$state, $image)
{
	return array(
		"imap_image"	=> array(
			"#type"	=> "value",
			"#value"=> $image,
		),
		"submit"		=> array(
			"#type"	=> "submit",
			"#value"=> t("Delete"),
		),
		"#submit"		=> array(
			"imap_delete_form_submit",
		),
		"#attributes"	=> array(
			"class"	=> "imap_delete_form",
		),
	);
}

function imap_fetch_delete_form_submit($form, &$state)
{
	module_load_include("inc", "imap");
	imap_fetch_delete_image($state['values']['image']);
	drupal_set_message(t("The image has been deleted"));
}

function imap_fetch_attach_form(&$state, $image)
{
	global $user;
	
	$collection_options = array(-1 => t("-- Collection --"));
	foreach (collection_get_collections($user->uid) as $c)
	{
		$collection_options[$c['cid']] = $c['title'];
	}
	
	return array(
		"cid"		=> array(
			"#type"		=> "select",
			"#options"	=> $collection_options,
		),
		"nid"		=> array(
			"#type"		=> "select",
			"#options"	=> array(-1 => t("-- Item --")),
		),
		"submit"	=> array(
			"#type"	=> "submit",
			"#value"=> t("Attach"),
		),
		"#attributes"	=> array(
			"class"	=> "imap_attach_form",
		),
	);
}
?>