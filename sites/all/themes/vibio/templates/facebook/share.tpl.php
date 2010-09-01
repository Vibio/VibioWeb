<?php
module_load_include("inc", "fb");
$share_params = json_encode(fb_get_share_params($node));

echo "
	<div class='fb_share_container'>
		<span class='fb_share_params'>$share_params</span>
		<a class='fb_share' href='#'>
			<img src='/sites/all/themes/vibio/images/facebook/share.png' />
		</a>
	</div>
";
?>