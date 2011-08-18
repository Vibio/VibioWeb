<?php
global $user;
$u = $profile['user'];
//print "(user/user-profile.tpl, do we use it";
if ($user->uid == $u->uid || user_access("administer users"))
{
	$text = t("Edit");
	$account_edit_link = theme("profile_ext_edit_link", "user/{$u->uid}/edit");
	$picture_edit_link = "
		<div id='profile_change_picture' class='profile_edit_link'>
			<a href='/profile/{$u->uid}/change-picture'>$text</a>
		</div>
	";
}

// "Send --- a message"
if ($user->uid && $user->uid != $u->uid && module_exists("privatemsg"))
{
	$message_link = l(t("Send !user a message", array("!user" => $u->name)), "messages/new/{$u->uid}");
}

if (!$u->picture)
{
	$profile['user_picture'] = "
		<div class='picture'>
			<a href='/user/{$u->uid}'>
				<img src='/themes/vibio/images/icons/default_user_large.png' />
			</a>
		</div>
	";
}

/* Friend information 
 * $profile['dos'] written by Nelson not explored yet shows up to second degree?
 * $vars['connect'] = You, Connection, Second or false
 */
switch ($who_to_you) {
	case 'you':
		$friends_info = "You";
		$my_options = "<div class='profile_options'><a href='/search' class='profile_option'>Search for and Add an Item to Your Profile</a>
	<a href='/friends' class='profile_friends'>Invite friends to view your collections</a>	</div>
		";
		break;
	case 'connection':
		$friends_info = "Direct Connection";
		break;
	case 'second':
		$friends_info = "In Your Network<div class='mouse_for_friends'>" .
			$profile['dos'] . "</div>";
		break;
	case 'none':
		$friends_info = "No Connection<div class='mouse_for_friends'>" .
			"Keep connecting with people till you find a common connection.</div>";
		break;
}

?>

<div id="profile_picture_container" class="profile_editable">
	<?php echo $picture_edit_link; ?>
	<div id="profile_picture" >
		<?php echo $profile['user_picture']; ?>
	</div>
</div>

<div id='profile_user_information' class='connect_<?php echo $who_to_you;?>'>
	<div id='profile_username' class='profile_data profile_editable '>
		<div>
			<?php
			echo "<div id='name_and_friend'><h5>$u->name</h5><div class='friend_info'>";

			echo $friends_info . "</div><div class='friend_actions'>" . 
				$profile['uri_actions']['actions'] . '</div></div>';
			echo "<br/>";
			echo $message_link;
						//echo $profile['profile_progress'];
			echo $my_options;
			?>
		</div>
		<?php echo $account_edit_link; ?>
		<?php echo $profile['public_info']; ?>

		<div class='clear'></div>
	</div>
</div>

<?php //
// this was the tab-based everything else,
// to be replaced with saner options
//echo $profile['social_info']; ?>
<div class="clear"></div>

<?php

/* The secondary content goes here.
	This replaces the odd $profile['social_info']	
 */



//print "<h3>Secondary Menu will go here</h3>";
//print "We need to include the parent but as if it's a tab.  Not sure if want nice-menus or not<p>";
// do we want to get the links, and then
// this seems like it should work:
/*$menu = theme('nice_menus', -1, 'primary-links', 21, 'down');
print $menu['content'];*/

/* Still to do:
		- badges and some others need a number... not necessarily well defined yet,
			badges, collections, friends -> in every case is that number new or total?
			how new?
		- active menu link, and menu-ify this in general
 */


$uid = $u->uid; // shortcut to move way up this tpl
$secondary = array ('activity' => "Activity", 
	'collections' => "Collections", 
	'badges' => "Badges", 
	'about' => "About"
	);
print "<div id='profile_tabs'>";
foreach ($secondary as $key => $name ) {
	print "<a href='/user/$uid/$key'>$name</a>";
}
print "</div>";

/*$menu = theme('nice_menus', 1, 'primary-links', 21, 'down');
print $menu['content'];*/
?>
<div class="clear"></div>
<?php

print $sec_content;

//echo "profile completeness: {$profile['profile_progress']}%";
?>
