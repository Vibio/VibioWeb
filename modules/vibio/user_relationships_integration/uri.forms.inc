<?php
function uri_elaborations_form($relationship=false)
{
	if (!$relationship)
	{
		return "";
	}
	
	$sql = "SELECT `elaboration`
			FROM {user_relationship_elaborations}
			WHERE `rid`=%d";
	$elaboration = db_result(db_query($sql, $relationship->rid));
	
	return array(
		"#type"			=> "textarea",
		"#description"	=> t("How do you know !user? Keep in mind that both you and !user are able to view this.", array("!user" => "nelson")),
		"#default_value"=> $elaboration,
	);
}

function uri_pending_request_action($action, $account, $rid)
{
	if ($_POST['submit'])
	{
		$out = uri_pending_request_action_do_submit($action, $account, $rid, $_POST);
	}
	else
	{
		$out = json_encode(array(
			"html"		=> drupal_get_form("uri_pending_request_action_form", $action, $account, $rid),
			"callback"	=> "pending_request",
		));
	}
	
	exit($out);
}

// similar to user_relationships_ui_pending_requested form
function uri_pending_request_action_form(&$state, $action, $account, $rid)
{
	global $user;
	$relationship = user_relationships_load(array("rid" => $rid));
	$relationship = $relationship[$rid];
	$viewed_id = $account->uid;
	
	$t_args = array(
		'@action'             => $action,
        '%relationship_name'  => ur_tt("user_relationships:rtid:$relationship->rtid:name", $relationship->name),
        '!name'               => theme('username', user_load(array('uid' => ($viewed_id == $relationship->requester_id ? $relationship->requestee_id : $relationship->requester_id))))
	);
	
	switch ($action)
	{
		case "approve":
			$confirmation_message = t("Are you sure you want to approve the %relationship_name request from !name?", $t_args);
			$header = t("Approve Relationship");
			break;
		case "disapprove":
			$confirmation_message = t("Are you sure you want to disapprove the %relationship_name request from !name?", $t_args);
			$header = t("Disapprove Relationship");
			break;
		case "cancel":
			$confirmation_message = t("Are you sure you want to cancel your %relationship_name request to !name?", $t_args);
			$header = t("Cancel %relationship_name Request", $t_args);
			break;
	}
	
	$form = array(
		"container"		=> array(
			"#type"			=> "fieldset",
			"#title"		=> $header,
			"#description"	=> $confirmation_message,
			"uri_pending_request_confirm"	=> array(
				"#type"	=> "submit",
				"#value"=> t("Yes"),
			),
			"uri_pending_request_cancel"	=> array(
				"#type"	=> "button",
				"#value"=> t("No"),
			),
		),
	);
	
	if ($action == "approve" && module_exists("user_relationships_elaborations"))
	{
		$form['container']['uri_pending_elaboration'] = uri_elaborations_form($relationship);
	}
	
	return $form;
}

function uri_pending_request_action_do_submit($action, $account, $rid, $extra)
{
	global $user;
	
	$relationship = user_relationships_load(array("rid" => $rid), array("include_user_info" => true));
	$relationship = $relationship[$rid];
	$requester = $relationship->requester;
	$requestee = $relationship->requestee;
	$saved = true;
	
	switch ($action)
	{
		case "approve":
			if (!user_access('maintain own relationships') || (!user_access('administer user relationships') && $user->uid != $requestee->uid))
			{
				$saved = false;
			}
			else
			{
				$relationship->approved = true;
				user_relationships_save_relationship($relationship, $action);
				$message = user_relationships_ui_get_message('accepted', $relationship);
			}
			break;
		case "cancel":
			if (!user_access('maintain own relationships') || (!user_access('administer user relationships') && $user->uid != $requester->uid))
			{
				$saved = false;
			}
			else
			{
				user_relationships_delete_relationship($relationship, $user, $action);
				$message = user_relationships_ui_get_message($action, $relationship);
			}
			break;
		case "disapprove":
			if (!user_access('maintain own relationships') || (!user_access('administer user relationships') && $user->uid != $requestee->uid))
			{
				$saved = false;
			}
			else
			{
				user_relationships_delete_relationship($relationship, $user, $action);
				$message = t("You have ignored the friend request.");
			}
			break;
		default:
			$saved = false;
			$message = t("Invalid Action");
	}
	
	return json_encode(array(
		"saved"		=> $saved,
		"message"	=> $message,
	));
}

function uri_remove_relationship($account, $relationship)
{
	if ($_POST['submit'])
	{
		$out = uri_remove_relationship_do_submit($account, $relationship);
	}
	else
	{
		$out = json_encode(array(
			"html"		=> drupal_get_form("uri_remove_relationship_form", $account, $relationship),
			"callback"	=> "remove_relationship",
		));
	}
	
	exit($out);
}

