<?php
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

function phptemplate_user_relationships_pending_request_approve_link($uid, $rid)
{
	return l(
		t("Accept"),
		"relationships/{$uid}/{$rid}/approve",
		array(
			"attributes"	=> array(
				"class"	=> "uri_popup_link",
			),
		)
	);
}

function phptemplate_user_relationships_pending_request_disapprove_link($uid, $rid)
{
	return l(
		t("Ignore"),
		"relationships/{$uid}/{$rid}/disapprove",
		array(
			"attributes"	=> array(
				"class"	=> "uri_popup_link",
			),
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
		t("Send !name a %rel_name request", array('!name' => $relate_to->name, '%rel_name' => ur_tt("user_relationships:rtid:$relationship_type->rtid:name", $relationship_type->name))),
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
