<?php
/**
 * In the default Vibio configuration, the following variables will be available
 * to provide the url that initiates the contact importation for the named
 * provider:
 * $gmail
 * $livehotmail
 * $hotmail
 *
 * If Vibio chooses to enable further services through the contact_importer
 * module, those services urls will be available under the variable name
 * as the service is named by contact_importer
 *
 * Note that you must put the class contact-importer-link on the links...
 */
?>

<ul id="providers-ul">
	<li id="connect-top">Connect with friends faster by importing your contacts from your webmail account</li>
	<li>
		<a class="contact-importer-link invite-gmail" href="<?php print $gmail;?>">Gmail</a>
	</li>
	<li>
		<a class="contact-importer-link invite-hm" href="<?php print $livehotmail;?>">Hotmail</a>
	</li>
	<li>
		<a class="contact-importer-link invite-yahoo" href="<?php print $yahoo;?>">Yahoo</a>
	</li>
</ul>
