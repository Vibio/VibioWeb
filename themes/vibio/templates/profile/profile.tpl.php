<?php
// reprofileicious
//This page isn't used is it?  Delete if no error message appears after
//multiple cache clears and testing...

global $user;
$u = $profile['user'];


// edit your picture
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

// Send a message
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

// not really a good spot to deal with user pictures.  How did Drupal
//  normally do this if don't break everything?
//  I use this in profile module but not sure if $u->picture 
	$alt = "User picture";
	$title = "";
	$attributes = "";
if (!$u->picture) {
	$picture = theme('imagecache', "full_profile_pic", "/themes/vibio/images/icons/default_user_large.png", $alt, $title, $attributes);

} else {
	$picture = theme('imagecache', "full_profile_pic", $u->picture, $alt, $title, $attributes);
}
?>

<div id='profile_user_summary' class='rounded_content'>
	<div id='profile_username' class='profile_data profile_editable rounded_content'>
		<div>
			<?php
			echo "<h5>$u->name</h5>";
			echo $profile['dos'];
			echo $message_link;
			echo $profile['uri_actions']['actions'];
			echo $profile['profile_progress'];
			?>
		</div>
		<?php echo $account_edit_link; ?>
		<div class='clear'></div>
	</div>
	<div id='profile_summary' class='profile_data profile_editable rounded_content'>
		<div>
			<?php echo $profile['public_info']; ?>
		</div>
		<div class='clear'></div>
	</div>
</div>

<div id="profile_picture_container" class="profile_editable">
	<?php echo $picture_edit_link; ?>
	<div id="profile_picture" class='profile_picture_display'>
		<?php echo $profile['user_picture']; ?>
	</div>
	<div id="profile_picture_bg" class='profile_picture_display'><div></div></div>
</div>

<?php echo $profile['social_info']; ?>
<div class="clear"></div>

<?php
echo "profile completeness: {$profile['profile_progress']}%";
//die("profile.tpl.php still runs"); why not running? cached?
print $sec_menu;

// Add a flag, for admins
// This isn't even called, is it...print "stuff here" . flag_create_link('feature_users', $u->uid); 

print $this_page;

?>

