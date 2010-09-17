<?php
$dashboard_link = l(t("My Dashboard"), "my-dashboard");
$profile_link = l(t("My Profile"), "user/{$profile_ext_user->uid}");
$logout_link = l(t("Logout"), "logout");
?>

<table><tr>
	<td>
		<?php echo l($profile_ext_user->name, "user/{$profile_ext_user->uid}"); ?>
	</td>
	<td id="profile_ext_headermenu">
		<table class="rootVoices"><tr>
			<td class="rootVoice {menu: 'profile_ext_submenu'}">
				<img src="/themes/vibio/images/icons/acct_dropdown.png" />
			</td>
		</tr></table>
	</td>
</tr></table>

<div id="profile_ext_submenu">
	<?php echo $dashboard_link.$profile_link.$logout_link; ?>
</div>