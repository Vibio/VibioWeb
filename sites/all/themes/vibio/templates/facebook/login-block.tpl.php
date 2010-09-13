<?php
global $user;

if (!$user->uid)
{
	echo "
		<div class='fb_login'>
			<img class='fb_login_button' src='/sites/all/themes/vibio/images/facebook/login-button.png' />
		</div>
	";
}
?>