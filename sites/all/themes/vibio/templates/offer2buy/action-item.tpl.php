<?php
$target_user = $type == "required" ? user_load($action['target_uid']) : user_load($action['uid']);
$node = node_load($action['nid']);

switch ($action['required_action'])
{
	case OFFER2BUY_ACTION_SEND_PAYMENT:
		$act = t("send payment to");
		break;
	case OFFER2BUY_ACTION_RECEIVE_PAYMENT:
		$act = t("accept payment from");
		break;
	case OFFER2BUY_ACTION_SEND_ITEM:
		$act = t("send item to");
		break;
	case OFFER2BUY_ACTION_RECEIVE_ITEM:
		$act = t("accept item from");
		break;
}

$message_params = array(
	"!action"	=> $act,
	"!user"		=> l($target_user->name, "user/{$target_user->uid}"),
	"!item"		=> l($node->title, "node/{$node->nid}"),
);

if ($type == "required")
{
	$message = t("You need to !action !user for !item", $message_params);
	$action_complete_form = drupal_get_form("offer2buy_action_complete_{$action['nid']}_{$action['uid']}_{$action['target_uid']}_{$action['required_action']}", $action);
}
else
{
	$message = t("!user needs to !action you for !item", $message_params);
}

$transaction_cancel_form = drupal_get_form("offer2buy_transaction_cancel_{$action['nid']}", $action['nid']);

echo $message.$action_complete_form.$transaction_cancel_form."<div style='clear: both;'></div>";
?>