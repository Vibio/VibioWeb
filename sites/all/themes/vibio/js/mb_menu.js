$(document).ready(function()
{
	var menu_topoffset = $.browser.mozilla ? 0 : 2;
	var menu_leftoffset = $.browser.mozilla ? -1 : 0;
	var menu_width = $.browser.msie ? 21 : $.browser.mozilla ? 19 : 20;
	
	$("#profile_ext_headermenu").buildMenu({
		menuWidth: $("#profile_ext_headermenu").width() - menu_width,
		hasImages: false,
		fadeInTime: 150,
		fadeOutTime: 150,
		shadow: true,
		openOnClick: false,
		closeOnMouseOut: true,
		closeAfter: 400,
		menuTop: menu_topoffset,
		menuLeft: menu_leftoffset
	});

	$("#search_type_menu").buildMenu({
		menuWidth: 30,
		hasImages: false,
		fadeInTime: 150,
		fadeOutTime: 150,
		shadow: true,
		openOnClick: false,
		closeOnMouseOut: true,
		closeAfter: 400,
		menuSelector: ".search_type_submenu_container"
	});

	$(".searchtype_image").live("click", function()
	{
		var type_id = $(this).attr("id").split("searchtype_")[1];
		$("#search_type_current")
			.attr("src", $(this).attr("src"))
			.attr("alt", $(this).attr("alt"))
			.attr("title", $(this).attr("title"));

		$("#edit-search-type").val(type_id);

		return false;
	});

	if ($.browser.mozilla)
	{
		$("#search_type_menu_container").css("top", "2px");
	}
});
