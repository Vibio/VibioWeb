<?php
if ($node->field_posting_type[0]['value'] == VIBIO_ITEM_TYPE_SELL)
{
	global $user;
	
	if ($node->offer2buy['settings']['allow_offer_views'] || $user->uid == $node->uid)
	{
		if (!empty($node->offer2buy['offers']))
		{
			var_dump($node->offer2buy['offers']);
		}
		else
		{
			echo t("There are currently no offers on this item.");
		}
		echo "<br />";
	}
	
	echo t("Item price: \$!price", array("!price" => $node->offer2buy['settings']['price']));
	
	if ($user->uid > 0 && $user->uid != $node->uid)
	{
		echo theme("offer2buy_init", $node->nid);
	}
}
var_dump($node);
?>