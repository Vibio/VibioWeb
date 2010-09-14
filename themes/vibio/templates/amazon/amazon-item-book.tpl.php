<?php
if (isset($item['author']))
{
	$author = "Author: ".implode(", ", $item['author'])."<br />";
}
if (isset($item['numberofpages']))
{
	$numpages = "Pages: {$item['numberofpages']}<br />";
}
if (isset($item['publisher']))
{
	$publisher = "Publisher: {$item['publisher']}<br />";
}
if (isset($item['isbn']))
{
	$isbn = "ISBN #: {$item['isbn']}<br />";
}

echo "
	{$author}
	{$publisher}
	{$isbn}
	{$numpages}
";
?>