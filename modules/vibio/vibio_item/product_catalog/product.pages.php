<?php
/* function product_ajax_add()
 *  This is coming from the Have/Want/Like buttons, 
 *  with POST variables $_POST['nid'] and 'possess'
 *  set in javascript
 */
function product_ajax_add() {
	// Possess-already is poorly thought through as we move from "have"
	//  to multiple options.  Should it say you already own something when you 
	//  now say you want it?  Is this verb defined by how you already possess
	//  the item, or what you are trying to do now? (UX: Can you want and item
	//  you already have?)
	$posess = 'have';  // key-word, do not change
	$possess_already = 'own';
	if ($_POST['possess'] == 'want') {
		$possess = 'want';
		$possess_already = 'want'; // expressed interest?
  }
	
if(!($product=node_load($_POST['nid']))) {
exit(t("Invalid product"));
} elseif(product_user_owns_product($product->nid)) {
exit(t("You already $possess_already this item!"));
}
module_load_include("php","product","product.forms");
module_load_include("inc","product");

$image= _product_get_image($product->nid,true);
// Ugly hack to fix Have popup image
//   Wait till get Ian approved measures for imagecache and then fix.
// Something is weird with the above function.  I'm surprised this
//  is the only thing breaking.  It needs to be looked at (this hack,
//  that function.)
//This was a quick-fix patch that can be erased in Dec11... fixed in overrides_file_url_alter instead of here:  $image = "/sites/default/files/uploads/" . $image; ... NOPE.  That fix breaks other things.  Here's the hack.  Imagecache
// this soon.
//   FIX THIS, why isn't it imagecache?  Hopefully the filesystem
//    cleanup now means this is an easy fix?
if(strpos($image, 'sites/default/files') == false){
	$image = "/sites/default/files/uploads/" . $image;
}
if ($_POST['possess'] == 'want') {
	$top_text = "  <div id='inventory_top'><span class='bold-text'>So you want this item, and think it expresses your unique sense of style?</span><br />Vibio lets you curate your favorite items into your Collections while you wait for birthdays, paychecks or miracles to turn your wants into haves.</div>
	";
} else {
	$top_text = "  <div id='inventory_top'><span class='bold-text'>So you own this item and want to add it to your Collections?</span><br />Vibio is for people who possess a unique sense of style so make it good!</div>
	";
}
$out ="<div class='inventory_add_image'><img src='$image' /></div>
    <div class='inventory_title'>{$product->title}</div>
  ";
$out.=drupal_get_form("product_ajax_add_form",$product, $possess);
exit($out);
}

// This is used for "Have" of Amazon items, after submitting from a popup.  
//Add a note here if it's used for regular/other adds.
function product_ajax_add_complete() {
	global $user;
	$p=$_POST;
	if(product_user_owns_product($p['nid'],$user->uid)) {
		exit(t("You already own this product"));
	} elseif(!($product=node_load($p['nid']))) {
		exit(t("Invalid product"));
	}
	module_load_include("inc","node","node.pages");
	$form_id="vibio_item_node_form";
	$node=new stdClass;
	$node->uid=$user->uid;
	$node->name=$user->name;
	$node->type="vibio_item";
	$node->product_nid=$product->nid;
	$state['values']=array("title"=>$product->title,"body"=>$p['body'],"name"=>$user->name,"op"=>t("Save"),"field_posting_type"=> array( array("value"=>$p['posting_type'],)),"privacy_setting"=>$p['privacy']);
	if(module_exists("offer2buy")&&$p['posting_type']==VIBIO_ITEM_TYPE_SELL) {
		$state['values']['o2b_price']=$p['node_price'];
		$state['values']['o2b_is_negotiable']=false;
		$state['values']['o2b_allow_offer_views']=true;
	}
/*print '<pre>';
print_r($state);
die("here");*/
	// Possession setting
	//$state['values']['field_have_want_like'] = array(array("value"=>30));
	// How do we figure out what this should be formatted as?
	//field_have_want_like[value]
	$state['values']['field_have_want_like'][0]['value'] = 30; 
	$state['values']['field_have_want_like'] = 20;
	$state['values']['field_have_want_like']['value'] = 30;
	$state['values']['have_want_like'][0]['value'] = 30; 
	$state['values']['have_want_like'] = 20;
	$state['values']['have_want_like']['value'] = 30;



	/* Here's v1.0 collections stuff */

 /* kill this old stuff later ... */
	if(module_exists("collection")) {
	$cids=array();
	foreach($p['collections'] as $cid) {
	$cids[$cid]=$cid;
	}
	if(empty($cids)) {
	module_load_include("inc","collection");
	$default=collection_get_user_default($user->uid,true);
	$cids[$default]=$default;
	}
	$state['values']['collection_info']['cid']=$cids;
	}
	


	node_object_prepare($node);
	drupal_execute($form_id,$state,$node);

	// Deal with problems --> test this!!!
	$messages=drupal_get_messages();
	$item_nid=$state['nid'];
	$t_args=array("!title"=>l($product->title,"node/{$item_nid}"),"!view_link"=>l(t("View the item"),"node/{$item_nid}"),"!close_link"=>l(t("close this window"),"",array("attributes"=> array("class"=>"vibio_dialog_close_link"))),  // being mostly, not completely,deprecated
	);

	if (!$state['nid']) {
		exit(t("There was an error adding the item to your inventory. Please try again later. !close_link",$t_args));
	}


	/* COLLECTIONS Integration? */
	//above: $p=$_POST;   
	//and: $p[collections] is the old collections
	//and: $p[collection] is the new.  It's a number
	if ( $p['collection'] == 0 ) {

		/* Now go create collection ... this should be an if */
		/* This, in accordance with the current architecture, is not rock-solid:
			 A user is allowed to not put an item in a collection, which we should
			 be prepared to deal with later on -> they can shut the browser
			 after have'ing an item before finishing the collection */
		/* We'll need to hide the nid of the item, pass it along, and deal with
			 it when the collection is saved. */

		// advice I haven't read, but maybe it's better to create the collection
		//  node now and let the user edit it instead of passing a hidden?
		//  Though maybe better to have no Collection than a "didn't bother to type
		//  a title" collection  
		// drupal.org/node/464906
		// or
		// http://api.drupal.org/api/drupal/includes--form.inc/function/drupal_get_form/6
		module_load_include('inc', 'node', 'node.pages'); 
			// maybe add current users info
		global $user;
		// create a blank node
		$node_collection = array(
			'uid' => $user->uid,
			'name' => (isset($user->name) ? $user->name : ''),
			'type' => 'collection',
		);
		// Invoke hook_nodapi and hook_node
		node_object_prepare($node_collection);
	ob_start();
	print_r($_POST);
	$printr = ob_get_contents();
	ob_end_clean();

	// does it matter that there's a post in the form?
	//  $_POST    [form_id] => product_ajax_add_form
$_POST['form_id'] = 'collection_add_form';
		$out = "Now add your collection  <p>Later I'll put a hidden field here for " . 
	$state['nid'] .
"<h3>heres the node</h3>" . $printr .
"<h3>form state</h3>" . $_SESSION['batch_form_state'] .
"<h3>and the post</h3>" . $_POST .
			drupal_get_form("collection_node_form", $node_collection);
		exit($out);
// would it be better to use: drupal_prepare_form

		// it seems to go here: http://localhost:8888/product/ajax/inventory-add/save
		// returns "Invalid product" on next page
	}

	/* MESSAGES, just messages no action, to do if no collection found*/
	if($item_nid) {
	if(!$cids) {
	//exit(t("\"!title\" has been added to your inventory! You can !view_link or !close_link", $t_args));
	exit(t("\"!title\" has been added to your inventory! !view_link",$t_args));
	}
	$collection_names=array();
	foreach($cids as $cid) {
	$collection=collection_load($cid);
	$collection_names[]=l($collection['title'],"collections/{$collection['cid']}");
	}
	if(count($cids)>1) {
	$key=count($cids)-1;
	$collection_names[$key]="and {$collection_names[$key]}";
	$t_args['!collection']=implode(", ",$collection_names);
	exit(t('<div class="congrats-popup"><h2>Congratulations</h2>"!title" has been added to your !collection collections. !view_link </div>',$t_args));
	}
	$t_args['!collection']=implode(", ",$collection_names);
	exit(t('<div class="congrats-popup"><h2>Congratulations</h2> "!title" has been added to your !collection collection. !view_link </div>',$t_args));
	}
	exit(t("There was an error adding the item to your inventory. Please try again later. !close_link",$t_args));
}

