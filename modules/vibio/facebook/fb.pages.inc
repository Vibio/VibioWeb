<?php
//this will actually handle logging in/sign up
function fb_user_signup()
{
	global $user;
	
	if ($user->uid)
	{
		drupal_goto($_GET['destination']);
	}
	
	module_load_include("inc", "fb");
	$fb_user = fb_get_user(fb_get_cookie()); // here we get the raw, unverified cookie. we need to do this here since the user is logged out at this point
	
	if ($uid = fb_user_exists($fb_user->id)) // if you have the cookie, you are good to go
	{
		user_external_login(user_load($uid));
		drupal_goto($_GET['destination']);
	}
	else // if you the user doesn't exist, we have to check if that username/email already exists in drupal
	{
		$sql = "SELECT `uid`
				FROM {users}
				WHERE `mail`='%s'";
		if ($uid = db_result(db_query($sql, $fb_user->email)))
		{
			fb_del_cookie();
			drupal_set_message(t("The email address !email already exists on Vibio. If you own this account, please log in and manually link your Facebook account.", array("!email" => $fb_user->email)), "error");
			drupal_goto($_GET['destination']);
		}
	}
	
	$state = array(
		"redirect"	=> null,
		"values"	=> array(
			"name"	=> $fb_user->name,
			"mail"	=> $fb_user->email,
			"pass"	=> user_password(),
			"fb_id"	=> $fb_user->id,
			"status"=> true,
		),
	);
	
	$form = drupal_retrieve_form("user_register", $state);
	drupal_prepare_form("user_register", $form, $state);
	drupal_validate_form("user_register", $form, $state);
	$success = !form_get_errors();
	
	if (!$success || !($account = user_save("", $state['values'])))
	{
		fb_del_cookie();
		drupal_set_message(t("Account registration failed. Please manually create an account and link your facebook account."), "error");
		drupal_goto($_GET['destination']);
	}
	
	drupal_set_message(t("You have successfully created an account!"));
	user_external_login($account);
	drupal_goto($_GET['destination']);
}

function fb_user_link_account()
{
	global $user;
	
	if (!$user->uid)
	{
		drupal_goto($_GET['destination']);
	}
	
	module_load_include("inc", "fb");
	$fb_user = fb_get_user(fb_get_cookie());
	
	$sql = "SELECT `uid`, `name`
			FROM {users} 
			WHERE `uid` IN (
				SELECT `uid`
				FROM {fb_users}
				WHERE `fb_id`=%d
			)";
	$u = db_fetch_object(db_query($sql, $fb_user->id));
	
	if ($u)
	{
		drupal_set_message(t("That Facebook account is already linked to the Vibio account !account. Please remove the account from !account's account before continuing.", array("!account" => $u->name)), "error");
		drupal_goto($_GET['destination']);
	}
	
	fb_save_user($user, $fb_user->id);
	drupal_set_message(t("Your Facebook account has been linked to Vibio!"));
	drupal_goto($_GET['destination']);
}

function fb_user_remove_account()
{
	global $user;
	
	module_load_include("inc", "fb");
	
	$fb_id = arg(2);
	if (!$fb_id || $user->uid != fb_user_exists($fb_id))
	{
		drupal_set_message(t("You are not allowed to unlink that Facebook account"), "error");
		drupal_goto($_GET['destination']);
	}
	
	$sql = "DELETE FROM {fb_users}
			WHERE `uid`=%d
				AND `fb_id`=%d";
	db_query($sql, $user->uid, $fb_id);
	
	drupal_set_message(t("Your Facebook account has been successfully removed from your Vibio account."));
	drupal_goto($_GET['destination']);
}
?>