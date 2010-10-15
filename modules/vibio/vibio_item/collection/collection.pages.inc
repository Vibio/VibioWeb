<?php
function collection_view_all_user_items($uid)
{
	$sql = "SELECT `name`
			FROM {users}
			WHERE `uid`=%d";
	$name = db_result(db_query($sql, $uid));
	
	drupal_set_title(t("All !name's items", array("!name" => $name)));
	
	return views_embed_view("user_inventory", "default", $uid, privacy_get_access_level($uid));
}

function collection_view_collection($collection)
{	
	$access = module_exists("privacy") ? privacy_get_access_level($collection['uid']) : 1;
	module_load_include("inc", "collection");

	if ($_GET['preview'])
	{
		$output = views_embed_view("user_collection_preview", "default", $collection['uid'], $collection['cid'], $access);
	}
	else
	{
		drupal_add_css("themes/vibio/css/collections.css");
		
		$collection_object = (object) $collection;
		$collection_object->collection_description = $collection['description'];
		$collection_object->collection_title = $collection['title'];
		
		$collection_display = theme("collection_list_item", $collection_object, $access, false);
		$collection_display .= views_embed_view("user_collection", "default", $collection['uid'], $collection['cid'], $access);
		
		$sidebar = collection_get_sidebar($collection['uid'], $collection['cid']);
		$output = theme("collection_view_collection", $collection_object->uid, $collection_display, $sidebar);
	}

	if ($_GET['ajax'])
	{
		exit($output);
	}

	return $output;
}

function collection_manage()
{
	global $user;
	
	module_load_include("inc", "collection");
	drupal_add_css("themes/vibio/css/collections.css");
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

function collection_edit(&$state, $collection=false)
{
	module_load_include("inc", "collection");
	$is_default_collection = $collection && collection_get_user_default() == $collection['cid'];
	
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
			"#default_value"=> $collection['title'],
			"#maxlength"	=> COLLECTION_TITLE_MAX_LENGTH,
			"#required"		=> true,
		),
		"description"	=> array(
			"#type"			=> "textarea",
			"#title"		=> t("Description"),
			"#default_value"=> $collection['description'],
		),
	);
	
	if ($collection)
	{
		$form['cid'] = array(
			"#type"	=> "value",
			"#value"=> $collection['cid'],
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

function collection_edit_validate($form, &$state)
{
	$vals = $state['values'];
	$op = $state['clicked_button']['#value'];

	if ($op != t("Delete") && strlen($vals['description']) > COLLECTION_DESCRIPTION_MAX_LENGTH)
	{
		form_set_error("description", t("Description cannot be longer than !maxlength characters", array("!maxlength" => COLLECTION_DESCRIPTION_MAX_LENGTH)));
	}
}

function collection_edit_submit($form, &$state)
{
	global $user;
	
	$vals = $state['values'];
	$vals['title'] = htmlspecialchars($vals['title']);
	$vals['description'] = htmlspecialchars($vals['description']);
	$op = $state['clicked_button']['#value'];

	if ($op == t("Delete") && $vals['cid'])
	{
		module_load_include("inc", "collection");
		collection_delete($vals['cid']);
		drupal_goto("collections/manage");
		return;
	}
	
	if ($vals['cid'])
	{
		global $user;
		
		$sql = "UPDATE {collection}
				SET `title`='%s', `description`='%s', `updated`=%d
				WHERE `cid`=%d";
		db_query($sql, $vals['title'], $vals['description'], time(), $vals['cid']);
		module_invoke_all("collection_update", $user->uid, array($vals['cid']));
		drupal_set_message(t("!collection has been updated", array("!collection" => l($vals['title'], "collections/{$vals['cid']}"))));
	}
	else
	{
		module_load_include("inc", "collection");
		$vals['cid'] = collection_insert($vals);
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
?>
