<?php
function offer2buy_admin()
{
	//offer2buy_offer_accept
	return system_settings_form(array(
		"#prefix"	=> t("Replacement variables: !offerer is the name/profile link of the person making the offer, !owner is the person who currently owns the item, !item is a link to the item, and !offer is the amount of the offer made. !owner_message_url is the url to send a message to the item owner, !offerer_message_url is the same for the offerer (Note that these will automatically have the item name as a subject line)"),
		"offer_creation"=> array(
			"#type"			=> "fieldset",
			"#title"		=> t("Offer Creation"),
			"#description"	=> t("Additional replacement variables: !comment is the comment made when making the offer"),
			"#collapsible"	=> true,
			"#collapsed"	=> false,
			"offer2buy_offer_message"		=> array(
				"#type"			=> "textarea",
				"#title"		=> t("Offer Message"),
				"#description"	=> t("This is the message sent to the owner of an item after someone makes an offer on it."),
				"#default_value"=> variable_get("offer2buy_offer_message", ""),
				"#required"		=> true,
			),
			"offer2buy_offer_confirmation"	=> array(
				"#type"			=> "textarea",
				"#title"		=> t("Offer Confirmation Message"),
				"#description"	=> t("This is the message sent to the person who made the offer, as confirmation that the offer was successfully sent."),
				"#default_value"=> variable_get("offer2buy_offer_confirmation", ""),
				"#required"		=> true,			
			),
		),
		"offer_accept"	=> array(
			"#type"			=> "fieldset",
			"#title"		=> t("Offer Acceptance"),
			"#collapsible"	=> true,
			"#collapsed"	=> false,
			"offer2buy_offer_accept"	=> array(
				"#type"			=> "textarea",
				"#title"		=> t("Acceptance Message"),
				"#description"	=> t("This is the message sent to the person who made the winning offer on an item."),
				"#default_value"=> variable_get("offer2buy_offer_accept", ""),
				"#required"		=> true,
			),
			"offer2buy_offer_accept_confirm" => array(
				"#type"			=> "textarea",
				"#title"		=> t("Acceptance Confirm Message"),
				"#description"	=> t("This is the message sent to the person who accepted the winning offer on an item."),
				"#default_value"=> variable_get("offer2buy_offer_accept_confirm", ""),
				"#required"		=> true,
			),
		),
	));
}

function offer2buy_ajax_edit_post_type($nid)
{
	$output = drupal_get_form("offer2buy_change_list_type", $nid);
	
	if ($_POST['ajax'])
	{
		exit($output);
	}
	
	return $output;
}

function offer2buy_change_list_type(&$state, $nid)
{
	$form = array_merge(_offer2buy_fields_form($nid, array()), array(
		"submit"	=> array(
			"#type"	=> "submit",
			"#value"=> t("Sell"),
		),
		"field_posting_type"	=> array(
			"#type"	=> "value",
			"#value"=> array(array("value" => VIBIO_ITEM_TYPE_SELL)), //to pass _offer2buy_node_submit
		),
		"#redirect"	=> isset($_GET['destination']) ? $_GET['destination'] : false,
		"#validate"	=> array(
			"_offer2buy_node_validate",
		),
		"#submit"	=> array(
			"_offer2buy_node_submit",
			"offer2buy_change_list_type_submit",
		),
	));
	
	return $form;
}

function offer2buy_change_list_type_submit($form, &$state)
{
	$node = node_load($state['values']['o2b_nid']);
	$node->field_posting_type[0]['value'] = VIBIO_ITEM_TYPE_SELL;
	$node->o2b_price = $state['values']['o2b_price'];
	$node->o2b_is_negotiable = $state['values']['o2b_is_negotiable'];
	$node->o2b_allow_offer_views = $state['values']['o2b_allow_offer_views'];
	node_save($node);
	
	drupal_set_message(t("!title has been updated", array("!title" => l($node->title, "node/{$node->nid}"))));
}
?>