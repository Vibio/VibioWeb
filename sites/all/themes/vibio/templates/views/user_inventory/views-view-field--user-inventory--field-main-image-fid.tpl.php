<?php
if (!$output && module_exists("product"))
{
	module_load_include("inc", "product");
	$output = _product_get_image($view->result[$view->row_index]->nid);
}

echo "<img src='$output' />";
?>