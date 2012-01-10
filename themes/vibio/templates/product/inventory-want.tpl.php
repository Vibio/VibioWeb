<?php
$text = t("Want");
echo "
	<a class='inventory_want' onclick=\"window.open('/node/{$nid}')\">
		$text
	</a>
";
