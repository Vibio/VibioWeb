<?php
$message = t("Your Facebook session has expired! Please use the link below to re-initialize your session.");
?>

<div id="facebook_login_prompt">
	<div class="fb_header">
		<img src="/sites/all/themes/vibio/images/facebook/logo.png" />
	</div>
	<div class="fb_body">
		<div class="fb_login_message">
			<?php echo $message; ?>
		</div>
		<a href="#" class="fb_login fb_refresh">Log in!</span>
	</div>
</div>