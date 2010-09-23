<?php
if ($dos === false)
{
	echo t("This is you");
}
elseif (!empty($dos))
{
	$links = array();
	$you_link = false;
	foreach ($dos as $uid => $name)
	{
		if (!$you_link)
		{
			$you_link = true;
			$name = t("You");
		}
		
		$links[] = l($name, "user/{$uid}");
	}
	
	echo implode(" -> ", $links);
}
?>