/**
 * @author Craig Tockman - Reoder Elements up and down the DOM
 */

/*Login button position */
$(window).load(function() {
	$(".login_form_separator").remove().insertBefore("#edit-submit");
});

/* feature and search page hover effects, have and want buttons, grey */
$(document).ready(function() {
	
//places h1 page titles over the tabs on some pages	
	$(".not-logged-in h1#page_title").remove().insertBefore("div.tabs");
});
