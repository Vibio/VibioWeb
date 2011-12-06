<?php
if ($profile_ext_user->uid)
{
	$message = theme("profile_ext_header_menu", $profile_ext_user);
}
else
{
	$message = t("!login or !signup", array("!login" => l(t("Login"), "user/login"), "!signup" => l(t("Sign Up"), "user/register")));
}

echo "
	<div id='profile_ext_header'>
		$message <div id='help-header'><div id='works-icon'></div><a id='how-works' class='works-modal' href='/steps'>How it works</a></div>
	</div>
";
?>