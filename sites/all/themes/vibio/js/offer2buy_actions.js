$(document).ready(function()
{
	$(".offer2buy_action_complete_form").submit(function()
	{
		return confirm("are you sure you want to mark this action as done?");
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