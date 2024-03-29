<?php
define("PRODUCT_SEARCH_RESULTS_PER_PAGE", 40);
define("PRODUCT_DETAIL_SNIPPET_LENGTH", 256);
// PRODUCT_SEARCH_RESULTS_PER_PAGE does this do anything?  
// Seems to be it's all done in search.module, hard-coded
function product_perm()
{
	return array(
		"product admin",
	);
}

function product_views_api()
{
	return array(
		"api"	=> 2,
	);
}

function product_menu()
{
	return array(
		"admin/settings/product-catalog"	=> array(
			"title"				=> "Product Catalog",
			"description"		=> "Change settings related to the product catalog",
			"page callback"		=> "drupal_get_form",
			"page arguments"	=> array("product_admin"),
			"access arguments"	=> array("product admin"),
			"file"				=> "product.forms.php",
		),
		"product/%node/add-to-inventory"	=> array(
			"title callback"	=> "_product_inventory_add_title",
			"title arguments"	=> array(1),
			"page callback"		=> "product_add_to_inventory",
			"page arguments"	=> array(1),
			"access arguments"	=> array("create vibio_item content"),
			"file"				=> "product.pages.php",
			"type"				=> MENU_CALLBACK,
		),
		"product/%node/add-to-inventory/quick"	=> array(
			"title callback"	=> "_product_inventory_add_title",
			"title arguments"	=> array(1),
			"page callback"		=> "product_add_to_inventory",
			"page arguments"	=> array(1, true),
			"access arguments"	=> array("create vibio_item content"),
			"file"				=> "product.pages.php",
			"type"				=> MENU_CALLBACK,
		),
	
		/* stephen thinks this should be removed.  It merely sets a message
     * that looks kindof silly as a "message" instead of just part of the 
     * page.  
			 And sets to true, what is this: $_SESSION['product']['auto_add'] = $val;
       Alec->According to Stephen notes in product.pages.php, this variable
     * indicates whether to create a new item along with the new product.
     */
		"product/add"			=> array(
			"title"				=> "Add New Product",
			"page callback"		=> "product_add_new",
			"access arguments"	=> array("create product content"),
			"file"				=> "product.pages.php",
			"type"				=> MENU_CALLBACK,
		),
		"product/get-owners"				=> array(
			"page callback"		=> "_product_get_owners_page",
			"access arguments"	=> array("access content"),
			"file"				=> "product.pages.php",
			"type"				=> MENU_CALLBACK,
		),
		"product/ajax/inventory-add" => array(
			"page callback"		=> "product_ajax_add",
			"access arguments"	=> array("create vibio_item content"),
			"file"				=> "product.pages.php",
			"type"				=> MENU_CALLBACK,
		),
		"product/ajax/inventory-add/save" => array(
			"page callback"		=> "product_ajax_add_complete",
			"access arguments"	=> array("create vibio_item content"),
			"file"				=> "product.pages.php",
			"type"				=> MENU_CALLBACK,
		),
	);
}

function product_external_search($args)
{	
	if (!($provider = variable_get("product_source", false)) || !function_exists("{$provider}_product_search"))
	{
		return false;
	}
	if (!is_array($keys))
	{
		$args = array("keywords"	=> $args);
	}
	
	module_load_include("inc", "product");
	product_set_external_search(true, true);
	
	$func = "{$provider}_product_search";
	$args['keywords'] = _product_remove_options($args['keywords']);
// example $func = vibio_amazon_product_search
	return $func($args);
}

function product_external_search_modify_search_query(&$sql)
{
	if (!($provider = variable_get("product_source", false)) || !function_exists("{$provider}_product_search_modify_search_query"))
	{
		return false;
	}
	
	$func = "{$provider}_product_search_modify_search_query";
	$func($sql);
}



