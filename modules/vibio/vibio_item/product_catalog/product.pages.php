<?php
function product_add_to_inventory($product)
{
	global $user;
	module_load_include("inc", "node", "node.pages");
	
	$form_id = "vibio_item_node_form";
	$node = new stdClass;
	$node->uid = $user->uid;
	$node->name = $user->name;
	$node->type = "vibio_item";
	$node->product_nid = $product->nid;
	
	node_object_prepare($node);
	
	$output = theme("node", $product);
	$output .= drupal_get_form($form_id, $node);
	
	return $output;
}
?>