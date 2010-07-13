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
	));
}
?>