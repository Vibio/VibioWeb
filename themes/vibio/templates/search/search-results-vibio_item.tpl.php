<?php
echo $pager;

echo "
	<table class='search-results $type'>
		$search_results
	</table>
";

echo $pager;

if ($external_products)
{
	echo $external_products;
}

echo $search_footer;
?>
