<?php    /* seems to print a link on the other user page -breadcrumb */
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
	
	$out = implode("<span class='breadcrumb-sep'>-</span>", $links);
}

echo "
	<div class='dos_display'>
		$out
	</div>
";
?>
