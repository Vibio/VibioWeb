<?php
function badge_admin()
{
	$sql = "SELECT *
			FROM {badge}
			ORDER BY `title`";
	$res = db_query($sql);
	
	$out = "<table>";
	while ($row = db_fetch_object($res))
	{
		$link = l($row->title, "admin/settings/badges/{$row->bid}");
		$image = file_create_url($row->image);
		
		$out .= "
			<tr>
				<td style='width: 100px; text-align: center;'>
					<img style='max-height: 80px;' src='$image' />
				</td>
				<td>$link</td>
				<td>{$row->description}</td>
			</tr>
		";
	}
	$out .= "</table>";
	
	return $out;
}

function badge_edit($form, $badge)
{
	module_load_include("inc", "badge");
	
	return array(
		"#attributes"	=> array(
			"enctype"	=> "multipart/form-data",
		),
		"badge"			=> array(
			"#type"	=> "value",
			"#value"=> $badge,
		),
		"image_upload"	=> array(
			"#type"	=> "file",
			"#title"=> t("Image"),
		),
		"title"			=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Title"),
			"#default_value"=> $badge->title,
			"#required"		=> true,
		),
		"description"	=> array(
			"#type"			=> "textarea",
			"#title"		=> t("Description"),
			"#default_value"=> $badge->description,
			"#required"		=> true,
		),
		"type"			=> array(
			"#type"			=> "select",
			"#title"		=> t("Type"),
			"#options"		=> badge_type_select(),
			"#default_value"=> $badge->type,
		),
		"trigger"		=> array(
			"#type"			=> "textfield",
			"#title"		=> t("Trigger"),
			"#default_value"=> $badge->trigger,
			"#required"		=> true,
		),
		"submit"		=> array(
			"#type"	=> "submit",
			"#value"=> t("Save"),
		),
	);
}

function badge_edit_validate($form, &$state)
{
	$vals = $state['values'];
	
	if ($vals['type'] != BADGE_TYPE_SPECIAL && !is_numeric($vals['trigger']))
	{
		form_set_error("trigger", t("Trigger value must be numeric"));
	}
	elseif (!$vals['badge'] && !$_FILES['files']['name']['image_upload'])
	{
		form_set_error("image_upload", t("You must select an image for this badge"));
	}
	elseif ($vals['badge'] && !$_FILES['files']['name']['image_upload'])
	{
		$state['values']['image'] = $vals['badge']->image;
	}
	else
	{
		$validators = array(
			"file_validate_is_image"	=> array(),
		);
		
		if ($file = file_save_upload("image_upload", $validators))
		{
			$destination = BADGE_IMAGE_DIR."/{$file->filename}";
			
			if (file_copy($file, $destination))
			{
				$state['values']['image'] = $file->filepath;
				
				if ($vals['badge'] && file_exists($vals['badge']->image))
				{
					file_delete($vals['badge']->image);
				}
			}
		}
		else
		{
			form_set_error("image_upload", t("Failed to upload the image; the %directory directory doesn't exist or is not writable.", array('%directory' => BADGE_IMAGE_DIR)));
		}
	}
}

function badge_edit_submit($form, &$state)
{
	$vals = $state['values'];
	
	if ($vals['badge'])
	{
		$sql = "UPDATE {badge}
				SET `image`='%s', `title`='%s', `description`='%s', `type`=%d, `trigger`='%s'
				WHERE `bid`=%d";
		db_query($sql, $vals['image'], $vals['title'], $vals['description'], $vals['type'], $vals['trigger'], $vals['badge']->bid);
	}
	else
	{
		$sql = "INSERT INTO {badge}
				SET `image`='%s', `title`='%s', `description`='%s', `type`=%d, `trigger`='%s'";
		db_query($sql, $vals['image'], $vals['title'], $vals['description'], $vals['type'], $vals['trigger']);
	}
	
	drupal_set_message(t("The badge !title has been saved", array("!title" => $vals['title'])));
	drupal_goto("admin/settings/badges");
}
?>