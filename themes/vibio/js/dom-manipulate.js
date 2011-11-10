3/**
* @author Craig Tockman - Reoder Elements up and down the DOM
*/

/*Login button position */
$(window).load(function() {
	$(".login_form_separator").remove().insertBefore("#edit-submit");
});
/* feature and search page hover effects, have and want buttons, grey */
$(document).ready(function() {
	$('div.views-field.views-field-nid, div.box div.search-links').hide();
	$('div.views-field.views-field-field-main-image-fid, div.views-field-field-main-image-fid').hover(function() {
		$(this).next().show();
	}, function() {
		$(this).next().hide();
	});
	$('div.views-field.views-field-nid, div.box div.search-links').hover(function() {
		$(this).show();
		$(this).prev().addClass('hover-shadow');
	}, function() {
		$(this).hide();
		$(this).prev().removeClass('hover-shadow');
	});
	//places h1 page titles over the tabs on some pages
	$(".not-logged-in h1#page_title").remove().insertBefore("div.tabs");
	$(".page-contacts h1#page_title").remove().insertBefore("#friends");
	$(".section-user h1#page_title").remove().insertAfter("div.tabs");

	//places forgot password snippet above page lost password form
	$(".not-logged-in h1#page_title").remove().insertBefore("div.tabs");
	$("#block-block-13").remove().insertBefore("form#user-pass");
	//Intial text value for the Search Bar
	$('input#edit-search-theme-form-1').attr('value', 'Search Items');
	//Remove search bar text on click of field
	$("input#edit-search-theme-form-1").focus(function() {
		$('input#edit-search-theme-form-1').attr('value', '');
	});
	//Change Serach box text to "Search Users" on click
	$('img#searchtype_user').click(function() {
		$('input#edit-search-theme-form-1').attr('value', 'Search Users');
	});
	//Change Serach box text to "Search Items" on click
	$('img#searchtype_vibio_item').click(function() {
		$('input#edit-search-theme-form-1').attr('value', 'Search Items');
	});
	//Team Pages hide show bio
	$('.team-bio').hide();

	$('.team-photo').hover(function() {
		$(this).parents(".team-box:first").find(".team_tooltip").show();
	}, function() {
		$(this).parents(".team-box:first").find(".team_tooltip").hide();
	});

	$('.team-photo, .team-name').click(function() {
		$('.team_tooltip').hide();
		$(this).parents(".team-box:first").find(".team-bio").toggle('fast');
	});
	/*scroll to top*/
	$('a.topOfPage').click(function() {
		$.scrollTo(0, 2000);
		return false;
	});
});
