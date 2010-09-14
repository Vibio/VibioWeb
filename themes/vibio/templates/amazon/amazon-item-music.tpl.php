<?php
$artist = isset($item['artist']) ? "Artist: ".implode(", ", $item['artist'])."<br />" : "";
$label = isset($item['label']) ? "Label: {$item['label']}<br />" : "";
$numdiscs = isset($item['numberofdiscs']) ? "Discs: {$item['numberofdiscs']}<br />" : "";
$release = isset($item['releasedate']) ? "Relase Date: {$item['releasedate']}<br />" : "";

echo "
	$artist
	$label
	$numdiscs
	$release
";
?>