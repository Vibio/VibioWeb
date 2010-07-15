<?php
echo "
	<div class='ebay_search_item'>
		Title: <a href='{$item->viewItemURL}'>{$item->title}</a><br />
		Current Price: \${$item->sellingStatus->convertedCurrentPrice}<br />
	</div>
";
?>