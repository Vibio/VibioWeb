<?php
/**
 * Available variables:
 * $fb_url (note that this must have an id of fb-request and the attributes 'username'
 * and 'app_id' defined to work.
 * $fb_appid
 * $twitter_url
 * $email_url
 */
global $user;
?>
<div id="invite-box">
<div id="invite-header">
	Send an invitation to your friends to connect with you on Vibio!
	<br />
	Use Facebook, Twitter, and email to expand your circle.
</div>
	<ul>
		<li>
			<a href="<?php print $fb_url;?>" id="fb-request" class="invite-fb" app_id="<?php print $fb_appid; ?>" username="<?php print $user->name; ?>">Send an invitation through your Facebook account</a>
		</li>
		<li>
			<a href="<?php print $twitter_url;?>" class="invite-tw">Send an invitation through your Twitter account</a>
		</li>
		<li>
			<a href="<?php print $email_url;?>" class="invite-em">Send an invitation through your Email account</a>
		</li>
	</ul>
</div>
