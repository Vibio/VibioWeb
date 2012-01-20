<?php

/**
 * Adds OG meta data for the front page, effectively representing Vibio to FB.
 *
 * @param <type> $variables
 */
function vibio_preprocess_page(&$variables){
  if(drupal_is_front_page()){
    global $base_url;
    $site_image = $base_url . '/'. drupal_get_path('theme', 'vibio') . '/vibio-logo.png';
    $og_image = '<meta property="og:image" content="' . $site_image . '"/>' . PHP_EOL;
    $og_text = '<meta property="og:description" content="Vibio is a social commerce network for people who possess a unique sense of style."/>';

    //This is apparently necessary, see http://api.drupal.org/api/drupal/includes--common.inc/function/drupal_set_html_head/6#comment-4614
    $variables['head'] .= $og_image . $og_text;
  }
  if ($variables['node']->type != "" && arg(2) != 'edit') {
    $variables['template_files'][] = "page-node-" . $variables['node']->type;
  }
}

/**
* Adds OG meta data to share an appropriate collection picture with Facebook.
* Note that vibio_addthis module contains the exact same functionality for vibio
* product nodes. It'd be great to combine all this functionality in one module, or,
* alternatively, to use an existing module like nodewords. This was rejected temporarily
* considering (hopefully) impending changes to the code and uncertainties concerning the
* adaptability of nodewords.
*
* Implements template_views_view__view_name().
*/
function vibio_preprocess_views_view__user_collection(&$variables){
  //This loads the collection, minus the collection image
  $collection = collection_load($variables['view']->args[1]);
  $image_path = collection_get_image($variables['view']->args[1]);
  //If it's not a default image...
  if(strpos($image_path,'box.png') == FALSE && strpos($image_path, 'default_item_large.png') == FALSE){
    //Do nothing
  }else{
    //Set the image path to a Vibio's logo
    $image_path = 'themes/vibio/vibio-logo.png';
  }
  //Get the CID from the view arguments, output an absolute link to the collection's image
  $collection_image = url($image_path, array('absolute' => TRUE));
  $og_image = '<meta property="og:image" content="' . $collection_image . '"/>';
  $collection_title = $collection->user_name . "'s " . $collection->title . " Collection";
  $og_title = '<meta property="og:title" content="' . $collection_title . '"/>';
  $og_data = $og_title . PHP_EOL . $og_image; 
  drupal_set_html_head($og_data);
}

/**
* Adds OG metadata for the collections (plural) page.
* 
* Implements template_views_view__view_name().
* @TODO: write a og_metadata($array) function that takes an array
* of og content keyed by og terms and produces the completed og
* meta data tags.
*
*/
function vibio_preprocess_views_view__user_collections1(&$variables){
  //Get the user's name from their id. 
  $id = $variables['view']->args[0];
  $collections_owner = db_result(db_query("SELECT name FROM {users} WHERE uid = %d", $id));
  //If the name ends in 's', simply add an apostrophe
  $reverse_name = strrev( $collections_owner);
  $reverse_name{0} == 's' ? $possessive = "'" : $possessive = "'s";
  //Generate the info we want to share
  $collections_title = $collections_owner . $possessive . " Collections"; 
  $collections_description = "See " . $collections_owner . $possessive . " collections on Vibio.";
  //Take the first collection's image to represent all the user's collections
  $collections_image_path = $variables['view']->result[0]->image;
  $collections_image = url($collections_image_path, array('absolute' => TRUE));  
  //Put together the metatags
  $og_title = '<meta property="og:title" content="' . $collections_title .'"/>';
  $og_description = '<meta property="og:description" content="' . $collections_description .'"/>';
  $og_image = '<meta property="og:image" content="' . $collections_image . '"/>'; 
  $og_data = $og_title . PHP_EOL . $og_description . PHP_EOL . $og_image;
  drupal_set_html_head($og_data);
}

/**
 * Theme function for AddThis module which generates the AddThis sharing
 * code.
 * 
 * This overrides the default function to add customizations for our desired
 * Vibio sharing functionality.
 * 
 * @param <type> $variables
 * @return <type> 
 */
