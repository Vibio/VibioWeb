<?php
// look familiar? taken from node_search('search', $keys) and modified to have type hardcoded as vibio_item, and to restrict based on network status
function _vibio_item_search($keys)
{
	if (!module_exists("network"))
	{
		return node_search("search", $keys. "type:vibio_item");
	}
	
	$valid_keys = array(
		"target_user"	=> "user",
		"item_status"	=> "item_status",
		"access"		=> "users",
		"dos"			=> "dos",
	);
	
	$target_user = search_query_extract($keys, "user");
	$item_status = search_query_extract($keys, "item_status");
	$access = search_query_extract($keys, "users");
	$dos = search_query_extract($keys, "dos");
	
	$keys = preg_replace('/(\s+)user:([0-9]+)/i', '', $keys);
	$keys = preg_replace('/(\s+)item_status:([0-9]+)/i', '', $keys);
	$keys = preg_replace('/(\s+)users:([a-z,0-9]+)/i', '', $keys);
	$keys = preg_replace('/(\s+)dos:([0-9]+)/i', '', $keys);
	
	_vibio_item_search_keys($keys);
	$is_product_search = !$target_user && module_exists("product");
	
	if ($is_product_search)
	{
		product_set_autoadd(false);
		if ((!variable_get("product_local_search", false) || $_GET['external_product_search']) && ($results = product_external_search($keys)))
		{
			$keys = _product_remove_options($keys);
//dsm($results);
// product_external_search is returning 10 results
// it calls vibio_amazon_product_search($args)
// Hey: we change the keys, then return $results, without redoing anything? confusing...
// $results above can come down a very twisted path, 
// vibio_amazon_product_search to amazon_search_search at the amazon module
// which was thrown into the core directory with unmodified modules, but is 
// modified, goes back to _vibio_amazon_search_result
			return $results;
		}
		
		$node_type = "product";
	}
	else
	{
		$node_type = "vibio_item";
	}
	
	global $user;
	
	// Build matching conditions
	list($join1, $where1) = _db_rewrite_sql();
	$arguments1 = array();
	$conditions1 = "n.status = 1 AND n.type='$node_type'";
	
	if (!$is_product_search)
	{
		$operator = $target_user ? "=" : "!=";
		$conditions1 .= " AND n.uid $operator %d";
		$arguments1[] = $target_user ? $target_user : $user->uid;
	}
	
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
	if (module_exists("privacy") && !$is_product_search && $target_user != $user->uid)
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
	
	//don't care if this is set to "all", since if that's the case we just don't do anything else
	if (!$is_product_search && $target_user != $user->uid && (!$access && $user->uid) || $access == "network")
	{
		if (!$dos)
		{
			$dos = 1;
		}
		$network = array_keys(network_get($user->uid, false, $dos));
		array_shift($network);
		$uids = implode(",", $network);
		$where1 .= " AND n.`uid` IN ($uids)";
	}
	
	if (module_exists("product") && variable_get("product_append_external", false))
	{
		product_external_search_modify_search_query($where1);
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
			'link' => url('node/'. $item->sid),
			'type' => check_plain(node_get_types('name', $node)),
			'title' => $node->title,
			'user' => theme('username', $node),
			'date' => $node->changed,
			'node' => $node,
			'extra' => $extra,
			'score' => $item->score / $total,
			//'snippet' => search_excerpt($keys, $node->body),
		);
	}
//dsm($results); -- seems to fire maybe on weirder searches w/o results?
	return $results;
}

function _vibio_item_access($node)
{
	if (is_numeric($node))
	{
		$node = node_load($node);
	}
	
	return privacy_get($node->uid, "node", $node->nid) <= privacy_get_access_level($node->uid);
}

function _vibio_item_unset(&$form, $type="vibio_item")
{
	if ($type == "vibio_item")
	{
		unset($form['revision_information']);
	}
	unset($form['path']);
	unset($form['menu']);
	unset($form['attachments']);
	unset($form['body_field']['teaser_include']);
	unset($form['body_field']['teaser_js']);
}

