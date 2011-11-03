<?php
$text = t("Have");
/* A javascript seems to catch this from v1.0 days */

/* text version:
echo "
	<a class='inventory_add' id='inventory_add_{$nid}'>
		$text
	</a>
";
*/

echo "
	<a class='inventory_add' id='inventory_add_{$nid}'><img
		src='/themes/vibio/images/have_button.png'/></a>
";





?>
