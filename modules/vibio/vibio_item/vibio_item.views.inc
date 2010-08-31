<?php
function vibio_item_views_data_alter(&$data)
{
	$data['node']['table']['join']['heartbeat_activity'] = array(
		"left_field"	=> "nid",
		"field"			=> "nid",
	);
	
	$data['node']['vibio_item_data'] = array(
		"real field"=> "nid",
		"title"		=> t("Vibio Item Data"),
		"help"		=> t("Item from a Vibio Item. This is similar to regular node data, but can also grab information from products (if products is enabled.)"),
		"field"		=> array(
			"handler"	=> "vibio_item_handler_item_data",
		),
	);
}

function vibio_item_views_handlers()
{
	return array(
		"info"		=> array(
			"path"	=> drupal_get_path("module", "vibio_item")."/handlers",
		),
		"handlers"	=> array(
			"vibio_item_handler_item_data"	=> array(
				"parent"	=> "views_handler_field",
			),
		),
	);
}
?>