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
	if ($view->name == "user_hb_incoming_activity")
	{
		global $user;
		
		$sql = "
			heartbeat_activity.`uid_target`=%d
			OR heartbeat_activity.`uid`=%d
			OR heartbeat_activity.`uid` IN (
				SELECT `requestee_id`
				FROM {user_relationships}
				WHERE `requester_id`=%d
					AND `approved`=1
					AND CASE
						WHEN heartbeat_activity.`message_id`='heartbeat_add_comment' THEN %d >= (
							SELECT `setting`
							FROM {privacy_settings}
							WHERE `type_id`=heartbeat_activity.`message_id`
								AND type='heartbeat_messages'
								AND `uid`=heartbeat_activity.`uid`
						)
						WHEN heartbeat_activity.`nid`=0 THEN %d >= (
							SELECT `setting`
							FROM {privacy_settings}
							WHERE `type_id`=heartbeat_activity.`message_id`
								AND `type`='heartbeat_messages'
								AND `uid`=users.`uid`
						)
						WHEN heartbeat_activity.`nid` > 0 AND heartbeat_activity.`message_id` != 'heartbeat_add_collection' THEN %d >= (
							SELECT `setting`
							FROM {privacy_settings}
							WHERE `type_id`=heartbeat_activity.`nid`
								AND `type`='node'
								AND `uid`=users.`uid`
                AND `setting`=1
						)
						WHEN heartbeat_activity.`nid` > 0 AND heartbeat_activity.`message_id`='heartbeat_add_collection' THEN %d >= (
							SELECT `setting`
							FROM {privacy_settings}
							WHERE `type_id`=heartbeat_activity.`nid`
								AND `type`='collection'
								AND `uid`=users.`uid`
						)
						ELSE 1
						END > 0
			)";
		$query->add_where(0, $sql, $user->uid, $user->uid, $user->uid, PRIVACY_CONNECTION, PRIVACY_CONNECTION, PRIVACY_CONNECTION, PRIVACY_CONNECTION);
	}
}
?>
