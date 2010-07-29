<?php
function privacy_views_data()
{	
    $data['privacy_settings'] = array(
		"table"		=> array(
			"group" => t("Privacy Settings"),
			"base"	=> array(
				"field"	=> "setting",
				"title"	=> t("Privacy settings"),
			),
			"join"		=> array(
				"heartbeat_activity"	=> array( //joining against heartbeat REQUIRES module vviews
					"left_field"	=> "message_id",
					"field"			=> "type_id",
					"handler"		=> "views_handler_join_between",
					"type"			=> "INNER",
					"extra"			=> array(
						"additional_join_fields"	=> array(
							"uid"	=> "uid",
						),
					),
				),
				"node"	=> array(
					"left_field"	=> "nid",
					"field"			=> "type_id",
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
				"handler"	=> "views_handler_comparison_argument_numeric",
			),
		),
    );

	return $data;
}

function privacy_views_query_alter(&$view, &$query)
{
	if ($view->name == "user_heartbeat_activity")
	{
		$uid = $view->args[0];
		$access = $view->args[1];
		
		// the "else 1" is so that items that are not referencing nodes get a free pass since they won't be found in the sub query
		$sql = "CASE
					WHEN nid > 0 THEN %d
					ELSE 1
					END >= ALL (
						SELECT `setting`
						FROM {privacy_settings}
						WHERE `type_id`=nid
							AND `type`='node'
							AND `uid`=%d
					)
		";
		$query->add_where(0, $sql, $access, $uid);
	}
	elseif ($view->name == "user_relational_activity" && module_exists("user_relationships_api"))
	{
		$uid = $view->args[0];
		
		$sql = "CASE
					WHEN nid=0 THEN 1
					ELSE %d
					END >= ALL (
						SELECT `setting`
						FROM {privacy_settings}
						WHERE `type_id`=nid
							AND `type`='node'
							AND `uid`=uid
					)
		";
		$query->add_where(0, $sql, PRIVACY_CONNECTION);
	}
}
?>