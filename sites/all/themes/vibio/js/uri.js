$(document).ready(function()
{
	var active_rid = false, form_html = false;
	
	$("a.uri_edit_elaboartion").click(function()
	{
		active_rid = $(this).closest("tr").attr("id").split("uri_relationship_")[1];
		show_details_form(active_rid);
		
		return false;
	});
	
	$("a.user_relationships_popup_link, a.uri_popup_link")
		.click(function()
		{
			var rid = $(this).closest("tr").attr("id").split("uri_relationship_")[1];
			var href = $(this).attr("href");
			
			busy_indicator.show(rid);
			
			$.ajax({
				url: href,
				dataType: "json",
				success: function(json, stat)
				{
					vibio_dialog.create(json.html);
					eval("bind_"+json.callback+"("+rid+", '"+href+"')");
				},
				complete: function()
				{
					busy_indicator.hide(rid);
				}
			});
			
			return false;
		})
		.each(function(i, e)
		{
			var href = $(this).attr("href").split("/");
			var rid = href[href.length - 2];
			
			$(this).closest("tr").attr("id", "uri_relationship_"+rid);
		});
	
	var bind_pending_request = function(rid, href)
	{
		var elements = {
			form: $("#uri-pending-request-action-form"),
			confirm: $("#edit-uri-pending-request-confirm"),
			cancel: $("#edit-uri-pending-request-cancel")
		};
		
		
		bind_callbacks(elements, rid, href);
	}
	
	var bind_remove_relationship = function(rid, href)
	{
		var elements = {
			form: $("#uri-remove-relationship-form"),
			confirm: $("#edit-uri-remove-relationship-confirm"),
			cancel: $("#edit-uri-remove-relationship-cancel")
		}
		
		bind_callbacks(elements, rid, href);
	}
	
	bind_request_relationship = function(rid, href)
	{
		var elements = {
			form: $("#uri-request-relationship-form"),
			confirm: $("#edit-uri-request-relationship-confirm"),
			cancel: $("#edit-uri-request-relationship-cancel")
		};
		
		bind_callbacks(elements, rid, href);
	}
	
	var bind_callbacks = function(elements, rid, href)
	{
		elements.form
			.unbind("submit", callbacks.submit)
			.bind("submit", callbacks.submit);
		elements.confirm
			.unbind("click", callbacks.confirm)
			.bind("click", {rid: rid, href: href}, callbacks.confirm);
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
			
			busy_indicator.show(rid);
			vibio_dialog.dialog.dialog("close");
			
			$.ajax({
				url: href,
				type: "post",
				data: {
					submit: true
				},
				dataType: "json",
				success: function(json, stat)
				{
					var message_type = json.saved ? "success" : "error";
					
					if (json.saved)
					{
						$("#uri_relationship_"+rid).remove();
					}
					
					vibio_utility.set_message(json.message, message_type);
				},
				complete: function()
				{
					busy_indicator.hide(rid);
				}
			});
			
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