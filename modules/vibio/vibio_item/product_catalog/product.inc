<?php
define("PRODUCT_OWNER_CACHE_TTL", 300);
define("PRODUCT_OWNER_DISPLAY_PER_PAGE", 10);

function _product_search_user_inventory($keys, $uid)
{
	global $user;
	
	$access_check = module_exists("privacy") ? privacy_get_access_level($uid, $user->uid) : false;
	$item_status = search_query_extract($keys, "item_status");
	$keys = _product_remove_options($keys);
	
	// Build matching conditions
	list($join1, $where1) = _db_rewrite_sql();
	$arguments1 = array();
	$conditions1 = "n.status = 1 AND n.type='product'";
	
	$join1 .= " JOIN {product_items} pi ON pi.`product_nid`=n.`nid` JOIN {node} item_node ON item_node.`nid`=pi.`item_nid`";
	$where1 .= " item_node.`uid`=%d";
	$arguments1[] = $uid;
	
	// eliminate nodes that the current user isn't allowed to see, based on friendship status
	if ($access_check)
	{
		$join1 .= " JOIN {privacy_settings} p ON p.`type_id`=item_node.`nid`";
		$where1 .= " AND p.`type`='node' AND p.`setting` <= %d";
		$arguments1[] = $access_check;
	}
	
	if (in_array($item_status, array(VIBIO_ITEM_TYPE_SELL, VIBIO_ITEM_TYPE_OWN)))
	{
		$join1 .= " JOIN {content_type_vibio_item} ctvi ON ctvi.`nid`=item_node.`nid`";
		$where1 .= " AND ctvi.`field_posting_type_value`=%d";
		$arguments1[] = $item_status;
	}
	
	if (trim($keys) == "")
	{
		$sql = "SELECT n.`nid` AS sid, item_node.`nid` AS item_nid
				FROM {node} n $join1
				WHERE $conditions1
					AND $where1
				ORDER BY n.`changed` DESC";
		$res = pager_query($sql, 10, 0, null, $arguments1);
		$find = array();
		while ($row = db_fetch_object($res))
		{
			$find[] = $row;
		}
	}
	else
	{
		// Build ranking expression (we try to map each parameter to a
		// uniform distribution in the range 0..1).
		$ranking = array();
		$arguments2 = array();
		$join2 = '';
		// Used to avoid joining on node_comment_statistics twice
		$stats_join = FALSE;
		$total = 0;
		if ($weight = (int)variable_get('node_rank_relevance', 5)) {
			// Average relevance values hover around 0.15
			$ranking[] = '%d * i.relevance';
			$arguments2[] = $weight;
			$total += $weight;
		}
		if ($weight = (int)variable_get('node_rank_recent', 5)) {
			// Exponential decay with half-life of 6 months, starting at last indexed node
			$ranking[] = '%d * POW(2, (GREATEST(MAX(n.created), MAX(n.changed), MAX(c.last_comment_timestamp)) - %d) * 6.43e-8)';
			$arguments2[] = $weight;
			$arguments2[] = (int)variable_get('node_cron_last', 0);
			$join2 .= ' LEFT JOIN {node_comment_statistics} c ON c.nid = i.sid';
			$stats_join = TRUE;
			$total += $weight;
		}
		if (module_exists('comment') && $weight = (int)variable_get('node_rank_comments', 5)) {
			// Inverse law that maps the highest reply count on the site to 1 and 0 to 0.
			$scale = variable_get('node_cron_comments_scale', 0.0);
			$ranking[] = '%d * (2.0 - 2.0 / (1.0 + MAX(c.comment_count) * %f))';
			$arguments2[] = $weight;
			$arguments2[] = $scale;
			if (!$stats_join) {
				$join2 .= ' LEFT JOIN {node_comment_statistics} c ON c.nid = i.sid';
			}
			$total += $weight;
		}
		if (module_exists('statistics') && variable_get('statistics_count_content_views', 0) &&
			$weight = (int)variable_get('node_rank_views', 5)) {
			// Inverse law that maps the highest view count on the site to 1 and 0 to 0.
			$scale = variable_get('node_cron_views_scale', 0.0);
			$ranking[] = '%d * (2.0 - 2.0 / (1.0 + MAX(nc.totalcount) * %f))';
			$arguments2[] = $weight;
			$arguments2[] = $scale;
			$join2 .= ' LEFT JOIN {node_counter} nc ON nc.nid = i.sid';
			$total += $weight;
		}
		
		// When all search factors are disabled (ie they have a weight of zero), 
		// the default score is based only on keyword relevance and there is no need to 
		// adjust the score of each item. 
		if ($total == 0) {
			$select2 = 'i.relevance AS score';
			$total = 1;
		}
		else {
			$select2 = implode(' + ', $ranking) . ' AS score';
		}
		
		$select2 .= ", item_node.`nid` AS item_nid";
		
		// Do search.
		$find = do_search($keys, 'node', 'INNER JOIN {node} n ON n.nid = i.sid '. $join1, $conditions1 . (empty($where1) ? '' : ' AND '. $where1), $arguments1, $select2, $join2, $arguments2);
	}
	
	// Load results.
	$results = array();
	foreach ($find as $item) {
		// Build the node body.
		$node = node_load($item->sid);
		$node->build_mode = NODE_BUILD_SEARCH_RESULT;
		$node = node_build_content($node, FALSE, FALSE);
		$node->body = drupal_render($node->content);
	  
		// Fetch comments for snippet.
		if (module_exists('comment')) {
			$node->body .= comment_nodeapi($node, 'update index');
		}
		// Fetch terms for snippet.
		if (module_exists('taxonomy')) {
			$node->body .= taxonomy_nodeapi($node, 'update index');
		}
	  
		$extra = node_invoke_nodeapi($node, 'search result');
		$results[] = array(
			'link' => url("node/{$item->item_nid}"),
			'type' => check_plain(node_get_types('name', $node)),
			'title' => $node->title,
			'user' => theme('username', $node),
			'date' => $node->changed,
			'node' => $node,
			'extra' => $extra,
			'score' => $item->score / $total,
			'snippet' => search_excerpt($keys, $node->body),
		);
	}
	return $results;
}

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
	if ($cache = cache_get($cache_key))
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
	cache_set($cache_key, $data, "cache", time() + PRODUCT_OWNER_CACHE_TTL);
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