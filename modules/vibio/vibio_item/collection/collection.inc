<?php
function collection_get_collections($uid=false)
{
	static $user_collections = array();
	
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}
	
	if (!isset($user_collections[$uid]))
	{
		$sql = "SELECT `cid`
				FROM {collection}
				WHERE `uid`=%d
				ORDER BY `title` ASC";
		$res = db_query($sql, $uid);
		
		$collections = array();
		while ($row = db_fetch_array($res))
		{
			$collections[$row['cid']] = collection_load($row['cid']);
		}
		
		$user_collections[$uid] = $collections;
	}
	
	return $user_collections[$uid];
}

function collection_insert($vals)
{
	global $user;
	
	$sql = "INSERT INTO {collection}
			SET `uid`=%d, `title`='%s', `description`='%s', `created`=%d";
	if (!db_query($sql, $user->uid, $vals['title'], $vals['description'], time()))
	{
		return false;
	}
	
	$vals['cid'] = db_last_insert_id("collection", "cid");
	$vals['uid'] = $user->uid;
	
	module_invoke_all("collections_insert", $vals);
	return $vals['cid'];
}

function collection_delete($cid)
{
	$collection = collection_load($cid);

	$sql = "SELECT `item_nid`
			FROM {collection_items}
			WHERE `cid`=%d";
	$res = db_query($sql, $cid);
	$num_items = db_affected_rows();

	$sql = "DELETE FROM {collection_user_defaults}
			WHERE `cid`=%d";
	db_query($sql, $cid);

	if ($num_items)
	{
		$new_cid = collection_get_user_default($collection['uid'], true);
		$items = array();

		while ($row = db_fetch_object($res))
		{
			$item_cids = collection_get_item_cid($row->item_nid);
			if (count($item_cids) == 1) // if an item is only in more than one cid, don't transfer it
			{
				$items[] = $row->item_nid;
			}
		}

		collection_transfer_items($items, $new_cid);
	}

	$sql = "DELETE FROM {collection}
			WHERE `cid`=%d";
	db_query($sql, $cid);

	module_invoke_all("collection_delete", $collection['uid'], $new_cid, $cid);
	drupal_set_message(t('The collection !title has been deleted', array("!title" => $collection['title'])));
}

function collection_transfer_items($items, $to_cid)
{
	$items = implode(",", $items);
	$sql = "UPDATE {collection_items}
			SET `cid`=%d
			WHERE `item_nid` IN ($items)";
	db_query($sql, $to_cid);
}

function collection_get_item_cid($nid)
{
	$sql = "SELECT `cid`
			FROM {collection_items}
			WHERE `item_nid`=%d";
	$res = db_query($sql, $nid);
	
	$ret = array();
	while ($row = db_fetch_array($res))
	{
		$ret[$row['cid']] = $row['cid'];
	}
	
	return $ret;
}

function collection_options()
{
	global $user;
	
	$options = array();
	foreach (collection_get_collections($user->uid) as $cid => $c)
	{
		$options[$cid] = $c['title'];
	}
	
	return $options;
}

function collection_get_user_default($uid=false, $auto_create=false)
{
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}
	
	$sql = "SELECT `cid`
			FROM {collection_user_defaults}
			WHERE `uid`=%d";
	$cid = db_result(db_query($sql, $uid));
	
	if ($cid || !$auto_create)
	{
		return $cid;
	}
	// if there is no default, create one for the user...
	$vals = array(
		"title"			=> t("Unsorted"),
		"description"	=> t("My unsorted things."),
	);
	
	if (!$vals['cid'] = collection_insert($vals))
	{
		return false;
	}
	
	$vals['uid'] = $uid;
	collection_set_user_default($uid, $vals['cid']);
	module_invoke_all("collection_save", $vals);

	return $vals['cid'];
}

function collection_set_user_default($uid, $cid)
{
	$sql = "REPLACE INTO {collection_user_defaults}
			SET `uid`=%d, `cid`=%d";
	return db_query($sql, $uid, $cid);
}

function collection_get_image($cid, $absolute=false, $access=false)
{
	if (!$access)
		$access = PRIVACY_ONLYME;
		
	$sql = "SELECT n.`nid`
			FROM {node} n JOIN {collection_items} ci JOIN {privacy_settings} p
				ON n.`nid`=ci.`item_nid`
					AND n.`nid`=p.`type_id`
					AND n.`uid`=p.`uid`
			WHERE ci.`cid`=%d
				AND p.`type`='node'
				AND p.`setting` <= %d
			ORDER BY n.`changed` DESC
			LIMIT 1";
	if (!$nid = db_result(db_query($sql, $cid, $access)))
	{
		return collection_default_image();
	}
	
	$image = _vibio_item_get_image($nid);
	return $image == _vibio_item_default_image() ? collection_default_image() : $image;
}

