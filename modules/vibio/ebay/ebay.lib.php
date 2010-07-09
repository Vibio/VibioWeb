<?php
define("EBAY_COMM_MSG_SUCCESS", "success");

function ebay_get_session_id($user=false)
{
	$xml = _ebay_xml_init("GetSessionIDRequest",  "xmlns='urn:ebay:apis:eBLBaseComponents'");
	$xml->addChild("RuName", variable_get("ebayapi_runame", ""));
	
	$res = _ebay_comm_send($xml, variable_get("ebayapi_trading_url", ""), variable_get("ebayapi_trading_version", ""));
	
	if (strtolower($res->Ack) != EBAY_COMM_MSG_SUCCESS)
	{
		return false;
	}
	
	return (string) $res->SessionID;
}

function ebay_fetch_token($uid, $session_id)
{
	//todo: make sure this session id is for this user
	$xml = _ebay_xml_init("FetchToken", 'xmlns="urn:ebay:apis:eBLBaseComponents"');
	$xml->addChild("Version", variable_get("ebayapi_trading_version", ""));
	$xml->addChild("SessionID", $session_id);
	
	$res = _ebay_comm_send($xml, variable_get("ebayapi_trading_url", ""), variable_get("ebayapi_trading_version", ""));
	
	//todo: store this token for this user (probably outside of this function)
	return (string) $res->eBayAuthToken;
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