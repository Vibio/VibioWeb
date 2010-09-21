<?php
function profile_ext_get_dashboard_notifications($uid, &$tabs)
{
	$num_notifications = 0;
	
	foreach (module_invoke_all("profile_ext_dashboard_notifications", $uid) as $module_name => $data)
	{
		$num_notifications += $data['count'];
		$output .= $data['display'];
	}
	
	$title = t("Notifications !num", array("!num" => $num_notifications > 0 ? "($num_notifications)" : ""));
	$tabs[$title] = $output;
	
	return $num_notifications;
}
?>