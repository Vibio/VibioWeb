<?php
if (!$output && module_exists("product"))
{
	module_load_include("inc", "product");
	$output = _vibio_item_get_image($view->result[$view->row_index]->nid);
}

echo "<img src='$output' />";
?>