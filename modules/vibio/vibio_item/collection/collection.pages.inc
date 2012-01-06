<?php
define("COLLECTION_RANDOM_COLLECTION_DESCRIPTION_MAXLENGTH", 64);

/**
 * Display view of all the user's items
 * 
 * @param <type> $uid
 * @return <type>
 */
function collection_view_all_user_items($uid)
{
	$sql = "SELECT `name`
			FROM {users}
			WHERE `uid`=%d";
	$name = db_result(db_query($sql, $uid));
	
	drupal_set_title(t("All !name's items", array("!name" => $name)));
	
	return views_embed_view("user_inventory", "default", $uid, privacy_get_access_level($uid));
}

/**
 * Display view of a single given collection
 * 
 * @param <type> $collection
 * @return <type>
 */
function collection_view_collection($collection)
{	
	$access = module_exists("privacy") ? privacy_get_access_level($collection->uid) : 1;
	module_load_include("inc", "collection");

	if ($_GET['preview'])
	{
		$output = views_embed_view("user_collection_preview1", "default", $collection->uid, $collection->nid, $access);
	}
	else
	{
		//drupal_add_css("themes/vibio/css/collections.css");
		
		$collection_object = (object) $collection;
		$collection_object->collection_description = $collection->body;
		$collection_object->collection_title = $collection->title;
		
		$collection_display = theme("collection_list_item", $collection_object, $access, false);
		$collection_display .= views_embed_view("user_collection1", "default", $collection->uid, $collection->nid, $access);
		//$sidebar = collection_get_sidebar($collection['uid'], $collection['cid']);
		$output = theme("collection_view_collection", $collection_object->uid, $collection_display); //, $sidebar);
	}

	if ($_GET['ajax'])
	{
		exit($output);
	}

	return $output;
}

/**
 * Sends the user to an inventory page or tells them they have no collections.
 *
 * @global <type> $user
 * @return <type>
 */
function collection_manage()
{
	global $user;
	
	module_load_include("inc", "collection");
	//drupal_add_css("themes/vibio/css/collections.css");
	$collections = collection_get_collections();
	
	if (empty($collections))
	{
		return t("You currently have no collections. !create", array("!create" => l(t("Create a collection"), "collections/new")));
	}
	else
	{
		drupal_goto("user/{$user->uid}/inventory");
	}
}

/**
 * Form for editing a collection
 * @todo: Make this into a default node edit form?
 *
 * @param <type> $state
 * @param <type> $collection
 * @return <type>
 */
function collection_edit(&$state, $collection=false)
{
	module_load_include("inc", "collection");

	$is_default_collection = $collection && collection_get_user_default() == $collection->nid;
	
	$form = array(
		"set_default"	=> array(
			"#type"			=> "checkbox",
			"#title"		=> t("Set as default collection"),
			"#description"	=> t("Check this box to set this collection as the collection used for inventory quick-add. This is useful for things like 'My unsorted stuff'"),
			"#default_value"=> $is_default_collection,
		),
		"title"			=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Collection Name"),
			"#default_value"=> $collection->title,
			"#maxlength"	=> COLLECTION_TITLE_MAX_LENGTH,
			"#required"		=> true,
		),
		"body"	=> array(
			"#type"			=> "textarea",
			"#title"		=> t("Description"),
			"#default_value"=> $collection->body,
		),
	);
	
	if ($collection)
	{
		$form['cid'] = array(
			"#type"	=> "value",
			"#value"=> $collection->nid,
		);
		if ($is_default_collection)
		{
			$form['current_default'] = array(
				"#type"	=> "value",
				"#value"=> true,
			);
		}
	}
	
	$form['submit'] = array(
		"#type"		=> "submit",
		"#value"	=> t("Save"),
		"#weight"	=> 10,
	);

	if ($collection)
	{
		$form['delete'] = array(
			"#type"		=> "submit",
			"#value"	=> t("Delete"),
			"#weight"	=> 11,
		);
	}
	return $form;
}

/**
 * Validation function for collection_edit form
 * 
 * @param <type> $form
 * @param <type> $state
 */
