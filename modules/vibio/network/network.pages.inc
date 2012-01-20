<?php
//  ajax defaulted to true in last version, default to false give own page.
/**
 * This code defines/uses hook_network_find_friends to find friends from external
 * social networks.
 *
 * 		$potential = new stdClass;
		$potential->info = _network_get_userinfo($friend_uid);
		$potential->src = "fb";
		$potential->link = fb_profile_link($friend->id);
    //Create an array of $potential friend objects keyed by vibio uid
		$potentials[$friend_uid] = $potential;
 *
 *
 *
 * Currently the only implementation that I (alec) know of is in Nelson's Facebook
 * module. I'm going to look at this code and possibly refactor it while I write
 * a new hook that works with fboauth, which is the new module we're using for FB
 * authentication.
 *
 *
 *
 * @global <type> $user
 * @param <type> $uid
 * @param <type> $ajax
 * @param <type> $specific_modules
 * @return <type>
 */
function network_find_friends($uid=false, $ajax=false, $specific_modules=false)
{
drupal_set_title("Connection Finder"); // !!! bad bad bad, why doesn't the code below work? note that invite didn't work either. ~/src/modules/vibio/user_relationships_integration$ vim uri.module  is somehow overpowering?  rush...



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
    //If there is no established connection between these two users
    //_user_relationships_ui_actions_between() returns a ur connection link...
		if ($action_links = _user_relationships_ui_actions_between($u, $friend_user))
		{
      //...and this function themes it
			$actions = theme("uri_action_list", $action_links);
		}else{
      //If already friends, don't display a link
      $actions = '';
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
