<?php
$row = $view->result[$view->row_index];

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
		$image = collection_get_image($row->nid);
	}
	
	$output = "
		<a href='/node/{$row->nid}'>
			<img class='vibio_item_views_image' src='$image' />
		</a>
		$output
	";
}

echo $output;
?>