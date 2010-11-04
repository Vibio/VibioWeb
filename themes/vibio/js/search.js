$(document).ready(function()
{
	if (!$.browser.msie)
	{
		$("#edit-search-form-type")
			.wrap("<div class='search_select' />")
			.after("<div class='clear'></div>")
			.csb({
				style: "search_dropdown",
				mode: "select",
				callback: function() {}
			});
			
		$("#edit-search-users")
			.wrap("<div class='search_select' />")
			.after("<div class='clear'></div>")
			.csb({
				style: "search_dropdown2",
				mode: "select",
				callback: function() {}
			});
	}
});