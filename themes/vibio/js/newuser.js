$(document).ready(function()
{
	var fade_time = 500;
	
	fb_ajax_actions.ajax_link = $.extend({}, fb_ajax_actions.ajax_link, {
		newuser_post_link: function()
		{
			var stage_container = $("#newuser_tutorial").find(".fb_ff_message").closest(".tutorial_stage");
			var stage_id = stage_container.attr("id").split("tutorial_stage_")[1];
			var next_step_id = parseInt(stage_container.find(".fb_ff_message").closest(".tutorial_step").attr("id").split("tutorial_stage_"+stage_id+"_")[1]) + 1;
			
			$("#newuser_tutorial")
				.find(".fb_ff_message")
					.closest(".step_content")
						.siblings(".step_complete")
							.fadeIn(fade_time)
						.end()
					.end()
					.remove()
					
			vibio_dialog.set_options({ position: "center" });
			
			var content_div = $("#tutorial_stage_"+stage_id+"_"+next_step_id)
				.find("h5.unavailable")
					.removeClass("unavailable")
				.end()
				.find(".step_content")
					.html("<div style='text-align: center'><img src='/themes/vibio/images/ajax-loader.gif' /></div>");
					
			vibio_dialog.set_options({ position: "center" });
					
			$.ajax({
				url: "/facebook/ff_ajax",
				success: function(html, stat)
				{
					content_div.html(html);
					vibio_dialog.set_options({ position: "center" });
					newuser_change_friend_links();
				}
			})
		}
	});
	
	vibio_dialog.create($("#newuser_tutorial_container").remove().html());
	vibio_dialog.set_options({
		maxHeight: 50,
		width: 650,
		position: "center"
	});
	
	var newuser_change_friend_links = function()
	{
		$("#newuser_tutorial")
			.find(".uri_popup_link")
				.removeClass("uri_popup_link")
				.addClass("uri_quick_request");
	}
	
	newuser_change_friend_links();
});