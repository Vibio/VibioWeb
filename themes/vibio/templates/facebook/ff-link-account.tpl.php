<div class='fb_ff_message'>
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
