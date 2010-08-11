<?php
global $user;

if (!$user->uid)
{
	echo "<div class='fb_login'>Log in with FB</div>";
}
?>