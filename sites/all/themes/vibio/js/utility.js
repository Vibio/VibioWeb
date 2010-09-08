var vibio_utility = {};

$(document).ready(function()
{
	var messages_container = $("<div id='js_messages_container'></div>").prependTo("#content-area");
	
	//get the GET args on an anchor tag
	vibio_utility.get_a_get_arg = function(a, target_arg)
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
	}
	
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
	
	$("a[rel^='prettyphoto']").prettyPhoto({
		allowResize: false,
		showTitle: false,
		theme: "facebook"
	});
});