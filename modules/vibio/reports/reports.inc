<?php
function reports_total_users()
{
	$sql = "SELECT COUNT(*)
			FROM {users}";
	$total = db_result(db_query($sql));
	
	reports_log("total users", $total);
}

function reports_new_users($since=0)
{
	$sql = "SELECT COUNT(*)
			FROM {users}
			WHERE `created` >= %d";
	$new = db_result(db_query($sql, $since));
	
	reports_log("new users", $new);
}

function reports_log($name, $val, $mod="")
{
	$db = db_set_active("reports");
	$sql = "INSERT IGNORE INTO {reports}
			SET `name`='%s', `value`=%d, `mod`='%s', `tstamp`=%d";
	db_query($sql, $name, $val, $mod, time());
	db_set_active($db);
}
?>