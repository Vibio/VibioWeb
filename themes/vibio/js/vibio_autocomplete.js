Drupal.jsAC.prototype.setStatus = function (status) {
  switch (status) {
    case 'begin':
      $(this.popup).empty().append('Wait...').show();
      break;
    case 'cancel':
    case 'error':
    case 'found':
  }
};

$(document).ready(function(){
  $('div.autocomplete-result').click(function(){
    $(this).children().click();
  })
})