function _vibio_item_defaults(&$form, $type="vibio_item")
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
	
	$form['body_field']['body']['#rows'] = 5;
	
	$form['options'] = array(
		"status"	=> array(
			"#type"	=> "value",
			"#value"=> true,
		),
	);
	
	if ($type != "vibio_item")
	{
		$form['revision_information'] = array(
			'revision' => array(
				"#type" => "value",
				"#value"=> true,
			),
		);
	}
	else
	{
		/*$form['title'] = array(
			"#type"	=> "value",
			"#value"=> $form['#title']['#default_value'],
		);*/
	}
}

function _vibio_item_owner($nid)
{
	$sql = "SELECT `uid`
			FROM {node}
			WHERE `nid`=%d
				AND `type`='vibio_item'";
	return db_result(db_query($sql, $nid));
}

function _vibio_item_user_options()
{
	return array(
		"network"	=> t("Network"),
		"all"		=> t("Vibio"),
	);
}


/* for example, called for the Showcase Item */
function _vibio_item_get_image($nid)
{
	if (!($node = node_load($nid)))
	{
		return _vibio_item_default_image();
	}
	
	if (!empty($node->field_main_image[0]['filepath']) && file_exists($node->field_main_image[0]['filepath']))
	{
		return file_create_url($node->field_main_image[0]['filepath']);
	}
	elseif (module_exists("product"))
	{
		module_load_include("inc", "product");
		return _product_get_image($nid);
	}
	
	return _vibio_item_default_image();
}

/* function vibio_item_get_image_for_imagecache($nid) // for imagecache
 * almost same as _vibio_item_get_image, but internal
 * path rather than URL
 * either by code (not yet) or by cutting (for now) 
 * return the main image for an item --
 * it's own, or it's products, or default.
 *   		- stephen
 */
function vibio_item_get_image($nid, $imagecachecode, $alt = null, $title = null, $attributes = null) { // for imagecache
	$url = _vibio_item_get_image($nid); // could recode and clean this,
		// previous code takes what we want and runs file_create_url over it,
		// then I undo that here sloppy fast works fine.
		// Duplicate code with function file_uncreate_url
	$pattern = "/sites/";
	$p = preg_split ( $pattern, $url, 2 );
	$path ="sites/"  . $p[1];

	return theme('imagecache', $imagecachecode, $path, $alt, $title, $attrbutes);

}
	
function _vibio_item_default_image()
{
	return file_create_url("themes/vibio/images/icons/default_item.png");
}

function _vibio_item_search_keys($keys=false)
{
	static $search_keys = false;
	
	if ($keys !== false)
	{
		$search_keys = $keys;
	}
	
	return $search_keys;
}

function _vibio_item_clear_insert_message()
{
	if (!empty($_SESSION['messages']['status']))
	foreach ($_SESSION['messages']['status'] as $i => $message)
	{
		if (preg_match('/vibio item (.*) has been created./i', $message))
		{
			unset($_SESSION['messages']['status'][$i]);
		}
	}
}

function vibio_item_newuser_search($form, $uid, $search_stage)
{
	$default_text = t("What would you like to find?");
	
	drupal_add_js(array(
		"newuser" => array(
			"search_default_text" => $default_text,
		)
	), "setting");
	
	return array(
		"uid"		=> array(
			"#type"	=> "value",
			"#value"=> $uid
		),
		"stage"			=> array(
			"#type"	=> "value",
			"#value"=> $search_stage,
		),
		"item_search"	=> array(
			"#type"			=> "textfield",
			"#default_value"=> $default_text,
			"#description"	=> t("Examples: Shrek DVD, bicycle, Converse shoes..."),
		),
		"submit"		=> array(
			"#prefix"	=> "<div class='tutorial_button_left' style='margin-left: 240px;'></div><div class='tutorial_button_mid'>",
			"#suffix"	=> "</div><div class='tutorial_button_right'></div>",
			"#type"		=> "submit",
			"#value"	=> t("Search"),
		),
	);
}

function vibio_item_newuser_search_submit($form, &$state)
{
	$vals = $state['values'];
	newuser_set_stage($vals['uid'], $vals['stage']);
	drupal_goto("search/vibio_item/{$vals['item_search']}");
}

function vibio_item_get_category($item_nid)
{
	if (!module_exists("product"))
	{
		return 0;
	}
	
	module_load_include("inc", "product");
	$product = _product_from_item($item_nid);
	$term = array_shift($product->taxonomy);
	return $term->tid;
}
?>
