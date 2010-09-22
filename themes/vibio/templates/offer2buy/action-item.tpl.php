<?php
$target_user = $type == "required" ? user_load($action['target_uid']) : user_load($action['uid']);
$profile_pic = $target_user->picture ? file_create_url($target_user->picture) : "/themes/vibio/images/icons/default_user.png";
$node = node_load($action['nid']);
$item_image = _vibio_item_get_image($node->nid);

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
//$message.$action_complete_form.$transaction_cancel_form."<div style='clear: both;'></div>";
echo "
	<tr>
		<td class='views-field views-field-picture'>
			<div class='picture'>
				<a href='/user/{$target_user->uid}'>
					<img src='$profile_pic' alt='{$target_user->name}' title='{$target_user->name}' />
				</a>
			</div>
		</td>
		<td class='views-field views-field-message'>
			<div class='views_node_image_container'>
				<a href='/node/{$node->nid}'>
					<img src='$item_image' />
				</a>
			</div>
			<div class='views_message'>
				$message
				<div class='views_actions'>
					$action_complete_form
					$transaction_cancel_form
				</div>
			</div>
		</td>
	</tr>
";
?>