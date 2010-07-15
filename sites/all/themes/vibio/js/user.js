$(document).ready(function()
{
	$("#social_tabs").tabs({
		cache: true,
		show: function(event, ui)
		{
			var selected_index = $(this).tabs("option", "selected");
			var selected_tab = $(this).find(".ui-tabs-nav > li:eq("+selected_index+") > a");
			
			if (!selected_tab.hasClass("tablink_custom_ajax") || $.data(selected_tab.get(0), "content_loaded") == true)
			{
				return;
			}
			
			var settings_id = selected_tab.attr("id").split("tablink_")[1];
			
			if (typeof tab_args == "undefined" || typeof tab_args[settings_id] == "undefined")
			{
				return;
			}
			
			$.ajax({
				url: tab_args[settings_id].url,
				type: "post",
				data: tab_args[settings_id],
				success: function(html, stat)
				{
					$("#"+settings_id).html(html);
					$.data(selected_tab.get(0), "content_loaded", true);
				}
			});
		}
	});
});