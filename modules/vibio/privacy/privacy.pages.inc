<?php
define("PRIVACY_BATCH_SIZE", 10000);

function privacy_mass_set()
{
	return array(
		"overwrite"	=> array(
			"#type"	=> "checkbox",
			"#title"=> t("Overwrite existing settings"),
		),
		"type"		=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Type"),
			"#required"		=> true,
		),
		"type_id"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Type ID"),
			"#required"		=> true,
		),
		"setting"	=> array(
			"#type"			=> "select",
			"#title"		=> t("Privacy setting"),
			"#options"		=> _privacy_options(),
		),
		"submit"	=> array(
			"#type"	=> "submit",
			"#value"=> t("Set Privacy"),
		),
	);
}

function privacy_mass_set_submit($form, &$state)
{
	set_time_limit(0);
	
	$form_vals = $state['values'];
	
	$op = $vals['overwrite'] ? "REPLACE" : "INSERT IGNORE";
	
	$sql = "SELECT `uid`
			FROM {users}
			WHERE `uid` > 0";
	$res = db_query($sql);
	
	$vals = $args = array();
	$sql = "$op INTO {privacy_settings}
			(`uid`, `type`, `type_id`, `setting`) VALUES";
	for ($i = 0; $row = db_fetch_object($res); ++$i)
	{
		if (($i + 1) % PRIVACY_BATCH_SIZE == 0)
		{
			if (!empty($vals))
			{
				$insert = $sql.implode(",", $vals);
				db_query($insert, $args);
			}
			
			$vals = array();
			$args = array();
		}
		
		$vals[] = "(%d, '%s', '%s', %d)";
		$args[] = $row->uid;
		$args[] = $form_vals['type'];
		$args[] = $form_vals['type_id'];
		$args[] = $form_vals['setting'];
	}
	
	if (!empty($vals))
	{
		$insert = $sql.implode(",", $vals);
		db_query($insert, $args);
	}
	
	drupal_set_message(t("The deed has been done."));
}
?>