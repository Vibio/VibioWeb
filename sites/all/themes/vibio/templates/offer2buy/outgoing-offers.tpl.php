<?php
global $user;

$header = "<h2>".t("Offers You've Made")."</h2>";
$content = "";

if (empty($offers))
{
	$empty_message = t("You've no outstanding offers.");
	$content = "<small>$empty_message</small>";
}
else
{
	$content = "<table>";
	foreach ($offers as $o)
	{
		$item_image = _vibio_item_get_image($o->item->nid);
		$pending = offer2buy_offer_is_pending($user->uid, $o->item->nid) ? t("(pending)") : "";
		$rejected = !is_null($o->offer->rejection_note) ? "<strong>".t("Rejected").":</strong> {$o->offer->rejection_note}" : "";
		$profile_pic = $o->item->picture ? file_create_url($o->item->picture) : "/themes/vibio/images/icons/default_user.png";
		$message = t("$!amount on !user's !item!comment", array(
			"!amount"	=> $o->offer->offer,
			"!user"		=> l($o->item->name, "user/{$o->item->uid}"),
			"!item"		=> l($o->item->title, "node/{$o->item->nid}"),
			"!comment"	=> $o->offer->comment ? ":<br />{$o->offer->comment}" : "",
		));
		$modify_offer = theme("offer2buy_init", $o->item->nid, "edit");

		$content .= "
			<tr>
				<td class='views-field views-field-picture'>
					<div class='picture'>
						<a href='/user/{$o->item->uid}'>
							<img src='$profile_pic' alt='{$o->item->name}' title='{$o->item->name}' />
						</a>
						<span class='view_activity_timestamp timestamp_uncalculated'>{$o->offer->timestamp}</span>
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
						$rejected
						$modify_offer
					</div>
				</td>
			</tr>
			";
	}
	$content .= "</table>";
}


echo "
	<div class='offer2buy_notification rounded_content'>
		$header
		<div class='view-content indent'>
			$content
		</div>
	</div>
";
?>
