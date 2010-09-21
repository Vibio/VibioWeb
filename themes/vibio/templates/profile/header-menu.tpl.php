<?php
$edit_account_link = l(t("Account Settings"), "user/{$profile_ext_user->uid}/edit");
$privacy_settings_link = l(t("Privacy Settings"), "user/{$profile_ext_user->uid}/edit/privacy_settings");
$faq_link = l(t("Help"), "faq");
$logout_link = l(t("Logout"), "logout");
?>

<table><tr>
	<td id="profile_ext_headermenu">
		<table class="rootVoices"><tr>
			<td class="rootVoice {menu: 'profile_ext_submenu'}">
				<div class='mbmenu_arrow_icon'> </div>
				<div class='mbmenu_title'>
					<?php echo $profile_ext_user->name; ?>
				</div>
			</td>
		</tr></table>
	</td>
</tr></table>

<div id="profile_ext_submenu">
	<?php
	echo "<a rel='separator'> </a>";
	echo $edit_account_link;
	echo $privacy_settings_link;
	echo $faq_link;
	echo "<a rel='separator'> </a>";
	echo $logout_link;
	?>
</div>