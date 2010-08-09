<?php
$title = $type == "network" ? t("In your network") : t("On Vibio");
$owners = "";

if (empty($items))
{
	$owners = t("No items found.");
}
else
{
	foreach ($items as $i)
	{
		$owners .= theme("product_owner", $i);
	}
}

echo "
	<h4>$title</h4>
	<div style='margin-left: 25px;'>
		$owners
	</div>
";
?>