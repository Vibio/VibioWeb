$(document).ready(function()
{
	var in_progress = false;
	
	$(".offer2buy_init").click(function()
	{
		if (in_progress)
		{
			return false;
		}
		
		var nid = $(this).siblings(".offer2buy_nid").html();
		in_progress = true;
		vibio_utility.dialog_busy();
		
		$.ajax({
			url: "/offer2buy/ajax/offer/"+nid+"?destination="+window.location.pathname.substring(1),
			type: "post",
			success: function(html, stat)
			{
				vibio_utility.dialog_unbusy(html);
			},
			complete: function()
			{
				in_progress = false;
			}
		});
		
		return false;
	});
	
	$(".offer2buy_offer_view_popup_init").click(function()
	{
		vibio_dialog.create($(this).siblings(".offer2buy_offer_view_popup").html());
	});
});