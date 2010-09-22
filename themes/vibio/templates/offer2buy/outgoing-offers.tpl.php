<?php
global $user;

$header = t("Offers You've Made");
echo "<h2>$header</h2>";

if (empty($offers))
{
	$empty_message = t("You've no outstanding offers.");
	echo "<div class='indent'><small>$empty_message</small></div>";
}

echo "<div class='view-content indent'><table>";
foreach ($offers as $o)
{
	$item_image = _vibio_item_get_image($o->item->nid);
	$pending = offer2buy_offer_is_pending($user->uid, $o->item->nid) ? t("(pending)") : "";
	$date = date("z") == date("z", $o->offer->timestamp) ? date("g:iA", $o->offer->timestamp) : date("M j", $o->offer->timestamp);
	$profile_pic = $o->item->picture ? file_create_url($o->item->picture) : "/themes/vibio/images/icons/default_user.png";
	$message = t("$!amount on !user's !item!comment", array(
		"!amount"	=> $o->offer->offer,
		"!user"		=> l($o->item->name, "user/{$o->item->uid}"),
		"!item"		=> l($o->item->title, "node/{$o->item->nid}"),
		"!comment"	=> $o->offer->comment ? ":<br />{$o->offer->comment}" : "",
	));
	
	echo "
		<tr>
			<td class='views-field views-field-picture'>
				<div class='picture'>
					<a href='/user/{$o->item->nid}'>
						<img src='$profile_pic' alt='{$o->item->name}' title='{$o->item->name}' />
					</a>
					<span class='view_activity_timestamp'>$date</span>
				</div>
			</td>
			<td class='views-field views-field-message'>
				<div class='views_node_image_container'>
					<a href='/node/{$o->item->nid}'>
						<img src='$item_image' />
					</a>
				</div>
				<div class='views_message'>
					$message<br />
					$pending
				</div>
			</td>
		</tr>
	";
}
echo "</table></div>";
?>