<?php
$default = false;
$submenu = "";

foreach ($search_types as $type => $data)
{
	if (!$default)
		$default = array(
			"type"	=> $type,
			"data"	=> $data,
		);

	$submenu .= "
		<a href='#'>
			<img id='searchtype_{$type}' src='{$data['image']}' class='searchtype_image'
				alt='{$data['title']}' title='{$data['title']}' />
		</a>
	";
}
?>

<div id="search_type_menu_container">
	<table><tr>
		<td id="search_type_menu">
			<table class="rootVoices"><tr>
				<td class="rootVoice {menu: 'search_type_submenu'}">
					<img id='search_type_current' src='<?php echo $default['data']['image']; ?>'
						alt='<?php echo $default['data']['title']; ?>'
						title='<?php echo $default['data']['title']; ?>' />
				</td>
			</tr></table>
		</td>
	</tr></table>
</div>

<div id='search_type_submenu'>
	<?php echo $submenu; ?>
</div>
