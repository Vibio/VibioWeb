<?php
$actors = implode(", ", $item['actor']);
$writers = implode(", ", $item['creator']);
$directors = implode(", ", $item['director']);

echo "
	Actors: {$actors}<br />
	Writers: {$writers}<br />
	Directors: {$directors}<br />
	Format: {$item['binding']}<br />
	Run Time: {$item['runningtime']} minutes<br />
	Aspect Ratio: {$item['aspectratio']}<br />
	Rating: {$item['audiencerating']}<br />
";
?>