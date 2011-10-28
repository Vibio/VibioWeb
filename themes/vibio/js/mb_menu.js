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
		//Parsing the .searchtype_image id: should grab whether
		//it's an item or people search.
		var type_id = $(this).attr("id").split("searchtype_")[1];
		//change the #search_type_current to have the proper
		//image src/alt/title attributes for the newly selected search type
		$("#search_type_current")
			//.attr("src", $(this).attr("src"))
			.attr("src", $(this).attr("src") + "_main")
			.attr("alt", $(this).attr("alt"))
			.attr("title", $(this).attr("title"));

		/* this used to use the same button up top and in the menu.
       Now it doesn't.  Need a non-displaying option that displays
       when it's the "Item ^" or "User ^" button
     */

		$("#edit-search-type").val(type_id); // this is hidden value for submit

		return false;
	});

	if ($.browser.mozilla)
	{
		$("#search_type_menu_container").css("top", "2px");
	}
});
