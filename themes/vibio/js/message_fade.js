$(document).ready(function() {
  /* Fade out system messages */
  jQuery.fn.delay = function(time,func){
    return this.each(function(){
      setTimeout(func,time);
    });
  };
  /* Where it says 4000, this is 4 seconds. Adjust to your preference.
   * Script from: http://drupal.org/node/1394032 thanks! */
  $('div.messages').delay(4000, function(){
    $('div.messages').fadeOut(300);
  });
});