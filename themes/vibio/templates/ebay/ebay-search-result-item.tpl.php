<?php
$image = $item->galleryURL ? $item->galleryURL : "/themes/vibio/images/icons/default_item.png";

echo "
	<div class='ebay_search_item'>
		<div class='ebay_image'>
			<img src='$image' />
		</div>
		<div class='ebay_info'>
			<a href='{$item->viewItemURL}'>{$item->title}</a><br />
			Current Price: \${$item->sellingStatus->convertedCurrentPrice}
		</div>
		<div class='clear'></div>
	</div>
";
?>