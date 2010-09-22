<?php
function imap_get_attachments()
{
	$server = variable_get("imap_serv", false);
	$user = variable_get("imap_user", false);
	$pass = variable_get("imap_pass", false);
	$max_images_saved = variable_get("imap_max_images", 30);
	
	if (!$server || !$user || !$pass)
	{
		return false;
	}
	elseif (!($mbox = @imap_open($server, $user, $pass)))
	{
		return false;
	}
	elseif (!($headers = @imap_check($mbox)))
	{
		return false;
	}
	
	$num_messages = $headers->Nmsgs;
	if ($num_messages == 0)
	{
		return 0;
	}
	
	$messages = imap_fetch_overview($mbox, "1:$num_messages", 0);
	$images_saved = 0;
	
	foreach ($messages as $i => $message)
	{
		if ($images_saved >= $max_images_saved)
		{
			break;
		}
		elseif (!(preg_match('/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $message->from, $matches)))
		{
			continue;
		}
		
		$sql = "SELECT `uid`
				FROM {users}
				WHERE `mail`='%s'";
		if ($uid = db_result(db_query($sql, $matches[0])))
		{
			$struct = imap_fetchstructure($mbox, $message->msgno);
			$numparts = count($struct->parts);
			
			if ($numparts >= 2)
			for ($j = 2; $j <= $numparts; ++$j)
			{
				$attachment = imap_bodystruct($mbox, $message->msgno, $j);
				$mime_type = imap_get_mime_type($attachment);
				
				if (strpos($mime_type, "IMAGE/") !== false)
				{
					imap_save_image($uid, $mbox, $message->msgno, $j, $attachment);
					++$images_saved;
				}
			}
		}
		
		imap_delete($mbox, $message->msgno);
	}
	
	imap_expunge($mbox);
	imap_close($mbox);
	
	return $images_saved;
}

function imap_save_image($uid, $mbox, $message_num, $message_part, &$attachment)
{
	if (!($file_dir = variable_get("imap_files", false)))
	{
		return false;
	}
	
	$time = time();
	$image = imap_base64(imap_fetchbody($mbox, $message_num, $message_part));
	$filename = "{$uid}_{$time}_{$attachment->parameters[0]->value}";
	$filepath = "$file_dir/$filename";
	
	file_put_contents($filepath, $image);
	
	$sql = "INSERT INTO {imap_images}
			SET `uid`=%d, `filepath`='%s', `timestamp`=%d, `status`=%d";
	db_query($sql, $uid, $filepath, $time, IMAP_STATUS_PENDING);
	
	return true;
}

function imap_delete_image($image)
{
	if (is_numeric($image))
	{
		$image = imap_get_image($image);
	}
	
	$sql = "UPDATE {imap_images}
			SET `status`=%d
			WHERE `imap_id`=%d";
	db_query($sql, IMAP_STATUS_DELETED, $image->imap_id);
	file_delete($image->filepath);
}

function imap_mark_image_as_saved($image)
{
	if (is_numeric($image))
	{
		$image = imap_get_image($image);
	}
	
	$sql = "UPDATE {imap_images}
			SET `status`=%d
			WHERE `imap_id`=%d";
	db_query($sql, IMAP_STATUS_SAVED, $image->imap_id);
	file_delete($image->filepath);
}

function imap_get_mime_type(&$struct)
{
	$primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
	
	if ($struct->subtype)
	{
		return $primary_mime_type[(int) $struct->type] . '/' .$struct->subtype;
	}
	
	return "TEXT/PLAIN";
}

function imap_user_pending_images($uid)
{
	$sql = "SELECT `imap_id`, `filepath`
			FROM {imap_images}
			WHERE `uid`=%d
				AND `status`=%d";
	$res = db_query($sql, $uid, IMAP_STATUS_PENDING);
	
	$images = array();
	while ($row = db_fetch_object($res))
	{
		$row->url = file_create_url($row->filepath);
		$images[] = $row;
	}
	
	return $images;
}

function imap_get_image($imap_id)
{
	$sql = "SELECT *
			FROM {imap_images}
			WHERE `imap_id`=%d";
	return db_fetch_object(db_query($sql, $imap_id));
}

function imap_image_owner($imap_id)
{
	$sql = "SELECT `uid`
			FROM {imap_images}
			WHERE `imap_id`=%d";
	return db_result(db_query($sql, $imap_id));
}

function imap_attach_image($image, $node)
{
	if (is_numeric($image))
	{
		$image = imap_get_image($image);
	}
	if (is_numeric($node))
	{
		$node = node_load($node);
	}
	
	$upload_path = file_directory_path();
	$file_destination = "$upload_path/".basename($image->filepath);
	$image_data = image_get_info($image->filepath);
	$mime_type = $image_data['mime_type'] ? $image_data['mime_type'] : "image/jpeg";
	
	file_put_contents($file_destination, file_get_contents($image->filepath));
	
	$file = new stdClass();
	$file->filename = basename($image->filepath);
	$file->filepath = $file_destination;
	$file->filemime = $mime_type;
	$file->filesize = filesize($file_destination);
	$file->uid = $user->uid;
	$file->status = FILE_STATUS_PERMANENT;
	$file->timestamp = time();
	drupal_write_record("files", $file);
	$file->fid = db_result(db_query("SELECT `fid` FROM {files} WHERE `filepath`='%s'", $file->filepath));
	
	if (count($node->field_images) == 1 && empty($node->field_images[0]))
	{
		$node->field_images = array(array("fid" => $file->fid));
	}
	else
	{
		$node->field_images[] = array("fid" => $file->fid);
	}
	
	node_save($node);
	imap_mark_image_as_saved($image);
}
?>