<?php
echo "
	<div class='profile_editable'>
		<div id='profile_about_me'>
			$profile_about_me
		</div>
		$about_edit_link
		<div class='clear'></div>
	</div>
	<div class='profile_editable'>
		<div id='profile_demographics'>
			$profile_demographics
		</div>
		$demographics_edit_link
		<div class='clear'></div>
	</div>
	$profile_external_accounts
";
?>