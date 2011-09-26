<?php
function uri_user_friends()
{
	global $user;

	/* What participates in the below theming?
 	 * function vviews_user_relationships($op, &$rel)-> make, remove connection

   */



	$pending = uri_profile_ext_dashboard_notifications($user->uid);
	return theme("uri_user_friends", $pending['uri']['display'], theme('user_relationships', $user, null));
}
?>