function vibio_addthis_toolbox($html, $variables) {
  global $addthis_counter;
  global $base_url;
  
  empty($addthis_counter) ? $addthis_counter = 0 : '';
  if($addthis_counter < 1){
    addthis_add_default_js();
    $addthis_counter++;
  }
  $url = $variables['url'];
  if(module_exists('shorten')){
    $abbreviated_url = shorten_url($url, 'TinyURL');
  }
  $title = $variables['title'];
  $node = $variables['node'];

  $description = $node->body;
  if($variables['image'] || $node->field_main_image[0]){
    $image_path = $variables['image'] ? $variables['image'] : $node->field_main_image[0]['filepath'];
    $pin_image = $base_url . '/' . $image_path; //url() wasn't working?
  }else{
    $pin_image = $base_url . '/themes/vibio/vibio-logo.png';
  }
  return '
  <div class="share-text">
  Share: 
  </div>
  <div class="addthis_toolbox addthis_default_style"
    addthis:title="'. $title .'"
    addthis:url="'. $url .'"
    addthis:description="'. $description .'">
  <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
  <a class="addthis_button_tweet"
    tw:via="vibio"
    addthis:url="'. $abbreviated_url .'">
  </a>
  <a class="addthis_button_pinterest"
    pi:pinit:url="'. $url .'"
    pi:pinit:media="'. $pin_image . '"
    pi:pinit:layout="horizontal">
  </a>
  <a class="addthis_counter addthis_pill_style"></a>
  </div>
  ';
}

/**
 * Adds an individual addthis button, small style.
 * 
 * @param <type> $variables
 * @return <type> 
 */
function vibio_addthis_button($variables){
  global $addthis_counter;
  empty($addthis_counter) ? $addthis_counter = 0 : '';
  if($addthis_counter < 1){
    addthis_add_default_js();
    $addthis_counter++; 
  }
  $title = $variables['title'];
  if($node = $variables['node']){
  $description = $node->body;
  }
  $url = $variables['url'];
  return '
  <div class="addthis_toolbox addthis_default_style"
      addthis:title="'. $title .'"
      addthis:url="'. $url .'"
      addthis:description="'. $description .'">
    <a class="addthis_button_compact"></a>
    <a class="addthis_counter addthis_bubble_style"></a>  
  </div>
  ';
}


/* stephen:  */

/* string override for search */
function vibio_preprocess_box(&$vars) {   //, $hook)
	if ( $vars['title'] == "Your search yielded no results" ) {
		$vars['content'] ='
<h3>Add it?</h3>
Can\'t find your item? <a href="/product/add">Add it directly to our Product Database</a>
<h3>Keep looking for it?</h3>
<ul>
	<li>Try removing quotes around phrases: "paisley tie" will match less than paisley tie.</li>
	<li>Use OR: paisley tie will match less than paisley OR tie.</li>
	</ul>';
		// We might add another "Add Item button here, perhaps?  Suggest to 
		//  design team
	}
}


/* Filter Tips */
//  Other option: use Better Formats module.  But this code seems a quick fix 
//  so here it is... (have Amelia look for more nicely done examples,
//  see if automodal is used for this... but this works so stopping.)

/* remove formatting tips from edit page, shorten for details page.
 *  may want to expand the Compose Tips a little more than the short
 *  version, without going to the long version.
 */
function vibio_filter_tips($tips, $long = FALSE, $extra = '') {
  if ($long) {
		return '<h2>Compose Tips</h2><ul class="tips"><li>Web page addresses and e-mail addresses turn into links automatically.</li><li>Allowed HTML tags: &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;cite&gt; &lt;code&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt;</li><li>Lines and paragraphs break automatically.</li></ul>';
  }
  else {
    return ""; // no short tips
  }
}

/* new link replacing 
 * More information about formatting options
 * filter/tips
 */
function vibio_filter_tips_more_info() {
  return '<p>If you want fancy formatting options, read ' . l(t('this stuff.'), "filter/tips", array('attributes' => 
			array('class' => "automodal"))) . '</p>';
			// at least Private Messages looks bad without <p> at end
}


/* modified preprocess_search_results
 *  1) fiddle (freely) with searches per page
 *  2) two ways to "zebra stripe" the columns

  The interesting values in $variables are
		type
		search -> array, data-style, of results
		search_results -> which has already been themed, but not well. It gets trashed.
		search_results_not_lost -> copy of search_results, an emergency fix to be erased.

 */
