<?php
global $pager_page_array, $pager_total;

$pager_element = 0;//is_array($pager_page_array) && !empty($pager_page_array) ? count($pager_page_array) : 0;
$owners = "";

$pager_page_array[$pager_element] = $data['page'];
$pager_total_items[$pager_element] = $data['count'];
$pager_total[$pager_element] = ceil($data['count'] / PRODUCT_OWNER_DISPLAY_PER_PAGE);

$pager = theme("pager", array(), PRODUCT_OWNER_DISPLAY_PER_PAGE, $pager_element);

if (empty($data['results']))
{
	$owners = t("No items found.");
}
else
{
	foreach ($data['results'] as $item)
	{
		$owners .= theme("product_owner", $item);
	}
}

echo "
	$owners
	$pager
";
?>