// This is called by the normal product/add form.  Is it also used 
//  by the Have popups?
function product_add_to_inventory($product,$quick_add=false) {
	global $user;
	if($item_id=product_user_owns_product($product->nid,$user->uid)) {
		drupal_set_message(t("You already own this item!"));
		drupal_goto("node/{$item_id}");
	}
	module_load_include("inc","node","node.pages");
	$form_id="vibio_item_node_form";
	$node=new stdClass;
	$node->uid=$user->uid;
	$node->name=$user->name;
	$node->type="vibio_item";
	$node->product_nid=$product->nid;
	node_object_prepare($node);
	if($quick_add) {
		$state['values']=array("title"=>$product->title,"name"=>$user->name,"op"=>t("Save"),"field_posting_type"=> array( array("value"=>VIBIO_ITEM_TYPE_OWN,),),);
		if($product->collection_info&&module_exists("collection")) {
			$state['values']['collection_info']['cid']=$product->collection_info['cid'];
		}
		// new_collection -> we'll have to something with the new collections
		//  similar to how collection_info is set.

		if($product->privacy_setting&&module_exists("privacy")) {
			$state['values']['privacy_setting']=$product->privacy_setting;
		}
		$state['values']=array_merge_recursive($state['values'],module_invoke_all("product_inventory_quick_add",$state['values']));
		//dsm($form_id,$state,$node);
print '<pre>';
print_r($state);

		drupal_execute($form_id,$state,$node);   
			// in v1.0, this outraced and ruined
			// file uploading

		if($nid=$state['nid']) {
		drupal_goto("node/$nid");
		} else {
		drupal_set_message(t("There was an error with quick add. Please fill out the form to add !product to your inventory",array("!product"=>$product->title)),"error");
		}
	}
	$output=theme("node",$product);
	$output.=drupal_get_form($form_id,$node);
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
module_load_include("inc","node","node.pages");
$form_id="product_node_form";
$node=new stdClass;
$node->uid=$user->uid;
$node->name=$user->name;
$node->type="product";
node_object_prepare($node);   // no nid yet
// v2: make less weird, move help into form, not message
drupal_set_message(t("Here’s where you get to write down stuff about your product. Please share descriptive things, like the size, fabric content or whatever you think will tell us exactly what you’ve got."),"notice");
return drupal_get_form($form_id,$node);
}
function _product_get_owners_page() {
global $user;
$p=$_POST;
if(!isset($p['product'])||!isset($p['type'])) {
return;
}
// This looks very similar to node-product.tpl
module_load_include("inc","product");
$data=_product_get_owners($p['product'],$user->uid,$p['type'],$p['page']);
$output=theme("product_owners",$p['type'],$data);
if($p['ajax']) {
exit($output);
}
return $output;
}
?>
