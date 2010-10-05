<?php
$t_args = array("!link" => l(t("click here"), "fb/add-account", array("attributes" => array("class" => "fb_link_account"))));
$message = t("You currently do not have your Facebook account linked to Vibio. Please !link to link your Facebook account.", $t_args);
?>

<div id="facebook_prompt_account_link" class="fb_dialog">
	<div class="fb_header">
		<img src="/themes/vibio/images/facebook/logo.png" />
	</div>
	<div class="fb_body">
		<div class="fb_login_message">
			<?php echo $message; ?>
		</div>
	</div>
</div>
