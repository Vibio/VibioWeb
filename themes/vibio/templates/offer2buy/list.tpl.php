<?php
if (empty($offers))
{
	echo t("There are currently no offers for this item.");
}
else
{
	foreach ($offers as $offer)
	{
		echo theme("offer2buy_offer", $offer, $is_owner);
	}
}
?>