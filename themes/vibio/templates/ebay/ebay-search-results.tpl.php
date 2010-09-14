<?php
$items = "";

foreach ($search_result->searchResult->item as $result)
{
	$items .= theme("ebay_search_result_item", $result);
}

if (!$data['hide_wrapper'])
{
	echo "<div class='ebay_search_results'>";
}

echo $items.$data['pager'];

if (!$data['hide_wrapper'])
{
	echo "</div>";
}
?>