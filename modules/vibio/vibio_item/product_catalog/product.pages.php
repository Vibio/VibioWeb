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
	//  the item, or what you are trying to do now? (UX: Can you also want an item
	//  you already have one of?)
	$posess = 'have';  // key-word, do not change
	$possess_already = 'own';
	if ($_POST['possess'] == 'want') {
		$possess = 'want';
		$possess_already = 'want'; // expressed interest?
  }
	
	if(!($product=node_load($_POST['nid']))) {
		exit(t("Invalid product number"));
	} elseif(product_user_owns_product($product->nid)) {
		exit(t("You already $possess_already this item!"));
	}
	module_load_include("php","product","product.forms");
	module_load_include("inc","product");

	$image = _product_get_image($product->nid,true);
// Ugly hack to fix Have popup image
//   Wait till get Ian approved measures for imagecache and then fix.
// Something is weird with the above function.  I'm surprised this
//  is the only thing breaking.  It needs to be looked at (this hack,
//  that function.)
//This was a quick-fix patch that can be erased in Dec11... fixed in overrides_file_url_alter instead of here:  $image = "/sites/default/files/uploads/" . $image; ... NOPE.  That fix breaks other things.  Here's the hack.  Imagecache
// this soon.
//   @ToDo, why isn't it imagecache?  Hopefully the filesystem
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
$out = $top_text . 
	"<div class='inventory_add_image'><img src='$image' /></div>
   <div class='inventory_title'>{$product->title}</div>
  ";

// Alter this, put collections downwards, with altered form.?
$out.=drupal_get_form("product_ajax_add_form",$product, $possess);
exit($out);
}

// This is used for "Have" of Amazon items, after submitting from a popup.  
//Add a note here if it's used for regular/other adds.
// It will also be used for Want and Likes
function product_ajax_add_complete() {
	global $user;
	$p=$_POST;
	if(product_user_owns_product($p['nid'],$user->uid)) {
		exit(t("You already own this product"));  // @ToDo -> think through 
			// what it means if they want or like it, don't own it.
	} elseif(!($product=node_load($p['nid']))) {
		exit(t("Invalid product".$p['nid']));
	}

  // Now, create an ITEM form amidst this product saving,
  //  and execute=save the item
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

	// have_want_like
	$state['values']['field_have_want_like'] =array( array("value"=>20));


  //@todo refactor so this is a submission function added through a form_alter
  //in collections
  //@todo refactor into a single select field; no multiple collection selection
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
	// Save the item.
	node_object_prepare($node);
	drupal_execute($form_id,$state,$node);

	// Deal with problems --> test this!!!
  $message_void = drupal_get_messages(); // this gets rid of messages ...
    // but if there is an error, then lets see them.  Will need clean-up
    // but this is better than nothing.

	$item_nid=$state['nid']; // is this right? or  "#value"=> $product->nid
		// I think this might be the product id, even when working?




	//@ToDo switch this over to state, move above execute
	//Ok, I can't get the state to work.  See "historical" copy of this file
	//  for many efforts.
	// Let's go back to my world
	//  to deal with the have_want_like button.  Terribly innefficient but not important...
	$node = node_load($item_nid);
	// make sure form not tampered, $p is an integer
	$possess = (int)$p[field_have_want_like];
	$node->field_have_want_like = Array
                (
                    '0' => Array
                        (
                            'value' => $possess
                        )

                );
//print_r(array("the node I just added possess value to" => $node));
	node_save($node);

	//@ToDo  we might want to render the possession level by building the node and rendering fields.
	// But I think we're likely to custom phrase it here anyway. 
	// So...
  // Add Possession value
  switch($possess) {
    case '30':
      $possess_words = ' as a product you like';
      break;
    case '20':
      $possess_words = ' as something you want';
      break;
    default:
      $possess_words = '';
  }
	



	$t_args=array("!title"=>l($product->title,"node/{$item_nid}"),"!view_link"=>l(t("View the item"),"node/{$item_nid}"),"!close_link"=>l(t("close this window"),"",array("attributes"=> array("class"=>"vibio_dialog_close_link"))),  // being mostly, not completely,deprecated
	);

  if ($item_nid) {
    if (!$cids) {
//exit(t("\"!title\" has been added to your inventory! You can !view_link or !close_link", $t_args));
      exit(t("\"!title\" has been added to your inventory$possess_words! !view_link", $t_args));
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
      exit(t('<div class="congrats-popup"><h2>Congratulations</h2>"!title" has been added to your !collection collections' . "$possess_words. !view_link </div>", $t_args));
    }
    $t_args['!collection'] = implode(", ", $collection_names);
    exit(t('<div class="congrats-popup"><h2>Congratulations</h2> "!title" has been added to your !collection collection' .  "$possess_words. <br/><br/>!view_link </div>", $t_args));
  }

  // Problems!  no $item_nid = $state['nid'];
  // @ToDo Improve the error messages
  if ($message_void['error']) {
    exit(t("There was an error adding the item to your inventory. <br/><br/>" .$message_void['error'][0] .  "<br/><br/>!close_link"   , $t_args));    // there could be more than one message, but this is a clean decent start
  } else {
    exit(t("There was an error adding the item to your inventory. Please try again later.</br/>" . print_r($message_void, true). "!close_link"   , $t_args));
  }
}




// This is called by the normal product/add form.  Is it also used 
//  by the Have popups?

/**
 * After a new product has been created, adds an item to the current user's
 * collection of that product type.
 * 
 * @global <type> $user
 * @param <type> $product
 * @param <type> $quick_add
 * @return <type>
 */
function product_add_to_inventory($product,$quick_add=false) {
	global $user;
  //If the user already has this type of product...
	if($item_id=product_user_owns_product($product->nid,$user->uid)) {
		//Go to the existing item page and display a message
		drupal_set_message(t("You already own this item!"));
		drupal_goto("node/{$item_id}");
	}
	//Populate basic item data
	module_load_include("inc","node","node.pages");
	$form_id="vibio_item_node_form";
	$node=new stdClass;
	$node->uid=$user->uid;
	$node->name=$user->name;
	$node->type="vibio_item";
	$node->product_nid=$product->nid;
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
