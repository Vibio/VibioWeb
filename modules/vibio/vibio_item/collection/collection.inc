<?php
function collection_get_collections($uid=false)
{
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}
	
	$sql = "SELECT `cid`
			FROM {collection}
			WHERE `uid`=%d";
	$res = db_query($sql, $uid);
	
	$collections = array();
	while ($row = db_fetch_array($res))
	{
		$collections[$row['cid']] = collection_load($row['cid']);
	}
	
	return $collections;
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

function collection_get_image($cid)
{
	return collection_default_image();
	
	/*$sql = "SELECT n.`nid`
			FROM {node} n JOIN {collection_items} ci ON n.`nid`=ci.`item_nid`
			WHERE ci.`cid`=%d
			ORDER BY n.`changed` DESC
			LIMIT 1";
	if (!$nid = db_result(db_query($sql, $cid)))
	{
		return collection_default_image();
	}
	
	return _vibio_item_get_image($nid);*/
}

function collection_default_image()
{
	return "/themes/vibio/images/collections/box.png";
}
?>