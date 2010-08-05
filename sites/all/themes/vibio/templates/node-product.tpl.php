<?php
$image = file_create_url($node->field_main_image[0]['filepath']);
$allowed_tags = array("br", "p", "strong", "sub", "sup", "span");

foreach ($node->amazon_data['editorialreviews'] as $review)
{
	if ($review['source'] == "Amazon.com Product Description")
	{
		$description = filter_xss($review['content'], $allowed_tags);
	}
}

if (!$description)
{
	$review = array_shift($node->amazon_data['editorialreviews']);
	$description = filter_xss($review['content'], $allowed_tags);
}

$external_link = t("Get \"!item\" from !external_link.", array("!item" => $node->title, "!external_link" => l(t("Amazon"), $node->amazon_data['detailpageurl'], array("absolute" => true))));
$theme = "vibio_amazon_item_"._amazon_clean_type($node->amazon_data['producttypename']);
$details = theme($theme, $node->amazon_data);

echo "
	<div>
		<img src='{$image}' style='float: left; padding: 0 10px 10px 0;' />
		<h2>{$node->title}</h2>
		<div style='clear: left;'></div>
		<h4>Description</h4>
		$description
		<p>$external_link</p>
		<h4>Details</h4>
		$details
	</div>
";
?>