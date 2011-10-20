<?php
define("PRODUCT_OWNER_CACHE_TTL", 300);
define("PRODUCT_OWNER_DISPLAY_PER_PAGE", 40);


/* _product_get_product_owners gets and array of owners by network and 
 *  vibio=not-network.  Seems to return it as data, not nodes (ouch)
 */
function _product_get_product_owners($nid, $as_uid=false)
{
	if (!$as_uid)
	{
		global $user;
		$as_uid = $user->uid;
	}
	
	return array(
		"network"	=> _product_get_owners($nid, $as_uid, "network"),
		"vibio"		=> _product_get_owners($nid, $as_uid, "vibio"),
	);
}

// I think this gets all the owners of an item, in network or not
//  Defaults to in-network only (stephen: deprecate this)
function _product_get_owners($nid, $as_uid, $type="network", $page=0)
{
	$depth = 3;
	$offset = $page*PRODUCT_OWNER_DISPLAY_PER_PAGE;
	
	$cache_key = "product_owners:{$nid}:{$as_uid}:{$type}:{$page}";
	if ($cache = cache_get($cache_key, "cache_content"))
	{
		return $cache->data;
	}
	
	$network = array_keys(network_get($as_uid, false, $depth));
	array_shift($network);
	$network = implode(",", $network);
	
	if (!$network && $type == "network")
	{
		return array();
	}
	
	switch ($type)
	{
		case "vibio":
			$network_op = "NOT IN";
			$privacy_check = $as_uid ? PRIVACY_AUTHENTICATED : PRIVACY_PUBLIC;
			break;
		case "network":
		default:
			$network_op = "IN";
			$privacy_check = PRIVACY_CONNECTION;
			break;
	}

	$network_check = $network ? " AND u.`uid` $network_op ($network)" : "";
	

	$sql = "SELECT n.`nid`, n.`title`, u.`uid`, u.`name`, u.`picture`
			FROM {node} n JOIN {users} u JOIN {privacy_settings} p
				ON n.`uid`=u.`uid` AND p.`uid`=n.`uid`
			WHERE p.`type`='node'
				AND p.`type_id`=n.`nid`
				AND p.`setting` <= %d
				AND u.`uid` != %d
				$network_check
				AND n.`nid` IN (
					SELECT `item_nid`
					FROM {product_items}
					WHERE `product_nid`=%d
				)
			ORDER BY n.`changed` DESC
         $highlight
			LIMIT $offset, %d";
	$count_sql = preg_replace(array('/SELECT.*?FROM /As', '/ORDER BY .*/', '/LIMIT .*/'), array('SELECT COUNT(*) FROM ', '', ''), $sql);
	$total_results = db_result(db_query($count_sql, $privacy_check, $as_uid, $nid));
	$res = db_query($sql, $privacy_check, $as_uid, $nid, PRODUCT_OWNER_DISPLAY_PER_PAGE);
	
	$results = array();
	while ($row = db_fetch_array($res))
	{
		$results[] = array(
			"node"	=> array(
				"link"	=> l($row['title'], "node/{$row['nid']}"),
				"nid"	=> $row['nid'],
			),
			"user"	=> array(
				"picture"	=> theme("user_picture", (object) $row),
				//v1.0 
				//"link"		=> $as_uid && $type == "network" && module_exists("dos") ? theme("dos_display", dos_get($row['uid'], false, $depth)) : "<br />".l($row['name'], "user/{$row['uid']}"),
				// changes: if user logged in, print friend-chain; if not, print nothing
				//  instead of printing the owner's name again.
				"link"		=> $as_uid && $type == "network" && ($GLOBALS['user']->uid > 0) && module_exists("dos") ? theme("dos_display", dos_get($row['uid'], false, $depth)) : "", 

				"uid"     => $row['uid'],  // stephen adds, to highlight
			),
		);
	}
	
	$data = array(
		"count"		=> $total_results,
		"page"		=> $page,
		"results"	=> $results,
	);
	cache_set($cache_key, $data, "cache_content", time() + PRODUCT_OWNER_CACHE_TTL);
	return $data;
}

function _product_nid_from_item($nid)
{
	$sql = "SELECT `product_nid`
			FROM {product_items}
			WHERE `item_nid`=%d";
	return db_result(db_query($sql, $nid));
}

function _product_from_item($item_nid)
{
	$product_nid = _product_nid_from_item($item_nid);
	return node_load($product_nid);
}

/* Does this get the product or item image, prefering one over the other? 
 * With imagecache, this would be real code?
 * Returns the URL of the image
 */ 
function _product_get_image($nid, $is_product=false)
{
	static $product_images = array();

	if ( isset($product_images[$nid]))
		return $product_images[$nid];

	$search_nid = $is_product ? $nid : _product_nid_from_item($nid);

	if (!$is_product && isset($product_images[$search_nid])) //if it's an item and we already have the product image cached..
		return $product_images[$search_nid];

	$product = node_load($search_nid);
// echo "<pre>"; var_dump($product); exit;
	if (!empty($product->field_main_image[0]['filepath']) && file_exists($product->field_main_image[0]['filepath']))
		$url = file_create_url($product->field_main_image[0]['filepath']);
	else
		$url = _product_default_image();

	$product_images[$search_nid] = $url;

	return $url;
}

function _product_default_image()
{
	return file_create_url("themes/vibio/images/icons/default_item.png");
}

function product_images($node, $get_main=false)
{
	if (is_numeric($node))
	{
		$node = node_load($node);
	}
	if (!$node)
	{
		return array();
	}
	
	$images = array();
	if ($get_main && !empty($node->field_main_image[0]['filepath']))
	{
		$images[] = file_create_url($node->field_main_image[0]['filepath']);
	}
	
	if (!empty($node->field_images))
	foreach ($node->field_images as $image)
	{
		if (file_exists($image['filepath']))
		{
			$images[] = file_create_url($image['filepath']);
		}
	}
	
	return $images;
}

function product_set_external_search($val=false, $set=false)
{
	static $is_external_search = false;
	
	if ($set)
	{
		$is_external_search = $val;
	}
	
	return $is_external_search;
}

function product_set_external_search_page_offset($offset=-1)
{
	if (!isset($_SESSION['product']['search_page_offset']))
	{
		$_SESSION['product']['search_page_offset'] = 0;
	}
	
	if ($offset != -1)
	{
		$_SESSION['product']['search_page_offset'] = $offset;
	}
	
	return $_SESSION['product']['search_page_offset'];
}
?>
