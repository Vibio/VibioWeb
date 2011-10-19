<?php
/* All user-profile pages go through here.  Too much is done in this
 * tpl for long-run.  Disentangling from a single page with internal nav
 * and javascript.
 */
global $user;
$u=$profile['user'];
if($user->uid==$u->uid||user_access("administer users")) {
$text=t("Edit");
$account_edit_link=theme("profile_ext_edit_link","user/{$u->uid}/edit");
/* profile_ext.module creates this meny callback */
$picture_edit_link="
		<div id='profile_change_picture' class='profile_edit_link'>
			<a href='/profile/{$u->uid}/change-picture'>$text</a>
		</div>
	";
}
// "Send --- a message"
if($user->uid&&$user->uid!=$u->uid&&module_exists("privatemsg")) {
$message_link=l(t("Send !user a message",array("!user"=>$u->name)),"messages/new/{$u->uid}");
}
if(!$u->picture) {
$profile['user_picture']="
		<div class='picture'>
			<a href='/user/{$u->uid}'>
				<img src='/themes/vibio/images/icons/default_user_large.png' />
			</a>
		</div>
	";
}
/* Friend information
 * $profile['dos'] written by Nelson not explored yet; shows up to second degree?
 * $vars['connect'] = You, Connection, Second or false
 */
switch ($who_to_you) {
case 'you':
$friends_info="You";
$my_options="<div class='profile_options'><a href='/product/add' class='profile_option'>+ Add an Item to Your Profile</a>
	<a href='/contacts' class='profile_friends'>Invite friends to view your collections</a>	</div>
		";
break;
case 'connection':
$friends_info="Direct Connection";
break;
case 'second':
$friends_info="In Your Network<div class='mouse_for_friends'>".$profile['dos']."</div>";
break;
case 'none':
$friends_info="No Connection<div class='mouse_for_friends'>"."Keep connecting with people till you find a common connection.</div>";
break;
}
?>

<div id="profile_picture_container" class="profile_editable">
	<?php echo $picture_edit_link;?>
	<div id="profile_picture" >
		<?php echo $profile['user_picture'];?>
	</div>
</div>
<div id='profile_user_information' class='connect_<?php echo $who_to_you;?>'>
	<div id='profile_username' class='profile_data profile_editable '>
		<div>
			<?php
echo "<div id='name_and_friend'><h5>$u->name</h5><div class='friend_info'>";
echo $friends_info."</div><div class='friend_actions'>".$profile['uri_actions']['actions'].'</div>';
if($profile['profile_progress']) {
echo $profile['profile_progress'];
}
echo '</div>';
echo $message_link;
//echo $profile['profile_progress'];
echo $my_options;
			?>
		</div>
		<?php echo $account_edit_link;?>
		<?php echo $profile['public_info'];?>

		<div class='clear'></div>
	</div>
</div>
<?php //
// this was the tab-based everything else,
// to be replaced with saner options
//echo $profile['social_info'];
?>
<div class="clear"></div>
<?php
/* The secondary content goes here.
 This replaces the odd $profile['social_info']
 */
// Secondary Menu  --> now formatted like tabs (but not really Drupal's tabs)
/* Still to do:
 - badges and some others need a number... not necessarily well defined yet,
 badges, collections, friends -> in every case is that number new or total?
 how new?
 - active menu link, and menu-ify this in general
 */
/* Currently,
 * these look a lot like tabs.  But, we don't want to mix in with other tabs,
 * and tabs are printed at the top of the page.  So unless really
 * wants to retheme every page, just spit out the tabs in a forced/faked way
 */
$uid=$u->uid;
// shortcut to move way up this tpl
$secondary=array('activity'=>"Activity<span class='tab-seperator'>|</span>",'collections'=>"Collections<span class='tab-seperator'>|</span>",'badges'=>"Badges<span class='tab-seperator'>|</span>",'about'=>"About Me<span class='tab-seperator'>|</span>");
print "<div id='profile_tabs'><ul class='primary'>";
/* primary? css currently calls for it, I think */
foreach($secondary as $key=>$name) {
print "<li {$active[$key]}><a href='/user/$uid/$key' ><span class='tab'>$name</span></a></li>";
}
//dsm(menu_get_active_trail());
//dsm($active);
print "<li><a href='/info' id='info-button' rel='automodal'><span class=tab>Info</span></a></li><li class='blank-li'><span class='tab'>&nbsp;</span></li></ul></div>";
/*
 $options['attributes']['class'] .= ($tabkey == $active_tab ? ' active' : '');
 $output .= '<li'. $attributes_li .'>'. l($tab['title'], $_GET['q'] . $tabkey, $options) .'</li>';
 */
?>

<?php if (!empty($tabs)):
?><div class="tabs"><?php print $tabs;?></div><?php endif;?>

<div class="clear"></div>
<?php
/* this is from function profile_ext_preprocess_user_profile(&$vars) */
print $sec_content;
//echo "profile completeness: {$profile['profile_progress']}%";
?>
