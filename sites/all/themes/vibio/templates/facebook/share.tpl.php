<?php
$alt = t("Share On Facebook");
echo "
	<div class='fb_share_container'>
		<span class='fb_share_params'>$share_params</span>
		<a class='fb_share' href='#'>
			<img src='{$share_image}' alt='$alt' title='$alt' />
		</a>
	</div>
";
?>
