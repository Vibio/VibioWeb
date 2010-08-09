<?php
$dos = !empty($item['user']['dos']) ? "<xmp style='float: right;'>".print_r($item['user']['dos'], true)."</xmp>" : "";
echo "
	<div class='item_owner'>
		<a href='/user/{$item['user']['uid']}'>
			<img src='{$item['user']['picture']}' title='{$item['user']['name']}' />
		</a>
		<span class='item_info'>
			{$item['node']}
		</span>
		$dos
	</div>
	<div style='clear: both;'></div>
";
?>