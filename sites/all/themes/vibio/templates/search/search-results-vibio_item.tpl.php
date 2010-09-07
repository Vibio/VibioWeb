<?php
echo $pager;

echo "
	<table class='search-results $type'>
		$search_results
	</table>
";

if (module_exists("product"))
{
	echo theme("product_add_product_link");
}

echo $pager;
?>
