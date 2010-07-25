<?php
// look familiar? taken from node_search('search', $keys) and modified to have type hardcoded as vibio_item, and to restrict based on network status
function _vibio_item_search($keys)
{
	if (!module_exists("network"))
	{
		return node_search("search", $keys. "type:vibio_item");
	}
	
	global $user;
	
	// Build matching conditions
	list($join1, $where1) = _db_rewrite_sql();
	$arguments1 = array();
	$conditions1 = "n.status = 1 AND n.type='vibio_item' AND n.uid != %d";
	$arguments1[] = $user->uid;
	
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
	
	// eliminate nodes that the current user isn't allowed to see, based on friendship status
	if (module_exists("privacy"))
	{
		$join1 .= " JOIN {privacy_settings} p ON p.`type_id`=n.`nid`";
	
		/*
		  note that we don't use the "user_item" string. that's because a user shouldn't see their own items in search.
		  also note that if a node doesn't have a privacy setting set, then it will show up. shouldn't happen, though.
		*/
		$where1 .= "
			p.`type`='node' AND
			CASE
				WHEN p.`setting`=%d THEN 'user_item'
				WHEN p.`setting`=%d THEN n.uid
				WHEN p.`setting` IN (%d, %d)
					AND %d > 0 THEN 'authenticated'
				WHEN p.`setting`=%d THEN 'public'
				END IN (
					'public',
					'authenticated',
					(
						SELECT ur.`requester_id`
						FROM {user_relationships} ur
						WHERE ur.`approved`=1
							AND ur.`requestee_id`=%d
							AND ur.`requester_id`=n.uid
					)
				)";
		$arguments1[] = PRIVACY_ONLYME;
		$arguments1[] = PRIVACY_CONNECTION;
		$arguments1[] = PRIVACY_AUTHENTICATED;
		$arguments1[] = PRIVACY_PUBLIC;
		$arguments1[] = $user->uid;
		$arguments1[] = PRIVACY_PUBLIC;
		$arguments1[] = $user->uid;
	}
	
	$access = search_query_extract($keys, "users");
	
	//don't care if this is set to "all", since if that's the case we just don't do anything else
	if ((!$access && $user->uid) || $access == "friends")
	{
		$join1 .= " JOIN {user_relationships} ur ON ur.`requester_id`=n.`uid`";
		$where1 .= "
			AND ur.`requestee_id`=%d
			AND ur.`requester_id`=n.`uid`
			AND ur.`approved`=1
		";
		$arguments1[] = $user->uid;
	}
	
	$keys = preg_replace('/(\s+)users:([a-z]+)/i', '', $keys);
	
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
	
	// Do search.
	$find = do_search($keys, 'node', 'INNER JOIN {node} n ON n.nid = i.sid '. $join1, $conditions1 . (empty($where1) ? '' : ' AND '. $where1), $arguments1, $select2, $join2, $arguments2);
	
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
			'link' => url('node/'. $item->sid, array('absolute' => TRUE)),
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

function _vibio_item_access($node)
{
	global $user;
	
	if ($user->uid == $node->uid) //always view your own items
	{
		return true;
	}
	
	switch ($node->field_privacy_settings[0]['value'])
	{
		case VIBIO_ITEM_ACCESS_ME:
			return $user->uid == $node->uid;
		case VIBIO_ITEM_ACCESS_AUTHENTICATED:
			return $user->uid > 0;
		case VIBIO_ITEM_ACCESS_FRIENDS:
			$params = array(
				"between"	=> array($user->uid, $node->uid),
			);
			$options = array(
				"count" => true,
			);
			
			return user_relationships_load($params, $options) > 0;
		case VIBIO_ITEM_ACCESS_ALL:
		default:
			return true;
	}
}

function _vibio_item_unset(&$form)
{
	unset($form['revision_information']);
	unset($form['path']);
	unset($form['menu']);
	unset($form['attachments']);
}

function _vibio_item_defaults(&$form)
{
	global $user;
	
	$form['author'] = array(
		"name"	=> array(
			"#type"	=> "value",
			"#value"=> $user->name,
		),
		"date"	=> array(
			"#type"	=> "value",
			"#value"=> "",
		),
	);
	
	$form['options'] = array(
		"status"	=> array(
			"#type"	=> "value",
			"#value"=> true,
		),
	);
}

function _vibio_item_user_options()
{
	return array(
		"friends"	=> t("Friends"),
		"all"		=> t("Vibio"),
	);
}
?>