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
?>