

/**
 * This function looks for jwplayer class items and loads them
 * as jwplayers. 
 */
Drupal.behaviors.jwplayerInit = function (context) {
  $('.jwplayer:not(.jwplayerInit-processed)', context).addClass('jwplayerInit-processed').each(function () {
    var config = Drupal.settings.jwplayer['files'][$(this).attr('id')];
    //A bit of a hack, but necessary to get inline events working.
    for (var index in config.events) {
      var temp = "";
      eval("temp = function() {alert('Player Ready')}");
      config.events[index] = temp;
    }
    jwplayer($(this).attr('id')).setup(config);
  });
};
