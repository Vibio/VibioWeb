<?php
define("EBAY_COMM_MSG_SUCCESS", "success");

function ebay_get_session_id($user=false)
{
	$xml = _ebay_xml_init("GetSessionIDRequest",  "xmlns='urn:ebay:apis:eBLBaseComponents'");
	$xml->addChild("RuName", variable_get("ebayapi_runame", ""));
	
	$res = _ebay_comm_send($xml, "ebayapi_trading_url", "ebayapi_trading_version");
	
	return $res ? (string) $res->SessionID : false;
}

function ebay_fetch_token($uid=false)
{
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}
	
	$sql = "SELECT `session_id`
			FROM {ebay_pending}
			WHERE `uid`=%d";
	$session_id = db_result(db_query($sql, $uid));
	
	if (!$session_id)
	{
		return false;
	}
	
	$xml = _ebay_xml_init("FetchToken", 'xmlns="urn:ebay:apis:eBLBaseComponents"');
	$xml->addChild("Version", variable_get("ebayapi_trading_version", ""));
	$xml->addChild("SessionID", $session_id);
	
	$res = _ebay_comm_send($xml, "ebayapi_trading_url", "ebayapi_trading_version");
	
	$token = $res ? (string) $res->eBayAuthToken : false;
	
	if ($token && _ebay_save_token($uid, $token))
	{
		_ebay_delete_pending($uid);
	}
	
	return $token;
}

function ebay_get_user($token)
{
	$xml = _ebay_xml_init("GetUserRequest", 'xmlns="urn:ebay:apis:eBLBaseComponents"');
	
	$credentials = $xml->addChild("RequesterCredentials");
	$credentials->addChild("eBayAuthToken", $token);
	
	$res = _ebay_comm_send($xml, "ebayapi_trading_url", "ebayapi_trading_version");
	
	return $res ? $res->User : false;
}

//saving a token is only successful if we are able to get the user's ebay userid
function _ebay_save_token($uid, $token)
{
	if (!($user = ebay_get_user($token)))
	{
		return false;
	}
	
	$sql = "REPLACE INTO {ebay_users}
			(`uid`, `ebay_id`, `token`)
			VALUES
			(%d, '%s', '%s')";
	db_query($sql, $uid, (string) $user->UserID, $token);
}

function _ebay_delete_pending($uid)
{
	$sql = "DELETE FROM {ebay_pending}
			WHERE `uid`=%d";
	db_query($sql, $uid);
}

function _ebay_get_login_url()
{
	return variable_get("ebayapi_signin_url", "https://siginin.ebay.com/wd/eBayISAPI.dll?SignIn");
}

function _ebay_xml_init($root_element="some_element_name", $attributes="")
{
	return new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><{$root_element} {$attributes}></{$root_element}>");
}
?>