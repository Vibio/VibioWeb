<?php
$tab_html = "";
$tab_content = "";
foreach ($profile_tabs as $title => $tab)
{
	$tab_id = "profiletab_".str_replace(" ", "_", strtolower(trim(preg_replace('/([^\sa-z])/i', '', $title))));
	$tab_html .= "
		<li>
			<a href='#{$tab_id}'>
				<span class='tab'>{$title}</span>
			</a>
		</li>
	";
	$tab_content .= "<div id='$tab_id'>{$tab}</div><div class='clear'></div>";
}
?>

<div id='profile_tabs' class='tabs'>
	<div class='tabs'>
		<ul class='tabs primary clearfix'>
			<?php echo $tab_html; ?>
		</ul>
	</div>
	<?php echo $tab_content; ?>
</div>