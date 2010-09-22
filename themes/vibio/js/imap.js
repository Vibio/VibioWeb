$(document).ready(function()
{
	var data_dictionary = {};
	
	$(".imap_delete_form").submit(function()
	{
		if (!confirm(Drupal.t("Are you sure you want to delete the image?")))
		{
			return false;
		}
		
		var form = $(this);
		var imap_id = form.attr("id").split("imap-delete-form-")[1];
		
		profile_actions.show_busy(form);
		
		$.ajax({
			url: "/imap/ajax",
			type: "post",
			data: {
				action: "delete",
				id: imap_id
			},
			dataType: "json",
			success: function(json, stat)
			{
				if (json.saved)
				{
					profile_actions.remove_notification(form);
				}
				
				vibio_utility.set_message(json.message, json.saved ? "success" : "error");
			},
			complete: function()
			{
				profile_actions.hide_busy(form);
			}
		});
		
		return false;
	});
	
	$(".imap_attach_form")
		.each(function()
		{
			var form = $(this);
			var cid_select = form.find("select[name='cid']");
			var nid_select = alter_element(form, "nid", "disable");
			var submit = alter_element(form, "submit", "disable");
			
			cid_select.change(function()
			{
				nid_select.attr("disabled", "disabled");
				submit.attr("disabled", "disabled");
				
				var cid = $(this).val();
				
				if (cid == -1)
				{
					return;
				}
				
				if (typeof data_dictionary["collection_items_"+cid] == "undefined")
				{
					data_dictionary["collection_items_"+cid] = {};
					profile_actions.show_busy(form);
					
					$.ajax({
						url: "/imap/ajax",
						type: "post",
						data: {
							action: "get_collection_items",
							cid: cid
						},
						dataType: "json",
						success: function(json, stat)
						{
							data_dictionary["collection_items_"+cid] = json.message;
						},
						complete: function()
						{
							profile_actions.hide_busy(form);
							fill_item_elements(nid_select, data_dictionary["collection_items_"+cid]);
							nid_select.attr("disabled", false);
						}
					});
				}
				else
				{
					fill_item_elements(nid_select, data_dictionary["collection_items_"+cid]);
					nid_select.attr("disabled", false);
				}
			});
			
			nid_select.change(function()
			{
				var nid = $(this).val();
				
				if (nid == -1)
				{
					submit.attr("disabled", "disabled");
					return;
				}
				
				submit.attr("disabled", false);
			});
		})
		.submit(function()
		{
			var form = $(this);
			var cid_select = form.find("select[name='cid']");
			var nid_select = alter_element(form, "nid", "disable");
			var submit = alter_element(form, "submit", "disable");
			var imap_id = form.attr("id").split("imap-attach-form-")[1];
			cid_select.attr("disabled", "disabled");
			
			profile_actions.show_busy(form);
			
			$.ajax({
				url: "/imap/ajax",
				type: "post",
				data: {
					action: "attach_image",
					id: imap_id,
					nid: nid_select.val()
				},
				dataType: "json",
				success: function(json, stat)
				{
					if (json.saved)
					{
						profile_actions.remove_notification(form);
					}
					
					vibio_utility.set_message(json.message, json.saved ? "success" : "error");
				},
				complete: function()
				{
					profile_actions.hide_busy(form);
					cid_select.attr("disabled", false);
					nid_select.attr("disabled", false);
					submit.attr("disabled", false);
				}
			});
			
			return false;
		});
		
	function alter_element(form, element, action)
	{
		var disabled_val = action == "disable" ? "disabled" : false;
		var target_element = false;
		
		if (element == "nid")
		{
			target_element = form.find("select[name='nid']");
		}
		else if (element == "submit")
		{
			target_element = form.find("input.form-submit");
		}
		
		return target_element ? target_element.attr("disabled", disabled_val) : false;
	}
	
	function fill_item_elements(select, options)
	{
		var option_html = "";
		$.each(options, function(nid, title)
		{
			option_html += "<option value='"+nid+"'>"+title+"</option>";
		});
		
		select
			.find("option:gt(0)")
				.remove()
				.end()
			.append(option_html);
	}
});