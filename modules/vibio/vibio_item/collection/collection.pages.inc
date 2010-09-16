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

	if ($_GET['preview'])
	{
		module_load_include("inc", "collection");
		$output = views_embed_view("user_collection_preview", "default", $collection['uid'], $collection['cid'], $access);
	}
	else
	{
		$output = $collection['description'].views_embed_view("user_collection", "default", $collection['uid'], $collection['cid'], $access);
	}

	if ($_GET['ajax'])
	{
		exit($output);
	}

	drupal_add_js("themes/vibio/js/offer2buy_actions.js");
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
	
	return views_embed_view("user_collections", "default", $user->uid, PRIVACY_ONLYME);
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
	
	return $form;
}

function collection_edit_validate($form, &$state)
{
	$vals = $state['values'];
	if (strlen($vals['description']) > COLLECTION_DESCRIPTION_MAX_LENGTH)
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
	
	if ($vals['cid'])
	{
		global $user;
		
		$sql = "UPDATE {collection}
				SET `title`='%s', `description`='%s', `updated`=%d
				WHERE `cid`=%d";
		db_query($sql, $vals['title'], $vals['description'], time(), $vals['cid']);
		
		module_invoke_all("collection_update", $user->uid, array($vals['cid']));
	}
	else
	{
		module_load_include("inc", "collection");
		$vals['cid'] = collection_insert($vals);
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
	drupal_set_message(t("The collection \"!name\" has been saved", array("!name" => $vals['title'])));
	drupal_goto("collections/manage");
}
?>