<?php
if ($item->field_posting_type[0]['value'] == VIBIO_ITEM_TYPE_SELL)
{
	if (!$item->offer2buy || $item->offer2buy['settings']['price'] == 0)
	{
		$image = $image_type == "full" ? "pricetag-large-dollarsign" : "pricetag-small";
		
		echo "
			<div class='item_pricetag'>
				<img src='/themes/vibio/images/item/{$image}.png' />
			</div>
		";
	}
	else
	{
		$price = number_format($item->offer2buy['settings']['price'], 2);
		echo "
			<div class='item_pricetag'>
				<div class='item_pricetag_left'></div>
				<div class='item_pricetag_right'>
					<span class='item_pricetag_price'>
						$price
					</span>
				</div>
			</div>
		";
	}
}
?>