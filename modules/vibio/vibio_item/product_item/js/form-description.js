(function ($) {
  Drupal.behaviors.front_page = {
    attach: function(context, settings){

      var fields = $('input.form-text');

      // If a field gets focus then hide the label
      // (which is the previous element in the DOM).
      fields.focus(function(){
        $(this).next().hide();
      });

      // If a field loses focus and nothing has
      // been entered in the field then show the label.
      fields.blur(function(){
        if (!this.value) {
          $(this).next().show();
        }
      });

      // If the form is pre-populated with some values
      // then immediately hide the corresponding labels.
      fields.each(function(){
        if (this.value) {
          $(this).next().hide();
        }
      });
    }
  }
})(jQuery);