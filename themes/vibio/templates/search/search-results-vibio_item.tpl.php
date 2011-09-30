<?php
/* normall the form would be above here.  I hid it with css in search_page.css
 * while waiting for design to completely stabilize.  -stephen
 */

echo theme("product_add_product_link");
echo $pager;

/* are $search_results from vibio, and $other_results from Amazon? */
/* there is some uncomented code  here
 * /var/www/vibio/src/modules/vibio/vibio_item/product_catalog/product.module
 * that might produce $other_results ... is this eBay ? 

 * The three columns are produced in template.php
 * vibio_preprocess_search_results
 */
echo "
	<!-- table class='search-results $type' -->
		<div class='col1'>$search_results_1</div>
		<div class='col2'>$search_results_2</div>
		<div class='col3'>$search_results_0</div>
		$other_results
	<!-- /table -->
";

echo $pager;
echo theme("product_add_product_link");
?>
