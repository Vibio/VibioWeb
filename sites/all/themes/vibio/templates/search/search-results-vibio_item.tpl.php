<?php
echo $pager;

echo "
	<table class='search-results $type'>
		$search_results
	</table>
";

if (user_access("create product content"))
{
	echo t("Can't find your product? !create_link to Vibio!", array("!create_link" => l(t("Add it"), "product/add")));
}

echo $pager;
?>
