$(document).ready(function()
{
	var active_rid = false, form_html = false;
	
	$("a.uri_edit_elaboartion")
		.click(function()
		{
			active_rid = $(this).closest("tr").attr("id").split("uri_relationship_")[1];
			show_details_form(active_rid);
			
			return false;
		})
		.each(function(i, e)
		{
			var href = $(this).attr("href").split("/");
			var rid = href[href.length - 1];
			
			$(this)
				.closest("tr")
					.attr("id", "uri_relationship_"+rid);
		});
	
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