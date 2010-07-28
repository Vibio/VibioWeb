<?php
function offer2buy_admin()
{
	//offer2buy_offer_accept
	return system_settings_form(array(
		"#prefix"	=> t("Replacement variables: !offerer is the name/profile link of the person making the offer, !owner is the person who currently owns the item, !item is a link to the item, and !offer is the amount of the offer made."),
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
?>