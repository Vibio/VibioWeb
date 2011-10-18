<?php
/*seems to print the upper search bar, not the search page */
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
		<a href='#' class='searchtype_{$type}'>{$data['title']}</a>
	";
}
?>

<div id="search_type_menu_container">
	<table><tr>
		<td id="search_type_menu">
			<table class="rootVoices"><tr>
				<td class="rootVoice {menu: 'search_type_submenu'}">
  <span class="button">Item</span>				
</td>
			</tr></table>
		</td>
	</tr></table>
</div>

<div id='search_type_submenu'>
	<?php echo $submenu; ?>
</div>