function uri_remove_relationship_form(&$state, $account, $relationship)
{
	global $user;
	
	$viewed_id = $account->uid;
	$confirmation_message = t("Are you sure you wish to delete the %relationship_name relationship with !name?", array(
		'%relationship_name'	=> ur_tt("user_relationships:rtid:$relationship->rtid:name", $relationship->name),
		'!name'					=> theme('username', user_load(array('uid' => ($viewed_id == $relationship->requester_id ? $relationship->requestee_id : $relationship->requester_id))))
    ));
	
	return array(
		"container"		=> array(
			"#type"			=> "fieldset",
			"#title"		=> t("Remove Relationship"),
			"#description"	=> $confirmation_message,
			"uri_remove_relationship_confirm"	=> array(
				"#type"	=> "submit",
				"#value"=> t("Yes"),
			),
			"uri_remove_relationship_cancel"	=> array(
				"#type"	=> "button",
				"#value"=> t("No"),
			),
		),
	);
}

function uri_remove_relationship_do_submit($account, $relationship)
{
	global $user;
	
	$saved = true;
	
	if (!user_access('maintain own relationships') || (!user_access('administer user relationships') && $user->uid != $relationship->requester_id && $user->uid != $relationship->requestee_id))
	{
		$saved = false;
		$message = t("You are not allowed to remove that relationship");
	}
	else
	{
		user_relationships_delete_relationship($relationship, $user);
		$message = user_relationships_ui_get_message('removed', $relationship);
	}
	
	return json_encode(array(
		"saved"		=> $saved,
		"message"	=> $message,
	));
}

function uri_request_relationship($requestee, $relationship)
{
	if ($_POST['submit'])
	{
		$out = uri_request_relationship_do_submit($requestee, $relationship, $_POST);
	}
	else
	{
		$out = json_encode(array(
			"html"		=> drupal_get_form("uri_request_relationship_form", $requestee, $relationship),
			"callback"	=> "request_relationship",
		));
	}
	
	exit($out);
}

function uri_request_relationship_form(&$state, $requestee, $relationship)
{
	global $user;
	$requester = $user;
	$error = uri_request_relationship_form_is_valid_request($requestee, $requester, $relationship);
	
	$form = array(
		"container" => array(
			"#type"			=> "fieldset",
			"#title"		=> t("%rel_name Request", array("!name" => $requestee->name, "%rel_name" => $relationship->name)),
		),			
	);
	
	if (!$error)
	{
		$description = $relationship->requires_approval ?
			t('Are you sure you wish to send a new %rel_name request to !name?', array('!name' => $requestee->name, '%rel_name' => ur_tt("user_relationships:rtid:$relationship->rtid:name", $relationship->name), '%rel_plural_name' => ur_tt("user_relationships:rtid:$relationship->rtid:plural_name", $relationship->plural_name), '!name' => theme('username', $requestee))) :
			t("Are you sure you want to become !name's %rel_name?", array('!name' => $requestee->name, '%rel_name' => ur_tt("user_relationships:rtid:$relationship->rtid:name", $relationship->name)));
			
		$form = array_merge_recursive($form, array(
			"container"	=> array(
				"#description" => $description,
				"uri_request_relationship_confirm" => array(
					"#type"	=> "submit",
					"#value"=> t("Send"),
				),
			),
		));
	}
	else
	{
		$form = array_merge_recursive($form, array(
			"container" => array(
				"error" => array(
					"#value" => $error,
				),
			),
		));
		
		if (module_exists("user_relationship_elaborations"))
		{
			$form['container']['uri_pending_elaboration'] = uri_elaborations_form();
		}
	}
	
	return array_merge_recursive($form, array(
		"container" => array(
			"uri_request_relationship_cancel" => array(
				"#type"	=> "submit",
				"#value"=> t("Cancel"),
			),
		),
	));
}

function uri_request_relationship_form_is_valid_request($requestee, $requester, $relationship)
{
	$error = false;
	
	if (!$relationship)
	{
		$error = t("Invalid relationship type");
	}
	elseif (empty($requestee->name))
	{
		$error = user_relationships_ui_get_message('non_existent_user');
	}
	elseif (!user_access("can have relationships", $requestee))
	{
		$error = user_relationships_ui_get_message('not_accepting_requests');
	}
	elseif ($user->uid == $requestee->uid)
	{
		$error = user_relationships_ui_get_message('self_request');
	}
	elseif (!variable_get('user_relationships_allow_multiple', TRUE) && $relationships_count = user_relationships_load(array('between' => array($requester->uid, $requestee->uid)), array('count' => TRUE)))
	{
		$error = user_relationships_ui_get_message('too_many_relationships');
	}
	
	return $error;
}

function uri_request_relationship_do_submit($requestee, $relationship, $extra)
{
	global $user;
	$requester = $user;
	$error = uri_request_relationship_form_is_valid_request($requestee, $requester, $relationship);
	$saved = true;
	
	if ($error)
	{
		$saved = false;
		$message = $error;
	}
	else
	{
		$relationship->requester = $requester;
		$relationship->requestee = $requestee;
		$relationship->type = $relationship; // zomg :O
		
		if (($relationship = user_relationships_request_relationship($relationship)) === false)
		{
			$saved = false;
			$message = user_relationships_ui_get_message('unknown_error', $relationship);
		}
		else
		{
			$message = user_relationships_ui_get_message(($relationship->approved ? 'pre_approved' : 'submitted'), $relationship);
		}
	}
	
	return json_encode(array(
		"saved"		=> $saved,
		"message"	=> $message,
	));
}
?>