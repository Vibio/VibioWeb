<?php
echo "
	<div class='badge_list_badge'>
		<div class='badge_image_container'>
			<img class='badge_image' src='{$badge->image_src}' />
		</div>
		<div class='badge_info'>
			{$badge->title}<br />
			{$badge->description}<br />
			<span class='timestamp_uncalculated'>{$badge->tstamp}</span>
		</div>
		<div class='clear'></div>
	</div>
";
?>