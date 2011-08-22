<?php
$friends_title = t("Friends");
$friend_finder_title = t("Friend Finder");

echo "
	$notifications
	<div id='friends_tabs' class='tabs'>
		<div class='tabs'>
			<ul class='tabs primary clearfix'>
				<li>
					<a href='#friends'>
						<span class='tab'>$friends_title</span>
					</a>
				</li>
				<li>
					<a href='/network/find-friends'>
						<span class='tab'>$friend_finder_title</span>
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
