<?php
if (isset($collection->collection_title))
{
	echo "
		<a href='/collections/{$collection->cid}'>
			<img src='{$collection->image}' title='{$collection->collection_description}' alt='{$collection->collection_description}' /><br />
			{$collection->collection_title}
		</a>
	";
	
	if ($collection->is_owner)
	{
		echo "<br />";
		echo "<small>".l(t("Manage Collection"), "collections/manage/{$collection->cid}")."</small>";
	}
}
?>