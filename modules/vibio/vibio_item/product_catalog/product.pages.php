<?php
function product_add_to_inventory($product, $quick_add=false)
{
	global $user;
	
	if ($item_id = product_user_owns_product($product->nid, $user->uid))
	{
		drupal_set_message(t("You already own this item!"));
		drupal_goto("node/{$item_id}");
	}
	
	module_load_include("inc", "node", "node.pages");
	
	$form_id = "vibio_item_node_form";
	$node = new stdClass;
	$node->uid = $user->uid;
	$node->name = $user->name;
	$node->type = "vibio_item";
	$node->product_nid = $product->nid;
	
	node_object_prepare($node);
	
	if ($quick_add)
	{
		$state['values'] = array(
			"title"	=> $product->title,
			"name"	=> $user->name,
			"op"	=> t("Save"),
			"field_posting_type"	=> array(
				array(
					"value"	=> VIBIO_ITEM_TYPE_OWN,
				),
			),
			"privacy_setting"	=> PRIVACY_ONLYME,
		);
		
		drupal_execute($form_id, $state, $node);
		
		if ($nid = $state['nid'])
		{
			drupal_goto("node/$nid");
		}
		else
		{
			drupal_set_message(t("There was an error with quick add. Please fill out the form to add !product to your inventory", array("!product" => $product->title)), "error");
		}
	}
	
	$output = theme("node", $product);
	$output .= drupal_get_form($form_id, $node);
	
	return $output;
}

function product_add_new()
{
	global $user;
	
	module_load_include("inc", "node", "node.pages");
	
	$form_id = "product_node_form";
	$node = new stdClass;
	$node->uid = $user->uid;
	$node->name = $user->name;
	$node->type = "product";
	
	node_object_prepare($node);
	
	return drupal_get_form($form_id, $node);
}

function _product_get_owners_page()
{
	global $user;
	$p = $_POST;
	
	if (!isset($p['product']) || !isset($p['type']))
	{
		return;
	}
	
	module_load_include("inc", "product");
	$data = _product_get_owners($p['product'], $user->uid, $p['type'], $p['page']);
	$output = theme("product_owners", $p['type'], $data);
	
	if ($p['ajax'])
	{
		exit($output);
	}
	
	return $output;
}
?>