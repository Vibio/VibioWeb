<?php
function ebay_ajax()
{
	$args = $_POST;
	$func = "_ebay_ajax_{$args['action']}";
	
	if (empty($args['action']) || !function_exists($func))
	{
		exit(t("No action specified or invalid action"));
	}
	
	unset($args['action']);

	exit($func($args));
}

function ebay_ajax_theme()
{
	$args = $_POST;
	
	$allowed_themes = array(
		"ebay_link_account_init",
	);
	
	if (empty($args['action']) || !in_array($args['action'], $allowed_themes))
	{
		exit(t("No action specified or invalid action"));
	}
	
	$theme = $args['action'];
	unset($args['action']);
	
	exit(theme($theme, $args));
}

function _ebay_ajax_find_items_advanced($args)
{
	if ($items = ebay_find_items_advanced($args))
	{
		$display_args = array(
			"hide_wrapper"	=> $args['hide_wrapper'],
		);
		return theme("ebay_search_results", $items, $display_args);
	}
	return theme("ebay_empty_search");
}

function _ebay_ajax_remove_account($args)
{
	global $user;
	
	if (!user_access("administer users") || !_ebay_is_owner($args['account']))
	{
		exit(json_encode(array(
			"status"	=> false,
			"message"	=> t("You are not authorized to unlink that account")
		)));
	}
	
	_ebay_delete_token($user->uid, $args['account']);
	
	return json_encode(array(
		"status"	=> true,
		"message"	=> t("The account !account_name has been successfully removed from your Vibio account", array("!account_name" => $args['account'])),
	));
}
?>