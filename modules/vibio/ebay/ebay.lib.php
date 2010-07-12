<?php
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