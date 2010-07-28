<?php
$accept_form = $is_owner ? drupal_get_form("offer2buy_offer_accept_form_{$offer['uid']}", $offer['uid'], $offer['nid']) : "";
$date = date("Y-m-d", $offer['timestamp']);

echo "
	<a href='/user/{$offer['uid']}'>{$offer['name']}</a><br />
	$date<br />
	{$offer['offer']}<br />
	$accept_form
	<br /><br />
";
?>