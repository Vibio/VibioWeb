<?php
if ($dos === false)
{
	$out = t("This is you");
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
	
	$out = implode(" -> ", $links);
}

echo "
	<div class='dos_display'>
		$out
	</div>
";
?>