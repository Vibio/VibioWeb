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

$action_html = "";
if (!empty($profile_actions))
{
	$new_header = t("Looks like you're new");
	$new_helptext = t("Here are some things to help you get started...");
	$action_html .= "<div id='profile_call_to_action'>";
	$action_html .= "<h3>$new_header</h3>";
	$action_html .= "<span>$new_helptext</span>";

	$action_html .= "<table>";
	foreach ($profile_actions as $action)
	{
		$action_html .= "
			<tr>
				<td class='profile_action_image'>
		";
		if ($action['image'])
			$action_html .= "<img src='{$action['image']}' />";
		$action_html .= "</td>";
		$action_html .= "<td class='profile_action'>{$action['text']}</td>";
	}

	$action_html .= "</table></div>";
}


echo $action_html;
?>

<div id='profile_tabs' class='tabs'>
	<div class='tabs'>
		<ul class='tabs primary clearfix'>
			<?php echo $tab_html; ?>
		</ul>
	</div>
	<?php echo $tab_content; ?>
</div>

<?php
echo $newuser_tutorial;
?>