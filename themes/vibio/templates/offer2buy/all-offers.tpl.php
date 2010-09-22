<?php
$header = t("Offers on Your Items");
echo "<h2>$header</h2>";

if (empty($items))
{
	$empty_message = t("There are no offers on any of your items");
	echo "<div class='indent'><small>$empty_message</small></div>";
}
echo "<div class='view-content indent'><table>";
foreach ($items as $i)
{
	echo theme("offer2buy_offer_list", $i['offers'], true);
}
echo "</table></div>";
?>