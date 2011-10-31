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

 * The three or four columns are produced in template.php
 * vibio_preprocess_search_results

 * It can't work like this, please, but seems to:
 *  $search_results fills will one type of search results, 
 *  so local products if there are some, Amazon if not.
 *  Then $other_results has the other results.  So Amazon
 *  products can come in EITHER way

 */
echo " 
	<!-- table class='search-results $type' -->
		<div class='search-col 1'>$search_results_0</div>
		<div class='search-col 2'>$search_results_1</div>
		<div class='search-col 3'>$search_results_2</div>
		<div class='search-col 4'>$search_results_3</div>
		<div class='clearfix'></div>
	<!-- /table -->
";

echo $pager;
echo theme("product_add_product_link");
?>
