<?php
if (isset($collection->collection_title))
{
	echo "
		<a href='/collections/{$collection->cid}'>
			<img src='{$collection->image}' /><br />
			{$collection->collection_title}
		</a>
	";
}
?>