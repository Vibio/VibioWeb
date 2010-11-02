<?php
function badge_give_badge($bid, $uid=false, $delivered=false)
{
	if (!$uid)
	{
		global $user;
		
		if (!$user->uid)
		{
			return false;
		}
		
		$uid = $user->uid;
	}
	
	if (badge_user_has_badge($bid, $uid))
	{
		return false;
	}
	
	$sql = "INSERT IGNORE INTO {badge_user_badges}
			SET `bid`=%d, `uid`=%d, `tstamp`=%d, `delivered`=%d";
	db_query($sql, $bid, $uid, time(), $delivered);
}

function badge_user_has_badge($bid, $uid=false)
{
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}
	
	$user_badges = badge_get_user_badges($uid);
	return array_key_exists($bid, $user_badges);
}

function badge_get_user_badges($uid)
{
	static $user_badges = array();
	
	if (isset($user_badges[$uid]))
	{
		return $user_badges[$uid];
	}
	
	$sql = "SELECT b.bid, b.`image`, b.`title`, b.`description`, ub.`tstamp`, ub.`delivered`
			FROM {badge} b JOIN {badge_user_badges} ub
				ON b.`bid`=ub.`bid`
			WHERE ub.`uid`=%d
			ORDER BY ub.`tstamp` DESC";
	$res = db_query($sql, $uid);
	
	$user_badges[$uid] = array();
	while ($row = db_fetch_object($res))
	{
		$user_badges[$uid][$row->bid] = $row;
	}
	
	return $user_badges[$uid];
}

function badge_user_get_amount($uid, $type)
{
	$amount = 0;
	switch ($type)
	{
		case BADGE_TYPE_ITEM:
			$sql = "SELECT COUNT(*)
					FROM {node}
					WHERE `type`='vibio_item'
						AND `uid`=%d";
			$amount = db_result(db_query($sql, $uid));
			break;
		case BADGE_TYPE_PRODUCT:
			$sql = "SELECT COUNT(*)
					FROM {node}
					WHERE `type`='product'
						AND `uid`=%d";
			$amount = db_result(db_query($sql, $uid));
			break;
		case BADGE_TYPE_COLLECTION:
			$sql = "SELECT COUNT(*)
					FROM {collection}
					WHERE `uid`=%d";
			$amount = db_result(db_query($sql, $uid));
			break;
		case BADGE_TYPE_FRIENDS:
			$params = array(
				"user"		=> $uid,
				"approved"	=> 1,
			);
			$options = array(
				"include_user_info"	=> true,
			);
			
			$amount = count(user_relationships_load($params, $options));
			break;
	}
	
	return $amount;
}

function badge_get_next_badge($type, $current_count)
{
	$sql = "SELECT `bid`, `trigger`
			FROM {badge}
			WHERE `type`=%d
				AND `trigger` >= %d
			ORDER BY `trigger`
			LIMIT 1";
	return db_fetch_object(db_query($sql, $type, $current_count));
}

function badge_load_special($trigger)
{
	static $badges = array();
	
	if (isset($badges[$trigger]))
	{
		return $badges[$trigger];
	}
	
	$sql = "SELECT *
			FROM {badge}
			WHERE `trigger`='%s'
				AND `type`=%d";
	$badges[$trigger] = db_fetch_object(db_query($sql, $trigger, BADGE_TYPE_SPECIAL));
	
	return $badges[$trigger];
}

function badge_type_select()
{
	return array(
		BADGE_TYPE_ITEM			=> t("Items"),
		BADGE_TYPE_FRIENDS		=> t("Friends"),
		BADGE_TYPE_COLLECTION	=> t("Collections"),
		BADGE_TYPE_PRODUCT		=> t("Products"),
		BADGE_TYPE_SPECIAL		=> t("SPECIAL"),
	);
}
?>