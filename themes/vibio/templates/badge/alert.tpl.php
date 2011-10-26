<?php
global $user;
$uid = $user->uid;

$header = t("Congratulations!");
$footer = t("!click_here", array("!click_here" => l(t("Click here to view all your badges"), "user/{$uid}/badges")));
?>

<div id="badge_alert_container_earned">
	<h5><?php echo $header; ?></h5>
	<?php echo $badge_html; ?>
	<div class="badge_alert_title_earned">
		<?php echo $badge_title; ?>
	</div>
	
	<div id="badge_alert_footer_earned">
		<?php echo $footer; ?>
	</div>
</div>