function product_nodeapi(&$node, $op, $a3=null, $a4=null)
{
	if ($node->type == "vibio_item")
	{
		switch ($op)
		{
			case "insert":
				if (isset($node->product_product_nid))
				{
					$sql = "REPLACE INTO {product_items}
							SET `product_nid`=%d, `item_nid`=%d";
					db_query($sql, $node->product_product_nid, $node->nid);
				}
				break;
			case "delete":
				$sql = "DELETE FROM {product_items}
						WHERE `item_nid`=%d";
				db_query($sql, $node->nid);
				break;
			case "view":
				$sql = "SELECT `product_nid`
						FROM {product_items}
						WHERE `item_nid`=%d";
				$node->product_nid = db_result(db_query($sql, $node->nid));
			default:
				break;
		}
	}

	if ($node->type == "product")
	{
		switch ($op)
		{
			case "insert":
				if (product_get_autoadd(false))    // false means leave autoadd on.
				{
					module_load_include("php", "product", "product.pages");
					drupal_get_messages(); // ooh, sneaky. Get the messages, 
						// but don't print them, so they go away quietly. 
						//  $clear_queue defaults to TRUE.  Would be nice if there
						//  was a testing mode for admins. TODO

					// PREVIOUSLY
					// We add the product-node we are about to insert [but haven't yet]
					//	as an item-node.  We drupal_execute($form_id... 
					//  and executing the item's form fubars the product before we've saved it.
					// See:  product_node_form_submit_wrapper

					// Not much left here, can this all move to the _wrapper?
				}
				break;
			case "update index":
				return theme("product_display", $node);
			case 'search result':
				module_load_include("inc", "product");
				
				return array(
					"image"	=> _product_get_image($node->nid, true),
				);
		}
	}
}

function product_theme()
{
	return array(
		"product_add_product_link"	=> array(
			"arguments"	=> array(),
			"template"	=> "templates/product/add-product-link",
			"path"		=> drupal_get_path("theme", "vibio"),
		),
		"product_amazon_display"	=> array(
			"arguments"	=> array("node"	=> false, "page" => false),
			"template"	=> "templates/product/amazon-display",
			"path"		=> drupal_get_path("theme", "vibio"),
		),
		"product_display"	=> array(
			"arguments"	=> array("node"	=> false, "page" => false),
			"template"	=> "templates/product/display",
			"path"		=> drupal_get_path("theme", "vibio"),
		),
		"product_owners"	=> array(
			"arguments"	=> array("type"	=> "network", "data" => array()),
			"template"	=> "templates/product/owners",
			"path"		=> drupal_get_path("theme", "vibio"),
		),
		"product_owner"		=> array(
			"arguments"	=> array("item"	=> array()),
			"template"	=> "templates/product/owner",
			"path"		=> drupal_get_path("theme", "vibio"),
		),
		// Create the have link/button, that's all.  Now variants :-(
		"product_inventory_add"	=> array(
			"arguments"	=> array("nid"	=> false, "searchcrumb" => "", $variant => "addtest"),
			"template"	=> "templates/product/inventory-add",
			"path"		=> drupal_get_path("theme", "vibio"),
		),
		// Create the want link/button, that's all.
		/* v1.0: it was very different from HAVE button 
		"product_inventory_want"=> array(
			"arguments"	=> array("nid"	=> false),
			"template"	=> "templates/product/inventory-want",
			"path"		=> drupal_get_path("theme", "vibio"),
		),*/
		// v1.4: WANT button now like HAVE button
    "product_inventory_want"  => array(
      "arguments" => array("nid"  => false, "searchcrumb" => "", $variant => "addtest"),
      "template"  => "templates/product/inventory-want",
      "path"    => drupal_get_path("theme", "vibio"),
    ),
		"product_inventory_manage_link"	=> array(
			"arguments"	=> array("product"	=> false, "searchcrumb" => ""), //, "variant" => "test"),
			"template"	=> "templates/product/inventory-manage-link",
			"path"		=> drupal_get_path("theme", "vibio"),
		),
	);
}

function product_preprocess_product_amazon_display(&$vars)
{
	drupal_add_css("themes/vibio/css/product.css");
}

function product_preprocess_product_display(&$vars)
{
	drupal_add_css("themes/vibio/css/product.css");
}

function product_preprocess_node(&$vars)
{
	if (in_array($vars['node']->type, array("product", "vibio_item")))
	{
		module_load_include("inc", "product");
	}
}

// Make an item have the same title as the parent Product
function product_form_vibio_item_node_form_alter(&$form, &$state)
{
	$product = node_load($form['#node']->product_nid);
	
	if (!$product)
	{
		return;
	}
	
	$form['title']['#default_value'] = $product->title;
	$form['product_product_nid'] = array(
		"#type"	=> "value",
		"#value"=> $form['#node']->product_nid,
	);
}

