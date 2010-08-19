<?php
global $user;
$image = file_create_url($node->field_main_image[0]['filepath']);

if (empty($node->amazon_data['editorialreviews']))
{
	$description = t("No description available.");
}
else
{
	foreach ($node->amazon_data['editorialreviews'] as $review)
	{
		if ($review['source'] == "Amazon.com Product Description")
		{
			$review = $review['content'];
		}
	}
	
	if (!$description)
	{
		$review = $node->amazon_data['editorialreviews'][0]['content'];
	}
	
	if (!($description = _vibio_amazon_clean_content($review)))
	{
		$description = _vibio_amazon_clean_content_allowhtml($review);
	}
}

$theme = "vibio_amazon_item_"._amazon_clean_type($node->amazon_data['producttypename']);
if ($details = theme($theme, $node->amazon_data))
{
	$details = "
		<h4>Details</h4>
		$details
	";
}

$manage_link = theme("product_inventory_manage_link", $node);

echo "
	<a href='/node/{$node->nid}'><img src='{$image}' style='float: left; padding: 0 10px 10px 0;' /></a>
	$manage_link
	<h4>Description</h4>
	$description
	<div style='clear: left;'></div>
	$details
";	
?>