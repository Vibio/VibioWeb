<?php
function imap_fetch_ajax_service()
{
	module_load_include("inc", "imap_fetch");
	
	$p = $_POST;
	$result = array(
		"saved"		=> false,
		"message"	=> t("There was an error."),
	);
	
	$function = "imap_fetch_ajax_service_{$p['action']}";
	if (function_exists($function) && ($message = $function($p)))
	{
		$result = array(
			"saved"		=> true,
			"message"	=> $message,
		);
	}
	
	exit(json_encode($result));
}

function imap_fetch_ajax_service_delete($post)
{
	global $user;
	
	if ($user->uid != imap_fetch_image_owner($post['id']))
	{
		return false;
	}
	
	imap_fetch_delete_image($post['id']);
	return t("Image has been deleted!");
}

function imap_fetch_ajax_service_get_collection_items($post)
{
	if (!collection_manage_collection_access($post['cid']))
	{
		return array(-1 => "");
	}
	
	$sql = "SELECT n.`nid`, n.`title`
			FROM {collection_items} ci JOIN {node} n
				ON n.`nid`=ci.`item_nid`
			WHERE ci.`cid`=%d";
	$res = db_query($sql, $post['cid']);
	
	$items = array();
	while ($row = db_fetch_object($res))
	{
		$items[$row->nid] = substr($row->title, 0, 35);
	}
	
	return empty($items) ? array(-1 => "") : $items;
}

function imap_fetch_ajax_service_attach_image($post)
{
	global $user;
	
	if ($user->uid != imap_fetch_image_owner($post['id']) || !($node = node_load($post['nid'])) || $user->uid != $node->uid)
	{
		return false;
	}
	
	imap_fetch_attach_image($post['id'], $node);
	return t("The image has been attached to !item", array("!item" => $node->title));
}

function imap_fetch_receive_image()
{
	$sessid = $_POST['session_id'];
	$image = file_get_contents($_FILES['image']['tmp_name']);
	
	if (!$sessid || !$image || !($file_dir = variable_get("imap_files", false)))
	{
		exit();
	}
	
	$sql = "SELECT `uid`
			FROM {sessions}
			WHERE `sid`='%s'";
	if (!($uid = db_result(db_query($sql, $sessid))))
	{
		exit();
	}
	
	$time = time();
	$filename = "android_{$uid}_{$time}.jpg";
	$filepath = "$file_dir/$filename";
	
	file_put_contents($filepath, $image);
	
	$sql = "INSERT INTO {imap_images}
			SET `uid`=%d, `filepath`='%s', `timestamp`=%d, `status`=%d";
	db_query($sql, $uid, $filepath, $time, IMAP_STATUS_PENDING);
}
?>