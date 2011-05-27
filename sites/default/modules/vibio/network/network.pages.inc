<?php
function network_find_friends($uid=false, $ajax=true, $specific_modules=false)
{
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
		$u = $user;
	}
	else
	{
		$u = user_load($uid);
	}
	
	$potential_friends = array();
	$errors = array();
	$existing_friends = _network_get_friends($uid, 1);
	
	if (empty($specific_modules))
	{
		foreach (module_implements("network_find_friends") as $module)
		{
			$func = "{$module}_network_find_friends";
			$func($uid, $potential_friends, $existing_friends, $errors);
		}
	}
	else
	{
		if (!is_array($specific_modules))
		{
			$specific_modules = array($specific_modules);
		}
		
		foreach ($specific_modules as $module)
		{
			$func = "{$module}_network_find_friends";
			$func($uid, $potential_friends, $existing_friends, $errors);
		}
	}
	
	$out = "";

	if (!empty($errors))
	{
		$error_html = "";
		foreach ($errors as $e)
		{
			$error_html .= $e;
		}

		$out .= theme("network_friend_finder_messages", $error_html);
	}

	foreach ($potential_friends as $friend_uid => $friend)
	{
		$friend_user = user_load($friend_uid);
		$friend->info['uid'] = $friend_uid;
		$picture = theme("user_picture", $friend_user);

		if ($action_links = _user_relationships_ui_actions_between($u, $friend_user))
		{
			$actions = theme("uri_action_list", $action_links);
		}
		
		$out .= theme("network_potential_friend", $friend, $picture, $actions);
	}

	$header = empty($potential_friends) ? t("No friends found") : $specific_modules ? "" : t("You've got friends already on Vibio!");
	$out = $header ? "<h4>$header</h4>$out" : $out;
	
	$out = theme("network_potential_friends", $out);
	
	if ($ajax)
	{
		exit($out);
	}
	
	return $out;
}
?>
