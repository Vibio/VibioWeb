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
	
	if (empty($args['page_number']) || $args['page_number'] < 1)
	{
		$args['page_number'] = 1;
	}
	
	drupal_add_js("var ebay_search_args = ".json_encode($args), "inline");
	
	$version = variable_get("ebayapi_finding_version", "");
	
	$xml = _ebay_xml_init("findItemsAdvancedRequest", "xmlns='http://www.ebay.com/marketplace/search/{$version}/services'");
	$xml->addChild("keywords", $args['keywords']);
	$page = $xml->addChild("paginationInput");
	$page->addChild("entriesPerPage", variable_get("ebayapi_entriesperpage", 20));
	$page->addChild("pageNumber", $args['page_number']);
	
	if (isset($args['friends_by_user']) && is_numeric($args['friends_by_user']))
	{
		$user_found = false;
		$network = network_get($args['friends_by_user'], false, 1); //1 is how deep to go, leave at 1 for now
		foreach ($network as $uid => $data)
		{
			if ($uid == $args['friends_by_user'] || empty($data['ebay_ids']))
			{
				continue;
			}
			
			foreach ($data['ebay_ids'] as $ebay_id)
			{
				if (!$user_found)
				{
					$user_found = true;
					$item_filter = $xml->addChild("itemFilter");
					$item_filter->addChild("name", "Seller");
					
				}
				
				$item_filter->addChild("value", $ebay_id);
			}
		}
		
		if (!$user_found)
		{
			return false;
		}
	}
	elseif (isset($args['users']))
	{
		$first = true;
		foreach (explode(",", $args['users']) as $uid)
		{
			$sql = "SELECT `ebay_id`
					FROM {ebay_users}
					WHERE `uid`=%d";
			$res = db_query($sql, $uid);
			
			while ($row = db_fetch_array($res))
			{
				if ($first)
				{
					$first = false;
					$item_filter = $xml->addChild("itemFilter");
					$item_filter->addChild("name", "Seller");
				}
				
				$item_filter->addChild("value", $row['ebay_id']);
			}
		}
		
		if ($first)
		{
			return false;
		}
	}
	elseif ($args['all_vibio'])
	{
		$sql = "SELECT `ebay_id`
				FROM {ebay_users}
				WHERE `uid` != %d";
		$res = db_query($sql, $user->uid);
		
		$first = true;
		while ($row = db_fetch_array($res))
		{
			if ($first)
			{
				$first = false;
				$item_filter = $xml->addChild("itemFilter");
				$item_filter->addChild("name", "Seller");
			}
			$item_filter->addChild("value", $row['ebay_id']);
		}
		
		if ($first)
		{
			return false;
		}
	}
	
	$x = $xml->asXML();
	$res = _ebay_comm_send($xml, "ebayapi_finding_url", "ebayapi_finding_version");
	
	return $res;
}
?>