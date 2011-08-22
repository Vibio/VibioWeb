<?php
global $user;

if (!$user->uid)
{
	$title = t("New to Vibio?");
	$description = t("You can log in with your Facebook account");

	echo "
		<h5 class='menu_header'>$title</h5>
		<span class='menu_description'>$description</span>
		<div class='fb_login'>
			<img class='fb_login_button' src='/themes/vibio/images/facebook/login-button.png' />
		</div>
	";
}
?>
