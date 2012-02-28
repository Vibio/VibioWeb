/*fixed scroll*/

$(document).ready(function() {
	$('#block-block-5').scrollToFixed();
	$('#splash-fb-bgd').scrollToFixed();

	$('#block-block-5').bind('fixed', function() {
		$('.logged-in .sidebar .rounded_content').css('height', '400px');
	});
	$('#block-block-5').bind('unfixed', function() {

		$('.logged-in .sidebar .rounded_content').css('height', 'auto');
	});
	/*scroll to top*/

	$('a.topOfPage').click(function() {
		$.scrollTo(0, {
			duration : 100
		});
	});
	/*Show back to top button based on screen height*/

	var target1 = 1000;
	var target2 = 900;
	var interval = setInterval(function() {
		if($(window).scrollTop() >= target1) {
			$('#block-block-22').animate({bottom:0},"fast");
		}
	}, 250);
	var interval = setInterval(function() {
		if($(window).scrollTop() <= target2) {
			$('#block-block-22').animate({bottom:-65},"fast");
		}
	}, 250);
});
