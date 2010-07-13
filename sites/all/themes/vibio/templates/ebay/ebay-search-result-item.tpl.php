<?php
echo "
	<div class='ebay_search_item'>
		Title: <a href='{$item->ViewItemURLForNaturalSearch}'>{$item->Title}</a><br />
		Current Price: {$item->ConvertedCurrentPrice}<br />
	</div>
";
?>