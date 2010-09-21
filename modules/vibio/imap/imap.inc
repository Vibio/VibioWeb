<?php
function imap_get_attachments()
{
	$server = variable_get("imap_serv", false);
	$user = variable_get("imap_user", false);
	$pass = variable_get("imap_pass", false);
	
	if (!$server || !$user || !$pass)
	{
		return false;
	}
	elseif (!($mbox = @imap_open($server, $user, $pass)))
	{
		return false;
	}
	elseif (!($headers = @imap_headers($mbox)))
	{
		return false;
	}
	
	$num_messages = count($headers);
	$messages = imap_fetch_overview($mbox, "1:$num_messages", 0);
	
	foreach ($messages as $i => $message)
	{
		if (!(preg_match('/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $message->from, $matches)))
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
				}
			}
		}
		
		imap_delete($mbox, $message->msgno);
	}
	
	imap_expunge($mbox);
	imap_close($mbox);
	
	return true;
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
			SET `uid`=%d, `filepath`='%s', `timestamp`=%d, `pending`=%d";
	db_query($sql, $uid, $filepath, $time, 1);
	
	return true;
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
			WHERE `uid`=%d";
	$res = db_query($sql, $uid);
	
	$images = array();
	while ($row = db_fetch_object($res))
	{
		$row->url = file_create_url($row->filepath);
		$images[] = $row;
	}
	
	return $images;
}
?>