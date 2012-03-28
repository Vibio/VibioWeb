<?php
define("COLLECTION_RANDOM_COLLECTION_DESCRIPTION_MAXLENGTH", 64);


/**
 * Get the $uid's collections.
 *
 * @global <type> $user
 * @staticvar array $user_collections
 * @param <type> $uid
 * @return Array the user's collections
 */
function collection_get_collections($uid=false)
{
	static $user_collections = array();

	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}

	if (!isset($user_collections[$uid]))
	{
		$sql = "SELECT `nid`
				FROM {node}
				WHERE `uid`=%d AND `type`='collection'
				ORDER BY `title` ASC";
		$res = db_query($sql, $uid);

		$collections = array();
    //Load all the user's collections
		while ($row = db_fetch_array($res))
		{
      //key: nid, value: collection object
			$collections[$row['nid']] = collection_load($row['nid']);
		}

		$user_collections[$uid] = $collections;
	}

	return $user_collections[$uid];
}

/**
 * Inserts a new collection. Depreciated.
 * @todo: delete, replace all calls with node_save($collection)
 * @global  $user
 * @param <type> $vals
 * @return <type>

function collection_insert($vals)
{
	global $user;
	$collection = new stdClass();
  $collection->title
	$sql = "INSERT INTO {collection}
			SET `uid`=%d, `title`='%s', `description`='%s', `created`=%d";
	if (!db_query($sql, $user->uid, $vals['title'], $vals['description'], time()))
	{
		return false;
	}

	$vals['cid'] = db_last_insert_id("collection", "cid");
	$vals['uid'] = $user->uid;

	module_invoke_all("collections_insert", $vals);
	return $vals['cid'];
}
 *
 * @param <type> $cid
 */

/**
 * Deletes the given collection. Depreciated.
 * @todo: delete, replace all calls with node_delete($nid)
 * @param <type> $cid
 */
function collection_delete($cid)
{

	//$collection = collection_load($cid);
  $sql = "SELECT `uid`
      FROM {node}
      WHERE `nid`=%d";
  $uid = db_fetch_object(db_query($sql, $cid))->uid;

	$sql = "SELECT `nid`
			FROM {content_field_collection}
			WHERE `nid`=%d";
	$res = db_query($sql, $cid);
	$num_items = db_affected_rows();

  //Delete the node
  node_delete($cid);

	if ($num_items)
	{
		$new_cid = collection_get_user_default($uid, true);
		$items = array();

		while ($row = db_fetch_object($res))
		{
			$item_cids = collection_get_item_cid($row->item_nid);
			if (count($item_cids) == 0) //If an item is now orphaned, transfer it to the user's unsorted
			{
				$items[] = $row->nid;
			}
		}

		collection_transfer_items($items, $new_cid);
	}

  //Let other modules hook in on the collection deletion
	module_invoke_all("collection_delete", $collection['uid'], $new_cid, $cid);
	drupal_set_message(t('The collection !title has been deleted', array("!title" => $collection['title'])));
}

/**
 * Transfer $items $to_cid
 *
 * @param <type> $items
 * @param <type> $to_cid
 */
function collection_transfer_items($items, $to_cid)
{
  foreach($items as $nid){
    $node = new stdClass();
    $node->nid = $nid;
    $node->field_collection[]['nid'] = $to_cid;
    node_save($node);
  }
}

/**
 * Get the collection id(s) of the given item.
 *
 * @param <type> $nid
 * @return <type>
 */
function collection_get_item_cid($nid)
{
	$sql = "SELECT `field_collection_nid`
			FROM {content_field_collection}
			WHERE `nid`=%d";
	$res = db_query($sql, $nid);

	$ret = array();
	while ($row = db_fetch_array($res))
	{
		$ret[$row['field_collection_nid']] = $row['field_collection_nid'];
	}

	return $ret;
}

/**
 * List of the current user's collections in format key: nid, value: title
 * format (suitable for a select list).
 *
 * @global  $user
 * @return <type>
 */
