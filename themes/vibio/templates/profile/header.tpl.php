<?php
if ($profile_ext_user->uid)
{
	$message = theme("profile_ext_header_menu", $profile_ext_user);
}
else
{
	$message = t("!login or !signup", array("!login" => l(t("Login"), "user/login"), "!signup" => l(t("Register"), "user/register")));
}

echo "
	<div id='profile_ext_header'>
		$message
	</div>
";
?>