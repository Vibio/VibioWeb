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
		
		$.ajax({
			url: "/offer2buy/ajax/offer/"+nid+"?destination="+window.location.pathname.substring(1),
			type: "post",
			success: function(html, stat)
			{
				vibio_dialog.create(html);
			},
			complete: function()
			{
				in_progress = false;
			}
		});
		
		return false;
	});
});