function collection_options()
{
	global $user;

	$options = array();
	foreach (collection_get_collections($user->uid) as $cid => $collection)
	{
		$options[$cid] = $collection->title;
	}

	return $options;
}

/**
 * Get the nid of the user's default collection
 *
 * @global  $user
 * @param <type> $uid
 * @param <type> $auto_create
 * @return <type>
 */
function collection_get_user_default($uid=false, $auto_create=false)
{
  //If not given a uid, use the current user
	if (!$uid)
	{
		global $user;
		$uid = $user->uid;
	}

	//Find the existing default collection
	$sql = "SELECT c.nid
			FROM {content_type_collection} AS c JOIN {node} AS n
			ON c.nid=n.nid WHERE n.uid=%d AND c.field_default_value=1";
	$cid = db_result(db_query($sql, $uid));

	if ($cid || !$auto_create)
	{
		return $cid;
	}
	// if there is no default, create one for the user...
	$new_collection = (object) array(
		"title"			=> t("Unsorted"),
		"body"	=> t("My unsorted things."),
    "type" => "collection",
    "uid" => $uid
	);
  $new_collection->field_default[]['value'] = 1;
  node_save($new_collection);

  //Do we need this case? @todo:delete
	if (!$new_collection->nid)
	{
		return false;
	}

	//collection_set_user_default($uid, $new_collection->nid);
  $vals = (array) $new_collection;
	module_invoke_all("collection_save", $vals);

	return $new_collection->cid;
}

/**
 * Set the $uid's default collection to $cid
 *
 * @todo: remove $uid from the signature
 * @todo: consider depreciating
 * @param <type> $uid
 * @param <type> $cid
 * @return <type>
 */
function collection_set_user_default($uid, $cid)
{
	$sql = "REPLACE INTO {content_type_collection}
			SET `field_default_value`=%d";
	return db_query($sql, $cid);
}

//Returns the image for a collection
function collection_get_image($cid, $absolute=false, $access=false)
{
	//include product.inc to get the file_uncreate_url function
	module_load_include('inc', 'product');

	if (!$access)
		$access = PRIVACY_ONLYME;

	$sql = "SELECT n.`nid`
			FROM {node} n JOIN {content_field_collection} ci JOIN {privacy_node} p
				ON n.`nid`=ci.`nid`
					AND n.`nid`=p.`nid`
			WHERE ci.`field_collection_nid`=%d
				AND p.`setting` <= %d
			ORDER BY n.`changed` DESC
			LIMIT 1";
	if (!$nid = db_result(db_query($sql, $cid, $access)))
	{
		$image = collection_default_image();
	}else{
		$image = _vibio_item_get_image($nid);
	}
	//Make sure that we're not given a full URL
	$image = file_uncreate_url($image);
	//Get rid of any initial slash marks in the url
        $pattern = "/^\//";
        $image = preg_replace($pattern,"",$image);

	return $image;
}

/**
 * Return the path to the default collection image
 *
 * @param Boolean $absolute
 * @return String
 */
function collection_default_image($absolute=false)
{
	$default_image = "themes/vibio/images/collections/box.png";
	return $absolute ? url($default_image, array("absolute" => true)) : "/$default_image";
}

/**
 * Return the number of items in a collection
 *
 * @param int $cid
 * @param <type> $access
 * @return int
 */
function collection_get_num_items($cid, $access=false)
{
	if (module_exists("privacy") && $access)
	{
		//$collection = collection_load($cid);

		$sql = "SELECT COUNT(*)
				FROM {content_field_collection} ci JOIN {privacy_node} p
					ON ci.`nid`=p.`nid`
				WHERE `field_collection_nid`=%d
					AND p.`setting` <= %d";
		return db_result(db_query($sql, $cid, $access));
	}

	$sql = "SELECT COUNT(*)
			FROM {content_field_collection}
			WHERE `nid`=%d";
	return db_result(db_query($sql, $cid));
}

