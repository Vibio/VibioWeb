<?php
function offer2buy_views_data()
{
	$data['offer2buy'] = array(
		"table"	=> array(
			"group"	=> t("Offer2Buy"),
			"join"	=> array(
				"node"	=> array(
					"left_field"=> "nid",
					"field"		=> "nid",
				),
			),
		),
		"price"	=> array(
			"title"	=> t("Price"),
			"help"	=> t("The asking price for the item"),
			"field"	=> array(
				"handler"		=> "views_handler_field_numeric",
				"click sortable"=> true,
			),
			"filter"=> array(
				"handler"	=> "views_handler_filter_numeric",
			),
		),
	);
	
	return $data;
}
?>