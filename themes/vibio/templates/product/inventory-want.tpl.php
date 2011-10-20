<?php
$text = t("Want");
$mouseover = t("I want to find this item");

$attributes = array(
	'id' => "",
	'onclick'=>"window.location='/node/{$nid}'",
);

echo 
	"<div class='inventory_want'>" .
	l($text, "node/$nid", $attributes) .
	"<div class='mouseover'>$mouseover</div></div>";


/*
echo "
	<button class='inventory_want' onclick=\"window.location='/node/{$nid}'\">
		$text
	</button>
";
*/
