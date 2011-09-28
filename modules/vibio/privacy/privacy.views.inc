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
					"type"			=> "LEFT",
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
	if (!privacy_views_ensure_privacy($view->args))
	{
		$query->add_where(0, "0"); // can't see it, DAWG!

		// stephen: this seems a bit odd so far.  If the view says
		// "show this person lots" it doesn't say "they can only see this much",
    //  instead it blocks everything.  So 711/1 shows me info about 711 that
    //  is public.  711/4 I'm not allowed to see.
    // Is this just here to prevent manipulations?

		// dsm($view->args);
		// 711, 4 returns false. 711, 1 returns true.  Signed in as uid 1.
		// 1,4 or 1,1 both true (meaning if above does not fire.)/privacy_views_ensure_privacy(
		$view->no_cache = true;
	}
	elseif ($view->name == "user_heartbeat_activity")
	{
		$uid = $view->args[0];
		$access = $view->args[1];
		
		// the "else 1" is so that items that are not referencing nodes get a free pass since they won't be found in the sub query
		$sql = "CASE
					WHEN heartbeat_activity.`nid` > 0 AND heartbeat_activity.`message_id`='heartbeat_add_collection' THEN %d >= (
						SELECT `setting`
						FROM {privacy_settings}
						WHERE `type_id`=heartbeat_activity.`nid`
							AND `type`='collection'
							AND `uid`=%d
					)
					WHEN heartbeat_activity.`nid` > 0 THEN %d >= (
						SELECT `setting`
						FROM {privacy_settings}
						WHERE `type_id`=heartbeat_activity.`nid`
							AND `type`='node'
							AND `uid`=%d
					)
					ELSE 1
					END > 0
		";
		$query->add_where(0, $sql, $access, $uid, $access, $uid);
	}
	elseif (in_array($view->name, array("user_relational_activity")) && module_exists("user_relationships_api"))
	{
		$uid = $view->args[0];
		
		$sql = "CASE
					WHEN heartbeat_activity.`message_id`='heartbeat_add_comment' THEN %d >= (
						SELECT `setting`
						FROM {privacy_settings}
						WHERE `type_id`=heartbeat_activity.`message_id`
							AND type='heartbeat_messages'
							AND `uid`=heartbeat_activity.`uid`
					)
					WHEN heartbeat_activity.`nid`=0 THEN %d >= (
						SELECT `setting`
						FROM {privacy_settings}
						WHERE `type_id`=heartbeat_activity.`message_id`
							AND `type`='heartbeat_messages'
							AND `uid`=users.`uid`
					)
					WHEN heartbeat_activity.`nid` > 0 AND heartbeat_activity.`message_id` != 'heartbeat_add_collection' THEN %d >= (
						SELECT `setting`
						FROM {privacy_settings}
						WHERE `type_id`=heartbeat_activity.`nid`
							AND `type`='node'
							AND `uid`=users.`uid`
					)
					WHEN heartbeat_activity.`nid` > 0 AND heartbeat_activity.`message_id`='heartbeat_add_collection' THEN %d >= (
						SELECT `setting`
						FROM {privacy_settings}
						WHERE `type_id`=heartbeat_activity.`nid`
							AND `type`='collection'
							AND `uid`=users.`uid`
					)
					ELSE 1
					END > 0
		";
		$query->add_where(0, $sql, PRIVACY_CONNECTION, PRIVACY_CONNECTION, PRIVACY_CONNECTION, PRIVACY_CONNECTION);
	}
}

/*
 if a view has multiple arguments, the first argument is a uid, last arg is a privacy setting.  The higher the privacy, the more likely to fail.

 * RETURNS boolean indicating logged in user (at leat for 2 arg case that I see)
 *  can see the $uid (first arg) at the given level of privacy.  
 */
function privacy_views_ensure_privacy($args)
{
	if (count($args) <= 1)
	{
		return true;
	}
	
	$uid = $args[0];
	$index = count($args) - 1;
	$privacy_setting = $args[$index];

	// so it returns true if this user has a higher general access level
	//  than requested.  Admin, returns true.  Normal user, if you want access 1
	//  that's no problem, access 4 is a problem.  
	return $privacy_setting <= privacy_get_access_level($args[0]);
}
?>