function vibio_preprocess_search_results(&$variables) {
	/* notes and research:
 	dsm(array("variables" => $variables));
	// If it's owned ($results under some circumstances),
	// $user has a link to the user (html, boolean)
	// $node->uid has the owner's uid
	//*/

	if ( $variables['type'] != 'vibio_item' ) {  return; }
			// this should fix search_type_not_lost --- ToDo!!


	// Is it mine?   Could go in preprocess_search_result (singular)?
	global $user;
	foreach ( $variables['results'] as $result) {
		$item = $result['node']; // !!! eyeball: will this fire inappropriately
				// for products?  Need to check.  Not themed yet, so who knows.
		if ( $user->uid == $item->uid ) {
			$item->thisismine = true;
		}
	}



	// (May change all this if move to masonry javascript) Move
	//	things into 4 columns		
  $variables['search_results'] = '';
	$zebra = 1;
	// Combine $variables['results'] and $variables['unthemed_other_results']
	// It's not consistent what these are: results is both local and Amazon,
	//  if results is local, other is Amazon.  I think. More varieties
	//  possible.  Keep testing or rebuild from scratch.
	if(!IsSet($variables['unthemed_other_results'])) {
		$all_results = $variables['results'];
	} else {  // hey, if this was a sane one-pass search, we'd need to deal
						// with only other results, but I don't think that's a case yet?
		$all_results = array_merge($variables['results'], $variables['unthemed_other_results']);
	}
//die("did we get here");
//dsm($variables['results']);
//dsm($all_results);
  foreach ($all_results as $result) {
		$z = $result['zebra'] = $zebra%4;
		$zebra++;
    $variables["search_results_$z"] .= theme('search_result', $result, $variables['type']);
  }
	// I think this is overridden somewhere for product search, which is called
  //  item search.
  $variables['pager'] = theme('pager', NULL, 40, 0);
  // Provide alternate search results template.
  $variables['template_files'][] = 'search-results-'. $variables['type'];
}


/* simple secondary menu link cleanup, for weird secondary menu links
 *  ! not sure where the menu_with_count_text comes from, being careful
 *    not to filter it out.
 *
 * We probably want the primary menu which is a parent, so this isn't right yet!
 */
function vibio_secondary_menu_ad_hoc($secondary_links) {
	$menu_html = "";
	foreach ($secondary_links as $link)
	{
		//print_r($link);
//		$menu_html .= l($link['link_title'], $link['link_path']);
		$menu_html .= '<a href="' . $link['href'] . '">' . $link['title'] . '</a>&nbsp;|&nbsp;';	
	}
	print "<h2>Secondary Menu ad hoc from template.php creates this</h2>" . 
		"I think nothing calls this function, if you see this note, something does" .
		$menu_html;
}





/**
 * Implementation of HOOK_theme().
 */
function vibio_theme(&$existing, $type, $theme, $path) {
	$hooks = zen_theme($existing, $type, $theme, $path);
	//came back empty anyway: dsm(array("hooks from zen" => $hooks));

	$hooks = array_merge($hooks, array(
		/* I think this is not used anymore -stephen */
		"user_social_info"	=> array(
			"arguments"	=> array("uid"	=> null),
			"template"	=> "templates/user/social-info",
		),

		/* templates for forms for negotiations, inserted into offers */
		// move this to module?
		"offer_neg_buyer_node_form" => array(
			'arguments' => array('form' => NULL),
			'template' => "templates/node-offer_neg_buyer-edit"
		),
		"offer_neg_seller_node_form" => array(
			'arguments' => array('form' => NULL),
			'template' => "templates/node-offer_neg_seller-edit"
		),
	));
	
	return $hooks;
}

function vibio_preprocess_user_profile(&$vars)
{
	drupal_add_js("themes/vibio/js/user.js");
	drupal_add_css("themes/vibio/css/user.css");
	$vars['profile']['social_info'] = theme("user_social_info", arg(1));
}


/* this seems weird to me, don't you have to tell user_picture
 * whose user picture you want? Or is that from the comment  -- Stephen */
function vibio_preprocess_comment(&$vars)
{
	if (empty($vars['picture']))
	{
		$vars['picture'] = theme('user_picture', $vars['comment']);
	}
}

function phptemplate_user_relationships_pending_request_approve_link($uid, $rid)
{
	return l(
		"<button>".t("Accept")."</button>",
		"relationships/{$uid}/{$rid}/approve",
		array(
			"attributes"=> array(
				"class"	=> "uri_popup_link approve",
			),
			"html"		=> true,
		)
	);
}

function phptemplate_user_relationships_pending_request_disapprove_link($uid, $rid)
{
	return l(
		"<button>".t("Ignore")."</button>",
		"relationships/{$uid}/{$rid}/disapprove",
		array(
			"attributes"=> array(
				"class"	=> "uri_popup_link disapprove",
			),
			"html"		=> true,
		)
	);
}

function phptemplate_user_relationships_remove_link($uid, $rid)
{
	return l(
		"<img src='/themes/vibio/images/not_now.png' />",
		"relationships/{$uid}/{$rid}/remove",
		array(
			"attributes"=> array(
				"class"	=> "uri_popup_link",
			),
			"html"		=> true,
		)
	);
}