// This should run during a normal node/add, any other time?
function product_form_alter(&$form, &$state, $id)
{
	// New Item/Product via: /product/add
	if ($id == "product_node_form")
	{
		 // Fiddles with the form, unset then set some components
		_vibio_item_unset($form, "product");
		_vibio_item_defaults($form, "product");

		// Replace node_form_submit with our similar wrapper
		$form['buttons']['submit']['#submit'] = array('product_node_form_submit_wrapper');
	}
}
function product_node_form_submit_wrapper($form, &$state) {
	// First call the normal form submit makes a node... all the usual suspects
	//	like nodeapi and adding additional ['#submit']'s to the form (that I found)
	//	change the node before you save it.  The v1.0 code at product_add_to_inventory
	//  breaks the node saving process for the product while it saves the item.
  node_form_submit($form, $state);

	if (product_get_autoadd(true)) {  // last step for autoadd'ing item to product,																					// true will clear it.
  	$product = node_load($state['nid']); // set in node_form_submit, the
		product_add_to_inventory($product, true);
	}
}



function product_reports_daily_cron($last_run)
{
	$sql = "SELECT td.`name` category, COUNT(*) items_created
			FROM {term_data} td JOIN {term_node} tn JOIN {product_items} pi JOIN {node} n
				ON td.`tid`=tn.`tid`
				AND pi.`product_nid`=tn.`nid`
				AND pi.`item_nid`=n.`nid`
			WHERE n.`type`='vibio_item'
				AND n.`created` >= %d
			GROUP BY td.`name`
			ORDER BY COUNT(*) DESC";
	$res = db_query($sql, $last_run);
	
	while ($row = db_fetch_object($res))
	{
		reports_log("items", $row->items_created, $row->category);
	}
	
	if ($employee_role = variable_get("reports_employee_role", false))
	{
		$sql = "SELECT td.`name` category, COUNT(*) items_created
				FROM {term_data} td JOIN {term_node} tn JOIN {product_items} pi JOIN {node} n
					ON td.`tid`=tn.`tid`
					AND pi.`product_nid`=tn.`nid`
					AND pi.`item_nid`=n.`nid`
				WHERE n.`type`='vibio_item'
					AND n.`created` >= %d
					AND n.`uid` NOT IN (
						SELECT `uid`
						FROM {users_roles}
						WHERE `rid`=%d
					)
				GROUP BY td.`name`
				ORDER BY COUNT(*) DESC";
		$res = db_query($sql, $last_run, $employee_role);
		
		while ($row = db_fetch_object($res))
		{
			reports_log("items noemployee", $row->items_created, $row->category);
		}
	}
}

function _product_remove_options($keys)
{
	return preg_replace('/(^| )([a-z0-9_]+):(.*)( |$)/i', '', $keys);
}

function _product_inventory_add_title($product)
{
	return t("Add \"!product\" to your inventory", array("!product" => $product->title));
}

function product_user_owns_product($product_id, $uid=false)
{
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}
	
	$sql = "SELECT n.`nid`
			FROM {node} n JOIN {product_items} pi
				ON n.`nid`=pi.`item_nid`
			WHERE n.`uid`=%d
				AND pi.`product_nid`=%d";
	return db_result(db_query($sql, $uid, $product_id));
}

/* warning: this uses search text for functional if
 * The point of this is something to do with searches with no results. 
 * Experiment when time
 * !!! I think I wrote some code for blank searches to. -Stephen
 */
function product_preprocess_box(&$vars)
{
	if ($vars['title'] == t("Your search yielded no results") /* ouch */ && arg(0) == "search" && arg(1) == "vibio_item")
	{
		if (!$_GET['external_product_search'])
		{
			module_load_include("inc", "product");
			product_set_external_search_page_offset(0);
			drupal_goto(substr(urldecode(request_uri()), 1), "external_product_search=1");
		}
		else
		{
			$vars['content'] .= theme("product_add_product_link");
		}
	}
}

/* Also note template.php's vibio_preprocess_search_results ? */
/* This function appears to fire on the first round of the search,
    not the "only external items" search.  The slightly dizzying:
    "If it's not an external search, add the external search results here"
 */
