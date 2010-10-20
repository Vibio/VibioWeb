<?php
if (user_access("create product content"))
{
	$link_args = array(
		"!add_link"				=> l(t("Add it"), "product/add"),
		"!external_search_link"	=> l(t("importing"), substr($_SERVER['SCRIPT_URL'], 1), array("query" => array("external_product_search" => 1))),
	);

	echo t("Can't find your product? !add_link to Vibio!", $link_args);
}
?>