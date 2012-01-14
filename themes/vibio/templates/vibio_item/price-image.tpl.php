<?php

// I don't have the right field yet in the if,
//  But this will throw an image instead of the dollar sign.
if ( $item->field_have_want_like[0]['value'] == VIBIO_ITEM_WANT ) {
		// css decision: item_pricetag or item_want or both?
		$image = "want.jpg";
    echo "
      <div class='item_pricetag item_want'>
        <img src='/themes/vibio/images/item/{$image}' />
      </div>
    ";	
}
/*echo "GREPTHISHERE " . print_r($item, true); ... have_want_like field
not showing up as expected */

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
