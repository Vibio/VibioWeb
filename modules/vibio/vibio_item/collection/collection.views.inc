<?php
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

function collection_views_data_alter(&$data)
{
	$data['privacy_settings']['table']['join']['collection'] = array(
		"left_field"	=> "cid",
		"field"			=> "type_id",
	);
}
?>