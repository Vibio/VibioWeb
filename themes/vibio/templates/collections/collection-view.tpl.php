<?php
$sidebar_header = t("My Collections");
$collections_link = l(t("View Complete List"), "user/{$collection_owner}/inventory");

echo "
	<div id='collection_main'>
		{$collection_display}
	</div>
	<div id='collection_sidebar'>
		<h5>{$sidebar_header}</h5>
		{$collections_link}
		{$collection_sidebar_output}
	</div>
	<div class='clear'></div>
";
?>