<?php
if ($is_last_stage)
{
	$next_stage_text = "<a href='#' class='tutorial_init_next_stage'>".t("Or, skip this step and explore Vibio!")."</a>";
}
else
{
	$next_stage_text = t("Continue");
	$next_stage_text = "
		<div class='tutorial_init_next_stage'>
			<div class='tutorial_button_left'></div>
			<div class='tutorial_button_mid'>
				$next_stage_text
			</div>
			<div class='tutorial_button_right'></div>
		</div>
	";
}

$stage_html = "
	<div id='tutorial_stage_{$stage['stage']}' class='tutorial_stage'>
		<h3 class='tutorial_stage_title'>{$stage['content_header']}</h4>
";

foreach ($stage['steps'] as $i => $step)
{
	$step_num = $i + 1;
	$complete_class = $step->complete ? "" : "hidden";
	$stepnum_class = $step->available ? "" : "unavailable";
	$complete_text = t("Done!");
	$header = $step->header ? "<div class='tutorial_step_header'>{$step->header}</div><div class='clear'></div>" : "";
	$stepnum = $step->header ? "<div class='tutorial_step_stepnum $stepnum_class'>$step_num</div>" : "";
	$stage_html .= "
		<div class='tutorial_step' id='tutorial_stage_{$stage['stage']}_{$step_num}'>
			$stepnum
			<div class='step_content'>
				$header
				<div class='step_step_content'>
					{$step->content}
				</div>
			</div>
			<div class='step_complete $complete_class'>
				$complete_text
			</div>
			<div class='clear'></div>
		</div>
	";
}

$stage_html .= "
		<div class='tutorial_next_stage'>
			<a href='#' class='tutorial_init_next_stage'>
				$next_stage_text
			</a>
		</div>
	</div>
";

echo $stage_html;
?>