$(document).ready(function()
{
	//If the search bar has the default value set, clear it on focus
	$("input#edit-search-theme-form-1").focus(function() {
  		if($('input#edit-search-theme-form-1').val() == 'Search Vibio'){
          		$('input#edit-search-theme-form-1').attr('value', '');
 		 }
	});

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
