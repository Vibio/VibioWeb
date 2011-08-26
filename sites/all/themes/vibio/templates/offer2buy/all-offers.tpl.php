<?php
$header = "<h2>".t("Offers on Your Items")."</h2>";
$content = "";

if (empty($items))
{
	$empty_message = t("There are no offers on any of your items");
	$content = "<small>$empty_message</small>";
}
else
{
	$content = "<table>";
	foreach ($items as $i)
	{
		$content .= theme("offer2buy_offer_list", $i['offers'], true);
	}
	$content .= "</table>";
}

echo "
	<div class='offer2buy_notification rounded_content'>
		$header
		<div class='view-content indent'>
			$content
		</div>
	</div>
";
?>
