<?php
if (isset($node->amazon_data))
{
	echo theme("product_amazon_display", $node, $page);
}
?>