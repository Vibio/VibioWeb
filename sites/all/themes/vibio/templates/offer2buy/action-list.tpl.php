<?php
$header = $type == "required" ? t("Required") : t("Pending");
$header = t("!type Actions", array("!type" => $header));
$description = $type == "required" ? t("These are actions that you need to do") : t("These are actions others need to do for items you've won");

$output = empty($list) ? t("There is nothing that needs to be done here.") : "<div class='view-content indent'><table>";

if (empty($list))
{
	$output = t("There's nothing you need to do here");
}
else
{
	$output = "<table>";
	foreach ($list as $action)
	{
		$output .= theme("offer2buy_action_item", $action, $type);
	}
	$output .= "</table>";
}


echo "
	<div class='offer2buy_notification rounded_content'>
		<h2>$header</h2>
		$description
		<div class='view-content indent'>
			$output
		</div>
	</div>
";
?>
