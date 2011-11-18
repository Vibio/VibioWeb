<?php
echo "
<div class='badge_list_badge'>
		<div class='badge_image_container'>
			{$badge_image}
		</div>
		<div class='badge_info'>
			<h1>{$badge->title}</h1>
			{$badge->description}
			<!-- <span class='timestamp_uncalculated'>{$badge->tstamp}</span> -->
		</div>
		<div class='clear'></div>
	</div>
";
?>
