<?php
if (isset($node->amazon_data))
{
	echo theme("product_display", $node, $page);
}
?>