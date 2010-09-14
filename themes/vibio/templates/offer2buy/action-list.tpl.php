<?php
$header = $type == "required" ? t("Required") : t("Pending");
$header = t("!type Actions", array("!type" => $header));
$description = $type == "required" ? t("These are actions that you need to do") : t("These are actions others need to do for items you've won");

$output = empty($list) ? t("There is nothing that needs to be done here.") : "";
foreach ($list as $action)
{
	$output .= theme("offer2buy_action_item", $action, $type);
}

echo "
	<h2>$header</h2>
	<small>$description</small><br />
	$output
";
?>