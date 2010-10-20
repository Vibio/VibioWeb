<?php
echo $pager;

echo "
	<table class='search-results $type'>
		$search_results
		$other_results
	</table>
";

echo $pager;
echo theme("product_add_product_link");
?>
