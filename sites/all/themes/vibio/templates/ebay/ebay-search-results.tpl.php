<?php
if ($args['expandable_search'])
{
	echo "
		<div class='ebay_expandable_search'>
			expandable search menu
		</div>
	";
}

$items = "";
foreach ($search_result->SearchResult->ItemArray->Item as $result)
{
	$items .= theme("ebay_search_result_item", $result);
}

echo "
	<div class='ebay_search_results'>
		$items
	</div>
";
?>