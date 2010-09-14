<?php
if (!empty($images))
{
	$header = t("Additional Images");
	$image_html = "";
	
	foreach ($images as $image)
	{
		$image_html .= "
			<a href='$image' rel='prettyphoto[item_image]'>
				<img src='$image' class='node_view_item_image' />
			</a>";
	}
	
	echo "
		<h4>$header</h4>
		$image_html
	";
}
?>