/**
 * Get the taxonomy categories a given collection belongs to.
 * @todo: depreciate all calls as we move collection taxonomy into the
 * taxonomy DB table
 * @param <type> $cid
 * @return Array taxonomy categories
 */
function collection_get_categories($cid)
{
	if (!module_exists("taxonomy") || !module_exists("product"))
	{
		return array();
	}
	//Get all the product nids that are in the collection
	$categories = array();
	$sql = "SELECT pi.`product_nid`
			FROM {content_field_collection} ci JOIN {product_items} pi
				ON ci.`nid`=pi.`item_nid`
			WHERE ci.`field_collection_nid`=%d";
	$res = db_query($sql, $cid);

  //Assemble a list of the taxonomy terms associated with those products.
	while ($row = db_fetch_object($res))
	{
		$product = node_load($row->product_nid);
		foreach (taxonomy_node_get_terms_by_vocabulary($product, variable_get("vibio_amazon_category_id", 1)) as $tid => $term)
		{
			$categories[$tid] = $term->name;
		}
	}
	//This list is considered the terms for the collection
  //@todo: refactor collections to use an independent taxonomy
	return $categories;
}

/**
 * Get the currently viewed item nid
 */
function collection_get_viewed_item($vibio_item = null){
  if(empty($vibio_item)){
    //Determine the terms for whatever is being viewed
    switch(arg(0)){
      case 'node':
      case 'collections':
        $nid = (int) arg(1);
        break;
      case 'product':
      case 'item':
        $real_path = drupal_get_normal_path(arg(1));
        $real_path_parts = explode('/', $real_path);
        $nid = (int) $real_path_parts[1];
        break;
      //Do something here to find terms similar to the collection's items
      case 'default':
      //Set up some default featured items?
      break;
    }
  }
  //$viewed_item = node_load($nid);
  return $nid;
}

/**
 * Load an item related to $vibio_item

function collection_get_related($vibio_item){
  if (!empty($vibio_item->taxonomy)) {
    $terms = array();
    foreach ($vibio_item->taxonomy as $term) {
      $terms[] = $term->tid;
    }
  }else{
    $terms = taxonomy_node_get_terms($vibio_item);
  }

  //find nodes that shares the taxonomy term
  $related = taxonomy_select_nodes($terms);
  $related_nids = array();
  while ($row = db_fetch_object($related)) {
    $related_nids[] = $row->nid;
  }

  //Pick a random nid
  $related_nid = $related_nids[rand(0, count($related_nids))];

  return $related_nid;
}
*/

function collection_display_related($vibio_item = false){
  /**
  //Get a currently viewed object
  $viewed_item = collection_get_viewed_item();
  
  //Get related nid
  $related_nid = collection_get_related($viewed_item);

  //Populate the data
  $related_node = node_load($related_nid);
  */
  
  //This is a solution using similarterm module; my custom solution
  //above could be used in the future or deleted--Alec
  $nid = collection_get_viewed_item();
  
  $related_nodes = views_get_view_result('related_item', 'default', $nid);

  if(empty($related_nodes)){
    $related_nodes = views_get_view_result('related_item', 'random_item');
  }
  $item = $related_nodes[0];

  $item->node_title = strlen($item->node_title) > COLLECTION_RANDOM_COLLECTION_DESCRIPTION_MAXLENGTH ?
		substr($item->node_title, 0, COLLECTION_RANDOM_COLLECTION_DESCRIPTION_MAXLENGTH)."..." : $item->node_title;
  $item->node_title = l($item->node_title, 'node/' . $item->nid);
  $item->users_name = l($item->users_name, 'user/' . $item->users_uid);

  //Choose a random related node for variety
  //$random_related = $related_nodes[rand(0, count($related_nodes))];
  //Theme it and return the markup
  return theme('collection_related_item', $item);
}