function product_preprocess_search_results(&$vars)
{
	if ($vars['type'] == "vibio_item")
	{
		global $pager_page_array, $pager_total;

		
		module_load_include("inc", "product");
		drupal_add_css("themes/vibio/css/product.css");
		
		$is_external = product_set_external_search();
		// modules/vibiomodules/vibio_item/product_catalog/product.inc
		// Some function, in a typical search, has already set it to true.
		// look for:
		// modules/vibio/vibio_item/product_catalog/product.module:  product_set_external_search(true, true);
	// I think it's called in product_external_search (this file), by 
	//  something before here?   start_here_tuesday

//PRODUCT_SEARCH_RESULTS_PER_PAGE does this do anything?  Is the idea that if you have a full page of results in $vars['results'] 
// which is either local, or local+Amazone but not eBay, or ???, then we don't need to do this next search?



// WARNING: $is_external is typical search true,
//	so what is all this?
//die("is external is $is_external"); = 1
//$is_external = false;
// um... the external results look just like what we already have
		
		if (!$is_external && variable_get("product_append_external", false) && (count($vars['results']) < PRODUCT_SEARCH_RESULTS_PER_PAGE || $_GET['page'] == $pager_total[0] - 1))
		{
			product_set_external_search_page_offset($pager_total[0] - 1);
			$external_results = product_external_search(_vibio_item_search_keys());
			
			$vars['other_results'] = "";
			if (!empty($external_results))
			{
				// Stephen: we need finer control on external results, add this
				//  the pager system is out of sorts
				$vars['unthemed_other_results'] = $external_results;
				
				foreach ($external_results as $result)
				{
					$vars['other_results'] .= theme("search_result", $result, "vibio_item"); /* stephen: what is this?  Ebay, or Ebay and Amazon?  OMG:
    *  I think other_results is basically less interesting results,
    *  so Amazon results can be regularly results if no other results are
    *  found, and 'other_results' if local results were found.   
    *  Not sure about this, check later.
    */
				}
				$vars['pager'] = theme("pager");
			}
			
			if ($_GET['page'] > product_set_external_search_page_offset())
			{
				$vars['search_results'] = "";    /* has this been tested */
			}
		}
		/*elseif (variable_get("product_append_external", false))
		{
			
		}*/
	}
}


/* two functions dealing with have-want buttons, 
     one for search, one for view
     main question is do you own this product already,
     now expanded to own/want/like. */
function product_preprocess_search_result(&$vars)
{
  if ($vars['type'] == "vibio_item")
  {
    $item_nid = product_user_owns_product($vars['result']['node']->nid);

    // get possess level ... repeat code turn to function if doesn't diverge
    $possess = get_possess(node_load($item_nid));

    switch ($possess) {
      case '30':
        $possess_words = 'You Like This!';
        $you_possess = 'you-like';
        break;
      case '20':
        $possess_words = 'You Want This!';
        $you_possess = 'you-want';
        break;
      default:
        $possess_words = 'You Own This mow!';
        $you_possess = 'you-own-it';
    }

    //@Craig: feel free to fiddle with the classes.  I left "you-own" for
    //  for all the possession levels, but you could replace that

		// Look for this note, and make the same changes in both places! erase
		//  these notes when done!

    $search_links = array();
    $search_links['own_have'] = ($item_nid) ? l(t($possess_words), "node/{$item_nid}", array('attributes' => array('class' => "you-own $you_possess"))) : theme("product_inventory_add", $vars['result']['node']->nid);
    $search_links['want'] = ($item_nid) ? "" : theme("product_inventory_want", $vars['result']['node']->nid);


		/* Stephen: um.  I'm having troubles understanding Ben's code.
     *  Why are there have/want buttons only IF the filepath is empty?
     *  Has this been tested against new Amazon results?  It's not ok
     *   if there are no have/want buttons on new/old results!
     */ 


    if (!empty($vars['result']['node']->field_main_image[0]['filepath'])) {
      $vars['img'] = theme('imagecache', 'product_fixed_width', $vars['result']['node']->field_main_image[0]['filepath']);
    }
    else {
      // Remote images and altered have/want links for Amazon items not yet converted to local products.
      if ($vars['result']['result_type'] == 'remote') {
        $vars['img'] = '<img src="' . $vars['result']['amazon_data']['imagesets']['largeimage']['url'] . '" width="160"/>';
        //Products must be created before have/want popup is generated; links
        //create product from Amazon item
        $search_links['want'] = l(t('Want'), 'product-from-asin',
          array(
            'query' => array('asin' => $vars['result']['amazon_data']['asin']),
            'attributes' => array('class' => 'inventory_want', 'asin' => $vars['result']['amazon_data']['asin'])
          ));
        $search_links['own_have'] = l(t('Have'), 'product-from-asin',
          array(
            'query' => array('asin' => $vars['result']['amazon_data']['asin']),
            'attributes' => array('class' => 'inventory_add', 'asin' => $vars['result']['amazon_data']['asin'])
          ));
      }
      else {
        // Fall back to default.
        $vars['img'] = theme('imagecache', 'product_fixed_width', "themes/vibio/images/icons/default_item_large.png");
      }
    }
  }
  $vars['search_links'] = implode("\n", $search_links);
}


