<?php
class vibio_item_handler_item_data extends views_handler_field
{
	function options(&$options)
	{
		parent::options($options);
		$options['data'] = "main_image";
		$options['link'] = false;
	}
	
	function options_form(&$form, &$state)
	{
		parent::options_form($form, $state);
		
		$form['data'] = array(
			"#title"		=> t("Item Data"),
			"#type"			=> "select",
			"#options"		=> array(
				"main_image"	=> t("Main Image"),
			),
			"#default_value"=>$this->options['data'],
		);
		$form['link'] = array(
			"#title"		=> t("Link this field to the item node?"),
			"#type"			=> "checkbox",
			"#default_value"=> $this->options['link'],
		);
	}
	
	function render($vals)
	{
		$nid = check_plain($vals->{$this->field_alias});
		$callback = "render_".$this->options['data'];
		return $this->$callback($nid, $vals);
	}
	
	function render_main_image($nid, $vals)
	{
		if ($img = _vibio_item_get_image($nid))
		{
			return $this->options['link'] ? "<a href='/node/{$nid}'><img class='vibio_item_views_image' src='$img' /></a>" : $img;
		}
	}
}
?>