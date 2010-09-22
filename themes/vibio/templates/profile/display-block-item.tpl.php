<?php
echo "
	<div class='profile_ext_displayblock_item'>
		<a href='{$item_data['href']}' alt='{$item_data['title']}' title='{$item_data['title']}'>
			<img src='{$item_data['image']}' alt='{$item_data['title']}' title='{$item_data['title']}' />
			<span>{$item_data['count']}</span>
		</a>
	</div>
";
?>