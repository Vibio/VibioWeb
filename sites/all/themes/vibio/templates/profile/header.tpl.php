<?php
if ($profile_ext_user->uid)
{
	$message = l($profile_ext_user->name, "user/{$profile_ext_user->uid}");
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