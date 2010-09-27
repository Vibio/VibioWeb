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
	$errors = array();
	$existing_friends = _network_get_friends($uid, 1);
	foreach (module_implements("network_find_friends") as $module)
	{
		$func = "{$module}_network_find_friends";
		$func($uid, $potential_friends, $existing_friends, $errors);
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
		$actions = "
			<table>
				<tr>
					<td>
						<img class='uri_edit_busy_indicator' src='/themes/vibio/images/ajax-loader.gif' />
					</td>
					<td>
						<a href='/relationships/{$friend_user->uid}/1/request' class='uri_popup_link'>
							<button>Invite to be friends</button>
						</a>
					</td>
				</tr>
			</table>
		";
		
		$out .= theme("network_potential_friend", $friend, $picture, $actions);
	}

	$header = empty($potential_friends) ? t("No friends found") : t("You've got friends already on Vibio!");
	$out = "<h4>$header</h4>$out";
	
	exit(theme("network_potential_friends", $out));
}
?>
