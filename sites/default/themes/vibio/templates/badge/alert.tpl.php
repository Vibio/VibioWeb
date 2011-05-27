<?php
global $user;
$uid = $user->uid;

$header = t("Congratulations!");
$footer = t("!click_here to view your badges", array("!click_here" => l(t("Click here"), "user/{$uid}/badges")));
?>

<div id="badge_alert_container">
	<h5><?php echo $header; ?></h5>
	<div class="badge_alert_title">
		<?php echo $badge_title; ?>
	</div>
	<?php echo $badge_html; ?>
	<div id="badge_alert_footer">
		<?php echo $footer; ?>
	</div>
</div>