/* two functions dealing with have-want buttons, 
     one for search, one for view
     main question is do you own this product alread. */
function product_preprocess_search_result_STEPHENS_STYLE(&$vars)
{
	if ($vars['type'] == "vibio_item")
	{
		$item_nid = product_user_owns_product($vars['result']['node']->nid);


		// get possess level ... repeat code turn to function if doesn't diverge
		$possess = get_possess(node_load($item_nid)); 			

		switch ($possess) {
			case '30':
      	$possess_words = 'You Like This!';
				$you_possess = 'you-like';
      	break;
    	case '20':
      	$possess_words = 'You Want This!';
        $you_possess = 'you-want';	
      	break;
    	default:
      	$possess_words = 'You Own This mow!';
        $you_possess = 'you-own-it';
  	}	

		//@Craig: feel free to fiddle with the classes.  I left "you-own" for
		//  for all the possession levels, but you could replace that
		$vars['search_links'] .= $item_nid ? l(t($possess_words), "node/{$item_nid}", array('attributes' => array('class' => "you-own $you_possess"))) : theme("product_inventory_add", $vars['result']['node']->nid);
		// ToDo: figure out something like product_user_wants_product
		$vars['search_links'] .= $item_nid ? "" : theme("product_inventory_want", $vars['result']['node']->nid); 
	}
}

// for the Feature view ... maybe elsewhere too?
function product_have_want($nid)
{
  //not used? $vars = array();  // not a preprocess function despite similiarities
	$item_nid = product_user_owns_product($nid);
		// get possess level ... repeat code turn to function if doesn't diverge
		$possess = get_possess(node_load($item_nid)); 			

		switch ($possess) {
			case '30':
      	$possess_words = 'You Like This!';
				$you_possess = 'you-like';
      	break;
    	case '20':
      	$possess_words = 'You Want This!';
        $you_possess = 'you-want';	
      	break;
    	default:
      	$possess_words = 'You Own This!';
        $you_possess = 'you-own-it';
  	}	

		//@Craig: feel free to fiddle with the classes.  I left "you-own" for
		//  for all the possession levels, but you could replace that



	$search_links .= $item_nid ? l(t($possess_words), "node/{$item_nid}", array('attributes' => array('class' => "you-own $you_possess"))) : theme("product_inventory_add", $nid); // theme just creates the button.
	$search_links .= $item_nid ? "" : theme("product_inventory_want", $nid); 
	return $search_links;
}

// answer this hook (perhaps other uses):
//       $extra_images = module_invoke_all("vibio_item_images", $node->nid);
// I believe this is really "additional" images only.  And only called for that?
function product_vibio_item_images($item_nid)
{
	module_load_include("inc", "product");

	// is this a serious error that should never happen?	be watchdogged?
	if (!($nid = _product_nid_from_item($item_nid)))
	{
		return array();
	}
	
	return product_images($nid);  // an array of urls
}

function product_set_autoadd($val=true)
{
	$_SESSION['product']['auto_add'] = $val;
}

function product_get_autoadd($clear=true)
{
	$val = $_SESSION['product']['auto_add'];

	if ($clear)
		unset($_SESSION['product']['auto_add']);

	return $val;
}
?>
