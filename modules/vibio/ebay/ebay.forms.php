<?php
function ebay_admin()
{
	return system_settings_form(array(
		"ebayapi_signin_url"=> array(
			"#title"		=> t("Sign in URL"),
			"#type"			=> "textfield",
			"#default_value"=> variable_get("ebayapi_signin_url", ""),
			"#required"		=> true,
		),
		"ebayapi_runame"	=> array(
			"#title"		=> t("RuName"),
			"#type"			=> "textfield",
			"#default_value"=> variable_get("ebayapi_runame", ""),
			"#required"		=> true,
		),
		"ebayapi_entriesperpage"	=> array(
			"#title"		=> t("Search Entries Per Page"),
			"#type"			=> "textfield",
			"#size"			=> 3,
			"#default_value"=> variable_get("ebayapi_entriesperpage", 20),
			"#required"		=> true,
		),
		"ebayapi_campaignid"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Campaign ID"),
			"#default_value"=> variable_get("ebayapi_campaignid", ""),
		),
		"keys"			=> array(
			"#type"			=> "fieldset",
			"#title"		=> t("Keys"),
			"#collapsible"	=> true,
			"#collapsed"	=> true,
			"ebayapi_devid"		=> array(
				"#title"		=> t("Dev ID"),
				"#type"			=> "textfield",
				"#default_value"=> variable_get("ebayapi_devid", ""),
				"#required"		=> true,
			),
			"ebayapi_appid"		=> array(
				"#title"		=> t("App ID"),
				"#type"			=> "textfield",
				"#default_value"=> variable_get("ebayapi_appid", ""),
				"#required"		=> true,
			),
			"ebayapi_certid"	=> array(
				"#title"		=> t("Cert ID"),
				"#type"			=> "textfield",
				"#default_value"=> variable_get("ebayapi_certid", ""),
				"#required"		=> true,
			),
		),
		"tradingapi"	=> array(
			"#type"			=> "fieldset",
			"#title"		=> t("Trading API"),
			"#collapsible"	=> true,
			"#collapsed"	=> true,
			"ebayapi_trading_version"	=> array(
				"#title"		=> t("Version"),
				"#type"			=> "textfield",
				"#size"			=> 4,
				"#default_value"=> variable_get("ebayapi_trading_version", ""),
				"#required"		=> true,
			),
			"ebayapi_trading_url"		=> array(
				"#title"		=> t("URL"),
				"#type"			=> "textfield",
				"#default_value"=> variable_get("ebayapi_trading_url", ""),
				"#required"		=> true,
			),
		),
		"shoppingapi"	=> array(
			"#type"			=> "fieldset",
			"#title"		=> t("Shopping API"),
			"#collapsible"	=> true,
			"#collapsed"	=> true,
			"ebayapi_shopping_version"	=> array(
				"#title"		=> t("Version"),
				"#type"			=> "textfield",
				"#size"			=> 4,
				"#default_value"=> variable_get("ebayapi_shopping_version", ""),
				"#required"		=> true,
			),
			"ebayapi_shopping_url"		=> array(
				"#title"		=> t("URL"),
				"#type"			=> "textfield",
				"#default_value"=> variable_get("ebayapi_shopping_url", ""),
				"#required"		=> true,
			),
		),
		"findingapi"	=> array(
			"#type"			=> "fieldset",
			"#title"		=> t("Finding API"),
			"#collapsible"	=> true,
			"#collapsed"	=> true,
			"ebayapi_finding_version"	=> array(
				"#title"		=> t("Version"),
				"#type"			=> "textfield",
				"#size"			=> 4,
				"#default_value"=> variable_get("ebayapi_finding_version", ""),
				"#required"		=> true,
			),
			"ebayapi_finding_url"		=> array(
				"#title"		=> t("URL"),
				"#description"	=> t("Include the version at the end of the URL"),
				"#type"			=> "textfield",
				"#default_value"=> variable_get("ebayapi_finding_url", ""),
				"#required"		=> true,
			),
		),
	));
}

function ebay_admin_validate($form, &$state)
{
	if (!is_numeric($state['values']['ebayapi_entriesperpage']))
	{
		form_set_error("ebayapi_entriesperpage", t("Entries Per Page must be numeric"));
	}
}
?>