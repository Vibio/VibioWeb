Drupal.behaviors.infinitescrollMasonry = function(context) {
  $viewSelector = $(
    '.view.view-flag-featured'
  );

  if ($(".pager-last").find('a').attr('href') != undefined) {
    // Get the number of pages from the Views Pager (Use the full pager, it will be hidden with .infinitescroll() anyway)
    lastPageHref = $(".pager-last").find('a').attr('href').toString();
    lastPageHref = lastPageHref.split("=");
    numOfPages = parseInt(lastPageHref[1]);

    $contentSelector = $('.view-content', $viewSelector);
    $contentSelector.infinitescroll({
        loading: {
          img: '/themes/vibio/images/barloader.gif'
        },
        state: {
          finishedMsg: "No more pages to load."
        },
        debug: false,
        nextSelector: ".pager .pager-next a", // selector for the NEXT link (to page 2)
        navSelector: ".pager", // selector for the paged navigation
        itemSelector: ".views-row", // selector for all items you'll retrieve
        animate: false,
        pathParse: '?page=1',
				errorCallback: function() {
        $('#infscr-loading').animate({
opacity : .8
}, 500).fadeOut('normal');
        //fade out the error message after 2 seconds
				}
        },
        // TODO: Call Masonry as a callback
        function() { });
  }
}
