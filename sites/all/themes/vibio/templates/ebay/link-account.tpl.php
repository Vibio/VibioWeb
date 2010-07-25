<?php
$login_url = _ebay_get_login_url()."&RuName=".variable_get("ebayapi_runame", "some_runame")."&SessID=$session_id";
?>
Linking accounts is what cool kids do.
<a href="<?php echo $login_url; ?>">Do it!</a>