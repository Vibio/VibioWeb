$(document).ready(function()
{
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
});