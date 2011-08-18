<?php 
 /**
  * coped from seller version.  Biggest difference: node-offer.tpl
	*  or perhaps a preprocess function sets
	*  $node->buyer_accepted = not yet | accepted | declined
	*/
?>
<!-- seller edits their response: node-offer_neg_seller-edit.tpl.php -->
<div OnMouseOver="setTimeout(document.getElementById('edit-field-chat-0-value').value='', 800);this.onfocus='';">


<?php 
 /* in the future, might put this function as OnFocus in every attribute
	* below.  But need to erase them all. Or perhaps more of a cascade:
	*	onFocus for any neighbor fires the onFocus for the textarea which 
  * erases itself, so if the other neighbors fire it, no problem?
 	*/ ?>


<?php //To REMOVE Title field, in case of popup
  unset($form['title']); ?>

    <?php 
unset($form['body_field']); // !!!??? go with the text version?

$form[field_chat][0]['value']['#title'] = '';

$accept = $form['#node']->buyer_accepted;
/* print everything you always see. */
print drupal_render($form[field_price]);
print drupal_render($form[field_offer_expires]);
print drupal_render($form[field_city]);


if ( $accept == 'accept' ) {
	print "buy that crap right now";
	print drupal_render($form[field_pay_sent]);
	print drupal_render($form[field_item_received]);
} else {
	unset($form[field_item_received]);
	unset($form[field_pay_sent]);
}
$form[field_chat][0][value]['#cols'] = 7; // does css override this?
					// rows works, cols doesn't
print drupal_render($form[field_chat]);



$form['buttons']['submit']['#value'] = 'Respond to Offer';
// not working yet....
//$form[field_chat]['#attributes']['onFocus'] = "this.value='';this.onfocus='';";
// nor this.
print drupal_render($form);

    ?>
</div>
<?php 
  
  //Enable below to show all Array Variables of Form 
/*
  print '<pre>';
	print_r($form['field_chat'][0][value]);
//  print_r($form);
  print '</pre>';
*/
