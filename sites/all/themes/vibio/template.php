<?php

/* stephen:  */

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
	print $menu_html;
}





/**
 * Implementation of HOOK_theme().
 */
function vibio_theme(&$existing, $type, $theme, $path) {
	$hooks = zen_theme($existing, $type, $theme, $path);

	$hooks = array_merge($hooks, array(
		"user_social_info"	=> array(
			"arguments"	=> array("uid"	=> null),
			"template"	=> "templates/user/social-info",
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

function vibio_menu_item_link($link)
{
	if ($link['type'] & MENU_IS_LOCAL_TASK && $link['path'] == "search/node/%")
	{
		return "";
	}
	elseif (strpos($link['router_path'], "my-dashboard/") !== false)
	{
		$link['localized_options']['html'] = true;
	}
	
	return zen_menu_item_link($link);
}

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
				"class"	=> "uri_popup_link",
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
				"class"	=> "uri_popup_link",
			),
			"html"		=> true,
		)
	);
}

function phptemplate_user_relationships_remove_link($uid, $rid)
{
	return l(
		"<img src='/themes/vibio/images/close_button.png' />",
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
		"<button>".t("Add friend")."</button>",
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

/*  note: we're in the theming section, not good to do any serious changes */
function vibio_user_login_block($form) {
   $form['submit']['#value'] = 'Sign up';
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
