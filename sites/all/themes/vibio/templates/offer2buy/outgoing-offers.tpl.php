<?php
$header = t("Offers You've Made");
echo "<h2>$header</h2>";

if (empty($offers))
{
	$empty_message = t("You've no outstanding offers.");
	echo "<small>$empty_message</small>";
}

foreach ($offers as $o)
{
	$item_link = l($o->item->title, "node/{$o->item->nid}");
	echo "
		<div class='offer2buy_outgoing_offer'>
			{$item_link}: \${$o->offer->offer}
		</div>
	";
}
?>