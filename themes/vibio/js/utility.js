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
	},
	set_time_offset: function()
	{
		var offset = typeof Drupal.settings.profile_ext.time_offset != "undefined" ? parseInt(Drupal.settings.profile_ext.time_offset) : 0;
		var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dev"];
		
		$(".timestamp_uncalculated").each(function()
		{
			var e = $(this);
			var current_tstamp = parseInt(e.text());
			
			if (isNaN(current_tstamp))
				return;
			
			var new_date = new Date((current_tstamp*1000) + (offset*1000));
			var same_day = new_date.getUTCMonth() == Drupal.settings.profile_ext.utc_date.m && new_date.getUTCDate() == Drupal.settings.profile_ext.utc_date.d;
			var display = "wow js time is so stupid.";
			
			if (same_day)
			{
				var hour = new_date.getUTCHours() == 0 ? 12 : new_date.getUTCHours();
				var display_hour = hour > 12 ? hour - 12 : hour;
				var display_time = new_date.getUTCMinutes() < 10 ? "0"+new_date.getUTCMinutes() : new_date.getUTCMinutes();
				var ampm = new_date.getUTCHours() > 11 ? "pm" : "am";
				
				display = display_hour+":"+display_time+ampm;
			}
			else
			{
				display = months[new_date.getUTCMonth()]+" "+new_date.getUTCDate();
			}
			
			e.text(display).removeClass("timestamp_uncalculated");
		});
	},
	busy: function(id, action)
	{
		var e = $("#busy_indicator_"+id);
		action == "show" ? e.show() : e.hide();
	},
	dialog_busy: function()
	{
		var busy_indicator = $("#dialog_busy_indicator_container").length ? $("#dialog_busy_indicator_container") : $("<div id='dialog_busy_indicator_container'><div id='dialog_busy_indicator'><img src='/themes/vibio/images/ajax-loader.gif' /></div></div>").prependTo("body");
		if (vibio_dialog.dialog)
		{
			vibio_dialog.dialog.dialog("close");
		}
		vibio_dialog.create(busy_indicator.html());
		vibio_dialog.set_options();
	},
	dialog_unbusy: function(content)
	{
		vibio_dialog.dialog.dialog("close");
		vibio_dialog.create(content);
	},
	invoke: function(funcs)
	{
		if (typeof funcs == "undefined")
		{
			return;
		}
		
		$.each(funcs, function(i, func)
		{
			if (typeof func == "function")
			{
				func();
			}
		});
	}
};

$(document).ready(function()
{
	vibio_utility.set_message = function(message, type)
	{
		if (typeof type == "undefined")
		{
			type = "status";
		}
		if (typeof clear_type == "undefined")
		{
			clear_type = false;
		}
		if (typeof clear_all == "undefined")
		{
			clear_all = false;
		}
		
		var message_div = $("div.messages."+type).length ? $("div.messages."+type) : $("<div class='messages "+type+"'></div>").prependTo("#content");
		var message_list = message_div.find("ul").length ? message_div.find("ul") : $("<ul></ul>").prependTo(message_div);
		
		message_list.append("<li>"+message+"</li>");
	}
	
	$("a[href^='/node/']").removeClass("item_link").addClass("item_link");
	vibio_utility.set_time_offset();
	$(document)
		.ajaxComplete(function()
		{
			$("a[href^='/node/']").removeClass("item_link").addClass("item_link");
			vibio_utility.set_time_offset();
		})
		.ajaxError(function(event, xhr, options, error)
		{
			if (xhr.status == 403)
			{
				vibio_dialog.dialog.dialog("close");
				vibio_dialog.create(Drupal.t("You must log in to do this"));
			}
		});
	
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

	$("#user-login-form")
		.find("div.item-list:eq(0)")
			.addClass("login_form_separator");
	
	setTimeout(function()
	{
		$("#user-register").find("div.password-description").remove();
	}, 200);
});
