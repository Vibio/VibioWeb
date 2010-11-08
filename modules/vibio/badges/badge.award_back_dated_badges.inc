<?php
// # of items in inventory
function badge_award_back_dated_badges_1()
{	
	$sql = "SELECT `bid`, `trigger`
			FROM {badge}
			WHERE `type`=%d
			ORDER BY `trigger`";
	$res = db_query($sql, BADGE_TYPE_ITEM);
	
	while ($badge = db_fetch_object($res))
	{
		$sql = "SELECT `uid`
				FROM {node}
				WHERE `type`='vibio_item'
					AND `uid` NOT IN (
						SELECT `uid`
						FROM {badge_user_badges}
						WHERE `bid`=%d
					)
					AND `uid` > %d
				GROUP BY `uid`
				HAVING COUNT(*) >= %d";
		$user_res = db_query($sql, $badge->bid, 0, $badge->trigger);
		
		while ($u = db_fetch_object($user_res))
		{
			badge_give_badge($badge->bid, $u->uid, true);
		}
	}
}

// # of collections
function badge_award_back_dated_badges_3()
{
	$sql = "SELECT `bid`, `trigger`
			FROM {badge}
			WHERE `type`=%d
			ORDER BY `trigger`";
	$res = db_query($sql, BADGE_TYPE_COLLECTION);
	
	while ($badge = db_fetch_object($res))
	{
		$sql = "SELECT `uid`
				FROM {collection}
				WHERE `uid` NOT IN (
					SELECT `uid`
					FROM {badge_user_badges}
					WHERE `bid`=%d
				)
				GROUP BY `uid`
				HAVING COUNT(*) >= %d";
		$user_res = db_query($sql, $badge->bid, $badge->trigger);
		
		while ($u = db_fetch_object($user_res))
		{
			badge_give_badge($badge->bid, $u->uid, true);
		}
	}
}

// # of friends on vibio
function badge_award_back_dated_badges_4()
{
	$sql = "SELECT `bid`, `trigger`
			FROM {badge}
			WHERE `type`=%d
			ORDER BY `trigger`";
	$res = db_query($sql, BADGE_TYPE_FRIENDS);
	
	while ($badge = db_fetch_object($res))
	{
		$sql = "SELECT `requester_id`
				FROM {user_relationships}
				WHERE `requester_id` > %d
					AND `approved`=%d
					AND `requester_id` NOT IN (
						SELECT `uid`
						FROM {badge_user_badges}
						WHERE `bid`=%d
					)
				GROUP BY `requester_id`
				HAVING COUNT(*) >= %d";
		$user_res = db_query($sql, 0, 1, $badge->bid, $badge->trigger);
		
		while ($u = db_fetch_object($user_res))
		{
			badge_give_badge($badge->bid, $u->requester_id, true);
		}
	}
}

function badge_award_back_dated_badges_SPECIAL_fb_link()
{
	if (!($badge = badge_load_special("fb_link")))
	{
		return false;
	}
	
	$sql = "SELECT `uid`
			FROM {fb_uid}
			WHERE `uid` NOT IN (
				SELECT `uid`
				FROM {badge_user_badges}
				WHERE `bid`=%d
			)";
	$res = db_query($sql, $badge->bid);
	
	while ($u = db_fetch_object($res))
	{
		badge_give_badge($badge->bid, $u->uid);
	}
}

function badge_award_back_dated_badges_SPECIAL_ebay_link()
{
	if (!($badge = badge_load_special("ebay_link")))
	{
		return false;
	}
	
	$sql = "SELECT `uid`
			FROM {ebay_users}
			WHERE `uid` NOT IN (
				SELECT `uid`
				FROM {badge_user_badges}
				WHERE `bid`=%d
			)";
	$res = db_query($sql, $badge->bid);
	
	while ($u = db_fetch_object($res))
	{
		badge_give_badge($badge->bid, $u->uid);
	}
}
?>