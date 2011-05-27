<?php
class views_handler_comparison_argument_numeric extends views_handler_argument_numeric
{
	function query()
	{
		$this->ensure_my_table();
		
		if (!empty($this->options['break_phrase']))
		{
			views_break_phrase($this->argument, $this);
		}
		else
		{
			$this->value = array($this->argument);
		}
		
		$operator = empty($this->options['not']) ? "<=" : ">";
		$this->query->add_where(0, "{$this->table_alias}.`{$this->real_field}` $operator %d", $this->argument);
	}
}
?>