function collection_edit_validate($form, &$state)
{
	$vals = $state['values'];
	$op = $state['clicked_button']['#value'];

	if ($op != t("Delete") && strlen($vals['body']) > COLLECTION_DESCRIPTION_MAX_LENGTH)
	{
		form_set_error("description", t("Description cannot be longer than !maxlength characters", array("!maxlength" => COLLECTION_DESCRIPTION_MAX_LENGTH)));
	}
}

/**
 * Submit function for collection_edit form
 * @todo: delete, replace with standard node edit form
 * 
 * @global  $user
 * @global  $user
 * @param <type> $form
 * @param <type> $state
 * @return <type>
 */
function collection_edit_submit($form, &$state)
{
	global $user;
	
	$vals = $state['values'];
	$vals['title'] = htmlspecialchars($vals['title']);
	$vals['body'] = htmlspecialchars($vals['body']);
	$op = $state['clicked_button']['#value'];
  //Create collection object
  $collection = new stdClass();
  foreach($vals as $field_name => $field_value){
    $collection->$field_name = $field_value;
  }
  //@todo refactor, legacy cid variable is actually nid
  $vals['cid'] ? $collection->nid = $vals['cid'] : '';

  //Delete collection
	if ($op == t("Delete") && $vals['cid'])
	{
		module_load_include("inc", "collection");
		collection_delete($vals['cid']);
		drupal_goto("collections/manage");
		return;
	}

  //Update Collection
	if ($vals['cid'])
	{
		global $user;

    //Save the node
 		node_save($collection);

    drupal_set_message(t("!collection has been updated", array("!collection" => l($vals['title'], "collections/{$vals['cid']}"))));
	}
	else
	{
    node_save($collection);
		$vals['cid'] = $collection->nid;
		drupal_set_message(t("!collection collection has been created", array("!collection" => l($vals['title'], "collections/{$vals['cid']}"))));
	}
	
	if ($vals['set_default'])
	{
		module_load_include("inc", "collection");
		collection_set_user_default($user->uid, $vals['cid']);
	}
	elseif ($vals['current_default'])
	{	
		$sql = "DELETE FROM {collection_user_defaults}
				WHERE `uid`=%d";
		db_query($sql, $user->uid);
	}
	
	$vals['uid'] = $user->uid;
	module_invoke_all("collection_save", $vals);
	drupal_goto("collections/manage");
}



/**
 * Displays a showcase collection ("showcase item")
 *
 * @param <type> $u
 * @return <type>
 */
function collection_random_collection($u)
{
	if (module_exists("network") && ($friends = _network_get_friends($u->uid, 1)) && !empty($friends))
	{
		$friend_ids = implode(",", $friends);
    /**
     * @todo: refactor privacy system to rely on nids exclusively (or switch to
     * node access)
     */
		$sql = "SELECT `nid`
				FROM {node} c JOIN {privacy_node} p
					ON c.`nid`=p.`nid`
				WHERE c.`uid` IN ($friend_ids)
					AND p.`setting` <= %d
				ORDER BY RAND()";
		$access = PRIVACY_CONNECTION;
	}
	else
	{
		$sql = "SELECT `nid`
				FROM {node} c JOIN {privacy_node} p
					ON c.`nid`=p.`nid`
					WHERE p.`setting` <= %d
				ORDER BY RAND()";
		$access = PRIVACY_PUBLIC;
	}
	
	if (!($cid = db_result(db_query($sql, $access))))
	{
		return "";
	}
	
	module_load_include("inc", "collection");

  /*
   * @todo: refactor to use the standard $collection node object; legacy code dealing
   * with the old data model is currently supported, but should be depreciated.
   */
	$collection = (object) collection_load($cid);
	$collection->tags = implode(", ", collection_get_categories($collection->cid));
	$collection->description = strlen($collection->body) > COLLECTION_RANDOM_COLLECTION_DESCRIPTION_MAXLENGTH ?
		substr($collection->description, 0, COLLECTION_RANDOM_COLLECTION_DESCRIPTION_MAXLENGTH)."..." : $collection->description;
	$collection->user = l($collection->user_name, "user/{$collection->uid}");
	$collection->title = l($collection->title, "collections/{$collection->cid}");
	$collection->image = collection_get_image($collection->cid, false, $access);
	$collection->num_items = collection_get_num_items($collection->cid, $access);
	
	return theme("collection_random_collection", $collection);
}
?>
