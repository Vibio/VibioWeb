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

$product = node_load($node->product_nid);
$product->item =& $node;
$product_data = $node->product_nid ? theme("node", $product) : "";
$user_other_items = theme("vibio_item_user_other_items", $node);

echo "
	{$product_data}
	<div class='product_extra_data'>
		{$node->body}
		{$item_extra_images}<br />
		{$user_other_items}
	</div>
";
?>