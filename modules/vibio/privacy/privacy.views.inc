<?php
function privacy_views_handlers()
{
	return array(
		"info"	=> array(
			"path"	=> drupal_get_path("module", "privacy")."/handlers",
		),
		"handlers"	=> array(
			"views_handler_privacy_argument_numeric"	=> array(
				"parent"	=> "views_handler_argument_numeric",
			),
		),
	);
}

function privacy_views_data()
{
	require_once("handlers/join_between.inc");
	
    $data['privacy_settings'] = array(
		"table"		=> array(
			"group" => t("Privacy Settings"),
			"base"	=> array(
				"field"	=> "setting",
				"title"	=> t("Privacy settings"),
			),
			"join"		=> array(
				"heartbeat_activity"	=> array(
					"left_field"	=> "message_id",
					"field"			=> "type_id",
					"handler"		=> "views_join_between",
					"type"			=> "INNER",
					"extra"			=> array(
						"additional_join_fields"	=> array(
							"uid"	=> "uid",
						),
					),
				),
			),
		),
		"type"		=> array(
			"title"	=> t("Privacy Type"),
			"help"	=> t("Type of the object whose privacy is being asked"),
			"filter"=> array(
				"handler"	=> "views_handler_filter_string",
			),
		),
		"setting"	=> array(
			"title"		=> t("Privacy Setting"),
			"help"		=> t("The privacy value as set in the database"),
			"filter"	=> array(
				"handler"	=> "views_handler_filter_numeric",
			),
		),
		"setting_requested"	=> array(
			"real field"	=> "setting",
			"title"			=> t("Currently requested setting (url arg) >= setting"),
			"argument"		=> array(
				"handler"	=> "views_handler_privacy_argument_numeric",
			),
		),
    );

	return $data;
}

function privacy_views_views_query_alter(&$view, &$query)
{
	if ($view->base_table == "heartbeat_activity")
	{
		//foreach (_privacy_get_fields()
	}
}
?>