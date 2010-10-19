<?php
function product_ajax_add()
{
	if (!($product = node_load($_POST['nid'])))
	{
		exit(t("Invalid product"));
	}
	elseif (product_user_owns_product($product->nid))
	{
		exit(t("You already own this item!"));
	}
	
	module_load_include("php", "product", "product.forms");
	module_load_include("inc", "product");
	$image = _product_get_image($product->nid, true);
	
	$out = "
		<table class='inventory_add_pinfo'><tr>
			<td class='inventory_add_image'>
				<img src='$image' />
			</td>
			<td>
				{$product->title}
			</td>
		</tr></table>
	";
	$out .= drupal_get_form("product_ajax_add_form", $product);
	
	exit($out);
}

function product_ajax_add_complete()
{
	global $user;
	$p = $_POST;
	
	if (product_user_owns_product($p['nid'], $user->uid))
	{
		exit(t("You already own this product"));
	}
	elseif (!($product = node_load($p['nid'])))
	{
		exit(t("Invalid product"));
	}
	
	module_load_include("inc", "node", "node.pages");
	
	$form_id = "vibio_item_node_form";
	$node = new stdClass;
	$node->uid = $user->uid;
	$node->name = $user->name;
	$node->type = "vibio_item";
	$node->product_nid = $product->nid;
	
	$state['values'] = array(
		"title"				=> $product->title,
		"body"				=> $p['body'],
		"name"				=> $user->name,
		"op"				=> t("Save"),
		"field_posting_type"=> array(array(
			"value"	=> $p['posting_type'],
		)),
		"privacy_setting"	=> $p['privacy'],
	);
	
	if (module_exists("offer2buy") && $p['posting_type'] == VIBIO_ITEM_TYPE_SELL)
	{
		$state['values']['o2b_price'] = $p['node_price'];
		$state['values']['o2b_is_negotiable'] = false;
		$state['values']['o2b_allow_offer_views'] = true;
	}
	
	if (module_exists("collection"))
	{
		$cids = array();
		foreach ($p['collections'] as $cid)
		{
			$cids[$cid] = $cid;
		}
		if (empty($cids))
		{
			module_load_include("inc", "collection");
			$default = collection_get_user_default($user->uid, true);
			$cids[$default] = $default;
		}
		
		$state['values']['collection_info']['cid'] = $cids;
	}
	
	node_object_prepare($node);
	drupal_execute($form_id, $state, $node);
	$messages = drupal_get_messages();
	
	$item_nid = $state['nid'];
	$t_args = array(
		"!title"		=> l($product->title, "node/{$item_nid}"),
		"!view_link"	=> l(t("view the item"), "node/{$item_nid}"),
		"!close_link"	=> l(t("close this window"), "", array("attributes" => array("class" => "vibio_dialog_close_link"))),
		
	);
	
	if ($item_nid)
	{
		if (!$cids)
		{
			exit(t("\"!title\" has been added to your inventory! You can !view_link or !close_link", $t_args));
		}
		
		$collection_names = array();
		foreach ($cids as $cid)
		{
			$collection = collection_load($cid);
			$collection_names[] = l($collection['title'], "collections/{$collection['cid']}");
		}
		
		if (count($cids) > 1)
		{
			$key = count($cids) - 1;
			$collection_names[$key] = "and {$collection_names[$key]}";
			$t_args['!collection'] = implode(", ", $collection_names);
			exit(t('"!title" has been added to your !collection collections. You can !view_link or !close_link', $t_args));
		}
		
		$t_args['!collection'] = implode(", ", $collection_names);
		exit(t('"!title" has been created and added to your !collection collection. You can !view_link or !close_link', $t_args));
	}
	
	exit(t("There was an error adding the item to your inventory. Please try again later. !close_link", $t_args));
}

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
		);
		
		if ($product->collection_info && module_exists("collection"))
		{
			$state['values']['collection_info']['cid'] = $product->collection_info['cid'];
		}
		
		$state['values'] = array_merge_recursive($state['values'], module_invoke_all("product_inventory_quick_add", $state['values']));
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
	
	product_set_autoadd();
	module_load_include("inc", "node", "node.pages");
	
	$form_id = "product_node_form";
	$node = new stdClass;
	$node->uid = $user->uid;
	$node->name = $user->name;
	$node->type = "product";
	
	node_object_prepare($node);
	
	drupal_set_message(t("This is a generic product, with generic info all users will be able to edit and see. Info that should go here might be a book's ISBN, a DVDs runtime, etc. It's definitive information about the product."), "notice");
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
