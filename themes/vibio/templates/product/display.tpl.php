<?php
if (strlen($description) > PRODUCT_DETAIL_SNIPPET_LENGTH) {
	$snippet = strip_tags(substr($node->body, 0, PRODUCT_DETAIL_SNIPPET_LENGTH))."...";
	
	$body = "
		<div class='product_snippet'>
			$snippet <a href='#' class='product_snippet_link'>More</a>
		</div>
		<div class='product_snippet product_snippet_expand'>
			{$node->body} <a href='#' class='product_snippet_link'>Less</a> 
			<div class='clear'></div>>
		</div>
	";
}
else {
	$body = "
		{$node->body}
		<div class='clear'></div>
	";
}

echo $body;
?>