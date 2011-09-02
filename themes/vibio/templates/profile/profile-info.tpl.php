<?php  
echo "
	<div class='profile_editable'>
<h2 class='section_title'>$username</h2>
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
		<h2 class='section_title'>Stats</h2>
		$demographics_edit_link
		<div class='clear'></div>
	</div>
	";
 /* previously included, below last div, $profile_external_accounts */

?>
