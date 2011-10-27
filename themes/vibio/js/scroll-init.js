$(function(){

    //Get the number of pages from the Views Pager (Use the full pager, it will be hidden with .infinitescroll() anyway.)
    lastPageHref = $(".pager-last").find('a').attr('href').toString(); 
    lastPageHref = lastPageHref.split("=");
    numOfPages = parseInt(lastPageHref[1]);

    $('.view.view-flag-featured .view-content').masonry({
        columnWidth: 180, 
        itemSelector: '.views-row:visible' 
    });

    $('.view.view-flag-featured .view-content').infinitescroll({
        navSelector  : ".pager",    // selector for the paged navigation 
        nextSelector : ".pager .pager-next a",    // selector for the NEXT link (to page 2)
        itemSelector : ".views-row",       // selector for all items you'll retrieve
        loadingImg : '/themes/vibio/images/loader.gif',
        donetext  : "No more pages to load.",
        debug: false,
        pathParse : '?page=0',
        pages: numOfPages, //NEW OPTION: number of pages in the Views Pager
        errorCallback: function() { $('#infscr-loading').animate({opacity: .8},2000).fadeOut('normal');//fade out the error message after 2 seconds

      }
    },
    // call masonry as a callback.
    function() { $('.view.view-flag-featured .view-content').masonry({ appendedContent: $(this) }); }
    );
});