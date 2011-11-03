<?php
// $Id: node-offer.tpl.php,v 1.2 2011/08/16 stephen Exp $

// Main display is presented ideally as a popupa -- oh, that changed again,
//  but I think it might change back.
// Also, people see their offers either as seller or buyer... these are
//  like teaser variants (but two for every offer, buy or sell).
//  This can be determined by teaser + node-by-viewer

// dpm(array("node-offer.tpl node" => $node));

/**
 * @file
 * Theme implementation to display a node -- THIS IS DEFAULT node.tpl commenting
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $display_submitted: whether submission information should be displayed.
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 *   The following applies only to viewers who are registered users:
 *   - node-by-viewer: Node is authored by the user currently viewing the page.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $build_mode: Build mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $build_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * The following variables are deprecated and will be removed in Drupal 7:
 * - $picture: This variable has been renamed $user_picture in Drupal 7.
 * - $submitted: Themed submission information output from
 *   theme_node_submitted().
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see zen_preprocess()
 * @see zen_preprocess_node()
 * @see zen_process()
 */
global $base_url;
?>

<?php /* we need the conversation/negotiation */
	// some of this move to pre_process or similar? 

	// Get key pieces of the view of past conversations
	list($chit_chat, $current_buyer, $current_seller) =
		_offer_conversation_info($node);


	/* We need the user, and perhaps picture, of the nodereferenced item */
	$item_nid = $node->field_item_sought[0][nid];
	$item = node_load($item_nid);
	$item_owner_uid = $item->uid;
	//dpr(array("item for sale is" => $item));
	//dpr(array("this offer node is" => $node));


	//print "<p>{admin}Most recent buyer neg is " . $current_buyer->nid . " uid " . $current_buyer->uid . " ... buyer is this user if no buyer set " . $user->uid;
	//print "<p>{admin}Most recent seller neg is " . $current_seller->nid  . " uid " . $current_seller->uid . " which always (unless blank) matches item owner " . $item_owner_uid;


	// Permissions are based on who this is 
	//  !!! What to do if making offer on your own item? For now, allowed
	global $user;
	if ( $current_buyer->uid == $user->uid ||
			 !$current_buyer) {  /* you made the offer, or there is none yet */
		$perm_buyer = 1; // permissions of the buyer
	}
	if ( $item_owner_uid == $user->uid ) { $perm_seller = 1; }

?>


<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix popthis">
 <div id="offer_top"><div id="product_info_in_offer">
  <?php /* product picture (name in title) */ 
	// dsm($item); -> I don't see collection as part of this

	// get the product this item is a part of.  This is some of the worst
	// code I can imagine... it's not a node reference, there's nothing
  // but internal functions for an item to load it's parent product.
	module_load_include('inc', 'product', 'product');  
	//include "~/www/vibio/src/modules/vibio/vibio_item/product_catalog/product.inc";
	
	if ( ( $product_nid = $item->product_nid ) ||  /* never set? */
  $product_nid = _product_nid_from_item($item_nid) ) {
		$product = node_load( $product_nid );
//dsm($product);
	} else { /* improve code later */ }
	$product_link = "/node/$product_nid";
 ?>
	<?php /* does the item have an image, or just the product? 
				 * In next iteration, grab $item->field_images[0] if it exists.
				 */
	//$image_filepath = $product->field_main_image[0]['filepath'];
	//$alt = "Picture of Item";
  ?>	
   <div class="item-picture-offer" ><a href="<?php print $product_link; ?>"><?php print vibio_item_get_image($item->nid, 'item_for_offers', "Picture of Item, or Product" ); ?></a>
  
 </div>
 </div>

 <div id="offer_item_offer">
  <div class="item_title"><a href="<?php print $product_link; ?>"><?php print $product->title; ?></a></div>

<?php
	// Print the listed asking price, if greater than 0
	$price = $item->offer2buy[settings][price];
	if ( $price > 0 ) {
		print "Asking Price: $price";
	}
?>


 </div><!-- /offer_item_offer -->

 <div id="seller_info_in_offer" >
	<?php 
	//Prepare $item_owner and the proper imagecached picture
	$item_owner = user_load($item_owner_uid);
	$item_owner->picture ? $item_owner_picture = $item_owner->picture : $item_owner_picture = 'themes/vibio/images/icons/default_user_large.png';
	?>
<?php	/* <div class="person_pic">
		<?php
			$alt = $item_owner->name . '\'s Picture';
                        $title = '$item_owner->name'; // orname, if it’snot printed right below anyway
                        $attributes = ‘’;
                        print theme('imagecache', "tiny_profile_pic", $item_owner_picture, $alt,$title, $attributes);
		?>
	</div> */ ?>
		<div class="person_pic_seller">
			<?php
			  print "<b>Seller:</b> <a href='" . $base_url ."/user/" . $item_owner->uid . "'>" .
			  $item_owner->name . "</a>" ; 
			?>  
