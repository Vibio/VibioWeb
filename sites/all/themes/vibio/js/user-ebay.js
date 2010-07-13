$(document).ready(function()
{
	if ($("#user_ebay_items").length == 0)
	{
		return; //can i do this?
	}
	
	$.ajax({
		type: "post",
		url: "/ebay/ajax/service",
		data: {
			action: "find_items_advanced",
			users: user_profile_uid
		},
		success: function(html, stat)
		{
			$("#user_ebay_items").html(html);
		}
	});
});