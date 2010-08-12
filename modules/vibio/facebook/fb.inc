<?php
function fb_add_js()
{
	if (!($app_id = variable_get("fb_app_id", false)))
	{
		drupal_add_js("alert('WARNING: Adding FB JS without settings!');", "inline");
		return false;
	}
	
	$perms = variable_get("fb_signup_perms", "email");
	
	drupal_set_html_head("<script type='text/javascript' src='http://connect.facebook.net/en_US/all.js'></script>");
	drupal_add_js("var fb_app_id = '$app_id'; var fb_app_perms = '$perms'", "inline");
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
?>