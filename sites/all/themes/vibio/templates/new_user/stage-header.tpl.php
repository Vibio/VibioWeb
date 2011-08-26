<?php
if ($stage['stage'] < $user_stage)
{
	$class = "stage_complete";
}
elseif ($stage['stage'] == $user_stage)
{
	$class = "stage_current_stage";
}
else
{
	$class = "stage_incomplete";
}

$class .= $stage['stage'] == -1 ? " stage_first" : "";

$zindex = min(100, 100 - $stage['stage']);

echo "
	<div id='stage_header_{$stage['stage']}' class='stage_header $class' style='z-index: $zindex;'>
		<span class='stage_header_text'>
			{$stage['header']}
		</span>
	</div>
";
?>