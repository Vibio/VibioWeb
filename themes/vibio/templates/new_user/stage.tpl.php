<?php
$stage_html = "
	<div id='tutorial_stage_{$stage['stage']}' class='tutorial_stage'>
		<h3>{$stage['content_header']}</h4>
";
foreach ($stage['steps'] as $i => $step)
{
	$step_num = $i + 1;
	$header_class = $step->available ? "" : "unavailable";
	$complete_class = $step->complete ? "" : "hidden";
	$complete_text = t("Done!");
	$stage_html .= "
		<div class='tutorial_step' id='tutorial_stage_{$stage['stage']}_{$step_num}'>
			<h5 class='$header_class'>{$step_num}: {$step->header}</h5>
			<div class='step_complete $complete_class'>
				$complete_text
			</div>
			<div class='step_content'>
				{$step->content}
			</div>
		</div>
	";
}

$stage_html .= "</div>";

echo $stage_html;
?>