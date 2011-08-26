<?php
foreach ($offers as $offer)
{
	echo theme("offer2buy_offer", $offer, $is_owner);
}
?>