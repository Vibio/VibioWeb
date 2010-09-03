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

function _offer2buy_complete_actions($nid, $new_owner_uid)
{
	$node = node_load($nid);
	$old_owner_uid = $node->uid;
	$node->uid = $new_owner_uid;
	$node->field_posting_type[0]['value'] = VIBIO_ITEM_TYPE_OWN;
	node_save($node);
	
	module_invoke_all("offer2buy_complete_actions", $old_owner_uid, $node);
}

function offer2buy_dashboard($uid=false)
{
	global $user;
	
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}
	
	drupal_add_js("sites/all/themes/vibio/js/offer2buy_actions.js");
	drupal_add_css("sites/all/themes/vibio/css/offer2buy.css");
	
	
	$incoming_offers = _offer2buy_get_user_incoming_offers($uid);
	$outgoing_offers = _offer2buy_get_user_outgoing_offers($uid);
	$required_actions = _offer2buy_dashboard_get_required_actions($uid);
	$pending_actions = _offer2buy_dashboard_get_pending_actions($uid);
	
	$out  = theme("offer2buy_list_all_offers", $incoming_offers);
	$out .= theme("offer2buy_list_outgoing_offers", $outgoing_offers);
	$out .= theme("offer2buy_actions_list", $required_actions, "required");
	$out .= theme("offer2buy_actions_list", $pending_actions, "pending");
	
	return $out;
}

function offer2buy_action_complete_form(&$state, $action)
{
	return array(
		"action"	=> array(
			"#type"	=> "value",
			"#value"=> $action,
		),
		"submit"	=> array(
			"#type"	=> "submit",
			"#value"=> t("Done"),
		),
		"#submit"	=> array(
			"offer2buy_action_complete_form_submit",
		),
		"#attributes"	=> array(
			"class"	=> "offer2buy_action_complete_form",
		),
		
	);
}

function offer2buy_action_complete_form_submit($form, &$state)
{
	$action = $state['values']['action'];
	$next_action = $action['required_action'] == OFFER2BUY_ACTION_RECEIVE_ITEM ? false : $action['required_action'] + 1;
	$maintain_same_uids = array( //maintain same uid, target_uid if the next required action is in this list.
		OFFER2BUY_ACTION_SEND_ITEM,
	);
	
	$sql = "INSERT INTO {offer2buy_action_history}
			SET `nid`=%d, `uid`=%d, `target_uid`=%d, `action`=%d, `timestamp`=%d";
	db_query($sql, $action['nid'], $action['uid'], $action['target_uid'], $action['required_action'], time());
	
	$sql = "DELETE FROM {offer2buy_pending_action}
			WHERE `nid`=%d
				AND `uid`=%d
				AND `target_uid`=%d
				AND `required_action`=%d";
	db_query($sql, $action['nid'], $action['uid'], $action['target_uid'], $action['required_action']);
	
	if ($next_action)
	{	
		$next_uid = in_array($next_action, $maintain_same_uids) ? $action['uid'] : $action['target_uid'];
		$next_target_uid = $next_uid == $action['target_uid'] ? $action['uid'] : $action['target_uid'];
		
		$sql = "INSERT INTO {offer2buy_pending_action}
				SET `nid`=%d, `uid`=%d, `target_uid`=%d, `required_action`=%d";
		db_query($sql, $action['nid'], $next_uid, $next_target_uid, $next_action);
	}
	elseif ($action['required_action'] == OFFER2BUY_ACTION_RECEIVE_ITEM) //transfer node
	{
		_offer2buy_complete_actions($action['nid'], $action['uid']);
	}
}

function _offer2buy_get_user_incoming_offers($uid)
{
	$sql = "SELECT o.*, u.name
			FROM {offer2buy_offers} o JOIN {users} u ON o.`uid`=u.`uid`
			WHERE o.`nid` IN (
				SELECT `nid`
				FROM {node}
				WHERE `uid`=%d
			)
			ORDER BY o.`offer` DESC, o.`timestamp` ASC";
	$res = db_query($sql, $uid);
	
	$offers = array();
	while ($row = db_fetch_array($res))
	{
		if (!array_key_exists($row['nid'], $offers))
		{
			$offers[$row['nid']]['item'] = node_load($row['nid']);
		}
		
		$offers[$row['nid']]['offers'][] = $row;
	}
	
	return $offers;
}

function _offer2buy_get_user_outgoing_offers($uid)
{
	$sql = "SELECT *
			FROM {offer2buy_offers}
			WHERE `uid`=%d";
	$res = db_query($sql, $uid);
	
	$offers = array();
	while ($row = db_fetch_object($res))
	{
		$offer = new stdClass;
		$offer->offer = $row;
		$offer->item = node_load($row->nid);
		$offers[$row->nid] = $offer;
	}
	
	return $offers;
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