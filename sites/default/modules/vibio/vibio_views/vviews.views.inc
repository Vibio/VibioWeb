<?php
require_once("handlers/views_handler_join_between.inc");

function vviews_views_plugins()
{
	return array(
		"cache"	=> array(
			"argument"	=> array(
				"title"		=> t("Simple Argument Based"),
				"handler"	=> "views_plugin_cache_argument",
				"path"		=> drupal_get_path("module", "vviews")."/plugins",
				"parent"	=> "views_plugin_cache",
			),
		),
	);
}

function vviews_views_handlers()
{
	return array(
		"info"	=> array(
			"path"	=> drupal_get_path("module", "vviews")."/handlers",
		),
		"handlers"	=> array(
			"views_handler_comparison_argument_numeric"	=> array(
				"parent"	=> "views_handler_argument_numeric",
			),
			"views_handler_join_between" => array(
				"parent"	=> "views_join",
			),
		),
	);
}

function vviews_views_data_alter(&$data)
{
	$data['node']['uid'] = array(
		'title'		=> t("Author UID"),
		'help'		=> t("The UID of the user who currently owns the node"),
		'argument'	=> array(
			'handler'	 	=> 'views_handler_argument_numeric',
			'name field'	=> t('Author'),
		),
	);
}
?>