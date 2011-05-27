<?php
$text = t("I want one!");
echo "
	<button class='inventory_want' onclick=\"window.location='/node/{$nid}'\">
		$text
	</button>
";