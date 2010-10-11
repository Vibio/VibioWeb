<?php
function reports_settings()
{
	$options = array();
	
	for ($i = 0; $i < 24; ++$i)
	{
		$options[$i] = $i;
	}
	
	// rid 1 is anon user, 2 is authenticated user
	$sql = "SELECT `rid`, `name`
			FROM {role}
			WHERE `rid` > 2
			ORDER BY `name` ASC";
	$res = db_query($sql);
	
	$role_options = array();
	while ($row = db_fetch_object($res))
	{
		$role_options[$row->rid] = $row->name;
	}
	
	return system_settings_form(array(
		"reports_employee_role" => array(
			"#type"			=> "select",
			"#title"		=> t("Employee Role"),
			"#description"	=> t("What role do all Vibio employees have?"),
			"#options"		=> $role_options,
			"#default_value"=> variable_get("reports_employee_role", false),
		),
		"reports_hour" 	=> array(
			"#type"			=> "select",
			"#title"		=> t("Reports Hour"),
			"#description"	=> t("This is the hour that the daily reporting cron will run. The hour is in server time."),
			"#options"		=> $options,
			"#default_value"=> variable_get("reports_hour", 0),
		),
		"reports_offset"=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Reports time offset"),
			"#description"	=> t("The time difference between SERVER time and GMT (in hours)"),
			"#required"		=> true,
			"#size"			=> 3,
			"#default_value"=> variable_get("reports_offset", 0),
		),
	));
}

function reports_overview()
{
	module_load_include("inc", "reports");
	
	return drupal_get_form("reports_report_selector", false, false, false, false);
}

function reports_view_report($name)
{	
	$from = $_GET['from'] ? $_GET['from'] : false;
	$to = $_GET['to'] ? $_GET['to'] : false;
	$start = date("M j, Y", $from);
	$end = date("M j, Y", $to);
	
	module_load_include("inc", "reports");
	drupal_set_title("Report: $name from $start to $end");
	
	$select_form = drupal_get_form("reports_report_selector", $name, $from, $to);
	$report = reports_load($name, $from, $to, $_GET['hide_employees']);
	
	if (empty($report))
	{
		return $select_form.t("No report data found.");
	}
	
	reports_add_js($name, $report);
	drupal_add_css(drupal_get_path("module", "reports")."/reports.css");
	
	return theme("reports_view_report", $select_form);
}
?>