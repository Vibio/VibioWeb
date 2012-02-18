/*fixed scroll*/
$(document).ready(function() {
	$('#block-block-5').scrollToFixed();
	$('#block-block-5').bind('fixed', function() {
		$('.logged-in .sidebar .rounded_content').css('height', '400px');
	});
	$('#block-block-5').bind('unfixed', function() {
		$(this).css('position', 'absolute');
		$('.logged-in .sidebar .rounded_content').css('height', 'auto');
	});
	//$('.view.view-flag-featured .view-footer').scrollToFixed();
});
/*scroll to top*/
$('a.topOfPage').click(function() {
	$.scrollTo(0, 500);
	return false;
});
