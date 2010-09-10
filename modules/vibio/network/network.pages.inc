<?php
function network_find_friends($uid=false)
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
	$existing_friends = _network_get_friends($uid, 1);
	foreach (module_implements("network_find_friends") as $module)
	{
		$func = "{$module}_network_find_friends";
		$func($uid, $potential_friends, $existing_friends);
	}
	
	$out = "";
	foreach ($potential_friends as $friend_uid => $friend)
	{
		$friend_user = user_load($friend_uid);
		$friend->info['uid'] = $friend_uid;
		
		if ($action_links = _user_relationships_ui_actions_between($u, $friend_user))
		{
			$actions = theme("uri_action_list", $action_links);
		}
		
		$out .= theme("network_potential_friend", $friend, file_create_url($friend_user->picture), $actions);
	}
	
	return theme("network_potential_friends", $out);
}
?>