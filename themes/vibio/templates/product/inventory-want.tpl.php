<?php
//No longer used for either Have or Want... the theming is a little painful,
// with CSS tricks determining if text shows or button with slightly different
// meaning
//$text = t("Want");


/* A javascript seems to catch this from v1.0 days */

/* text version:
echo "
	<a class='inventory_add' id='inventory_add_{$nid}'>
		$text
	</a>
";
*/

/* This is called for Featured, Search and Product pages.
 * The text is ignored (css indentin) on the Featured and Search pages.
 * If we need to get finer tuning, then for example the Product page gets here:
 * $manage_link = theme("product_inventory_manage_link
 			in node-product.tpl.php  (grep "variant" for started code)
 * product.module sets product_inventory_manage_link to
 * inventory-manage-link.tpl.php which goes back through product.module to here. */


echo "
	<div class='already-have-link'><a class='inventory_want' id='inventory_want_{$nid}'>Want</a></div>
";





?>
