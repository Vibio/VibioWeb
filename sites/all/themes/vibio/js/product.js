$(document).ready(function()
{
	$(".product_owners_results .pager a").live("click", function()
	{
		var page = vibio_utility.get_a_get_arg($(this), "page");
		var type = $(this).closest(".product_owners_type_container").attr("id").split("product_type_")[1]
		var nid = window.location.pathname.split("/")[2];
		
		$.ajax({
			url: "/product/get-owners",
			type: "post",
			data: {
				product: nid,
				type: type,
				page: page,
				ajax: true
			},
			success: function(html, stat)
			{
				$("#product_type_"+type)
					.find(".product_owners_results")
						.html(html);
			}
		});
		
		return false;
	});
	
	$(".search-results.vibio_item a").each(function()
	{
		var href = $(this).attr("href")+"?searchcrumb="+unescape(window.location.pathname)+window.location.search;
		
		$(this).attr("href", href);
	});
});