<?php
global $user;
$image = file_create_url($node->field_main_image[0]['filepath']);

if (isset($node->amazon_data))
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
	
	$theme = "vibio_amazon_item_"._amazon_clean_type($node->amazon_data['producttypename']);
	if ($details = theme($theme, $node->amazon_data))
	{
		$details = "
			<h4>Details</h4>
			$details
		";
	}
	
	$create = $page || $user->uid != $node->uid ? l("I have one!", "product/{$node->nid}/add-to-inventory") : "";
	$external_link = $page ? t("Get \"!item\" from !external_link.", array("!item" => $node->title, "!external_link" => l(t("Amazon"), $node->amazon_data['detailpageurl'], array("absolute" => true)))) : "";
	
	echo "
		<img src='{$image}' style='float: left; padding: 0 10px 10px 0;' />
		$create
		<h4>Description</h4>
		$description
		$details
		<p>$external_link</p>
		<div style='clear: left;'></div>
	";	
}
?>