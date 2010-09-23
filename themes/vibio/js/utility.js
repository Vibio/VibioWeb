var vibio_utility = {
	get_a_get_arg: function(a, target_arg)
	{
		var get_args = a.attr("href").split("?")[1].split("&");
		var value = 0;
		
		$.each(get_args, function(i, e)
		{
			var arg = e.split("=");
			if (arg[0] == target_arg)
			{
				value = arg[1];
			}
		});
		
		return value;
	},
	get_get_arg: function(target_arg)
	{
		var get_args = vibio_utility.get_args();
		var result = false;
		
		$.each(get_args, function(arg, value)
		{
			if (arg == target_arg)
			{
				result = value;
			}
		});
		
		return result;
	},
	get_args: function()
	{
		var args = window.location.search.substring(1).split("&");
		var get_args = {};
		
		$.each(args, function(i, e)
		{
			get_args[e.split("=")[0]] = e.split("=")[1];
		});
		
		return get_args;
	}
};

$(document).ready(function()
{
	var messages_container = $("#js_messages_container").length ? $("#js_messages_container") : $("<div id='js_messages_container'></div>").prependTo("#content-area");
	
	vibio_utility.set_message = function(message, type, clear_type, clear_all)
	{
		if (typeof type == "undefined")
		{
			type = "success";
		}
		if (typeof clear_type == "undefined")
		{
			clear_type = false;
		}
		if (typeof clear_all == "undefined")
		{
			clear_all = false;
		}
		
		if (clear_type)
		{
			messages_container.find(".js_message_"+type).remove();
		}
		
		if (clear_all)
		{
			messages_container.html("");
		}
		
		messages_container.append("<div class='js_message_"+type+"'>"+message+"</div>");
	}
	
	$("a[href^='/node/']").addClass("item_link");
	
	$("a[rel^='prettyphoto']").prettyPhoto({
		allowResize: false,
		showTitle: false,
		theme: "facebook"
	});
	
	//should probably make a file for these types of things...
	if ($.browser.safari)
	{
		$("#search input[type='submit']").css("top", "2px");
	}
});