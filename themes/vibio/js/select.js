/**
* attach Jquery Dropdown functionality
*/
Drupal.behaviors.themeAttachJQDropDown = function(context) {
  $('#node-form select').each(function () {
    $(this).addClass('jquery_dropdown jquery_dropdown_jump');
  });
}