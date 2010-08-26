<?php
function product_views_data()
{
	return array(
		"product_items"	=> array(
			"table"	=> array(
				"group"	=> t("Product Items"),
				"join"	=> array(
					"node"	=> array(
						"field"		=> "item_nid",
						"left_field"=> "nid",
					),
				),
			),
			"product_nid"	=> array(
				"title"	=> t("Product NID"),
				"field"	=> array(
					"handler"	=> "views_handler_field_numeric",
				),
			),
			"item_nid"		=> array(
				"title"	=> t("Item NID"),
				"field"	=> array(
					"handler"	=> "views_handler_field_numeric",
				),
			),
			"product_data" => array(
				"real field"=> "product_nid",
				"title"		=> t("Product Data"),
				"field"		=> array(
					"handler"		=> "product_handler_product_data",
					"click sortable"=> true,
				),
			),
		),
	);
}

/*function product_views_data_alter(&$data)
{
	/*$data['term_data']['table']['join']['node'] = array(
		"left_table" 	=> "term_node",
		"left_field" 	=> "tid",
		"field"			=> "tid",
	);
	$data['term_data']['product_category'] = $data['term_data']['name'];
	$data['term_data']['product_category']['real field'] = 'name';
	
	$data['term_node']['table']['join']['node']['left_table'] = "product_items";
}*/

function product_views_handlers()
{
	return array(
		"info"	=> array(
			"path"	=> drupal_get_path("module", "product")."/handlers",
		),
		"handlers"	=> array(
			"product_handler_product_data"	=> array(
				"parent"	=> "views_handler_field",
			),
			"views_handler_join_between"	=> array(
				"parent"	=> "views_join",
			),
		),
	);
}
?>