<div class="profile_progress">
	<?php echo t("Profile Completeness"); ?>:
</div>
<div id="profile_progressbar" class="profile_progress"></div>
<?php
if ($completion_steps)
{
	$text = t("What's left?");
	$open_link = "<a href='#' id='profile_completion_steps_init'>$text</a>";
	$header = t("You're almost done...");
	$body = t("There are only a few more things you need to do to have a complete Vibio profile!");
	
	echo "
		<div class='profile_progress'>
			$open_link
		</div>
		<div id='profile_completion_steps_container'>
			<div style='font-size: 18px; font-weight: bold;'>$header</div>
			<div>$body</div>
			$completion_steps
		</div>
	";
}
?>
<div class="clear"></div>