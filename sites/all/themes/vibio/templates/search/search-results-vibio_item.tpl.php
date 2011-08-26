<?php
echo theme("product_add_product_link");
echo $pager;

/* are $search_results from vibio, and $other_results from Amazon? */
echo "
	<!-- table class='search-results $type' -->
		$search_results
		$other_results
	<!-- /table -->
";

echo $pager;
echo theme("product_add_product_link");
?>
