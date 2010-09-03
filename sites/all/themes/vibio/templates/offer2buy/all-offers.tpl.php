<?php
$header = t("Offers on Your Items");
echo "<h2>$header</h2>";

if (empty($items))
{
	$empty_message = t("There are no offers on any of your items");
	echo "<small>$empty_message</small>";
}
foreach ($items as $i)
{
	$item_link = l($i['item']->title, "node/{$i['item']->nid}");
	$offer_list = theme("offer2buy_offer_list", $i['offers'], true);
	
	echo "
		$item_link
		<div style='margin: 0 0 10px 30px;'>
			$offer_list
		</div>
	";
}
?>