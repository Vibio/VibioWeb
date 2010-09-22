<?php
$accept_form = $is_owner ? drupal_get_form("offer2buy_offer_accept_form_{$offer['uid']}", $offer['uid'], $offer['nid']) : "";
$date = date("z") == date("z", $offer['timestamp']) ? date("g:iA", $offer['timestamp']) : date("M j", $offer['timestamp']);
$item_image = _vibio_item_get_image($offer['nid']);
$offerer = user_load($offer['uid']);
$node = node_load($offer['nid']);
$profile_pic = $offerer->picture ? file_create_url($offerer->picture) : "/themes/vibio/images/icons/default_user.png";
$alt = $offerer->name;

$message = t("!user made an offer of $!amount on !item!comment", array(
	"!user"		=> l($offer['name'], "user/{$offer['uid']}"),
	"!amount"	=> $offer['offer'],
	"!item"		=> l($node->title, "node/{$offer['nid']}"),
	"!comment"	=> $offer['comment'] ? ":<br />".$offer['comment'] : "",
));

echo "
	<tr>
		<td class='views-field views-field-picture'>
			<div class='picture'>
				<a href='/user/{$offerer->uid}'>
					<img src='$profile_pic' alt='$alt' title='$alt' />
				</a>
				<span class='view_activity_timestamp'>$date</span>
			</div>
		</td>
		<td class='views-field views-field-message'>
			<div class='views_node_image_container'>
				<a href='/node/{$node->nid}'>
					<img src='$item_image' />
				</a>
			</div>
			<div class='views_message'>
				$message
				$accept_form
			</div>
			<div class='clear'></div>
		</td>
	</tr>
";
?>