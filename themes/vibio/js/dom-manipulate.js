/**
 * @author Craig Tockman - Reoder Elements up and down the DOM
 */
$(window).load(function() {
	$(".login_form_separator").remove().insertBefore("#edit-submit");
});

/* feature and search page hover effects, have and want buttons, grey */
$(document).ready(function() {
	$('div.views-field.views-field-nid').hide();
	$('div.views-field.views-field-field-main-image-fid').hover(function() {
		$(this).next().show();
	}, function() {
		$(this).next().hide();
	});
	$('div.views-field.views-field-nid').hover(function() {
		$(this).show();
		$(this).prev().addClass('hover-shadow');
	}, function() {
		$(this).hide();
		$(this).prev().removeClass('hover-shadow');
	});
});
