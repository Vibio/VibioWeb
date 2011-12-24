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

/* function product_ajax_add_form($state, $product, $possess)
  This is a somewhat dizzying form: when you want to add an item,
   instead you call this function to add a product, then add all the pieces
   to add an item instead.
  possess = have, want, like
*/
function product_ajax_add_form($state, $product, $possess)
{
	global $user;
	
	$form = array(
		"#action" => url("product/ajax/inventory-add/save"),
		"nid"	=> array(
			"#type"	=> "hidden",
			"#value"=> $product->nid,
		),
	);
	
	// Add Possession value
 	switch($possess) {
		case 'like':
			$possess_int = 30;	
			break;
		case 'want':
			$possess_int = 20;
			break;
		default:
			$possess_int = 10;
	}

	$form['field_have_want_like'] = array(
		"#title"		=> t("$possess ... Have it, Want it, Like it?"),
		"#type"			=> "select",
		"#options" => array(
			10 => "Have",
			20 => "Want",
			30 => "Like"
		),
		"#default_value"=> $possess_int,
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
		"#default_value"=> privacy_get($user->uid, "account_setting", "item_default"),
	);

	// Figure out how to get default collections
	// new_collection.  Have to create form field by hand.  Everything else
	//  in this odd form is blank or super-custom.
	// We're getting an item via the $product.

// $edit[$field->name], -> I see this in demos, but where could #edit come from
	// Can I load a blank node?  Or see how cck module does it?

	// If change "Create new collection" replace every instance in code
	$form['collection'] = array(
		"#title"		=> t("Choose Collection"),
// Oh, please just use defaults and keep things simpler...
		"#type"			=> "select",
		"#options" => array("Create new collection", "test", "books", "more", "what if more than 5", "and another", "why is collections with height not this", "fix this crazy form grep this"),
//		"#options"		=> _privacy_options(),
//		"#default_value"=> privacy_get($user->uid, "account_setting", "item_default"),

// THIS CHANGES THE WAY THE FORM LOOKS, taller      "#multiple"   => true,

	);
	
	
	$form['submit'] = array(
		"#type"	=> "submit",
		"#value"=> t("Add Item"),
	);
	
	return $form;
}
?>
