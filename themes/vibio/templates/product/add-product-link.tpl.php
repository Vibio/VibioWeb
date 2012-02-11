<?php
/* This is called on the search page.  Anywhere else? */


if (user_access("create product content"))
{
/* older stuff I think we may go back to
	$link_args = array(
		"!add_link"				=> l(t("Add it directly to our Product Database"), "product/new"),
		"!external_search_link"	=> l(t("importing"), substr($_SERVER['SCRIPT_URL'], 1), array("query" => array("external_product_search" => 1))),
	);

	//echo t("Can't find your product? !add_link to Vibio!", $link_args);
	//echo '<span class="link_to_add_item">' . t("Can't find your item? !add_link" , $link_args) . '</span>';
*/
	echo l('+ Add Item', "product/new", array(
		attributes => array(
			alt=>"Add an item to our database",
			'class'=>"autonodal ",   
				// not discussed or time-budgeded, does it work
				// out of box?
			)
		)
	);
}
?>
