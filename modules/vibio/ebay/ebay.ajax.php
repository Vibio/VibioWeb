<?php
function ebay_ajax()
{
	$args = $_POST;
	
	if (empty($args['action']))
	{
		exit(t("No action specified."));
	}
	$func = "_ebay_ajax_{$args['action']}";

	exit($func($args));
}

function _ebay_ajax_find_items_advanced($args)
{
	if ($items = ebay_find_items_advanced($args))
	{
		$display_args = array(
			"expandable_search"	=> $args['expandable_search'],
		);
		
		return theme("ebay_search_results", $items, $display_args);
	}
	return theme("ebay_empty_search");
}
?>