$(document).ready(function()
{
	user_profile_set_bg();
	if ($.browser.safari)
	{
		$("#profile_picture img").load(function()
		{
			user_profile_set_bg();
		});
	}
	
	$("#profile_user_tabs").tabs();
	
	$(".profile_editable").hover(
		function()
		{
			$(this).find(".profile_edit_link").fadeIn(200);
		},
		function()
		{
			$(this).find(".profile_edit_link").fadeOut(200);
		}
	);
	
	$("#profile_change_picture a").click(function()
	{
		vibio_utility.dialog_busy();
		
		$.ajax({
			url: $(this).attr("href"),
			success: function(html, stat)
			{
				vibio_utility.dialog_unbusy(html);
			}
		});
		
		return false;
	});

	if (Drupal.settings.profile_settings.default_tab != false)
	{
		$("a[href='"+Drupal.settings.profile_settings.default_tab+"']").click();
	}
	
	if (Drupal.settings.profile_ext.profile_progress)
	{
		$("#profile_progressbar").progressbar({
			value: Drupal.settings.profile_ext.profile_progress
		});
		
		$("#profile_completion_steps_init").click(function()
		{
			vibio_dialog.create($("#profile_completion_steps_container").html());
			return false;
		});
	}
	
	function user_profile_set_bg()
	{
		var height = $("#profile_picture").css("height");
		var width = $("#profile_picture").css("width");
		
		$("#profile_picture_bg")
			.css({
				height: height,
				width: width,
				"-webkit-transform": "rotate(-8deg)",
				"-moz-transform": "rotate(-8deg)"
			})
			.show();
	}
});
