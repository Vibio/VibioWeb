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
		"product_local_search"		=> array(
			"#type"			=> "checkbox",
			"#title"		=> t("Local Search Enabled"),
			"#description"	=> t("Whether or not to try and grab information from the local product catalog."),
			"#default_value"=> variable_get("product_local_search", false),
		),
		"product_append_external"	=> array(
			"#type"			=> "checkbox",
			"#title"		=> t("Append External Search Results"),
			"#description"	=> t("Show external search results in addition to local search results when searching local products"),
			"#default_value"=> variable_get("product_append_external", false),
		),
		"product_source"			=> array(
			"#type"			=> "select",
			"#title"		=> t("Source"),
			"#description"	=> t("The source of external product information when local information is either not available, or searching locally is disabled."),
			"#options"		=> $source_options,
			"#default_value"=> variable_get("product_source", false),
		),
	));
}

function product_ajax_add_form($state, $product)
{
	global $user;
	
	$form = array(
    //Form submission function is product_ajax_add_complete() in product.pages
		"#action" => url("product/ajax/inventory-add/save"),
		"nid"	=> array(
			"#type"	=> "hidden",
			"#value"=> $product->nid,
		),
	);
	
	if (module_exists("collection"))
	{
		module_load_include("inc", "collection");
		$default = collection_get_user_default($user->uid, true);
		
		$form['collections'] = array(
			"#type"			=> "select",
			"#title"		=> t("Collection(s)"),
			"#multiple"		=> true,
			"#options"		=> collection_options(),
			"#default_value"=> $default,
		);
	}
	
	if (module_exists("offer2buy"))
	{
		$form['posting_type'] = array(
			"#type"			=> "select",
			"#title"		=> t("For Sale?"),
			"#options"		=> array(
				VIBIO_ITEM_TYPE_SELL	=> t("Yes"),
				VIBIO_ITEM_TYPE_OWN		=> t("No"),
			),
		);
		
		$form['node_price'] = array(
			"#type"		=> "textfield",
			"#title"	=> t("Price"),
			"#size"		=> 10,
			"#prefix"	=> "<div class='inventory_add_price'>",
			"#suffix"	=> "</div>",
		);
	}
	
	$form['body'] = array(
		"#type"		=> "textarea",
		"#title"	=> t("Description"),
		"#rows"		=> 5,
		"#cols"		=> 50,
	);
	
	$form['privacy'] = array(
		"#title"		=> t("Privacy Settings"),
		"#type"			=> "select",
		"#options"		=> _privacy_options(),
		"#default_value"=> privacy_user_get($user->uid, "account_setting", "item_default"),
	);
	
	$form['submit'] = array(
		"#type"	=> "submit",
		"#value"=> t("Add Item"),
	);
	
	return $form;
}
?>
