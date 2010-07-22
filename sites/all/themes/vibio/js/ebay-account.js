$(document).ready(function()
{
	$(".ebay_remove_account").click(function()
	{
		var account_name = $(this).siblings(".account_id").text();
		var confirm_text = external_accounts.ebay.confirm_text.replace('!account_name', account_name);
		
		if (!confirm(confirm_text))
		{
			return false;
		}
		
		$.ajax({
			url: "/ebay/ajax/service",
			type: "post",
			data: {
				action: "remove_account",
				account: account_name
			},
			dataType: "json",
			success: function(json, stat)
			{
				alert(json.message);
			}
		});
		
		return false;
	});
	
	$(".ebay_add_account").click(function()
	{
		$.ajax({
			url: "/ebay/ajax/theme",
			type: "post",
			data: {
				action: "ebay_link_account_init"
			},
			success: function(html, stat)
			{
				vibio_dialog.create(html);
			}
		});
		
		return false;
	});
});