<?php

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
  return 'If you want fancy formatting options, read ' .
		l(t('this stuff.'), "filter/tips", array('attributes' => 
			array('class' => "automodal"))) . '<p>';
			// at least Private Messages looks bad without <p> at end
}


/* modified preprocess_search_results
 *  1) fiddle (freely) with searches per page
 *  2) two ways to "zebra stripe" the columns
 */
function vibio_preprocess_search_results(&$variables) {
	/* stephen, fixing stuff: wonder if $variables['search_results']
   * gets overwritten when you have a non-Amazon product-line?
   */


  $variables['search_results'] = '';
	$zebra = 0;
  foreach ($variables['results'] as $result) {
		$z = $result['zebra'] = $zebra%4; // change to number of columns you want
		$zebra++;
    $variables["search_results_$z"] .=  /* "col$z" .*/ theme('search_result', $result, $variables['type']);
  }
	// other_results
  // Stephen: make other_results more like regular results
  // !!! I think later might go back deeper, and make other_results
  //  just be more normal. Rabbit hole for another day.
  foreach ($variables['unthemed_other_results'] as $result) {
    $z = $result['zebra'] = $zebra%4;    
		$zebra++;
    $variables["search_results_$z"] .= "col$z" . theme('search_result', $result, $variables['type']);
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

/* Not sure what this is for, maybe nothing, generating errors...
   Remove 20110920 and see if we need any of it 
 */

/* What's this?  Custom menu links, not noticed when doing v1.2 work  
function vibio_menu_item_link($link)
{


Worried this may have a real function:

	if ($link['type'] & MENU_IS_LOCAL_TASK && $link['path'] == "search/node/%")
	{
		return "";
	}

	/* no longer used? doesn't look important 
	elseif (strpos($link['router_path'], "my-dashboard/") !== false)
	{
		$link['localized_options']['html'] = true;
	}
  * /	

	// this loads missing style.css
Is it really desired for anything ever to go to parent zen theme?
	return zen_menu_item_link($link);
}
*/

function vibio_preprocess_page(&$vars, $hook)
{
	zen_preprocess_page($vars, $hook);
	
	$css = "";
	
	foreach (drupal_add_css() as $media => $types)
	{
		$css .= "<style type='text/css' rel='stylesheet' media='$media'>";
		foreach ($types as $type => $files)
		{
			foreach ($files as $file => $preprocess)
			{
				$css .= "@import \"/{$file}\";\n";
			}
		}
		$css .= "</style>";
	}
	
	$vars['styles'] = $css;
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
				"class"	=> "uri_popup_link accept",
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
				"class"	=> "uri_popup_link ignore",
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
				"class"	=> "uri_popup_link remove",
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
				"class"	=> "uri_popup_link cancel",
			),
		)
	);
}

function phptemplate_user_relationships_request_relationship_direct_link($relate_to, $relationship_type)
{
	return l(
		"<button>".t("Add friend")."</button>",
		"relationships/{$relate_to->uid}/{$relationship_type->rtid}/request",
		array(
			"attributes"=> array(
				"class"	=> "uri_popup_link add_friend",
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

/*this is for theming selects*/

function vibio_select($element) {
  $select = '';
  if (in_array($element['#post']['form_id'], 'node-form')) {
  $element['#attributes'] = array('class' => 'jquery_dropdown jquery_dropdown_jump');
  }
  $size = $element['#size'] ? ' size="'. $element['#size'] .'"' : '';
  _form_set_class($element, array('form-select'));
  $multiple = $element['#multiple'];
  return theme('form_element', $element, '<select name="'. $element['#name'] .''. ($multiple ? '[]' : '') .'"'. ($multiple ? ' multiple="multiple" ' : '') . drupal_attributes($element['#attributes']) .' id="'. $element['#id'] .'" '. $size .'>'. form_select_options($element) .'</select>');
}


/*fieldset theming*/
function vibio_fieldset($ele)
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
/*
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
