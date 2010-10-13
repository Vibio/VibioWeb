<?php
function offer2buy_ajax_offer($nid)
{
	if (!$nid || !is_numeric($nid) || !($node = node_load($nid)) ||
		!_vibio_item_access($node))
	{
		exit(t("Unable to place an offer on item !nid", array("!nid" => $nid)));
	}
	
	exit(drupal_get_form("offer2buy_offer_form", $nid, $_GET['destination']));
}

function offer2buy_ajax_reject($uid, $nid)
{
	global $user;
	
	if (!$nid || !$uid || $user->uid != _vibio_item_owner($nid))
	{
		exit("You cannot reject any offers on that item");
	}
	
	exit(drupal_get_form("offer2buy_reject_form_{$uid}_{$nid}", $uid, $nid));
}
?>