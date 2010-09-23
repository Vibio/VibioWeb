<?php
global $user;
$u = user_load($uid);

if ($access >= privacy_get($uid, "profile", "profile_about_me"))
{
	$about_me = theme("profile_ext_view_fields", $u, "about me");
}

if ($user->uid == $u->uid || user_access("administer users"))
{
	$edit_link = theme("profile_ext_edit_link", "user/{$uid}/edit/About Me");
}

echo "
	<div class='profile_editable'>
		<div id='profile_about_me'>
			$about_me
		</div>
		$edit_link
		<div class='clear'></div>
	</div>
";
?>