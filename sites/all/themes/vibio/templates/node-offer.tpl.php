<h1>Note: using the node-offer template</h1>

<?php
// $Id: node.tpl.php,v 1.10 2009/11/02 17:42:27 johnalbin Exp $
dpm(array("node-offer.tpl node" => $node));


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

  <?php /* we need the conversation/negotiation */
$viewName = 'offer_conversation';
$display_id = 'default';
$myArgs = array($node->nid); // node is this offer
	// below is much like views_embed_view
  $view_neg = views_get_view($viewName);
  $view_neg->set_arguments($myArgs);
	// access could get complicated, not wireframed yet!
	$chit_chat = $view_neg->preview($display_id, $args);
				print "<h1>Result</h1>";
						//dpr( $view_neg->result );
				/* looks like this, sorts by date:
						[0] => stdClass Object
								(
										[nid] => 20029
										[node_created] => 1309999051
								)
				*/

	/* Need the most recent buyer and seller node from the communications */
	foreach ( $view_neg->result as $neg ) {
		$neg = node_load($neg->nid); // need rest of node loaded, another way???!!!
		//dpr( $neg );
		if ( !$current_seller && ( $neg->type == 'offer_neg_seller' ) ) {
			$current_seller = $neg;
		} 
		elseif ( !$current_buyer && ( $neg->type == 'offer_neg_buyer' ) ) {
			$current_buyer = $neg;
		}
	}


	/* We need the user, and perhaps picture, of the nodereferenced item */
	$item_nid = $node->field_item_sought[0][nid];
	$item = node_load($item_nid);
	$item_owner = $item->uid;
	print "<h3>Owned by $item_owner, I am $user->uid</h3>";
	//dpr(array("item for sale is" => $item));
	//dpr(array("this offer node is" => $node));


	print "<p>{admin}Most recent buyer neg is " . $current_buyer->nid . " uid " . $current_buyer->uid . " ... buyer is this user if no buyer set " . $user->uid;
	print "<p>{admin}Most recent seller neg is " . $current_seller->nid  . " uid " . $current_seller->uid . " which always (unless blank) matches item owner " . $item_owner;


	// Permissions are based on who this is 
	//  !!! What to do if making offer on your own item? For now, allowed
	global $user;
	if ( $current_buyer->uid == $user->uid ||
			 !$current_buyer) {  /* you made the offer, or there is none yet */
		$perm_buyer = 1; 
	}
	if ( $item_owner == $user->uid ) { $perm_seller = 1; }

?>
</pre>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix popthis">

 <div id="product_info_in_offer" style="border: 1px solid black;">
  <?php /* product picture (name in title) */ ?>
   <div class="item_title"><?php print $item->title; ?></div>
   <div class="item_picture" style="height: 60px; widht: 60px;"><?php print $item->picture; ?></div>
 </div>

 <?php /* render cck fields?  drupal_render($node->content); 
		field_myfield[0]['view']; */ ?>


 <div id="seller_info_in_offer" style="border: 1px solid black;">
  <?php print "Picture and name of $item_owner"; ?>
  <br>Requested price: <?php print $item->offer2buy[settings][price]; ?>
  get the most recent seller post from a view?
<?php print content_view_field(content_fields("field_sale_status"), $current_seller, FALSE, FALSE);?>
<?php print content_view_field(content_fields("field_sale_actions"), $current_seller, FALSE, FALSE);?>


 </div>
 
 <div id="buyer_info_in_offer" style="border: 1px solid black;">
  <?php print "Picture and name of " . $node->uid; ?>
  <br><?php print "Last offer (get last offer post info)"; ?>
<?php print content_view_field(content_fields("field_offer_expires"), $current_buyer, FALSE, FALSE);?>
<?php print content_view_field(content_fields("field_city"), $current_buyer, FALSE, FALSE);?>


 </div>
   
  <?php if ($unpublished ): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>

  <?php if ('get rid of' == 'normal content' && $display_submitted || $terms): ?>
    <div class="meta">
      <?php if ($display_submitted): ?>
        <span class="submitted">
          <?php
            print t('Submitted by !username on !datetime',
              array('!username' => $name, '!datetime' => $date));
          ?>
        </span>
      <?php endif; ?>

    </div>
  <?php endif; ?>


<?php
  if(!function_exists("node_object_prepare")) {
    include_once(drupal_get_path('module', 'node') . '/node.pages.inc');
  }

		if ( $perm_seller ) { /* permissions of seller true */
			echo '<h3>Negotiate Your Sale</h3>';

			// Add 1st add node form
			// title?
			global $user;
			$first_node = new stdClass();
			$first_node->type = 'offer_neg_seller';
			// set the user
			$first_node->uid = $user->uid;	
			$first_node->name = $user->name;
			// set the offer it's attached to
			// [field_offer]
			$first_node->field_offer[0]['nid'] = $node->nid;  // node is this offer
			$output = drupal_get_form('offer_neg_seller_node_form', $first_node);
  	}
		if ( $perm_buyer ) { 
      echo '<h3>Negotiate to Buy</h3>';

			$second_node = new stdClass();
			$second_node->type = 'offer_neg_buyer';
			// set the user
			$second_node->uid = $user->uid;	
			$second_node->name = $user->name;
			// set the offer it's attached to
			// [field_offer]
			$second_node->field_offer[0]['nid'] = $node->nid;  // node is this offer
			$output .= drupal_get_form('offer_neg_buyer_node_form', $second_node);
		}

print $output;
?>

<h3>Chit Chat</h3>
  <?php /* print the conversation, view defined (and cache loaded) above */
//print views_embed_view($viewName, $display_id, $myArgs);
print $chit_chat;
  ?>

  <?php print $links; ?>
</div> <!-- /.node -->
