<?php
$accept_form = $is_owner ? drupal_get_form("offer2buy_offer_accept_form_{$offer['uid']}", $offer['uid'], $offer['nid']) : "";
$date = format_interval(time() - $offer['timestamp']);

echo "
	<div class='offer_details'>
		{$offer['offer']} from <a href='/user/{$offer['uid']}'>{$offer['name']}</a>. {$date} ago
	</div>
	<div class='offer_accept'>
		$accept_form
	</div>
	<div style='clear: both;'></div>
";
?>