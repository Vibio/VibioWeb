/*fixed scroll*/
$(document).ready(function() {
    $('#block-block-5').scrollToFixed();
    $('#block-block-5').bind('fixed', function() { $(this).css('margin', '50px 0px 0'); });
    $('#block-block-5').bind('unfixed', function() { $(this).css('margin', '50px -10px 0'); });
    //$('.view.view-flag-featured .view-footer').scrollToFixed();
});
/*scroll to top*/
$('a.topOfPage').click(function() {
$.scrollTo(0, 500);
return false;
}); 