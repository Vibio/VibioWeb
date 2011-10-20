<?php
$friends_title = t("Connections");
$friend_finder_title = t("Connection Finder");
$invite_title = t("Invite Friends");

/* Simon 20110907 remove notifications, which was first line in echo:
 *	$notifications
 * I lean towards UI change, remove notifications link instead.
 *  Just a preference, but keeping the note here.
 * 
 * EFFICIENCY: what creates $notifications, and can we remove it?
 */

//dsm($friends);
echo "
	<div id='friends_tabs' class='tabs'>
		<div class='tabs' style='display: none;'>
			<ul class='tabs primary clearfix'>
				<li>
					<a href='#friends'>
						<span class='tab'>$friends_title</span>
					</a>
				</li>
				<li>
					<a href='/network/find-friends'> turning into contacts/find-friends
						<span class='tab'>$friend_finder_title</span>
					</a>
				</li>
				<li>
					<a href='/invite'>

Mocked this up but didn't get it working.  End the single-page-ajax effort.

						<span class='tab'>$invite_title</span>
					</a>
				</li>
			</ul>
		</div>
		<div id='friends'>
			$friends
		</div>
	</div>
	<div class='clear'></div>
";
?>
