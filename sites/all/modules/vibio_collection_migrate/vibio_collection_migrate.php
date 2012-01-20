#!/usr/bin/env drush

/**
 * Aggregated migration script to be run from Drush
 */

  vibio_collection_migrate_privacy();
  vibio_collection_migrate_collections();
  vibio_collection_migrate_taxonomy();

//Functions follow
function vibio_collection_migrate_menu(){
      $items['vibio_collection_migrate_collections'] = array(
      'page callback' => 'vibio_collection_migrate_collections',
      'access arguments'    => array('access content'),
      'type'                => MENU_CALLBACK
    );
    return $items;
}

function vibio_collection_migrate_collections(){

  //construct query
  $query = 'select title, description, created, updated, drupal_collection.cid, drupal_collection.uid, item_nid, drupal_collection_user_defaults.uid AS default_collection from (drupal_collection left join drupal_collection_items on drupal_collection.cid = drupal_collection_items.cid)
left join drupal_collection_user_defaults on drupal_collection.cid = drupal_collection_user_defaults.cid';
  $count = 0;
  //Perform query
  $result = db_query($query) or die(mysql_error() . '<br />' . $query);
  //takes every row of results from the query and transfers the old collections
  //data into standard Drupal data objects
  $previous_cid = null;
  $previous_nid = null;
  while($row = db_fetch_array($result)){
    $cid = $row['cid'];
    $title = $row['title'];
    $description = $row['description'];
    $created = $row['created'];
    $updated = $row['updated'];
    $uid = $row['uid'];
    $item_nid = $row['item_nid'];
    $default_collection = $row['default_collection'];
    $collection = new stdClass();

    //If it's a new collection entry...
    if($previous_cid != $cid){
      //Get privacy access value
      $sql = "SELECT setting FROM {privacy_settings} WHERE type='collection' AND type_id=%d";
      $privacy = db_result(db_query($sql, $cid));

      //Port collection
      $collection->title = $title;
      $collection->type = 'collection';
      $collection->language = 'en-US';
      $collection->body = $description;
      $collection->created = $created;
      $collection->uid = $uid;
      $collection->field_default[0]['value'] = !empty($default_collection) ? 1 : 0;
      $collection->privacy_setting = $privacy;
      node_save($collection);
      //Associate items with the new collection
      if(!empty($item_nid)){
        $item = node_load($item_nid);
        $item->field_collection[0]['nid'] = $collection->nid;
        node_save($item);

//        db_query('UPDATE drupal_collection_items SET cid=%d WHERE item_nid=%d', $collection->nid, $item_nid);
        //Keep a record for the next loop, in case there are more items to associate
        $previous_nid = $collection->nid;
      }
      $previous_cid = $cid;
    }else{
      //The collection has already been entered, record more items
    //    db_query('UPDATE drupal_collection_items SET cid=%d WHERE item_nid=%d', $previous_nid, $item_nid);
     
        $item = node_load($item_nid);
        $item->field_collection[0]['nid'] = $previous_nid;
        node_save($item);
      }
  }
}

/**
 * Script for filling in collection taxonomy based on the products contained
 * within the collection.
 */

function vibio_collection_migrate_taxonomy(){
  $sql = "SELECT nid FROM {node} WHERE type='collection'";
  $collection_nids = db_query($sql);

  while($collection = db_fetch_array($collection_nids)){
    $node = node_load($collection['nid']);
    vibio_collection_taxonomy_migrate_categories($node);
  }

}

function vibio_collection_taxonomy_migrate_categories($node)
{
	//Get all the product nids that are in the collection
	$categories = array();
	$sql = "SELECT pi.`product_nid`
			FROM {content_field_collection} ci JOIN {product_items} pi
				ON ci.`nid`=pi.`item_nid`
			WHERE ci.`field_collection_nid`=%d";
	$res = db_query($sql, $node->nid);
  $terms = array();
  //Assemble a list of the taxonomy terms associated with those products.
	while ($row = db_fetch_object($res))
	{
		$product = node_load($row->product_nid);
		foreach (taxonomy_node_get_terms_by_vocabulary($product, variable_get("vibio_amazon_category_id", 1)) as $tid => $term)
		{
			$terms[] = $term;
		}
	}
  taxonomy_node_save($node, $terms);
}

/**
  * Move old privacy schema into the new submodules, privacy_user/privacy_node
*/
function vibio_collection_migrate_privacy(){

  $user_sql = "INSERT INTO {privacy_user} (`uid`, `type`, `type_id`, `setting`) SELECT `uid`, `type`, `type_id`, `setting` FROM {privacy_settings} WHERE `type`='account_setting' OR `type`='profile'";
  db_query($user_sql);

  $node_sql = "INSERT INTO {privacy_node} (`nid`, `setting`) SELECT `type_id`, `setting` FROM {privacy_settings} WHERE `type`='node'";
  db_query($node_sql);

}
