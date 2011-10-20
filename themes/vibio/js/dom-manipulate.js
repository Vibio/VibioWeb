/**
 * @author Craig Tockman - Reoder Elements up and down the DOM
 */
$(window).load(function() {
	$(".login_form_separator").remove().insertBefore("#edit-submit");
});
$(document).ready(function() {
	$('div.views-field.views-field-nid').hide();
	$('img.imagecache-product_fixed_fluid_grid').hover(function() {
		$('div.views-field.views-field-nid').show();
	}, function() {
		$('div.views-field.views-field-nid').hide();
	});
	$('div.views-field.views-field-nid').hover(function() {
		$(this).show();
	}, function() {
		$(this).hide();
	});
});
