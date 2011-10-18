<?php
$edit_account_link = l(t("Account Settings"), "user/{$profile_ext_user->uid}/edit", array('attributes' => array('id' => 'user-account')));
$privacy_settings_link = l(t("Privacy Settings"), "user/{$profile_ext_user->uid}/edit/privacy_settings", array('attributes' => array('id' => 'user-privacy')));
$faq_link = l(t("Help"), "faq", array('attributes' => array('id' => 'user-faq')));
$logout_link = l(t("Logout"), "logout", array('attributes' => array('id' => 'user-logout')));
$menu_title = strlen($profile_ext_user->name) > 20 ? substr($profile_ext_user->name, 0, 20)."..." : $profile_ext_user->name
?>

<table><tr>
	<td id="profile_ext_headermenu">
		<table class="rootVoices"><tr>
			<td class="rootVoice {menu: 'profile_ext_submenu'}">
				<div class='mbmenu_arrow_icon'> </div>
				<div class='mbmenu_title'>
					<?php echo $menu_title; ?>
				</div>
			</td>
		</tr></table>
	</td>
</tr></table>

<div id="profile_ext_submenu">
	<?php
	//echo "<a rel='separator'> </a>";
	echo $edit_account_link;
	echo $privacy_settings_link;
	echo $faq_link;
	//echo "<a rel='separator'> </a>";
	echo $logout_link;
	?>
</div>
