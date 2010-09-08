<?php
function uri_edit_elaboration($rid)
{
	global $user;
	
	$status = false;
	$sql = "SELECT `rid`
			FROM {user_relationships}
			WHERE (`requester_id`=%d OR `requestee_id`=%d)
				AND `rid`=%d";
	if (!(db_result(db_query($sql, $user->uid, $user->uid, $rid))))
	{
		$out = t("You are not allowed to edit that elaboration");
	}
	elseif (empty($_POST['elaboration']))
	{
		$out = t("No elaboration provided");
	}
	elseif (!module_exists("user_relationship_elaborations"))
	{
		$out = t("Elaborations are disabled.");
	}
	elseif (!empty(trim($_POST['elaboration'])))
	{
		$sql = "REPLACE INTO {user_relationship_elaborations}
				SET `rid`=%d, `elaboration`='%s'";
		$status = db_query($sql, $rid, $_POST['elaboration']);
	}
	else
	{
		$sql = "DELETE FROM {user_relationship_elaborations}
				WHERE `rid`=%d";
		$status = db_query($sql, $rid);
	}
	
	if ($status)
	{
		$status = true; //since it is currently a db resource
		$out = $_POST['elaboration'];
	}
	elseif (!$out)
	{
		$out = t("There was an error saving friend details. Please try again later.");
	}
	
	exit(json_encode(array(
		"status"	=> $status,
		"message"	=> $out,
	)));
}
?>