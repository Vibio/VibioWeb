Drupal.behaviors.homepageFilter = function(context) {
  $('#vibio-product-displays-filter-form #edit-submit').hide();
  $('#vibio-product-displays-filter-form #edit-categories').change(function() {
    $(this).parents('form:eq(0)').submit();
  });
  // Update the form action appropriately when the tab changes
  $('#quicktabs-homepage_tabs li a').click(function() {
    $form = $('#vibio-product-displays-filter-form');
    // Sorry, other query string parameters, but I don't care about you.
    $form.attr('action', $(this).attr('href'));
  });
}

// http://paste.pocoo.org/show/548965
Drupal.behaviors.refreshableTabs = function(context) {
  var pathname = window.location.pathname;
  // Enable tab memory for ALL quicktabs.
  var tablocator = 'quicktabs_wrapper';
  var tab = $.cookie(pathname + tablocator);
  if (tab != '') {
    $('div.' + tablocator + ' ul.quicktabs_tabs a#' + tab).click();
  }
  $('div.' + tablocator + ' ul.quicktabs_tabs a').click(function() {
    $.cookie(pathname + tablocator, $(this).attr('id'));
  });
}
