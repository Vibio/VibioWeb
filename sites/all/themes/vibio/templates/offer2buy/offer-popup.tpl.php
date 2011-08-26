<?php
$text = t("view offers");
echo "
	<div class='offer2buy_popup'>
		&nbsp;- <a class='offer2buy_offer_view_popup_init'>
			$text
		</a>
		<div class='offer2buy_offer_view_popup'>
			<div class='view-content'><table>
				$offer_list
			</table></div>
		</div>
	</div>
";
?>
