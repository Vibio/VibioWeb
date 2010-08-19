<?php
require_once(drupal_get_path("module", "views")."/includes/base.inc");
require_once(drupal_get_path("module", "views")."/includes/handlers.inc");

class views_handler_join_between extends views_join
{
	function join($table, &$query)
	{
		$left = $query->get_table_info($this->left_table);
		$out = " {$this->type} JOIN {".$this->table."} {$table['alias']} ON {$left['alias']}.`{$this->left_field}`={$table['alias']}.`{$this->field}`";
		
		foreach ($table['join']->extra['additional_join_fields'] as $left_field => $right_field)
		{
			$out .= " AND {$left['alias']}.`{$left_field}`={$table['alias']}.`{$right_field}`";
		}
		
		return $out;
	}
}
?>