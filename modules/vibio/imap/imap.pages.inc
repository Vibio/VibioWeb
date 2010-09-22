<?php
function imap_ajax_service()
{
	global $user;
	
	module_load_include("inc", "imap");
	
	$p = $_POST;
	$result = array(
		"saved"		=> false,
		"message"	=> t("Invalid action or you are not allowed to modify that image"),
	);
	
	$function = "imap_ajax_service_{$p['action']}";
	if (function_exists($function) && $user->uid == imap_image_owner($p['id']) && ($message = $function($p['id'])))
	{
		$result = array(
			"saved"		=> true,
			"message"	=> $message,
		);
	}
	
	exit(json_encode($result));
}

function imap_ajax_service_delete($id)
{
	imap_delete_image($id);
	return t("Image has been deleted!");
}
?>