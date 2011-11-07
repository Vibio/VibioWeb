<?php
$row = $view->result[$view->row_index];

/* stephen: this prints the row for a heartbeat.
 * At some point in September 2011 the messages disappeared.
 * I'm not sure what Nelson was trying to do or where this info was 
 *  supposed to come from / how it was supposed to be transformed,
 *  but it appears to be right there in the $row->message
 */
$message = "";
// Get's a second image if it's got an nid (item, product?)
if ($row->nid)
{
	$is_item_message = strpos($row->message_id, "node") !== false || strpos($row->message_id, "item") !== false;
	if ($is_item_message && module_exists("vibio_item")) //this nid is for a node
	{
		$image = theme('vibio_item_image', $row->nid, 'tiny_profile_pic');
	}
	elseif (strpos($row->message_id, "collection") !== false && module_exists("collection")) //this nid is actually a cid. hax.
	{
		module_load_include("inc", "collection");
		$access = privacy_get_access_level($row->actor->uid); // this works because other users viewing this view will have the same access level as the current user
		$image = collection_get_image($row->nid, false, $access);
	}
	else
	{
		$image = theme('vibio_item_image', $row->nid, 'tiny_profile_pic');
	}
	$message .= "
		<div class='views_node_image_container'>
			<a href='/node/{$row->nid}'>
				{$image}
			</a>
		</div>
	";
}

/* Neither friends nor badges print the second image.  Seems like they
 *  sure should.  Begun work here, realized it's a feature not a bug/priority,
 *  code snippets should get you started...
 */
//if   [message_id] => heartbeat_become_friends,  $row->uid_target

/*if ($row->variables['@badge']) {

print '<pre>';
print_r($row);
print '</pre>';

}
*/


// where does "$output" come from?  appears to come from nowhere = 
//  it's never set, not sure how this code ever worked (did it?)...
// v1.1 force it.  On my personal homepage, $output never[?] appears
//  to be set
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