function phptemplate_user_relationships_pending_request_cancel_link($uid, $rid)
{
	return l(
		t("Cancel"),
		"relationships/{$uid}/{$rid}/cancel",
		array(
			"attributes"	=> array(
				"class"	=> "uri_popup_link",
			),
		)
	);
}

function phptemplate_user_relationships_request_relationship_direct_link($relate_to, $relationship_type)
{
	return l(
		"<button class='add_connection'>".t("Connect")."</button>",
		"relationships/{$relate_to->uid}/{$relationship_type->rtid}/request",
		array(
			"attributes"=> array(
				"class"	=> "uri_popup_link",
			),
			"html"		=> true,
		)
	);
}

function phptemplate_node_preview($node)
{
	drupal_set_message(t("Please note that images are not saved or displayed in the preview until the 'save' button is clicked"));
	
	$node_view = node_view($node);
	
	return "
		<div class='preview'>
			$node_view
		</div>
	";
}

function vibio_status_messages($display=null)
{
	$out = "";
	
	foreach (drupal_get_messages($display) as $type => $messages)
	{
		$message_out = "";
		
		foreach ($messages as $m)
		{
			$message_out = "<li>$m</li>";
		}
		
		$out .= "
			<div class='messages $type'>
				<ul>
					$message_out
				</ul>
			</div>
		";
	}
	
	return $out;
}


function vibio_user_login_block($form) {
   $form['submit']['#value'] = 'Sign up';
}
/*custom automodal widths*/
if(module_exists('automodal')){
	automodal_add('.make-modal', array(
	    'autoFit' => false
	    ,'width'   => 700
	    ,'height'  => 605
	    ,'draggable' => false)
	);
	automodal_add('.works-modal', array(
	    'autoFit' => false
	    ,'width'   => 543
	    ,'height'  => 675
		,'draggable' => false)
	);
	automodal_add('.info-modal', array(
	    'autoFit' => false
	    ,'width'   => 535
	    ,'autoFit' => true
	    ,'draggable' => false)
	);
		automodal_add('.video-modal', array(
	    'autoFit' => false
	    ,'width'   => 530
	    ,'height'  => 320
	    ,'draggable' => false)
	);
}

/*function vibio_fieldset($ele)
{
	if (!empty($ele['#collapsible']))
	{
		drupal_add_js("misc/collapse.js");

		if (!isset($ele['#attributes']['class']))
			$element['#attributes']['class'] = "";

		$element['#attributes']['class'] .= " collapsible";

		if (!empty($ele['#collapsed']))
			$element['#attributes']['class'] .= " collapsed";
	}

	$ele['#attributes']['class'] .= " rounded_content";

	$attributes = drupal_attributes($ele['#attributes']);
	$title = $ele['#title'] ? "<div class='title'>{$ele['#title']}</div>" : "";
	$description = $ele['#description'] ? "<div class='description'>{$ele['#description']}</div>" : "";
	$children = !empty($ele['#children']) ? "<table>{$ele['#children']}</table>" : "";
	$value = isset($ele['#value']) ? $ele['#value'] : "";

	return "
		<fieldset $attributes>
			$title
			$description
			$children
			$value
		</fieldset>
	";

}

function vibio_form_element($ele, $val)
{
	$out = "<tr class='form-item'";
	if (!empty($ele['#id']))
		$out .= " id='{$ele['#id']}-wrapper'";
	$out .= ">";

	$required = !empty($ele['#required']) ? "<span class='form-required'>*</span>" : "";

	$out .= "<td class='form-item-label'>";
	if (!empty($ele['#title']))
		$out .= t("!title!required", array("!title" => filter_xss_admin($ele['#title']), "!required" => $required));
	$out .= "</td>";

	$out .= "<td class='form-item-value'>$val";
	if (!empty($ele['#description']))
		$out .= "<div class='description'>{$ele['#description']}</div>";
	$out .= "</td>";

	$out .= "</tr>";
	return $out;
}

function vibio_radios($ele)
{
	$ele['#children'] = "<table>{$ele['#children']}</table>";
	return theme_radios($ele);
}

function vibio_checkboxes($ele)
{
	$ele['#children'] = "<table>{$ele['#children']}</table>";
	return theme_checkboxes($ele);
}*/

/*Remove Resizable funtion of text area*/
/*function phptemplate_textarea($element) {
  if (strpos($_GET['q'], 'node/add') !== 0) {
    $element['#resizable'] = FALSE;
  }
  return theme_textarea($element);
}*/

?>
