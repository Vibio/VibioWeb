$(document).ready(function () {
  //only apply to the multistep pages
  if($('body.page-product-new-item').is('*') || $('body.page-product-new').is('*')
    || $('body.page-product-new-product').is('*')){

    var textarea = $('#edit-body-wrapper textarea.form-textarea');
    var description = $('#edit-body-wrapper .description');
    if(textarea.val() == ''){
      description.show();
    }else{
      description.hide();
    }

    textarea.focus(function(){
      description.hide();
    });

    description.click(function(){
      description.hide();
      textarea.focus();
    });

    textarea.blur(function(){
      if(textarea.val() == ''){
        description.show();
      }
    });
    
    //only apply to the item step
    if($('body.page-product-new-item').is('*')){
      var initRadioValue = $('input[name=have_want]:checked').val();

      //If we're in the "want" mode
      if(initRadioValue == 1){
        //...hide elements
        $('#edit-field-main-image-0-ahah-wrapper, ' +
          '#edit-field-posting-type-value-wrapper').hide();
        $('div#field-images-items').css('display', 'none');
        $('div#edit-o2b-price-wrapper').parent().hide();
        //Set the body description
        description.html('Why do you want this item?');
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
          description.html('Why do you want this item?');
        }else{
          $('#edit-field-main-image-0-ahah-wrapper, div#field-images-items, ' +
            '#edit-field-posting-type-value-wrapper').show();
          $('div#field-images-items').css('display', 'inline');
          description.html('This is where you add details that are specific to the item you own. <br /> For example: Condition, Damage, History etc.');
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
  }
})