<?php
define("PRODUCT_OWNER_CACHE_TTL", 300);
define("PRODUCT_OWNER_DISPLAY_PER_PAGE", 10);

function _product_get_product_owners($nid, $as_uid=false)
{
	if (!$as_uid)
	{
		global $user;
		$as_uid = $user->uid;
	}
	
	return array(
		"network"	=> _product_get_owners($nid, $as_uid),
		"vibio"		=> _product_get_owners($nid, $as_uid, "vibio"),
	);
}

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
			LIMIT $offset, %d";
	$count_sql = preg_replace(array('/SELECT.*?FROM /As', '/ORDER BY .*/', '/LIMIT .*/'), array('SELECT COUNT(*) FROM ', '', ''), $sql);
	$total_results = db_result(db_query($count_sql, $privacy_check, $as_uid, $nid));
	$res = db_query($sql, $privacy_check, $as_uid, $nid, PRODUCT_OWNER_DISPLAY_PER_PAGE);
	
	$results = array();
	while ($row = db_fetch_array($res))
	{
		$results[] = array(
			"node"	=> l($row['title'], "node/{$row['nid']}"),
			"user"	=> array(
				"picture"	=> file_create_url($row['picture']),
				"name"		=> $row['name'],
				"uid"		=> $row['uid'],
				"dos"		=> $as_uid && $type == "network" && module_exists("dos") ? dos_get($row['uid'], false, $depth) : array(),
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

function _product_get_image($nid)
{
	static $product_images = array();
	
	if (isset($product_images[$nid]))
	{
		return $product_images[$nid];
	}
	
	$product = node_load(_product_nid_from_item($nid));
	$url = file_create_url($product->field_main_image[0]['filepath']);
	$product_images[$nid] = $url;
	
	return $url;
}
?>