/**
 * Is this for grabbing "showcase item" values?
 * @todo: delete this hasn't been refactored, not sure if it's necessary. I've commented
 * out all its calls.
 *
 * @global  $user
 * @param <type> $uid
 * @param <type> $ignore_cid
 * @param <type> $max_collections
 * @param <type> $as_uid
 * @return <type>

function collection_get_sidebar($uid, $ignore_cid=0, $max_collections=4, $as_uid=false)
{
	if (!$as_uid)
	{
		global $user;
		$as_uid = $user->uid;
	}

	if (module_exists("privacy"))
	{
		$access = privacy_get_access_level($uid, $as_uid);
		$sql = "SELECT n.`nid`, n.`title`, n.`description`, COUNT(*) AS num_items
				FROM {node} c JOIN {privacy_settings} p JOIN {collection_items} ci
					ON c.`cid`=p.`type_id`
						AND c.`uid`=p.`uid`
						AND c.`cid`=ci.`cid`
				WHERE p.`type`='collection'
					AND p.`setting` <= %d
					AND c.`uid`=%d
					AND c.`cid` != %d
				GROUP BY c.`cid`
				ORDER BY RAND()
				LIMIT %d";
		$res = db_query($sql, $access, $uid, $ignore_cid, $max_collections);
	}
	else
	{
		$sql = "SELECT c.`cid`, c.`title`, c.`description`, COUNT(*) AS num_items
				FROM {collection} c JOIN {collection_items} ci
					ON c.`cid`=ci.`cid`
				WHERE `uid`=%d
					AND c.`cid` != %d
				GROUP BY c.`cid`
				ORDER BY RAND()
				LIMIT %d";
		$res = db_query($sql, $uid, $ignore_cid, $max_collections);
	}

	$collections = array();
	while ($c = db_fetch_object($res))
	{
		$c->items = collection_get_sidebar_items($c->cid, $uid, $as_uid);
		$c->image = collection_get_image($c->cid);
		$collections[] = $c;
	}

	return $collections;
}
*/

/**
 * Is this for grabbing "showcase item" values?
 * @todo:delete this hasn't been refactored, not sure if it's necessary. I've commented
 * out all its calls.
 *
 * @global  $user
 * @param <type> $cid
 * @param <type> $owner
 * @param <type> $as_uid
 * @param <type> $num_items
 * @return <type>

function collection_get_sidebar_items($cid, $owner=false, $as_uid=false, $num_items=3)
{
	//Get the file_uncreate_url() function
	module_load_include('inc', 'product');
	if (!$as_uid)
	{
		global $user;
		$as_uid = $user->uid;
	}

	if (module_exists("privacy"))
	{
		if (!$owner)
		{
			$collection = collection_load($cid);
			$owner = $collection['cid'];
		}

		$access = privacy_get_access_level($owner, $as_uid);
		$sql = "SELECT ci.`item_nid`, n.`title`
				FROM {collection_items} ci JOIN {node} n JOIN {privacy_settings} p
					ON ci.`item_nid`=n.`nid`
						AND n.`nid`=p.`type_id`
				WHERE ci.`cid`=%d
					AND p.`type`='node'
					AND p.`uid`=%d
					AND p.`setting` <= %d
				ORDER BY RAND()
				LIMIT %d";
		$res = db_query($sql, $cid, $owner, $access, $num_items);
	}
	else
	{
		$sql = "SELECT ci.`item_nid`, n.`title`
				FROM {collection_items} ci JOIN {node} n
					ON ci.`item_nid`=n.`nid`
				WHERE ci.`cid`=%d
				ORDER BY RAND()
				LIMIT %d";
		$res = db_fetch_object($sql, $cid, $num_items);
	}

	$items = array();
	while ($item = db_fetch_object($res))
	{
		$item->image = file_uncreate_url(_vibio_item_get_image($item->item_nid));
		$items[] = $item;
	}

	return $items;
}
 *
 */
?>
