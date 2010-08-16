<?php
require_once(drupal_get_path("module", "views")."/plugins/views_plugin_cache.inc");

class views_plugin_cache_argument extends views_plugin_cache {
	function summary_title()
	{
		return t("Simple Argument Based");
	}
	
	function get_results_key()
	{
		if (!isset($this->_results_key))
		{
			$args = implode(":", $this->view->args);
			foreach (array("page", "sort", "order") as $additional_arg)
			{
				if (isset($_GET[$additional_arg]))
				{
					$args .= ":{$_GET[$additional_arg]}";
				}
			}
			$this->_results_key = $this->view->name.":".$args;
		}
		
		return $this->_results_key;
	}
}
?>