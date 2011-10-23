<?php
/* quick reverse-engineer:  suspect class=fb_login clicks are dealt with
 *  by themes/vibio/js/facebook.js
 * If we switch to a normal facebook module, um...
 */


global $user;

if (!$user -> uid) {
	echo "
		<span class='fb-or'>OR</span>
		<div class='fb_login'>
			<img class='fb_login_button' src='/themes/vibio/images/facebook/login-button.png' />
		</div>
	";
}
?>
