<?php
function hbi_views_data_alter(&$data)
{
	$data['user_relationships']['table']['join']['heartbeat_activity'] = array(
		'left_field'	=> 'uid',
		'field'			=> 'requestee_id',
	);
}

function hbi_views_query_alter(&$view, &$query)
{
	if ($view->name == "user_relational_activity")
	{
		$sql = "uid_target != %d";
		$query->add_where(0, $sql, $view->args[0]);
	}
}
?>