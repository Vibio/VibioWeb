<?php
function profile_ext_service_home()
{
	$result = array();
	$view = views_service_get("user_hb_incoming_activity", "default", array(), 0, 20);
	$view = views_get_view("user_hb_incoming_activity");
}