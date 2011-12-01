<div class='fb_ff_message'>
	<div id='fb-message-img'>
		<img src='/themes/vibio/images/no_friends.png' alt='Invite your Facebook friends'/>
	</div>
	<div class='fb_message'>
		<?php echo $message; ?>
	</div>
	<div class='fb_link'>
		<?php
		echo "
			<a href='{$href->url}' class='{$href->class}'>
				<img src='{$fb_image}' />
			</a>
		";
		?>
	</div>
	<div class="clear"></div>
</div>
