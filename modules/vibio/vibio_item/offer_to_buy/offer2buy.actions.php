<?php
define("OFFER2BUY_ACTION_CANCEL_ACCEPTED_OFFER", 0);
define("OFFER2BUY_ACTION_SEND_PAYMENT", 1);
define("OFFER2BUY_ACTION_RECEIVE_PAYMENT", 2);
define("OFFER2BUY_ACTION_SEND_ITEM", 3);
define("OFFER2BUY_ACTION_RECEIVE_ITEM", 4);

function _offer2buy_init_actions($node, $offerer, $owner)
{
	$sql = "REPLACE INTO {offer2buy_pending_action}
			SET `nid`=%d, `uid`=%d, `target_uid`=%d, `required_action`=%d";
	db_query($sql, $node, $offerer, $owner, OFFER2BUY_ACTION_SEND_PAYMENT);
}

function offer2buy_dashboard($uid=false)
{
	global $user;
	
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}
	
	$required = _offer2buy_dashboard_get_required_actions($uid);
	$pending = _offer2buy_dashboard_get_pending_actions($uid);
	
	$out = theme("offer2buy_actions_list", $required, "required");
	$out .= theme("offer2buy_actions_list", $pending, "pending");
	
	return $out;
}

// these are the actions this user needs to do
function _offer2buy_dashboard_get_required_actions($uid)
{
	$sql = "SELECT *
			FROM {offer2buy_pending_action}
			WHERE `uid`=%d";
	$res = db_query($sql, $uid);
	
	$actions = array();
	while ($row = db_fetch_array($res))
	{
		$actions[] = $row;
	}
	
	return $actions;
}

// these are the actions this user is waiting for
function _offer2buy_dashboard_get_pending_actions($uid)
{
	$sql = "SELECT *
			FROM {offer2buy_pending_action}
			WHERE `target_uid`=%d";
	$res = db_query($sql, $uid);
	
	$actions = array();
	while ($row = db_fetch_array($res))
	{
		$actions[] = $row;
	}
	
	return $actions;
}
?>