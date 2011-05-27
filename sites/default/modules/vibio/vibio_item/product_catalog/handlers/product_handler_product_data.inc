<?php
class product_handler_product_data extends views_handler_field {
	function options(&$options)
	{
		parent::options($options);
		$options['data'] = "terms";
		$options['link'] = false;
	}
	
	function options_form(&$form, &$state)
	{
		parent::options_form($form, $state);
		
		$form['data'] = array(
			"#title"		=> t("Product Data"),
			"#type"			=> "select",
			"#options"		=> array(
				"terms" => t("Category Terms"),
				"title"	=> t("Product Title"),
				"nid"	=> t("Product NID"),
			),
			"#default_value"=> $this->options['data'],
		);
		$form['link'] = array(
			"#title"		=> t("Link this field to the product node"),
			"#type"			=> "checkbox",
			"#default_value"=> $this->options['link'],
		);
	}
	
	function click_sort($order)
	{
		$callback = "sort_".$this->options['data'];
		$this->$callback($order);
	}
	
	function sort_terms($order)
	{
		$sql = "SELECT td.name
				FROM {term_data} td JOIN {term_node} tn
					ON tn.tid=td.tid
				WHERE tn.`nid`=".check_plain($this->field_alias)."
					AND td.vid=".variable_get("vibio_amazon_category_id", 1)."
				ORDER BY td.`name` ASC
				LIMIT 1";
		$this->query->orderby[] = "($sql) $order";
	}
	
	function sort_nid($order)
	{
		$this->query->add_orderby($this->table, $this->field, $order, $this->field_alias);
	}
	
	function sort_title($order)
	{
		$sql = "SELECT title
				FROM {node}
				WHERE `nid`=".check_plain($this->field_alias);
		$this->query->orderby[] = "($sql) $order";
	}
	
	function render($vals)
	{
		$nid = check_plain($vals->{$this->field_alias});
		$callback = "render_".$this->options['data'];
		$out = $this->$callback($nid, $vals);
		
		return $this->options['link'] ? l($out, "node/{$nid}") : $out;
	}
	
	function render_terms($nid, $vals)
	{	
		$node = node_load($nid);
		$target_vid = variable_get("vibio_amazon_category_id", 1);
		
		$output = array();
		foreach ($node->taxonomy as $tid => $term)
		{
			if ($term->vid == $target_vid)
			{
				$output[] = $term->name;
			}
		}
		
		return implode(", ", $output);
	}
	
	function render_title($nid, $vals)
	{
		$node = node_load($nid);
		return $node->title;
	}
	
	function render_nid($nid, $vals)
	{
		return $nid;
	}
}
?>