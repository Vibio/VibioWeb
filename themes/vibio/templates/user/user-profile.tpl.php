<?php
global $user;
$u = $profile['user'];

if ($user->uid == $u->uid || user_access("administer users"))
{
	$account_edit_link = theme("profile_ext_edit_link", "user/{$u->uid}/edit");
	$demographics_edit_link = theme("profile_ext_edit_link", "user/{$u->uid}/edit/Demographics");
}

if ($user->uid && $user->uid != $u->uid && module_exists("privatemsg"))
{
	$message_link = l(t("Send !user a message", array("!user" => $u->name)), "messages/new/{$u->uid}");
}
?>

<div id='profile_user_summary' class='rounded_content'>
	<div id='profile_username' class='profile_data profile_editable rounded_content'>
		<div>
			<?php
			echo "<h5>$u->name</h5>";
			echo $profile['dos'];
			echo $profile['uri_actions']['actions'];
			echo $message_link;
			?>
		</div>
		<?php echo $account_edit_link; ?>
		<div class='clear'></div>
	</div>
	<div id='profile_summary' class='profile_data profile_editable rounded_content'>
		<div>
			<?php echo $profile['demographics']; ?>
		</div>
		<?php echo $demographics_edit_link; ?>
		<div class='clear'></div>
	</div>
</div>

<div id="profile_picture" class='profile_picture_display'>
	<?php echo $profile['user_picture']; ?>
</div>
<div id="profile_picture_bg" class='profile_picture_display'><div></div></div>

<?php echo $profile['social_info']; ?>
<div class="clear"></div>

<?php
//echo "profile completeness: {$profile['profile_progress']}%";
?>