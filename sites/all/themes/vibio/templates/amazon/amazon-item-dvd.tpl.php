<?php
if (isset($item['actor']))
{
	$actors = "Actors: ". implode(", ", $item['actor'])."<br />";
}
if (isset($item['creator']))
{
	$writers = "Writers: ".implode(", ", $item['creator'])."<br />";
}
if (isset($item['director']))
{
	$directors = "Directors: ".implode(", ", $item['director'])."<br />";
}

echo "
	{$actors}
	{$writers}
	{$directors}
	Format: {$item['binding']}<br />
	Run Time: {$item['runningtime']} minutes<br />
	Aspect Ratio: {$item['aspectratio']}<br />
	Rating: {$item['audiencerating']}<br />
	Studio: {$item['studio']}
";
?>