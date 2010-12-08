<?php
function profile_ext_service_home()
{
	global $user;
	
	if (!$user->uid)
	{
		return array();
	}
	
	module_load_include("inc", "views_service");
	
	$result = array();
	$result['activity_feed'] = views_service_get("user_hb_incoming_activity", "default", array(), 0, 20);
	$result['notifications'] = array();
	foreach (module_invoke_all("profile_ext_dashboard_notifications", $uid, false) as $module => $data)
	{
		$result['notifications'] = array_merge($result['notifications'], $data['display']);
	}
	
	return $result;
}