function collection_default_image($absolute=false)
{
	$default_image = "themes/vibio/images/collections/box.png";
	return $absolute ? url($default_image, array("absolute" => true)) : "/$default_image";
}

function collection_get_num_items($cid, $access=false)
{
	if (module_exists("privacy") && $access)
	{
		$collection = collection_load($cid);
		
		$sql = "SELECT COUNT(*)
				FROM {collection_items} ci JOIN {privacy_settings} p
					ON ci.`item_nid`=p.`type_id`
				WHERE `cid`=%d
					AND p.`type`='node'
					AND p.`uid`=%d
					AND p.`setting` <= %d";
		return db_result(db_query($sql, $collection['cid'], $collection['uid'], $access));
	}
	
	$sql = "SELECT COUNT(*)
			FROM {collection_items}
			WHERE `cid`=%d";
	return db_result(db_query($sql, $cid));
}

function collection_get_categories($cid)
{
	if (!module_exists("taxonomy") || !module_exists("product"))
	{
		return array();
	}
	
	$categories = array();
	$sql = "SELECT pi.`product_nid`
			FROM {collection_items} ci JOIN {product_items} pi
				ON ci.`item_nid`=pi.`item_nid`
			WHERE `cid`=%d";
	$res = db_query($sql, $cid);
	
	while ($row = db_fetch_object($res))
	{
		$product = node_load($row->product_nid);
		foreach (taxonomy_node_get_terms_by_vocabulary($product, variable_get("vibio_amazon_category_id", 1)) as $tid => $term)
		{
			$categories[$tid] = $term->name;
		}
	}
	
	return $categories;
}

function collection_get_sidebar($uid, $ignore_cid=0, $max_collections=4, $as_uid=false)
{
	if (!$as_uid)
	{
		global $user;
		$as_uid = $user->uid;
	}
	
	if (module_exists("privacy"))
	{
		$access = privacy_get_access_level($uid, $as_uid);
		$sql = "SELECT c.`cid`, c.`title`, c.`description`, COUNT(*) AS num_items
				FROM {collection} c JOIN {privacy_settings} p JOIN {collection_items} ci
					ON c.`cid`=p.`type_id`
						AND c.`uid`=p.`uid`
						AND c.`cid`=ci.`cid`
				WHERE p.`type`='collection'
					AND p.`setting` <= %d
					AND c.`uid`=%d
					AND c.`cid` != %d
				GROUP BY c.`cid`
				ORDER BY RAND()
				LIMIT %d";
		$res = db_query($sql, $access, $uid, $ignore_cid, $max_collections);
	}
	else
	{
		$sql = "SELECT c.`cid`, c.`title`, c.`description`, COUNT(*) AS num_items
				FROM {collection} c JOIN {collection_items} ci
					ON c.`cid`=ci.`cid`
				WHERE `uid`=%d
					AND c.`cid` != %d
				GROUP BY c.`cid`
				ORDER BY RAND()
				LIMIT %d";
		$res = db_query($sql, $uid, $ignore_cid, $max_collections);
	}
	
	$collections = array();
	while ($c = db_fetch_object($res))
	{
		$c->items = collection_get_sidebar_items($c->cid, $uid, $as_uid);
		$c->image = collection_get_image($c->cid);
		$collections[] = $c;
	}
	
	return $collections;
}

function collection_get_sidebar_items($cid, $owner=false, $as_uid=false, $num_items=3)
{
	if (!$as_uid)
	{
		global $user;
		$as_uid = $user->uid;
	}
	
	if (module_exists("privacy"))
	{
		if (!$owner)
		{
			$collection = collection_load($cid);
			$owner = $collection['cid'];
		}
		
		$access = privacy_get_access_level($owner, $as_uid);
		$sql = "SELECT ci.`item_nid`, n.`title`
				FROM {collection_items} ci JOIN {node} n JOIN {privacy_settings} p
					ON ci.`item_nid`=n.`nid`
						AND n.`nid`=p.`type_id`
				WHERE ci.`cid`=%d
					AND p.`type`='node'
					AND p.`uid`=%d
					AND p.`setting` <= %d
				ORDER BY RAND()
				LIMIT %d";
		$res = db_query($sql, $cid, $owner, $access, $num_items);
	}
	else
	{
		$sql = "SELECT ci.`item_nid`, n.`title`
				FROM {collection_items} ci JOIN {node} n
					ON ci.`item_nid`=n.`nid`
				WHERE ci.`cid`=%d
				ORDER BY RAND()
				LIMIT %d";
		$res = db_fetch_object($sql, $cid, $num_items);
	}
	
	$items = array();
	while ($item = db_fetch_object($res))
	{
		$item->image = _vibio_item_get_image($item->item_nid);
		$items[] = $item;
	}
	
	return $items;
}
?>
