<?php
function uri_user_friends()
{
	global $user;

	/* What participates in the below theming?
 	 * function vviews_user_relationships($op, &$rel)-> make, remove connection
   * function hbi_user_relationships
   * function network_user_relationships

   * facebook/fb.module  function fb_preprocess_uri_user_friends(&$vars)
   * function network_preprocess_uri_user_friends(&$vars)
			both of these just add_css

   * uri_user_friends => templates/uri/friends 
   */


	// seems to be about approving or rejecting friends? .module in this dir.
	$pending = uri_profile_ext_dashboard_notifications($user->uid);
	// this skips the pieces of this page. return theme("uri_user_friends", $pending['uri']['display']);
	return theme("uri_user_friends", $pending['uri']['display'], theme('user_relationships', $user, null));
}
?>
