$(document).ready(function () {
  if($('body.page-product-new-item').is('*')){
    var initRadioValue = $('input[name=have_want]:checked').val();

    if(initRadioValue == 1){
        //...hide elements
        $('#edit-field-main-image-0-ahah-wrapper, ' +
          '#edit-field-posting-type-value-wrapper').hide();
        $('div#field-images-items').css('display', 'none');
        $('div#edit-o2b-price-wrapper').parent().hide();
    }

    $('div.form-radios').click(function (){
      var radioValue = $('input[name=have_want]:checked').val();
      //If it's a want form...
      if(radioValue == 1){
        //...hide elements
        $('#edit-field-main-image-0-ahah-wrapper, div#field-images-items, ' +
          '#edit-field-posting-type-value-wrapper').hide();
        $('div#field-images-items').css('display', 'none');
        $('div#edit-o2b-price-wrapper').parent().hide();
      }else{
        $('#edit-field-main-image-0-ahah-wrapper, div#field-images-items, ' +
          '#edit-field-posting-type-value-wrapper').show();
        $('div#field-images-items').css('display', 'inline');
      }
    })
    
    //Only show offer2buy if the item is marked for sale
    var postingType = $('select#edit-field-posting-type-value').val();

    if(postingType == 1){
      $('div#edit-o2b-price-wrapper').parent().hide();
    }

    //If offer2buy is toggled on, show the price field.
    $('select#edit-field-posting-type-value').click(function (){
      if($('select#edit-field-posting-type-value').val() == 2){
        $('div#edit-o2b-price-wrapper').parent().show();
      }else{
        $('div#edit-o2b-price-wrapper').parent().hide();
      }
    })
  }
})