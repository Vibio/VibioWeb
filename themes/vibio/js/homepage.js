Drupal.behaviors.homepageFilter = function(context) {
  $('#vibio-product-displays-filter-form #edit-submit').hide();
  $('#vibio-product-displays-filter-form #edit-categories').change(function() {
    $(this).parents('form:eq(0)').submit();
  });
  // Update the form action appropriately when the tab changes
  $('#quicktabs-homepage-tabs li[class^="qtab"]').click(function() {
    $form = $(this).parents('form:eq(0)'); 

  });
}
