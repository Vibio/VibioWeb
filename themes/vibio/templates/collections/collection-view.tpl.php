<?php
$sidebar_header = t("!user Collections", array("!user" => $collection_owner_name));
$collections_link = l(t("View Complete List"), "user/{$collection_owner}/inventory");

echo "
	<div id='collection_main'>
		{$collection_display}
	</div>
	<div id='collection_sidebar'>
		$add_item_link
		<h5>{$sidebar_header}</h5>
		{$collections_link}
		{$collection_sidebar_output}
	</div>
	<div class='clear'></div>
";
?>