<?php /* it was Requested price, Jason sent frames that had it as Price,
	* Simon said "Asking", Jason changed it to "List", back to Asking. */ ?>
<?php

//Accept: 
print  _vibio_offer_simplify_accept($current_seller, $current_buyer);
print  _vibio_offer_simplify_pay($current_seller, $current_buyer);
print  _vibio_offer_simplify_ship($current_seller, $current_buyer);
/* another way to print them:
print content_view_field(content_fields("field_accept"), $current_seller, FALSE, FALSE);
print content_view_field(content_fields("field_pay_received"), $current_seller, FALSE, FALSE);
print content_view_field(content_fields("field_item_sent"), $current_seller, FALSE, FALSE);
*/
?>
</div>
 </div></div>
<?php /* Prep the buyer info section. Moves depending on buyer logged in. */ 
	$offer_user = user_load($node->uid);
	$amount = $node->field_price[0][value];
	$offer_user->picture ? $offer_user_picture = $offer_user->picture : $offer_user_picture = 'themes/vibio/images/icons/default_user_large.png';
        $offer_user_alt = $offer_user->name . '\'s Picture';
        $offer_user_title = ''; // orname, if it’snot printed right below anyway
        $offer_user_attributes = '';
	$buyer_div = 
   '<div class="person_pic">' .
	 theme('imagecache', "tiny_profile_pic", $offer_user_picture, $offer_user_alt, $offer_user_title, $offer_user_attributes) .
	 '</div>' .
	 "<div class='person_pic_seller'>Buyer: " . $name . "</div><div class='person_pic_offer'>Offer:$amount</div>"; 

if ($perm_seller) {
 	$buyer_div .=
  content_view_field(content_fields("field_offer_price"), $current_buyer, FALSE, FALSE) 
. content_view_field(content_fields("field_offer_expires"), $current_buyer, FALSE, FALSE) 
. content_view_field(content_fields("field_city"), $current_buyer, FALSE, FALSE) 
. content_view_field(content_fields("field_actions"), $current_buyer, FALSE, FALSE);
} else {
	/* have no wireframes on offers visible to neither buyer nor seller,
	 *  but not sure they don't exist. 
   * For now, show seller info more publicly, and buyer info is just name
   *  and photo (not actual offer).  This is the spot to change it. */
}
   

  // huh?
  if(!function_exists("node_object_prepare")) {
    include_once(drupal_get_path('module', 'node') . '/node.pages.inc');
  }

	// Add appropriate 'add node' form
	
	
	if ( $perm_seller ) { /* permissions of seller true */
						global $user;
			$first_node = new stdClass();
			$first_node->type = 'offer_neg_seller';
			// set the user
			$first_node->uid = $user->uid;	
			$first_node->name = $user->name;
			// set the offer it's attached to
			// [field_offer]
			$first_node->field_offer[0]['nid'] = $node->nid;  // node is this offer
			_load_default_offer($first_node,$current_seller);
			$output = "<div id='form-column'>
			  <div id='buyer-for-seller'>$buyer_div</div>\n" . 
			  '<div id="seller-neg-form" class="neg-form">' .
				  drupal_get_form('offer_neg_seller_node_form', $first_node) .
				'</div></div>';	
		} elseif ( $perm_buyer ) { // can't sell to yourself.

			$second_node = new stdClass();
			$second_node->type = 'offer_neg_buyer';
			// set the user
			$second_node->uid = $user->uid;	
			$second_node->name = $user->name;
			// set the offer it's attached to
			// [field_offer]
			$second_node->field_offer[0]['nid'] = $node->nid;  // node is this offer
			// pass on some very temporary info to the node
			$second_node->buyer_accepted = $current_seller->field_accept[0][value];
			//$second_node->brandnew = 
			_load_default_offer($second_node,$current_buyer);

		//print "<h1>" . $second_node->buyer_accepted . "</h1>";
			$output .= "<div id='form-column'>" .
				'<div id="buyer-neg-form" class="neg-form">' .
				drupal_get_form('offer_neg_buyer_node_form', $second_node) .
				'</div></div>';
			  
		} else {
			// for now, non-buyer non-seller uses what will become an archaic div,
			//  but works bet for now.
			$output = "<div id='buyer_info_in_offer' >$buyer_div</div>";
		}

print $output; ?>

  <div id="neg-discuss">
  <?php /* print the conversation, view defined (and cache loaded) above */
//print views_embed_view($viewName, $display_id, $myArgs);
	if ( $perm_buyer || $perm_seller ) { // you  are buyer or seller
		print "<h3>Negotiation Log</h3><div id='chit-chat'>" . $chit_chat . "</div>";
	} else {
		print "<div><h3>Discussions are private to the participants.</h3></div>";
	}
  ?>
</div>

  <?php // print $links; Links contains: Archive, Decline, Decline (huh?)  ?>
 <!-- /.node -->

</div>
