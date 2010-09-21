<?php
$message = t("You have a pending image");

echo "
	<div class='profile_notification rounded_content'>
		<div class='picture'>
			<img src='{$imap_image->url}' />
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