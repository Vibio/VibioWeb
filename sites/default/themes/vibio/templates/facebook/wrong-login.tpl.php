<?php
$message = t("The Facebook account you logged in with is not the account you have linked on Vibio. Please try again with the correct account.");
?>

<div id="facebook_wrong_login" class="fb_dialog">
	<div class="fb_header">
		<img src="/themes/vibio/images/facebook/logo.png" />
	</div>
	<div class="fb_body">
		<div class="fb_login_message">
			<?php echo $message; ?>
		</div>
		<a href="#" class="fb_login fb_refresh">Log in!</a>
	</div>
</div>