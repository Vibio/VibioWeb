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
//places forgot password snippet above page lost password form
	$(".not-logged-in h1#page_title").remove().insertBefore("div.tabs");
	$("#block-block-13").remove().insertBefore("form#user-pass");	
});
