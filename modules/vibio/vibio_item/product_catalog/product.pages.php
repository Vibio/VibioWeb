<?php

function product_ajax_add() {
  if (!($product = node_load($_POST['nid']))) {
    exit(t("Invalid product"));
  } elseif (product_user_owns_product($product->nid)) {
    exit(t("You already own this item!"));
  }
  module_load_include("php", "product", "product.forms");
  module_load_include("inc", "product");

  $image = _product_get_image($product->nid, true);
// Ugly hack to fix Have popup image
//   Wait till get Ian approved measures for imagecache and then fix.
// Something is weird with the above function.  I'm surprised this
//  is the only thing breaking.  It needs to be looked at (this hack,
//  that function.)
//This was a quick-fix patch that can be erased in Dec11... fixed in overrides_file_url_alter instead of here:  $image = "/sites/default/files/uploads/" . $image; ... NOPE.  That fix breaks other things.  Here's the hack.  Imagecache
// this soon.
  if (strpos($image, 'sites/default/files') == false) {
    $image = "/sites/default/files/uploads/" . $image;
  }

  $out = "  <div id='inventory_top'><span class='bold-text'>So you own this item and want to add it to your Collections?</span><br />Vibio is for people who possess a unique sense of style so make it good!</div>
		<div class='inventory_add_image'><img src='$image' /></div>
		<div class='inventory_title'>{$product->title}</div>
	";
  $out.=drupal_get_form("product_ajax_add_form", $product);
  exit($out);
}

