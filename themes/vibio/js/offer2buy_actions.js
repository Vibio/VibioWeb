$(document).ready(function()
{
	$(".offer2buy_action_complete_form").submit(function()
	{
		return confirm("are you sure you want to mark this action as done?");
	});
	
	$(".offer2buy_transaction_cancel_form").submit(function()
	{
		return confirm("are you sure you want to cancel this transaction?");
	});
	
	$(".offer2buy_offer_accept_form .offer2buy_accept_init").live("click", function()
	{
		return confirm("Accepting an offer will cancel all pending transactions for that item. Are you sure you want to accept this offer?")
	});
	
	$(".offer2buy_offer_accept_form .offer2buy_reject_init").live("click", function()
	{
		var classes = $(this).attr("class").split(/\s+/);
		var uid = false;
		var nid = false;
		
		$.each(classes, function(i, e)
		{
			if (uid && nid)
			{
				return;
			}
			
			var args = e.split("reject_offer_");
			if (args.length == 2)
			{
				args = args[1].split("_");
				uid = args[0];
				nid = args[1];
			}
		});
		
		if (!uid && !uid)
		{
			return false;
		}
		
		vibio_utility.dialog_busy();
		
		$.ajax({
			url: "/offer2buy/ajax/reject/"+uid+"/"+nid,
			success: function(html, stat)
			{
				vibio_utility.dialog_unbusy(html);
			}
		});
		
		return false;
	});
	
	$(".offer2buy_offer_reject_form #edit-cancel").live("click", function()
	{
		vibio_dialog.dialog.dialog("close");
		return false;
	});
	
	$(".offer2buy_edit_post_type").live("click", function()
	{
		$.ajax({
			url: $(this).attr("href")+"?destination="+window.location.pathname.substring(1),
			type: "post",
			data: {
				ajax: true
			},
			success: function(html, stat)
			{
				vibio_dialog.create(html);
			}
		});
		
		return false;
	});
});