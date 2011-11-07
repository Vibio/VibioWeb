/*fixed scroll*/
$(document).ready(function() {
    $('#block-block-5').scrollToFixed();
    $('#block-block-5').bind('fixed', function() { $(this).css('margin', '0px 0px'); });
    $('#block-block-5').bind('unfixed', function() { $(this).css('margin', '0px -10px'); });
    //$('.view.view-flag-featured .view-footer').scrollToFixed();
});
/*scroll to top*/
$('a.topOfPage').click(function() {
$.scrollTo(0, 500);
return false;
}); 