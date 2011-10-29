$(document).ready(function()
{
	var menu_topoffset = $.browser.mozilla ? 0 : 2;
	var menu_leftoffset = $.browser.mozilla ? -1 : 0;
	var menu_width = $.browser.msie ? 21 : $.browser.mozilla ? 19 : 20;
	//Tells us what kind of search is currently being performed
	var type_id = $("#edit-search-type").val(type_id);
       	
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

        //Set the current search type image
        $("#search_type_menu .button").css("background-image", 'url("/themes/vibio/images/' + type_id + '_button.png")');	

	$(".searchtype_image").live("click", function()
	{
		//see what the updated search type is
		type_id = $(this).attr("id").split("searchtype_")[1];
		/* this used to use the same button up top and in the menu.
       Now it doesn't.  Need a non-displaying option that displays
       when it's the "Item ^" or "User ^" button
     */

		$("#edit-search-type").val(type_id); // this is hidden value for submit
		//display the currently selected search type as a button label on the
		//left of the search bar.
                $("#search_type_menu .button").css("background-image", 'url("/themes/vibio/images/' + type_id + '_button.png")');

		return false;
	});

	if ($.browser.mozilla)
	{
		$("#search_type_menu_container").css("top", "2px");
	}
});
