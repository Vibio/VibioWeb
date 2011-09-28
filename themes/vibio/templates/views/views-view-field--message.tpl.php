<?php
$row = $view->result[$view->row_index];

/* stephen: this prints the row for a heartbeat.
 * At some point in September 2011 the messages disappeared.
 * I'm not sure what Nelson was trying to do or where this info was 
 *  supposed to come from / how it was supposed to be transformed,
 *  but it appears to be right there in the $row->message
 */
$message = "";
if ($row->nid)
{
	$is_item_message = strpos($row->message_id, "node") !== false || strpos($row->message_id, "item") !== false;
	if ($is_item_message && module_exists("vibio_item")) //this nid is for a node
	{
		$image = _vibio_item_get_image($row->nid);
	}
	elseif (strpos($row->message_id, "collection") !== false && module_exists("collection")) //this nid is actually a cid. hax.
	{
		module_load_include("inc", "collection");
		$access = privacy_get_access_level($row->actor->uid); // this works because other users viewing this view will have the same access level as the current user
		$image = collection_get_image($row->nid, false, $access);
	}
	else
	{
		$image = _vibio_item_get_image($row->nid); // dunno what else to do here.. just assume it's an nid
	}
	
	$message .= "
		<div class='views_node_image_container'>
			<a href='/node/{$row->nid}'>
				<img src='$image' />
			</a>
		</div>
	";
}
// where does "$output" come from?  appears to come from nowhere...
// v1.1 force it.
if ( !$output ) {
	$output = $row->message;
}
$message .= "
	<div class='views_message'>
		$output
	</div>
	<div class='clear'></div>
";

echo $message;
?>
