<script>
$(document).ready(function()
{
	var login_url = "<?php echo _ebay_get_login_url()."&RuName=".variable_get("ebayapi_runame", "some_runame")."&SessID=$session_id"; ?>";
	
	$("#account_link").click(function()
	{
		window.open(login_url)
	});
});
</script>

<input id="account_link" type="submit" value="link my account!" />