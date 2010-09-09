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
	fb_add_prompt_js();
	drupal_add_js("sites/all/themes/vibio/js/facebook.js");
	
	return true;
}

function fb_add_prompt_js()
{
	if (!($prompts = fb_get_prompts()))
	{
		return;
	}
	
	$prompt_data = array();
	foreach ($prompts as $prompt)
	{
		switch ($prompt->type)
		{
			case "node";
				$data = fb_get_share_params(node_load($prompt->id), $prompt->action);
				break;
			case "collection":
				$data = fb_get_collection_share_params(collection_load($prompt->id), $prompt->action);
				break;
		}
		
		$prompt_data[] = $data;
	}
	
	$prompt_data = json_encode($prompt_data);
	drupal_add_js("var fb_share_prompts = $prompt_data;", "inline");
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

function fb_get_share_params($node, $action="share_item")
{
	$picture = _vibio_item_get_image($node->nid);
	$t_params = array(
		"!item"		=> $node->title,
	);
	$href = url("node/{$node->nid}", array("absolute" => true,));
	$default_message = t("wants to share \"!item\" with you on Vibio!", $t_params);
	
	if ($action == "share_item")
	{
		$message = $node->field_posting_type[0]['value'] == VIBIO_ITEM_TYPE_OWN ?
			$default_message : t("is selling \"!item\" on Vibio and would like to share it with you!", $t_params);
	}
	elseif ($action == "buy_item")
	{
		$message = t("just bought \"!item\" on Vibio", $t_params);
	}
	else
	{
		$message = $default_message;
	}
	
	return array(
		"message"		=> $message,
		"attachment"	=> array(
			"name"		=> $node->title,
			"href"		=> $href,
			"media"		=> array(array(
				"type"	=> "image",
				"src"	=> $picture,
				"href"	=> $href,
			)),
		),
	);
}

function fb_get_collection_share_params($collection, $action="share_collection")
{
	module_load_include("inc", "collection");
	$image = collection_get_image($collection['cid']);
	
	$t_args = array(
		"!collection"	=> $collection['title'],
	);
	$href = url("collections/{$collection['cid']}", array("absolute" => true,));
	
	switch ($action)
	{
		case "create_collection":
			$message = t('just made the "!collection" collection on Vibio', $t_args);
			break;
		case "share_collection":
			$message = t('wants to share their Vibio collection "!collection"', $t_args);
			break;
	}
	
	return array(
		"message"	=> $message,
		"attachment"=> array(
			"name"			=> $collection['title'],
			"href"			=> $href,
			"description"	=> $collection['description'],
			"media"			=> array(array(
				"type"	=> "image",
				"src"	=> $image,
				"href"	=> $href,
			)),
		),
	);
}

function fb_register_prompt($action, $id, $type="node")
{	
	$prompt = new stdClass;
	$prompt->action = $action;
	$prompt->id = $id;
	$prompt->type = $type;
	
	$_SESSION['fb_prompts'][] = $prompt;
}

function fb_get_prompts($clear=true)
{
	$prompts = $_SESSION['fb_prompts'];
	
	if ($clear)
	{
		fb_clear_prompts();
	}
	
	return $prompts;
}

function fb_clear_prompts()
{
	unset($_SESSION['fb_prompts']);
}
?>