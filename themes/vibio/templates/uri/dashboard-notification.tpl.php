<?php
switch ($rel_type)
{
	default:
		$message = t("!user wants to be friends", array("!user" => l($relationship->requester->name, "user/{$relationship->requester_id}")));
}

$requester_image = theme("user_picture", $relationship->requester);
echo "
	<div class='profile_notification rounded_content'>
		$requester_image
		<div class='profile_notifications_summary'>
			$message
			<div class='profile_notifications_actions'>
				$rel_actions
			</div>
		</div>
		<div class='clear'></div>
	</div>
"
?>