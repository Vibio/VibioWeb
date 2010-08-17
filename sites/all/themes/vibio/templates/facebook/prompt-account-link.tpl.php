<?php
$message = t("You currently do not have your Facebook account linked to Vibio. Please !link and follow the instructions to link your Facebook account.", array("!link" => l(t("click here"), "user/{$user->uid}/linked-accounts")));
?>

<div id="facebook_prompt_account_link" class="fb_dialog">
	<div class="fb_header">
		<img src="/sites/all/themes/vibio/images/facebook/logo.png" />
	</div>
	<div class="fb_body">
		<div class="fb_login_message">
			<?php echo $message; ?>
		</div>
	</div>
</div>