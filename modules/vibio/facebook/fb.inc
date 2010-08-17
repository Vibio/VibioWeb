<?php
function fb_add_js()
{
	global $user;
	
	if (!($app_id = variable_get("fb_app_id", false)))
	{
		drupal_add_js("alert('WARNING: Adding FB JS without settings!');", "inline");
		return false;
	}
	
	$perms = variable_get("fb_signup_perms", "email");
	$fb_uid = $user->uid ? fb_user_has_fb($user->uid) : 0;
	
	$fb_vars = json_encode(array(
		"app_id"	=> $app_id,
		"perms"		=> $perms,
		"fb_uid"	=> $fb_uid,
	));
	
	drupal_set_html_head("<script type='text/javascript' src='http://connect.facebook.net/en_US/all.js'></script>");
	drupal_add_js("var fb_settings = $fb_vars;", "inline");
	drupal_add_js("sites/all/themes/vibio/js/facebook.js");
	
	return true;
}

function fb_get_cookie()
{
	$app_id = variable_get("fb_app_id", false);
	$secret = variable_get("fb_app_secret", false);
	
	if (!isset($_COOKIE["fbs_{$app_id}"]))
	{
		return false;
	}
	
	$args = array();
	
	parse_str(trim($_COOKIE["fbs_{$app_id}"], '\\"'), $args);
	ksort($args);
	
	$payload = "";
	foreach ($args as $key => $val)
	{
		if ($key != "sig")
		{
			$payload .= "$key=$val";
		}
	}
	
	if (md5($payload.$secret) != $args['sig'])
	{
		return false;
	}
	
	return $args;
}

function fb_del_cookie()
{
	$app_id = variable_get("fb_app_id", false);
	
	setcookie("fbs_{$app_id}", "", time()-1);
}

function fb_get_user($cookie=false)
{
	if (!$cookie)
	{
		$cookie = fb_verify_cookie();
	}
	
	return json_decode(file_get_contents("https://graph.facebook.com/me?access_token={$cookie['access_token']}"));
}

function fb_save_user(&$u, $fb_uid)
{
	$sql = "DELETE FROM {fb_users}
			WHERE `uid`=%d";
	db_query($sql, $u->uid);
	
	$sql = "REPLACE INTO {fb_users}
			SET `uid`=%d, `fb_id`=%d, `time`=%d";
	db_query($sql, $u->uid, $fb_uid, time());
	
	module_invoke_all("fb_link_account", $u);
}

function fb_verify_cookie($uid=false)
{
	global $user;
	static $fb_cookie = null;
	
	if (isset($fb_cookie))
	{
		return $fb_cookie;
	}
	
	if (!$uid && !($uid = $user->uid))
	{
		return false;
	}
	
	$cookie = fb_get_cookie();
	
	$sql = "SELECT `fb_id`
			FROM {fb_users}
			WHERE `uid`=%d";
	$fb_cookie = db_result(db_query($sql, $uid)) == $cookie['uid'] ? $cookie : false;
	
	return $fb_cookie;
}

function fb_user_exists($fb_id)
{
	$sql = "SELECT `uid`
			FROM {fb_users}
			WHERE `fb_id`=%d";
	return db_result(db_query($sql, $fb_id));
}

function fb_user_has_fb($uid)
{
	static $fb_user_has_fb = array();
	
	if (isset($fb_user_has_fb[$uid]))
	{
		return $fb_user_has_fb[$uid];
	}
	
	$sql = "SELECT `fb_id`
			FROM {fb_users}
			WHERE `uid`=%d";
	$fb_user_has_fb[$uid] = db_result(db_query($sql, $uid));
	
	return $fb_user_has_fb[$uid];
}
?>