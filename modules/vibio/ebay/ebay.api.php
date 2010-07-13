<?php
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

function ebay_find_items_advanced($args)
{
	global $user;
	
	$xml = _ebay_xml_init("FindItemsAdvancedRequest", 'xmlns="urn:ebay:apis:eBLBaseComponents"');
	
	if (isset($args['keywords']))
	{
		$xml->addChild("QueryKeywords", $args['keywords']);
	}
	
	if (isset($args['friends_by_user']) && is_numeric($args['friends_by_user']))
	{
		$do_search = false;
		$network = network_get($args['friends_by_user'], "default", 1); //1 is how deep to go, leave at 1 for now
		foreach ($network as $uid => $data)
		{
			if ($uid == $args['friends_by_user'] || !$data['ebay_id'])
			{
				continue;
			}
			
			$do_search = true;
			$xml->addChild("SellerID", $data['ebay_id']);
		}
		
		if (!$do_search)
		{
			return false;
		}
	}
	elseif (isset($args['users']))
	{
		foreach (explode(",", $args['users']) as $user)
		{
			$sql = "SELECT `ebay_id`
					FROM {ebay_users}
					WHERE `uid`=%d";
			if (!($ebay_id = db_result(db_query($sql, $user))))
			{
				continue;
			}
			
			$xml->addChild("SellerID", $ebay_id);
		}
	}
	elseif ($args['all_vibio'])
	{
		$sql = "SELECT `ebay_id`
				FROM {ebay_users}
				WHERE `uid` != %d";
		$res = db_query($sql, $user->uid);
		
		while ($row = db_fetch_array($res))
		{
			$xml->addChild("SellerID", $row['ebay_id']);
		}
	}

	$res = _ebay_comm_send($xml, "ebayapi_shopping_url", "ebayapi_shopping_version");
	
	return $res;
}
?>