<?php
$unavailable = t("No description available.");
if (empty($node->amazon_data['editorialreviews']))
{
	$description = $unavailable;
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
	
	if (empty($description))
	{
		$description = $unavailable;
	}
}

$details = theme("vibio_amazon_item_details", $node);

echo "
	<h4 class='product_description'>Description</h4>
	$description
	<div style='clear: left;'></div>
	$details
";	
?>