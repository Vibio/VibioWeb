<?php
/**
 * Available variables:
 * $fb_url (note that this must have an id of fb-request to work
 * $twitter_url
 * $email_url
 */
?>
<div id="invite-box">
<div id="invite-header">
	Send an invitation to your friends to connect with you on Vibio!
	<br />
	Use Facebook, Twitter, and email to expand your circle.
</div>
	<ul>
		<li>
			<a href="<?php print $fb_link;?>" id="fb-request" class="invite-fb">Send an invitation through your Facebook account</a>
		</li>
		<li>
			<a href="<?php print $twitter_link;?>" class="invite-tw">Send an invitation through your Twitter account</a>
		</li>
		<li>
			<a href="<?php print $email_link;?>" class="invite-em">Send an invitation through your Email account</a>
		</li>
	</ul>
</div>
