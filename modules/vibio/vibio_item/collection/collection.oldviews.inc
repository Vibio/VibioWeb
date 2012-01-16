<?php
/**
 * Implementation of the views api to make the depreciated custom collections
 * data model available to views.
 * @todo: delete and replace all views with standard node fields
 */


function collection_views_data()
{
	return array(
		"collection"	=> array(
			"table"		=> array(
				"group"	=> t("Collections"),
				"base"	=> array(
					"field"	=> "cid",
					"title"	=> t("Collections"),
				),
			),
			"cid"	=> array(
				"title"		=> t("ID"),
				"argument"	=> array(
					"handler"	=> "views_handler_argument_numeric",
				),
				"field"		=> array(
					"handler"	=> "views_handler_field_numeric",
				),
			),
			"uid"	=> array(
				"title"		=> t("User who owns the collection"),
				"argument"	=> array(
					"handler"	=> "views_handler_argument_user_uid",
				),
				"field"		=> array(
					"handler"	=> "views_handler_field_numeric",
				),
			),
			"title"	=> array(
				"title"		=> t("Title"),
				"field"		=> array(
					"handler"	=> "views_handler_field"
				),
				"filter"	=> array(
					"handler"	=> "views_handler_filter_string",
				),
				"sort"		=> array(
					"handler"	=> "views_handler_sort",
				),
			),
			"description" => array(
				"title" => t("Description"),
				"field" => array(
					"handler" => "views_handler_field",
				),
			),
		),
		"collection_items"	=> array(
			"table"		=> array(
				"group"	=> t("Collection Items"),
				"join"	=> array(
					"node"	=> array(
						"left_field"=> "nid",
						"field"		=> "item_nid",
					),
				),
			),
			"cid"		=> array(
				"title"		=> t("Collection ID"),
				"argument"	=> array(
					"handler"	=> "views_handler_argument_numeric",
				),
			),
			"item_nid"	=> array(
				"title"		=> t("Item NID"),
				"field"		=> array(
					"handler"	=> "views_handler_field_numeric",
				),
			),
		),
	);
}

/**
 * Joins collections to the custom privacy system
 *
 * @todo: Should this be kept if the privacy system remains unchanged?
 *
 * @param array $data
 */
function collection_views_data_alter(&$data)
{
	$data['privacy_settings']['table']['join']['node'] = array(
		"left_field"	=> "nid",
		"field"			=> "type_id",
	);
}

function collection_views_query_alter(&$view, &$query)
{
	if ($view->name == "user_collection")
	{
		$order_by = isset($_GET['order']) ? $_GET['order'] : "node_changed";
		$order_sort = isset($_GET['sort']) ? $_GET['sort'] : "desc";
		$query->orderby = array("$order_by $order_sort");
	}
	
}
?>