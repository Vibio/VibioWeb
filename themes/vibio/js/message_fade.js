$(document).ready(function() {
  /* Fade out system messages */
  jQuery.fn.delay = function(time,func){
    return this.each(function(){
      setTimeout(func,time);
    });
  };
  /* Where it says 3000, this is 3 seconds. Adjust to your preference.
   * Script from: http://drupal.org/node/1394032 thanks! */
  $('.messages').delay(3000, function(){
    $('.messages').fadeOut(300);
  });
});