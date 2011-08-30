<?php 
 /**
  * coped from seller version.  Biggest difference: node-offer.tpl
	*  or perhaps a preprocess function sets
	*  $node->buyer_accepted = not yet | accepted | declined
	*/
?>
<!-- seller edits their response: node-offer_neg_seller-edit.tpl.php -->
<div id="offer_neg_buyer" OnMouseOver="setTimeout(document.getElementById('edit-field-chat-0-value').value='', 800);this.onmouseover='';">


<?php
	$buyer = user_load($form['#node']->uid);
?>
<div class="person_pic"><?php print theme('user_picture', $buyer); ?></div>

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

//city box too big.  Themer can adjust freely:
$form[field_city][0][value]['#size'] = 50;

$form[field_price][0][value]['#title'] ='My Offer';
print drupal_render($form[field_price]);
print drupal_render($form[field_offer_expires]);
print drupal_render($form[field_city]);

// Why aren't defaults being set?  Deal with that later.
// This tpl does not appear to be the cause..
// Oh: maybe it's just old data before defaults were set,
//	before required was set?
if ( $accept != 'accept' ) {
  $hide_class = ' hide';
}
//if ( $form[field_pay_sent][0][value]
print "<div class='dependent $hide_class'>";
print drupal_render($form[field_pay_sent]);         
print drupal_render($form[field_item_received]);
print "</div>";

/* this doesn't work if defaults not set 
if ( $accept == 'accept' ) {
	print drupal_render($form[field_pay_sent]); 
	print drupal_render($form[field_item_received]);
} else {
	unset($form[field_item_received]);
	unset($form[field_pay_sent]);
}
*/

print drupal_render($form[field_chat]);



$form['buttons']['submit']['#value'] = 'Make or Update Offer';
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
