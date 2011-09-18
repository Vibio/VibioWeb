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
echo "

<div id='profile_ext_displayblock'>	
	<div id='upper_title'>
		<a href='/'>Home</a> | <a href='$url_name'>Profile</a>
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
			<li><a title='Offers you have made' href='/buying'><span class='menu_with_count_text'>Buy (Offers from You)</span><span class='menu_with_count_count'>$buying</span></a></li>
			<li><a title='Offers others have have made to you' href='/selling'><span class='menu_with_count_text'>Sell (Offers to You)</span><span class='menu_with_count_count'>$selling</span></a></li>
	
			<li><a title='Connect with other Vibio Users' href='/notifications'><span class='menu_with_count_text'>Invitations</span><span class='menu_with_count_count'>$notifications</span></a></li>
			<li><span class='menu_with_count_text'><a href='/messages'>Messages</a></span><span class='menu_with_count_count'>$msg_count</span></li>

		<li><a title='Build a network of Facebook friends and Vibio contacts so you can Trade with Trust' href='/contacts'><span class='menu_with_count_text'>Contacts</span><span class='menu_with_count_count'>$friend_count</span></a></li>


		</ul>
	</div>
</div>
	<div class='clear'></div>
";
?>
