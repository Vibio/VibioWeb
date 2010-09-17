<?php
function hbi_views_data_alter(&$data)
{
	$data['user_relationships']['table']['join']['heartbeat_activity'] = array(
		'left_field'	=> 'uid',
		'field'			=> 'requestee_id',
	);
	
	$data['users']['table']['join']['heartbeat_activity'] = array(
		'left_field'	=> 'uid',
		'field'			=> 'uid',
	);
}

function hbi_views_query_alter(&$view, &$query)
{
	if ($view->name == "user_relational_activity")
	{
		$sql = "uid_target != %d";
		$query->add_where(0, $sql, $view->args[0]);
	}
	elseif ($view->name == "user_hb_incoming_activity")
	{
		global $user;
		
		$sql = "heartbeat_activity.`uid_target`=%d";
		$query->add_where(0, $sql, $user->uid);
	}
}
?>