<?php
if ($profile_picture)
{
	echo "
		<a href='/my-dashboard'>
			<img id='profile_ext_displayblock_image' src='$profile_picture' />
		</a>
	";
}

echo "
	<div id='profile_ext_displayblock_subitems'>
		$sub_items
	</div>
	<div class='clear'></div>
";
?>