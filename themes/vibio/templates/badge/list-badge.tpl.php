<?php
echo "
<p class='section_title'>Badges Awarded <span class='section_sub_title'><a href='/badge/unearned-list'>What other badges can I earn?</a></span></p>
<p class='badges-intro'>Build your social status on Vibio by creating collections of your most prized and unique possessions. Badges are earned when you reach certain milestones.</p>	
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