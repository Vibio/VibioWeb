<?php
// $Id: node.tpl.php,v 1.10 2009/11/02 17:42:27 johnalbin Exp $

// Copied from the ""buying" version, then tweaked. -- stephen 20110825 


/**
 * @file
 * Theme implementation to display a node.
 *
 * $node is the offer (for this row of the view) for the buying page
 */
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix">

<?php //dsm($node);
// get the item sought
$item = node_load( $node->field_item_sought[0][nid] );
$item_title = $item->title;
// messy: get field_main_image, but that's probably empty, get product.
$item_pic_url = $item->picture;

//buyer specific
//skip: $owner_uid = $item->uid;
$owner = user_load($item->uid);


// New style (buyer_then_seller)
list($chit_chat, $current_buyer, $current_seller) =
  _offer_conversation_info($node);

//dsm($current_buyer);

// get collection
$cid = isset($item->collection_info) ? $item->collection_info['cid'] : collection_get_item_cid($item->nid);
//trim($cid);
if ( is_array($cid) ) {
	$cid = array_shift($cid);
}
$collection = collection_load($cid);  
//dsm(array($item,$collection));
if ( $collection['title'] ) {
	$collection['title'] = '<div class="collection"><a href="/collections/' .
		$cid . '">' . 
		$collection['title'] . "</a></div>";
}


$item_pic = vibio_item_get_image($item->nid, 'product_fixed_width_teaser');
// at the moment, product_fixed_width is 180px, product_fixed_width_teaser 120px

// Offer2Buy info
$price = $item->offer2buy['settings']['price'];

 ?>
<div class="selling_item_info"
 >
<div class="teaser_item_pic"><?php print $item_pic; ?></div>
<?php print $collection['title']; ?>
<?php print $node->field_item_sought[0][view];?>
<br>List Price: $<?php print $price; ?>
</div>


  <?php  /* vibiosity will go here
/* put this code into sellers version when chance */
$alt = $owner->name . 'Picture';
$title = ''; // orname, if it’snot printed right below anyway
$attributes = '';
print theme('imagecache', 'little_profile_pic', $owner->picture, $alt,$title, $attributes);
  ?>

	<div class="negotiation_block" style="float: right; width: 200px;">
		<a  class="popups" href="/node/<?php print $node->nid; ?>">View / Edit</a>
	</div>

  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>

  <div class="content">
    <?php print "Seller: " . $owner->name;  /* v2: connection level */
 			/* is this up to date in the offer, or do we need to do more to load the
				 negs */
		 ?>
    <br><strong>Your Offer: <?php print $current_buyer->field_price[0][value]; ?> <?php
	//label of "Latest Offer" not correct text... print content_view_field(content_fields("field_price"), $current_buyer, FALSE, FALSE);
	print  _vibio_offer_simplify_accept($current_seller, $current_buyer);
	print  _vibio_offer_simplify_pay($current_seller, $current_buyer);
	print  _vibio_offer_simplify_ship($current_seller, $current_buyer);

?></strong>
	<div class="comment">Last Comment:	<?php /* we want one line from the most recent conversation,
			yours or theirs.  Similar to node-offer.tpl.php */
  $viewName = 'offer_conversation';
  $display_id = 'default';
  $myArgs = array($node->nid); // node is this offer
        // below is much like views_embed_view
  $view_neg = views_get_view($viewName);
  $view_neg->set_arguments($myArgs);
	/*$chit_chat = */$view_neg->preview($display_id, $args);
	$neg = node_load( $view_neg->result[0]->nid );   // is this most recent? or oldest?
	// note" if $neg->uid == $user->uid, it's me.  
	// I think this should be in design
	$chars = 70;  // length of string to print before elipses
	$text = $neg->field_chat[0][value];
	if ( strlen($text) > ($chars+3) ) {
		print substr($text, 0 , $chars) . "...";
	} else {
		print $text;
	}
		?>
	</div><!-- comment -->
  </div>
	<?php print $links; //should just be archive flag link ?>
</div> <!-- /.node -->
