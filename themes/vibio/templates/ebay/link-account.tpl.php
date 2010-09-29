<?php
$login_url = _ebay_get_login_url()."&RuName=".variable_get("ebayapi_runame", "some_runame")."&SessID=$session_id";
?>
<a href="<?php echo $login_url; ?>">Link your eBay and Vibio accounts!</a>
