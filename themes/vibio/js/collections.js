$(document).ready(function()
{
	$(".collection_preview_init").live("click", function()
	{
		var load_request_sent = $.data(this, "preview_requested");
		var is_expanded = $(this).hasClass("expanded");
		var preview = $(this).siblings(".collection_preview_items");
		var loading = $(this).siblings(".collection_preview_loading");

		if (loading.length && load_request_sent) // currently loading
		{
			return;
		}

		if (is_expanded)
		{
			$(this)
				.removeClass("expanded")
				.find("img")
					.attr("src", "/themes/vibio/images/collections/expand.png")
					.end()
				.find("span")
					.html(Drupal.t("Expand Item List"));
					
			if (loading.length)
			{
				loading.hide();
			}
			else // if there isn't a loading div, then we should collapse the preview div
			{
				preview.slideToggle();
			}
		}
		else
		{
			$(this)
				.addClass("expanded")
				.find("img")
					.attr("src", "/themes/vibio/images/collections/minimize.png")
					.end()
				.find("span")
					.html(Drupal.t("Minimize Item List"));

			if (loading.length)
			{
				loading.show();
			}
			else
			{
				preview.slideToggle();
			}
		}

		if (!load_request_sent)
		{
			$.data(this, "preview_requested", true);
			var cid = $(this).closest(".collection_list_collection").attr("id").split("collection_")[1];

			$.ajax({
				url: "/collections/"+cid,
				data: {
					preview: 1,
					ajax: 1
				},
				success: function(html, stat)
				{
					preview
						.html(html)
						.slideToggle();
				},
				complete: function()
				{
					loading.remove();
				}
			});
		}
	});
	
	$("#views-exposed-form-user-collection-default #edit-collection-order-by").live("change", function()
	{
		var sort_args = $(this).val().split("_");
		
		$(this)
			.closest("#views-exposed-form-user-collection-default")
				.find("#edit-order")
					.val(sort_args[0])
					.end()
				.find("#edit-sort")
					.val(sort_args[1]);
	});
});