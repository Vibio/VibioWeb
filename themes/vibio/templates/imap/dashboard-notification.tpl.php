<?php
$message = t("You have a pending image");

echo "
	<div class='profile_notification rounded_content'>
		<div class='picture'>
			<a href='{$imap_image->url}' rel='prettyphoto'>
				<img src='{$imap_image->url}' />
			</a>
		</div>
		<div class='profile_notifications_summary'>
			$message
			<div class='profile_notifications_actions'>
				$rel_actions
			</div>
		</div>
		<div class='clear'></div>
	</div>
";
?>