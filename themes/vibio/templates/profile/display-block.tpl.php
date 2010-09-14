<?php
if ($profile_picture)
{
	echo "
		<a href='/user/{$display_user->uid}'>
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