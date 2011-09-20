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
		//$description = _vibio_amazon_clean_content_allowhtml($review);
	}
	
	if (empty($description))
	{
		$description = $unavailable;
	}
}

$details = theme("vibio_amazon_item_details", $node);

if (strlen($description) > PRODUCT_DETAIL_SNIPPET_LENGTH)
{
	$snippet = strip_tags(substr($description, 0, PRODUCT_DETAIL_SNIPPET_LENGTH))."...";
	
	$body = "
		<div class='product_snippet'>
			$snippet <a href='#' class='product_snippet_link'>more</a>
		</div>
		<div class='product_snippet product_snippet_expand'>
			$description 
			<div class='clear'></div>
			$details<br />
			<a href='#' class='product_snippet_link'>less</a>
		</div>
	";
}
else
{
	$body = "
		$description
		<div class='clear'></div>
		$details
	";
}

echo $body;
?>