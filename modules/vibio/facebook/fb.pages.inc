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
	
	$sql = "SELECT `uid`
			FROM {users}
			WHERE `name`='%s'
				OR `mail`='%s'";
	$uid = db_result(db_query($sql, $fb_user->name, $fb_user->email));
	
	if ($uid)
	{
		if ($uid == fb_user_exists($fb_user->id)) // if the uid exists and the uid matches an existing fb user, auto login
		{
			user_external_login(user_load($uid));
			drupal_goto($_GET['destination']);
		}
		
		fb_del_cookie();
		drupal_set_message(t("The username !username or email address !email already exists", array("!username" => $fb_user->name, "!email" => $fb_user->email)), "error");
		drupal_set_message(t("Account registration failed. Please manually create an account and link your facebook account."));
		drupal_goto($_GET['destination']);
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
		drupal_set_message(t("Account registration failed. Please manually create an account and link your facebook account."));
		drupal_goto($_GET['destination']);
	}
	
	drupal_set_message(t("You have successfully created an account!"));
	user_external_login($account);
	drupal_goto($_GET['destination']);
}
?>