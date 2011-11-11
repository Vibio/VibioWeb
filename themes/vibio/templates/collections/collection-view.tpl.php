
<?php
$sidebar_header = t("!user Collections", array("!user" => $collection_owner_name));
$collections_link = l(t("View Complete List"), "user/{$collection_owner}/inventory");

echo "
	<div id='collection_main'>
		{$collection_display}
	</div>
	<div class='clear'></div>
";
?>