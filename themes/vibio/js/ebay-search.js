$(document).ready(function()
{
	$(".ebay_search_results .pager a").live("click", function()
	{
		var search_results = $(this).closest(".ebay_search_results");
		var get_args = $(this).attr("href").split("?")[1].split('&');
		var next_page = 0;
		
		$.each(get_args, function(i, e)
		{
			var args = e.split("=");
			if (args[0] == "page")
			{
				next_page = parseInt(args[1]);
			}
		});
		
		var search_diff = {
			page_number:  next_page + 1,
			action: "find_items_advanced",
			hide_wrapper: true
		};
		var next_search = $.extend({}, ebay_search_args, search_diff);
		
		search_results.html('');
		
		$.ajax({
			url: "/ebay/ajax/service",
			type: "post",
			data: next_search,
			success: function(html, stat)
			{
				search_results.html(html);
			}
		})
		
		return false;
	});
});