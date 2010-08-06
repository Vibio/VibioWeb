$(document).ready(function()
{
	$("#social_loading_div").hide();
	var uid = window.location.pathname.split("/")[2];
	
	$("#vibio-item-user-inventory-search").submit(function()
	{
		user_inventory_search(0);
		return false;
	});
	
	$("#user_inventory .pager li > a").live("click", function()
	{
		var get_args = $(this).attr("href").split("?")[1].split("&");
		var page = 0;
		
		$.each(get_args, function(i, e)
		{
			var arg = e.split("=");
			if (arg[0] == "page")
			{
				page = arg[1];
			}
		});
		
		user_inventory_search(page);
		return false;
	});
	
	var user_inventory_search = function(page)
	{
		$("#social_loading_div").show();
		
		var submit_data = {
			phrase: $("#edit-phrase").val(),
			item_status: $("#edit-item-status").val(),
			page: page
		};
		
		$.ajax({
			type: "post",
			url: "/user-inventory/"+uid+"/search",
			data: submit_data,
			success: function(html, stat)
			{
				$("#user_inventory")
					.html(html)
					.closest(".view-content")
						.siblings(".item-list")
							.remove();
			},
			complete: function()
			{
				$("#social_loading_div").hide();
			}
		});
	}
	
	/*$("#social_tabs").tabs({
		cache: true,
		show: function(event, ui)
		{
			var selected_index = $(this).tabs("option", "selected");
			var selected_tab = $(this).find(".ui-tabs-nav > li:eq("+selected_index+") > a");
			
			if (!selected_tab.hasClass("tablink_custom_ajax") || (selected_tab.hasClass("tablink_custom_ajax") && $.data(selected_tab.get(0), "content_loaded") == true))
			{
				return;
			}
			
			var settings_id = selected_tab.attr("id").split("tablink_")[1];
			
			if (typeof tab_args == "undefined" || typeof tab_args[settings_id] == "undefined")
			{
				return;
			}
			
			$("#social_loading_div").show();
			
			$.ajax({
				url: tab_args[settings_id].url,
				type: "post",
				data: tab_args[settings_id],
				success: function(html, stat)
				{
					$("#"+settings_id).html(html);
					$.data(selected_tab.get(0), "content_loaded", true);
					$("#social_loading_div").hide();
				}
			});
		}
	});*/
});