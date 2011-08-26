<?php
$text = t("Want");
echo "
	<button class='inventory_want' onclick=\"window.location='/node/{$nid}'\">
		$text
	</button>
";
