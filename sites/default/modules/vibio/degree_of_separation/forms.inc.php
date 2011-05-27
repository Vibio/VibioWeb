<?php
function dos_admin()
{
	$relationships = user_relationships_types_load();
	$relationship_options = array(0 => t("Select"));
	
	foreach ($relationships as $rtid => $rel)
	{
		$relationship_options[$rtid] = $rel->name;
	}
	
	return system_settings_form(array(
		"dos_enabled"	=> array(
			"#type"			=> "checkbox",
			"#title"		=> t("Enabled"),
			"#description"	=> t("Whether or not this feature is enabled"),
			"#default_value"=> variable_get("dos_enabled", true),
		),
		"dos_max_depth"	=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Max Depth"),
			"#description"	=> t("The maximum depth traversed when determining the degree of separation between users. Note that the higher this value is, the more expensive it is to calculate, in terms of both time and space."),
			"#default_value"=> variable_get("dos_max_depth", DOS_DEFAULT_DEPTH),
			"#size"			=> 3,
			"#maxlength"	=> 2,
			"#required"		=> true,
		),
		"dos_relation_type" => array(
			"#type"			=> "select",
			"#title"		=> t("Relation Type"),
			"#description"	=> t("The default relation type to use for degree of separation determination"),
			"#options"		=> $relationship_options,
			"#default_value"=> variable_get("dos_relation_type", 0),
		),
	));
}

function dos_admin_validate($form, &$state)
{
	$vals = &$state['values'];

	if (!is_numeric($vals['dos_max_depth']))
	{
		form_set_error("dos_max_depth", t("Max Depth must be numeric"));
	}
	if (!$vals['dos_relation_type'])
	{
		form_set_error("dos_relation_type", t("Must select a relationship type"));
	}
}
?>