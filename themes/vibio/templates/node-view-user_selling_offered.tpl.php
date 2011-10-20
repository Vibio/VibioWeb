<?php
// $Id: node.tpl.php,v 1.10 2009/11/02 17:42:27 johnalbin Exp $

/**
 * @file
 * Theme implementation to display a node.
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
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix">

<?php //dsm($node);
// get the item sought
$item = node_load( $node->field_item_sought[0][nid] );
$item_title = $item->title;
// messy: get field_main_image, but that's probably empty, get product.
$item_pic_url = $item->picture;

//dsm($item);

// get collection
$cid = isset($item->collection_info) ? $item->collection_info['cid'] : collection_get_item_cid($item->nid);
//trim($cid);
if ( is_array($cid) ) {
	$cid = array_shift($cid);
}
//$cid[]= 7;
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
<!-- sites/default/themes/vibio/templates/node-view-user_selling_offered.tpl.php -->
<div class="selling_item_info">
<div class="teaser_item_pic"><?php print $item_pic; ?></div>
<?php print $collection['title']; ?>
<?php print $node->field_item_sought[0][view];?>
<br>Requested: $<?php print $price; ?>
</div>


  <?php print $user_picture; /* vibiosity will go here
 * Watch out -- weird css here: themes/vibio/css/pages.css
 * imagecache on next revision	
 */ ?>

	<?php /* create a tpl just for this? */ 
	/* ANOTHER POSSIBLE STYLE, but these are just notes, trying popups module first:
       <div class='offer2buy_popup'>
               &nbsp;- <a class='offer2buy_offer_view_popup_init'>
                      Negotiate as a popup
               </a>
               <div class='offer2buy_offer_view_popup'>
                       <div class='view-content'>whatever goes in the popup</div>
               </div>
       </div>
*/
/* Popup styles:
 1 - what's above,Nelson's I think
 2 - a class="popups" ...
 3 - Nelson: "class" => "uri_popup_link"
*/



// New style (buyer_then_seller)
list($chit_chat, $current_buyer, $current_seller) =
  _offer_conversation_info($node);


?>
	<div class="negotiation_block" style="float: right; width: 200px;">
		<a  class="automodal popups-form-reload" href="/node/<?php print $node->nid; ?>">Review Offer</a>
	</div>

  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>

  <div class="content">
    <?php print $name;  /* v2: connection level */
 			/* is this up to date in the offer, or do we need to do more to load the
				 negs */
		 ?>

 <br><strong>Their Offer: <?php
if ( $current_buyer->field_price[0][value] ) {
	print $current_buyer->field_price[0][value] ;
} else {
	print "--";
}
if ( $current_buyer->field_city[0][value] ) {
	print "<br>Ship: " . $current_buyer->field_city[0][value]; 
}

        //label of "Latest Offer" not correct text... print content_view_field(content_fields("field_price"), $current_buyer, FALSE, FALSE);
        print  _vibio_offer_simplify_accept($current_seller, $current_buyer);
        print  _vibio_offer_simplify_pay($current_seller, $current_buyer);
        print  _vibio_offer_simplify_ship($current_seller, $current_buyer);

?></strong>

	<br>Last Comment:	<?php /* we want one line from the most recent conversation,
			yours or theirs.  Similar to node-offer.tpl.php */
  $viewName = 'offer_conversation';
  $display_id = 'default';
  $myArgs = array($node->nid); // node is this offer
        // below is much like views_embed_view
  $view_neg = views_get_view($viewName);
  $view_neg->set_arguments($myArgs);
					$chit_chat = $view_neg->preview($display_id, $args);
					$neg = node_load( $view_neg->result[0]->nid );   // is this most recent? or oldest?
	//dsm($neg);
	// note" if $neg->uid == $user->uid, it's me.  
	// I think this should be in design
	$chars = 70;  // length of string to print before elipses
	$text = $neg->field_chat[0][value];

  if ( strlen($text) > ($chars+3) ) {
    print substr($text, 0 , $chars) . "...";
  } else {
    print $text;
  }

	//dsm(array($node, $view_neg));	
		?>
  </div>
	<?php print $links; //should just be archive flag link ?>
</div> <!-- /.node -->
