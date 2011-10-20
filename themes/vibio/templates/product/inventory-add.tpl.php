<?php
$text = t("Have");
$mouseover = "I already have this item";
      //alt wording  "Let people know you already have this item"

$attributes = array(
  'id' => "inventory_add_{$nid}",
);

$attributes['class'] = 'inventory_add';

echo 
"<div class='inventory_add'>" .
l($text, "node/$nid", $attributes) .
"<div class='mouseover'>$mouseover</div></div>";



/*
echo "
	<button class='inventory_add' id='inventory_add_{$nid}'>
		$text
	</button>
";
*/
?>
