<?php
if ($node->field_posting_type[0]['value'] == VIBIO_ITEM_TYPE_SELL)
{
	global $user;
	
	if ($node->offer2buy['settings']['allow_offer_views'] || $user->uid == $node->uid)
	{
		echo theme("offer2buy_offer_list", $node->offer2buy['offers'], $user->uid == $node->uid);
		echo "<br />";
	}
	
	echo t("Item price: \$!price", array("!price" => $node->offer2buy['settings']['price']));
	
	if ($user->uid > 0 && $user->uid != $node->uid)
	{
		echo theme("offer2buy_init", $node->nid);
	}
}

echo "
	<h2>{$node->title}</h2>
	{$node->body}
";
?>