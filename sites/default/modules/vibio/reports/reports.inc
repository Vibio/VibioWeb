<?php
function reports_total_users()
{
	$sql = "SELECT COUNT(*)
			FROM {users}";
	$total = db_result(db_query($sql));
	
	reports_log("total users", $total);
	
	if ($employee_role = variable_get("reports_employee_role", false))
	{
		$sql = "SELECT COUNT(*)
				FROM {users}
				WHERE `uid` NOT IN (
					SELECT `uid`
					FROM {users_roles}
					WHERE `rid`=%d
				)";
		$total = db_result(db_query($sql, $employee_role));
		reports_log("total users noemployee", $total);
	}
}

function reports_new_users($since=0)
{
	$sql = "SELECT COUNT(*)
			FROM {users}
			WHERE `created` >= %d";
	$new = db_result(db_query($sql, $since));
	
	reports_log("new users", $new, "manual signup");
	
	if ($employee_role = variable_get("reports_employee_role", false))
	{
		$sql = "SELECT COUNT(*)
				FROM {users}
				WHERE `created` >= %d
					AND `uid` NOT IN (
						SELECT `uid`
						FROM {users_roles}
						WHERE `rid`=%d
					)";
		$total = db_result(db_query($sql, $since, $employee_role));
		reports_log("new users noemployee", $total, "manual signup");
	}
}

function reports_add_js($name, $data)
{
	drupal_add_js(drupal_get_path("module", "reports")."/js/flot/jquery.flot.js");
	drupal_add_js(drupal_get_path("module", "reports")."/js/reports.js");
	
	$name = str_replace(" noemployee", "", $name);
	$callback = "reports_add_js_".str_replace(" ", "_", $name);
	
	if (function_exists($callback))
	{
		$callback($data);
	}
	else
	{
		reports_default_js($name, $data);
	}
}

function reports_add_js_items($data)
{
	drupal_add_js(drupal_get_path("module", "reports")."/js/jquery.flot.pie.js");
	
	$totals = array();
	foreach ($data as $point)
	{
		$totals[$point->mod] += $point->value;
	}
	
	$pie_data = array();
	foreach ($totals as $key => $val)
	{
		$pie_data[] = array(
			"label"	=> $key,
			"data"	=> $val,
		);
	}
	
	$flot = array(
		"flot"	=> array(
			"data"		=> $pie_data,
			"options"	=> array(
				"series"	=> array(
					"pie"	=> array(
						"show"			=> true,
						"radius"		=> "auto",
						"innerRadius"	=> 0,
						"tilt"			=> 1,
						"label"			=> array(
							"show"	=> true,
						),
						"highlight"		=> array(
							"opacity"	=> 1,
						),
					),
				),
			),
			"merge_defaults" => false,
			"is_pie"		=> true,
		),
	);
	
	drupal_add_js($flot, "setting");
}

function reports_default_js($name, $data)
{
	drupal_add_js(drupal_get_path("module", "reports")."/js/flot/jquery.flot.selection.js");
	
	$flot = array(
		"flot"	=> array(
			"merge_defaults"=> true,
			"is_pie"		=> false,
		),
	);
	
	$mods = array();
	foreach ($data as $point)
	{
		if (!in_array($point->mod, $mods))
		{
			$mods[] = $point->mod;
		}
		
		$key = array_search($point->mod, $mods);
		$flot['flot']['data'][$key]['data'][] = array(
			$point->tstamp,
			$point->value,
		);
		$flot['flot']['data'][$key]['label'] = $point->mod;
	}
	
	drupal_add_js($flot, "setting");
}

function reports_log($name, $val, $mod="")
{
	global $db_url;
	$db = db_set_active("reports");
	$sql = "INSERT IGNORE INTO {reports}
			SET `name`='%s', `value`=%d, `mod`='%s', `tstamp`=%d";
	db_query($sql, $name, $val, $mod, time());
	db_set_active($db);
}

function reports_report_selector(&$state, $name, $from, $to, $collapsed=true)
{
	$report_name = str_replace(" noemployee", "", $name);
	$from = $from ? $from : time() - REPORT_DEFAULT_START;
	$to = $to ? $to : time();
	
	$from = array(
		"year"	=> date("Y", $from),
		"month"	=> date("n", $from),
		"day"	=> date("d", $from),
	);
	$to = array(
		"year"	=> date("Y", $to),
		"month"	=> date("n", $to),
		"day"	=> date("d", $to),
	);
	
	$db = db_set_active("reports");
	$sql = "SELECT DISTINCT(`name`)
			FROM {reports}
			WHERE `name` NOT LIKE '%%noemployee'
			ORDER BY `name`";
	$res = db_query($sql);
	db_set_active($db);
	
	$report_options = array();
	while ($row = db_fetch_object($res))
	{
		$report_options[$row->name] = $row->name;
	}
	
	return array(
		"date_selector"	=> array(
			"#type"			=> "fieldset",
			"#title"		=> t("Report Selector"),
			"#collapsible"	=> true,
			"#collapsed"	=> $collapsed,
			"report_name"	=> array(
				"#type"			=> "select",
				"#title"		=> t("Name"),
				"#options"		=> $report_options,
				"#default_value"=> $report_name,
			),
			"report_start"	=> array(
				"#type"			=> "date",
				"#title"		=> t("Start Date"),
				"#default_value"=> $from,
			),
			"report_end"	=> array(
				"#type"			=> "date",
				"#title"		=> t("End Date"),
				"#default_value"=> $to,
			),
			"hide_employees"=> array(
				"#type"			=> "checkbox",
				"#title"		=> t("Hide Employee Data"),
				"#default_value"=> $_GET['hide_employees'],
			),
			"submit"		=> array(
				"#type"	=> "submit",
				"#value"=> t("View Report"),
			),
		),
	);
}

function reports_report_selector_submit($form, &$state)
{
	$vals = $state['values'];
	$from = strtotime("{$vals['report_start']['year']}-{$vals['report_start']['month']}-{$vals['report_start']['day']}");
	$to = strtotime("{$vals['report_end']['year']}-{$vals['report_end']['month']}-{$vals['report_end']['day']}");
	$hide_employees = $vals['hide_employees'] ? 1 : 0;
	
	drupal_goto("reports/{$vals['report_name']}", "from=$from&to=$to&hide_employees=$hide_employees");
}
?>