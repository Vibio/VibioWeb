<?php
function fb_service_login($fb_uid)
{
	global $user;
	
	if ($user->uid) 
	{
		return services_error(t("Already logged in as !user", array("!user" => $user->name)));
	}
	
	module_load_include("inc", "fb");
	$uid = fb_user_exists($fb_uid);
	
	if ($uid)
	{
		$user = user_load($uid);
		sess_regenerate();
		user_module_invoke("login", $array, $user, null);
		
		$return = new stdClass();
		$return->sessid = session_id();
		$return->user = $user;
		
		return $return;
	}
	
	session_destroy();
	return services_error(t("Unknown User"));
}