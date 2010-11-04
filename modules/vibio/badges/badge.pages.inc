<?php
function badge_admin()
{
	module_load_include("inc", "badge");
	
	$sql = "SELECT *
			FROM {badge}
			ORDER BY `title`";
	$res = db_query($sql);
	$options = badge_type_select();
	
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
				<td>{$options[$row->type]}</td>
				<td>{$row->trigger}</td>
			</tr>
		";
	}
	$out .= "</table>";
	
	return $out;
}

function badge_edit($form, $badge)
{
	module_load_include("inc", "badge");
	$trigger_description = "
		This changes depending on the value of 'type':
		<ul>
			<li>SPECIAL: The text trigger that the event fires. If you don't know what this is, check <a href='/admin/settings/badges/triggers'>this</a> page.</li>
			<li>
				Items (bought by category and time): underscore separated amount_categoryid_timeframe (ex: 10_5_30 = 10 items of category id 5 in 30 days).
				Categories can be found on <a href='/admin/content/taxonomy/2'>this</a> page by clicking on the appropriate category and taking the number from the end of the URL
				(/taxonomy/term/6 means 6 is the category id).
			</li>
			<li>Member for X time: Number of days the user needs to be a member of the site for before receiving the badge</li>
			<li>Anything else: The amount (items sold, items added to inventory, collections created, etc.) needed to get the badge</li>
		</ul>
	";
	
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
			"#description"	=> $trigger_description,
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
	
	if (!$vals['badge'] && !$_FILES['files']['name']['image_upload'])
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

function badge_special_triggers_page()
{
	module_load_include("inc", "badge");
	
	$triggers = array(
		"user_create"		=> t("Sign Up for Vibio"),
		"fb_link"			=> t("Link Facebook Account"),
		"ebay_link"			=> t("Link eBay Account"),
		"sell_to_friend"	=> t("Sell to a Friend"),
		"buy_from_friend"	=> t("Buy from a Friend"),
		"fast_sell"			=> t("Sell an item within 6 hours of listing it"),
	);
	
	$out = "These are the triggers for type SPECIAL that the Vibio code knows about.";
	$out .= "<table><tr><th>Trigger</th><th>Description</th><th>Badge Link</th></tr>";
	
	foreach ($triggers as $t => $title)
	{
		$badge = badge_load_special($t);
		$badge_link = $badge ? l($badge->title, "admin/settings/badges/{$badge->bid}") : "";
		
		$out .= "
			<tr>
				<td>$t</td>
				<td>$title</td>
				<td>$badge_link</td>
			</tr>
		";
	}
	$out .= "</table>";
	
	return $out;
}
?>