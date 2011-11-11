<?php
/* called by function profile_ext_display_block($u)
 * return theme("profile_ext_displayblock", $u, $pic, $vars);

 * $vars['msg_count']
 * $vars['notifications']
 * later want new offers in or out !!!
 */
$uid = $user->uid; // always me
$name = $user->name;
$url_name = url("user/$uid");

$msg_count = $vars['msg_count'];
$notifications = $vars['notifications'];
$selling = $vars['selling'];
$buying = $vars['buying'];
$friend_count = $vars['friend_count'];
//dsm($vars);

/* We're building a menu by hand, with counts
 *  theme_menu_item wants to be told if it's $in_active_trail.
 * The "primary menu" should in the future, when the dust settles,
 *  be built in a way that lets us use functions like
 *  $tree = menu_tree_page_data($menu_name);

http://api.drupal.org/api/drupal/includes--menu.inc/group/menu/6
menu_get_active_menu_name
 */

// Selling: no children in active-trail if popups work
// always says "navigation," seems broke: $active_menu = menu_get_active_menu_name();

$taint_path = drupal_get_path_alias($_GET['q']);  // unsafe ugc except for ==
// user/xx/something means highlight the profile button
$pieces = explode("/", $taint_path);
if ( $pieces[0] == 'user' ) {
	if ( $uid == $pieces[1] ) {
		$taint_path = 'profile';
	}
}
     
$selling_menu = l("Offers to you", "selling", array(
	attributes => array(
		'title' => 'Selling: offers others have have made to you',
		'class' => $taint_path == 'selling' ? " active" : '',
		),
	)
);
$buying_menu = l("Offers from you", "buying", array(
	attributes => array(
		'title' => 'Buying: offers you have made',
		'class' => $taint_path == 'buying' ? " active" : '',
		),
	)
);
// !!! Notifications becoming invitations?
$notifications_menu =
	l("Invitations", "notifications", array(
	attributes => array(
		'title' => 'Connect with other Vibio Users',
		'class' => $taint_path == 'notifications' ? " active" : '',
		),
	)
);
$messages_menu =
	l("Messages", "messages", array(
	attributes => array(
		'title' => 'Messages sent by other Vibio Users',
		'class' => $taint_path == 'messages' ? " active" : '',
		),
	)
);
$contacts_menu =
	l("Contacts", "contacts", array(
	attributes => array(
		'title' => 'Widen your circle of contacts on Vibio or link to Facebook',
		'class' => $taint_path == 'contacts' ? " active" : '',
		),
	)
);
// !!! children menus look like: user/$uid/something
$profile_menu =
	l("Profile", "user/$uid/activity", array(
	attributes => array(
		'title' => 'My Profile',
		'class' => $taint_path == 'profile' ? "profile-b active" : 'profile-b inactive',
		),
	)
);
$home_menu = 
	l("Home", "", array(
	attributes => array(
		'title' => 'Home',
		'class' => $taint_path == 'home' ? "home-b active" : 'home-b inactive',
		),
	)
);

echo "

<div id='profile_ext_displayblock'>	
	<div id='upper_title'>
		$home_menu  $profile_menu
	</div>
	<div id='profile_ext_displayblock_user_section'>
		<a href='$url_name' id='profile_ext_displayblock_image'>
			$profile_picture
		</a>
		<a href='$url_name' id='profile_ext_displayblock_name'>
			$name
		</a>
		<div class='clear'></div>
	</div>
	<div id='profile_ext_displayblock_subitems'>
		<ul class='primary_menu_by_hand'>
			<li><span class='menu_with_count_text'>$buying_menu</span><span class='menu_with_count_count'>$buying</span></a></li>
			<li><span class='menu_with_count_text'>$selling_menu</span><span class='menu_with_count_count'>$selling</span></a></li>
			<li><span class='menu_with_count_text'>$notifications_menu</span><span class='menu_with_count_count'>$notifications</span></a></li>
			<li><span class='menu_with_count_text'>$messages_menu</span><span class='menu_with_count_count'>$msg_count</span></li>
		<li><span class='menu_with_count_text'>$contacts_menu</span><span class='menu_with_count_count'>$friend_count</span></a></li>


		</ul>
	</div>
</div>
	<div class='clear'></div>
";
?>
