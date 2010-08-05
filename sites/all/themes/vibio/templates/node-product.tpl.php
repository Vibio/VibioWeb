<?php
$image = file_create_url($node->field_main_image[0]['filepath']);

foreach ($node->amazon_data['editorialreviews'] as $review)
{
	if ($review['source'] == "Amazon.com Product Description")
	{
		$allowed_tags = array("br", "p", "strong", "sub", "sup", "span");
		$description = filter_xss($review['content'], $allowed_tags);
	}
}

echo "
	<div>
		<img src='{$image}' style='float: left; padding: 0 10px 10px 0;' />
		<h2>{$node->title}</h2>
		<div style='clear: left;'></div>
		$description
	</div>
	<div style='clear: left;'></div>
";
?>