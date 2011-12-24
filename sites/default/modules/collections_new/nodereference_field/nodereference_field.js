// $Id: nodereference_field.js,v 1.1 2009/06/12 18:31:17 boombatower Exp $

// On drop of row update the region (status) information.
Drupal.behaviors.fieldDrag = function(context) {
  var table = $('table#nodereference_field');
  var tableDrag = Drupal.tableDrag.nodereference_field;

  tableDrag.onDrop = function() {
    dragObject = this;
    if ($(dragObject.rowObject.element).prev('tr').is('.region')) {
      var group = $($(dragObject.rowObject.element).prev('tr').filter('.region').children('td').get(0));
      var field_name = jQuery.trim($($(dragObject.rowObject.element).children('td').get(1)).text());
      var select = $('select[name=\"referenceable_fields[' + field_name + '][region]\"]');

      select.val(group.hasClass('nodereference_field-enabled') ? 'enabled' : 'disabled');
    }
  }
}

// Hide status column.
$(document).ready(function() {
  $('table#nodereference_field tr').each(function() {
    var children = $(this).children();
    if (children.length > 1) {
      $(children[3]).hide();
    }
  });
});
