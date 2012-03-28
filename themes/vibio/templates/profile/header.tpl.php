<?php
if ($profile_ext_user -> uid) {
	$message = theme("profile_ext_header_menu", $profile_ext_user) . "<a id='head-collections' href='/user/" . $user -> uid . "/collections'>My Collections</a>" . "<a id='how-works' class='works-modal' href='/steps'>How it works</a>";
} else {
	$message = '<ul id="sign-in-btn" class="quicksand" ><li><a href="/user/register">REGISTER</a></li><li><a href="/user/login">SIGN IN</a></li><li><a href="/about">ABOUT</a></li></ul>';
}

echo "$message";
?>

