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
	ebay_find_items_advanced($args);
}
?>