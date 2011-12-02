/* This code is BELIEVED to work on the Featured page (yeah!) and the Search page (boo! -- fixed below)
 And maybe everywhere else on the site.
 It hides the pager, and almost certainly many more things that bug fixers can document
 when they break things.
 It appears to call/coordinate the masonry javascript.
 */

$(function() {

	// encapsulate this run-away script, Features page only
	if($("body").hasClass("front") && $("body").hasClass("logged-in")) {

		if($(".pager-last").find('a').attr('href') != undefined) {
			//Get the number of pages from the Views Pager (Use the full pager, it will be hidden with .infinitescroll() anyway)
			lastPageHref = $(".pager-last").find('a').attr('href').toString();
			lastPageHref = lastPageHref.split("=");
			numOfPages = parseInt(lastPageHref[1]);
            $('.view.view-flag-featured .view-content').imagesLoaded(function(){
      		$('.view.view-flag-featured .view-content').masonry({
        	columnWidth : 180,
        	itemSelector : '.views-row:visible'
     	 	});
	    });


			$('.view.view-flag-featured .view-content').infinitescroll({
				navSelector : ".pager", // selector for the paged navigation
				nextSelector : ".pager .pager-next a", // selector for the NEXT link (to page 2)
				itemSelector : ".views-row", // selector for all items you'll retrieve
				loadingImg : '/themes/vibio/images/barloader.gif',
				donetext : "No more pages to load.",
				debug : false,
				pathParse : '?page=1',
				animate : false,
				pages : numOfPages, //NEW OPTION: number of pages in the Views Pager
				errorCallback : function() {
					$('#infscr-loading').animate({
						opacity : .8
					}, 2000).fadeOut('normal');
					//fade out the error message after 2 seconds

				}
			},
			// call masonry as a callback.
			function() {
        var existingMaterial = $(this);
				$('.view.view-flag-featured .view-content').imagesLoaded(function(){
          $('.view.view-flag-featured .view-content').masonry({
            appendedContent : existingMaterial
          });
        });
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
		}

	}

});
