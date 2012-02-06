$(document).ready(function () {
  if($('body.page-product-new-item').is('*')){
    var initRadioValue = $('input[name=have_want]:checked').val();

    if(initRadioValue == 1){
        //...hide elements
        $('#edit-field-main-image-0-ahah-wrapper, #field-images-items, ' +
          '#edit-field-posting-type-value-wrapper, ' +
          '').css('display', 'none');
    }

    $('div.form-radios').click(function (){
      var radioValue = $('input[name=have_want]:checked').val();
      //If it's a want form...
      if(radioValue == 1){
        //...hide elements
        $('#edit-field-main-image-0-ahah-wrapper, #field-images-items, ' +
          '#edit-field-posting-type-value-wrapper, ' +
          '').css('display', 'none');
      }else{
         $('#edit-field-main-image-0-ahah-wrapper, #field-images-items, ' +
          '#edit-field-posting-type-value-wrapper, ' +
          '').css('display', 'inline');
      }
    })
    
    //Only show offer2buy if the item is marked for sale
    var postingType = $('select#edit-field-posting-type-value').val();

    if(postingType == 1){
      $('div#edit-o2b-price-wrapper').parent().css('display', 'none');
    }

    //If offer2buy is toggled on, show the price field.
    $('select#edit-field-posting-type-value').click(function (){
      if($('select#edit-field-posting-type-value').val() == 2){
        $('div#edit-o2b-price-wrapper').parent().css('display', 'inline');
      }else{
        $('div#edit-o2b-price-wrapper').parent().css('display', 'none');
      }
    })
  }
})