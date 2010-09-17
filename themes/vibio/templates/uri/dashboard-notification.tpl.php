<?php
switch ($rel_type)
{
	default:
		$message = t("!user wants to be friends", array("!user" => l($relationship->requester->name, "user/{$relationship->requester_id}")));
}

$requester_image = theme("user_picture", $relationship->requester);
echo "
	<div class='uri_notification rounded_content'>
		$requester_image
		<div class='uri_notifications_summary'>
			$message
			<div class='uri_notifications_actions'>
				$rel_actions
			</div>
		</div>
		<div class='clear'></div>
	</div>
"
?>