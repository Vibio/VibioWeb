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
	
	$("#offer2buy-offer-form").livequery("submit", function()
	{
		var offer = $(this).find("#edit-offer");
		var offer_val = offer.val();
		var replaced_offer = parseFloat(offer_val.replace(/([^\d\.]+)/g, ""));
		
		if (isNaN(replaced_offer))
		{
			alert(Drupal.t("Invalid offer amount"));
			offer.focus();
			return false;
		}
		
		offer.val(replaced_offer);
		vibio_utility.dialog_busy();
		
		return true;
	});
});