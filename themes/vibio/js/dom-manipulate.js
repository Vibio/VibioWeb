/**
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
	$(".not-logged-in h1#page_title").insertBefore("div.tabs");
	$(".page-contacts h1#page_title").insertBefore("#friends");
	$(".section-user h1#page_title").insertAfter("div.tabs");
	$(".page-contacts-find-friends h1#page_title").insertAfter("div.tabs");
	$(".page-contacts-invite h1#page_title").insertAfter("div.tabs");

	//Invite form. Move Email contacts field above Subject field.
	$("form#invite-form div#edit-email-wrapper").insertAfter("form#invite-form div:eq(1)");

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
	/*Messages close button*/
	$('div.messages').prepend('<div id="close-message">Close</div>');
	/*Messages close function*/
	$('div#close-message').click(function() {
		$('div.messages').fadeOut();
	});
	//User dropdown menu
	$('#profile-menu-wrapper').hover(function() {
		$('#profile-submenu').fadeIn();
		$('.profile-arrow').css('background-position', '-50px -38px');
		$('.profile-icon').css('background-position', '-30px -36px');
		$('.profile-username').css('color', '#00AEF0');
	}, function() {
		$('#profile-submenu').fadeOut();
		$('.profile-arrow').css('background-position', '-50px -14px');
		$('.profile-icon').css('background-position', '-30px -9px');
		$('.profile-username').css('color', '#FFF');
	});
	$('a#how-works').hover(function() {
		$('#works-icon').css('background-position', '0px -31px');
	}, function() {
		$('#works-icon').css('background-position', '0px 0px');
	});
	/*Not for Sale Popup*/
	$('#offer-buttons .not_for_sale, .action .not_for_sale').hover(function() {
		$(this).prepend('<div class="not-sale-popup">Many items on Vibio are not for sale. <a href="/faq#why">Find out why</a>.</div>');
	}, function() {
		$('.not-sale-popup').remove();
	});
	$('#offer-buttons a.offer-button').hover(function() {
		$(this).prepend('<div class="make-offer-popup">Many items on Vibio are not for sale. <a href="/faq#buy">Find out why</a>.</div>');
	}, function() {
		$('.make-offer-popup').remove();
	});
	$('.action a').hover(function() {
		$(this).prepend('<div class="make-offer-popup">Many items on Vibio are not for sale. <a href="/faq#buy">Find out why</a>.</div>');
	}, function() {
		$('.make-offer-popup').remove();
	});
	//$('#offer-buttons .not_for_sale').prepend('<div class="not-sale-popup">Many items on Vibio are not for sale. <a href="/faq#why">Find out why</a>.</div>');
	//$('#offer-buttons a.offer-button').prepend('<div class="make-offer-popup">Many items on Vibio are not for sale. <a href="/faq#buy">Find out why</a>.</div>');

});