function product_ajax_add_complete() {
  global $user;
  $p = $_POST;
  if (product_user_owns_product($p['nid'], $user->uid)) {
    exit(t("You already own this product"));
  } elseif (!($product = node_load($p['nid']))) {
    exit(t("Invalid product"));
  }
  module_load_include("inc", "node", "node.pages");
  $form_id = "vibio_item_node_form";
  $node = new stdClass;
  $node->uid = $user->uid;
  $node->name = $user->name;
  $node->type = "vibio_item";
  $node->product_nid = $product->nid;
  $state['values'] = array("title" => $product->title, "body" => $p['body'], "name" => $user->name, "op" => t("Save"), "field_posting_type" => array(array("value" => $p['posting_type'],)), "privacy_setting" => $p['privacy'],);
  if (module_exists("offer2buy") && $p['posting_type'] == VIBIO_ITEM_TYPE_SELL) {
    $state['values']['o2b_price'] = $p['node_price'];
    $state['values']['o2b_is_negotiable'] = false;
    $state['values']['o2b_allow_offer_views'] = true;
  }
  //@todo refactor so this is a submission function added through a form_alter
  //in collections
  //@todo refactor into a single select field; no multiple collection selection
  if (module_exists("collection")) {
    $cids = array();
    foreach ($p['collections'] as $cid) {
      $cids[$cid] = $cid;
    }
    if (empty($cids)) {
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
  $t_args = array("!title" => l($product->title, "node/{$item_nid}"), "!view_link" => l(t("View the item"), "node/{$item_nid}"), "!close_link" => l(t("close this window"), "", array("attributes" => array("class" => "vibio_dialog_close_link"))), // being mostly, not completely,deprecated
  );
  if ($item_nid) {
    if (!$cids) {
//exit(t("\"!title\" has been added to your inventory! You can !view_link or !close_link", $t_args));
      exit(t("\"!title\" has been added to your inventory! !view_link", $t_args));
    }
    $collection_names = array();
    foreach ($cids as $cid) {
      $collection = collection_load($cid);
      $collection_names[] = l($collection->title, "collections/{$collection->nid}");
    }
    if (count($cids) > 1) {
      $key = count($cids) - 1;
      $collection_names[$key] = "and {$collection_names[$key]}";
      $t_args['!collection'] = implode(", ", $collection_names);
      exit(t('<div class="congrats-popup"><h2>Congratulations</h2>"!title" has been added to your !collection collections. !view_link </div>', $t_args));
    }
    $t_args['!collection'] = implode(", ", $collection_names);
    exit(t('<div class="congrats-popup"><h2>Congratulations</h2> "!title" has been added to your !collection collection. !view_link </div>', $t_args));
  }
  exit(t("There was an error adding the item to your inventory. Please try again later. !close_link", $t_args));
}

/**
 * After a new product has been created, adds an item to the current user's
 * collection of that product type.
 * 
 * @global <type> $user
 * @param <type> $product
 * @param <type> $quick_add
 * @return <type>
 */
function product_add_to_inventory($product, $quick_add=false) {
  global $user;
  //If the user already has this type of product...
  if ($item_id = product_user_owns_product($product->nid, $user->uid)) {
    //Go to the existing item page and display a message
    drupal_set_message(t("You already own this item!"));
    drupal_goto("node/{$item_id}");
  }
  //Populate basic item data
  module_load_include("inc", "node", "node.pages");
  $form_id = "vibio_item_node_form";
  $node = new stdClass;
  $node->uid = $user->uid;
  $node->name = $user->name;
  $node->type = "vibio_item";
  $node->product_nid = $product->nid;
  node_object_prepare($node);
  //If this is a quick_add...
  //Note: there was a bug where the add_item buttons where setting $quick_add to 1,
  //which did not parse to TRUE. Hense the !empty, which should work for numerical
  //and boolean values. @TODO: Find the error and replace with booleans.
  if (!empty($quick_add)) {
    //Prepopulate the item title and username with $product info
    $state['values'] = array("title" => $product->title, "name" => $user->name, 
        "op" => t("Save"), "field_posting_type" => array(array("value" => VIBIO_ITEM_TYPE_OWN,),),);
    //If there is collection info, add it
    print_r($product);
    //This formerly refered to $product->collection_info--Alec
    if ($_POST['collection_info'] && module_exists("collection")) {
      $state['values']['collection_info']['cid'] = $_POST['collection_info']['cid'];
    }
    //If there is privacy info, add it
    if ($product->privacy_setting && module_exists("privacy")) {
      $state['values']['privacy_setting'] = $product->privacy_setting;
    }
    $state['values'] = array_merge_recursive($state['values'], module_invoke_all("product_inventory_quick_add", $state['values']));
    //dsm($form_id,$state,$node);

    drupal_execute($form_id, $state, $node);
    // in v1.0, this outraced and ruined
    // file uploading

    if ($nid = $state['nid']) {
      drupal_goto("node/$nid");
    } else {
      drupal_set_message(t("There was an error with quick add. Please fill out the form to add !product to your inventory", array("!product" => $product->title)), "error");
    }
  }
  $output = theme("node", $product);
  $output.=drupal_get_form($form_id, $node);
  return $output;
}

/* Stephen wonders: what happens here?  It looks like a totally normal
 *  node add page, except it has a message set to act as a kind of help.
 *  Should all this be ripped out?
 *
 *  How important is product_set_autoadd()
 *  ... That determines whether this is a search, or a person adding their
 *       own product in which case they'll have an item of that product type.
 *       It is important.
 */

function product_add_new() {
  global $user;
  product_set_autoadd();
  module_load_include("inc", "node", "node.pages");
  $form_id = "product_node_form";
  $node = new stdClass;
  $node->uid = $user->uid;
  $node->name = $user->name;
  $node->type = "product";
  node_object_prepare($node);   // no nid yet
// v2: make less weird, move help into form, not message
  drupal_set_message(t("Here’s where you get to write down stuff about your product. Please share descriptive things, like the size, fabric content or whatever you think will tell us exactly what you’ve got."), "notice");
  return drupal_get_form($form_id, $node);
}

function _product_get_owners_page() {
  global $user;
  $p = $_POST;
  if (!isset($p['product']) || !isset($p['type'])) {
    return;
  }
// This looks very similar to node-product.tpl
  module_load_include("inc", "product");
  $data = _product_get_owners($p['product'], $user->uid, $p['type'], $p['page']);
  $output = theme("product_owners", $p['type'], $data);
  if ($p['ajax']) {
    exit($output);
  }
  return $output;
}

?>
