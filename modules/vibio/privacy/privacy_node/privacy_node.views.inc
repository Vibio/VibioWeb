<?php
/**
 * Describes the table to views and how it should join.
 * 
 * @return array
 */
function privacy_node_views_data()
{	
    $data['privacy_node'] = array(
		"table"		=> array(
			"group" => t("Privacy Settings"),
			"base"	=> array(
				"field"	=> "nid",
				"title"	=> t("Node Privacy Settings"),
			),
			"join"		=> array(
        //When the node table is the base, these two will be joined
				"node"	=> array(
					"left_field"	=> "nid",
					"field"			=> "nid",
				),
        "heartbeat_activity"	=> array( //joining against heartbeat REQUIRES module vviews
					"left_field"	=> "nid",
					"field"			=> "nid",
					//"handler"		=> "views_handler_join_between",
					//"type"			=> "LEFT",
				),
			),
		),
		"nid"		=> array(
			"title"	=> t("Node ID"),
			"help"	=> t("The node whose privacy is being asked"),
			"filter"=> array(
				"handler"	=> "views_handler_filter_numeric",
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

/**
 *
 * @param <type> $view
 * @param <type> $query
 */
function privacy_node_views_query_alter(&$view, &$query)
{
	if (!privacy_node_views_ensure_privacy($view->args))
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
}

/*
 if a view has multiple arguments, the first argument is a uid, last arg is a privacy setting.  The higher the privacy, the more likely to fail.

 * RETURNS boolean indicating logged in user (at leat for 2 arg case that I see)
 *  can see the $uid (first arg) at the given level of privacy.  
 */
function privacy_node_views_ensure_privacy($args)
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
