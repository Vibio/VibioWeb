<?php
global $user;

if (!$user->uid)
{
	echo "
		<div class='fb_login'>
			<img class='fb_login_button' src='/themes/vibio/images/facebook/login-button.png' />
		</div>
	";
}
?>