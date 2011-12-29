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
 */
?>

<ul>
  <li>
    <a class="automodal" href="<?php print $gmail;?>">Gmail</a>
  </li>
  <li>
    <a class="automodal" href="<?php print $yahoo;?>">Yahoo</a>
  </li>
  <li>
    <a class="automodal" href="<?php print $livehotmail;?>">Hotmail</a>
  </li>
</ul>
