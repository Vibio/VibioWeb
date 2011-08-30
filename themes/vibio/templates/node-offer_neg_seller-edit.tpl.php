<!-- seller edits their response: node-offer_neg_seller-edit.tpl.php -->
<?php /* <div OnMouseOver="setTimeout(document.getElementById('edit-field-chat-0-value').value='', 800);this.onfocus='';"> */ ?>
<div OnMouseOver="setTimeout(document.getElementById('edit-field-chat-0-value').value='', 800);this.onmouseover='';">



<?php 
 /* in the future, might put this function as OnFocus in every attribute
	* below.  But need to erase them all. Or perhaps more of a cascade:
	*	onFocus for any neighbor fires the onFocus for the textarea which 
  * erases itself, so if the other neighbors fire it, no problem?
 	*/ ?>


<?php //To REMOVE Title field, in case of popup
  unset($form['title']); ?>

    <?php 
// Some configuration that one day might move from here
//$form['body_field']['body']['#rows'] = 3;
/*print drupal_render($form[field_sale_actions]);
print drupal_render($form[field_sale_status]);*/
//body, we want suggested text, disapears on focus: onfocus="this.value=''
/*
only if default
value="Search" onfocus="if
(this.value==this.defaultValue) this.value='';"

burn after opening:
onFocus="this.value='';this.onfocus='';" 
*/
unset($form['body_field']); // !!!??? go with the text version?

?>
<?php
//print drupal_render($form['body_field']['body']);
//dsm($form[field_chat]);
//$form[field_chat][display_settings][label][exclude] = 1;
/* This works, but insufficient: you have to click in the
 * text area for the text to disappear, but you might not
 * do that before hitting submit.
 $form[field_chat][0]['value']['#attributes'] = array(
	'class' => 'testthis-form',
	'onFocus' =>"this.value='';this.onfocus='';"
	);
*/
// note: this would work better if the javascript fired for this
// from the parent level onfocus, or maybe mouseover.
// this id=edit-field-chat-0-value
// document.getElementById
// <div onFocus="document.getElementById('edit-field-chat-0-value').value=&#039;&#039;;this.onfocus=&#039;&#039;;"
// Erase the content of the child text area, and erase this div's onFocus

$form[field_chat][0]['value']['#title'] = '';
//print_r($form[field_chat]['#attributes']);
//dsm($form[field_chat]['#attributes']);
// not sure why labels aren't working, and form is out of order...
//  does not seem related to this tpl
// if "accepted"
print drupal_render($form[field_accept]);
if ( $form[field_accept]['#default_value'][0]['value'] == 'accept' ) {
	print drupal_render($form[field_pay_received]);
	print drupal_render($form[field_item_sent]);
} else {
	unset($form[field_pay_received]);
	unset($form[field_item_sent]);
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
