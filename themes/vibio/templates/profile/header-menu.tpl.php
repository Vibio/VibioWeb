<?php
$edit_account_link = l(t("Account Settings"), "user/{$profile_ext_user->uid}/edit", array('attributes' => array('id' => 'user-account')));
$privacy_settings_link = l(t("Privacy Settings"), "user/{$profile_ext_user->uid}/edit/privacy_settings", array('attributes' => array('id' => 'user-privacy')));
$faq_link = l(t("Help"), "faq", array('attributes' => array('id' => 'user-faq')));
$logout_link = l(t("Logout"), "logout", array('attributes' => array('id' => 'user-logout')));
$menu_title = strlen($profile_ext_user->name) > 20 ? substr($profile_ext_user->name, 0, 20)."..." : $profile_ext_user->name
?>
<div id="profile-menu-wrapper">
	<div id='profile-menu'>
		<div class="profile-icon"></div>
		<div class='profile-username'>
			<?php echo $menu_title;?>
		</div><div class='profile-arrow'></div>
		<div id='profile-submenu'>
			<ul>
				<li>
					<?php echo $edit_account_link;?>
				</li>
				<li>
					<?php echo $privacy_settings_link;?>
				</li>
				<li>
					<?php echo $faq_link;?>
				</li>
				<li class='menu-last'>
					<?php echo $logout_link;?>
				</li>
			</ul>
		</div>
	</div>
</div>
