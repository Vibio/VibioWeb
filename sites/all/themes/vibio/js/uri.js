$(document).ready(function()
{
	var active_rid = false, form_html = false;
	
	$("#friends_tabs").tabs({
		cache: true,
		spinner: "<img src='/themes/vibio/images/ajax-loader.gif' />"
	});

	$("table.user-relationships-listing-table tr").hover(
		function()
		{
			$(this).find("td.ur_friend_remove img").show();
		},
		function()
		{
			$(this).find("td.ur_friend_remove img").hide();
		}
	);

	$("a.uri_edit_elaboartion").click(function()
	{
		var rid = $(this).closest("tr").attr("id").split("uri_relationship_")[1];

		if (!rid)
		{
			var href_args = $(this).attr("href").split("/");
			rid = href_args[href_args.length - 1];
			$(this).closest("tr").attr("id", "uri_relationship_"+rid);
		}

		active_rid = rid;
		show_details_form(active_rid);
		
		return false;
	});
	
	$("a.uri_popup_link").live("click", function()
	{
		var rid = $(this).closest("tr").attr("id").split("uri_relationship_")[1];
		var href = $(this).attr("href");

		if (!rid)
		{
			var href_args = href.split("/");
			rid = href_args[href_args.length - 2];
			$(this).closest("tr").attr("id", "uri_relationship_"+rid);
		}
		
		busy_indicator.show(rid);
		
		$.ajax({
			url: href,
			dataType: "json",
			success: function(json, stat)
			{
				var users = {
					current: json.users.current,
					target: json.users.target
				};
				
				vibio_dialog.create(json.html);
				eval("bind_"+json.callback+"("+rid+", '"+href+"', users)");
			},
			complete: function()
			{
				busy_indicator.hide(rid);
			}
		});
		
		return false;
	});
	
	$("a.uri_quick_request").live("click", function()
	{
		var rid = $(this).closest("tr").attr("id").split("uri_relationship_")[1];
		var href = $(this).attr("href");

		if (!rid)
		{
			var href_args = href.split("/");
			rid = href_args[href_args.length - 2];
			$(this).closest("tr").attr("id", "uri_relationship_"+rid);
		}
		
		busy_indicator.show(rid);
		
		$.ajax({
			url: href,
			dataType: "json",
			success: function(json, stat)
			{
				var users = {
					current: json.users.current,
					target: json.users.target
				};
				
				callbacks.confirm({
					data: {
						rid: rid,
						href: href,
						users: users,
						action: "request",
						keep_dialog_open: true
					}
				});
			},
			complete: function()
			{
				busy_indicator.hide(rid);
			}
		});
		
		return false;
	});
	
	var bind_pending_request = function(rid, href, users)
	{
		var elements = {
			form: $("#uri-pending-request-action-form"),
			confirm: $("#edit-uri-pending-request-confirm"),
			cancel: $("#edit-uri-pending-request-cancel")
		};
		
		bind_callbacks(elements, rid, href, false, users);
	}
	
	var bind_remove_relationship = function(rid, href, users)
	{
		var elements = {
			form: $("#uri-remove-relationship-form"),
			confirm: $("#edit-uri-remove-relationship-confirm"),
			cancel: $("#edit-uri-remove-relationship-cancel")
		}
		
		bind_callbacks(elements, rid, href, "remove", users);
	}
	
	var bind_request_relationship = function(rid, href, users)
	{
		var elements = {
			form: $("#uri-request-relationship-form"),
			confirm: $("#edit-uri-request-relationship-confirm"),
			cancel: $("#edit-uri-request-relationship-cancel")
		};
		
		bind_callbacks(elements, rid, href, "request", users);
	}
	
	var bind_callbacks = function(elements, rid, href, action_type, users)
	{
		elements.form
			.unbind("submit", callbacks.submit)
			.bind("submit", callbacks.submit);
		elements.confirm
			.unbind("click", callbacks.confirm)
			.bind("click", {rid: rid, href: href, action: action_type, users: users}, callbacks.confirm);
		elements.cancel
			.unbind("click", callbacks.cancel)
			.bind("click", callbacks.cancel)
	}
	
	var callbacks = {
		submit: function()
		{
			return false;
		},
		confirm: function(e)
		{
			var rid = e.data.rid;
			var href = e.data.href;
			var users = e.data.users;
			var action = e.data.action ? e.data.action : href.split("/")[href.split("/").length - 1];
			var message = Drupal.settings.uri.messages[action].replace("!target", "<a href='/user/"+users.target.uid+"'>"+users.target.name+"</a>");
			
			if (!e.data.keep_dialog_open)
			{
				vibio_dialog.dialog.dialog("close");
			}
			
			$.ajax({
				url: href,
				type: "post",
				data: {
					submit: true,
					elaboration: $("#edit-uri-pending-elaboration").length ? $("#edit-uri-pending-elaboration").val() : ""
				}
			});
			
			$("#uri_relationship_"+rid).remove();
			vibio_utility.set_message(message, "status");
			return false;
		},
		cancel: function()
		{
			vibio_dialog.dialog.dialog("close");
			
			return false;
		}
	};
	
	var busy_indicator = {
		show: function(rid)
		{
			$("#uri_relationship_"+rid+" .uri_edit_busy_indicator").show();
		},
		hide: function(rid)
		{
			$("#uri_relationship_"+rid+" .uri_edit_busy_indicator").hide();
		}
	};
	
	var show_details_form = function(rid)
	{
		if (!form_html)
		{
			form_html = $("#uri_elaborations_edit").remove().html();
		}
		
		var row = $("#uri_relationship_"+rid)
		var friend = row.find("td:eq(1) a").html();
		var elaboration = row.find(".uri_elaboration").html();
		
		vibio_dialog.create(form_html);
		
		$(".uri_edit_elaborations_form")
			.find(".uri_elaboration_target")
				.html(friend)
				.end()
			.find("#edit-elaboration")
				.html(elaboration)
				.end()
			.unbind("submit", elaborations_edit_submit)
			.bind("submit", elaborations_edit_submit);			
	}
	
	var elaborations_edit_submit = function()
	{
		var row = $("#uri_relationship_"+active_rid);
		var busy = row.find(".uri_edit_busy_indicator");
		var elaboration = row.find(".uri_elaboration");
		
		vibio_dialog.dialog.dialog("close");
		busy.show();
		
		$.ajax({
			url: "/relationships/edit-elaboration/"+active_rid,
			type: "post",
			data: {
				elaboration: $("#edit-elaboration").val()
			},
			dataType: "json",
			success: function(json, stat)
			{
				if (!json.status)
				{
					alert(json.message);
					return;
				}
				
				elaboration.html(json.message);
			},
			complete: function()
			{
				busy.hide();
			}
		});
		
		return false;
	}
});