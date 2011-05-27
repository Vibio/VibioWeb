<?php
require_once(drupal_get_path("module", "views")."/plugins/views_plugin_cache.inc");

class views_plugin_cache_argument extends views_plugin_cache {
	function summary_title()
	{
		return t("Simple Argument Based");
	}
	
	function cache_set($type)
	{
		if (!$this->view->no_cache)
		{
			return parent::cache_set($type);
		}
	}
	
	function cache_get($type)
	{
		if (!$this->view->no_cache)
		{
			return parent::cache_get($type);
		}
	}
	
	function get_results_key()
	{
		if (!isset($this->_results_key))
		{
			$args = implode(":", $this->view->args);
			$filter_args = array(
				"page",
				"sort",
				"order",
				"keys",
				"item_status",
				"title",
			);
			foreach ($filter_args as $additional_arg)
			{
				if (!empty($_GET[$additional_arg]))
				{
					$args .= ":{$additional_arg}={$_GET[$additional_arg]}";
				}
			}
			$this->_results_key = $this->view->name.":{$args}:results";
		}
		
		return $this->_results_key;
	}
	
	function get_output_key()
	{
		if (!isset($this->_output_key))
		{
			$args = implode(":", $this->view->args);
			$filter_args = array(
				"page",
				"sort",
				"order",
				"keys",
				"item_status",
				"title",
			);
			foreach ($filter_args as $additional_arg)
			{
				if (!empty($_GET[$additional_arg]))
				{
					$args .= ":{$additional_arg}={$_GET[$additional_arg]}";
				}
			}
			$this->_output_key = $this->view->name.":{$args}:output";
		}
		
		return $this->_output_key;
	}
}
?>