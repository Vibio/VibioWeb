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
});