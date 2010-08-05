<?php
function product_admin(&$state)
{
	/*
	 The module implementations of this hook should return an array in the form:
		module_name => display
	 Products will look for a function using this module_name when searching for external products.
	*/
	$source_options = module_invoke_all("product_source");
	
	return system_settings_form(array(
		"product_local_search"	=> array(
			"#type"			=> "checkbox",
			"#title"		=> t("Local Search Enabled"),
			"#description"	=> t("Whether or not to try and grab information from the local product catalog."),
			"#default_value"=> variable_get("product_local_search", false),
		),
		"product_source"		=> array(
			"#type"			=> "select",
			"#title"		=> t("Source"),
			"#description"	=> t("The source of external product information when local information is either not available, or searching locally is disabled."),
			"#options"		=> $source_options,
			"#default_value"=> variable_get("product_source", false),
		),
	));
}
?>