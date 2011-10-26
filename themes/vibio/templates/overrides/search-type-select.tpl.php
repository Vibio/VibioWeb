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

	/* what v1.0 did, works with sites/default/themes/vibio/js/mb_menu.js
  $submenu .= "
    <a href='#'>
      <img id='searchtype_{$type}' src='{$data['image']}' class='searchtype_imag
e'
        alt='{$data['title']}' title='{$data['title']}' />
    </a>
  ";
	*/

	/* what v1.1 theming did -- notice the id became class, and the class
		is gone... the javascript depends on the class:
	$submenu .= "
		<a href='#' class='searchtype_{$type}'>{$data['title']}</a>
	";
	*/

	// 1) Stephen smushing to get theming started -- proof before declaring done,
	//  this clearly never worked! (maybe Carolyn)  Get dev right away!

	// 2) ALSO, not cool to get rid of alts (and titles) of images in general

	// 3) Have to use src because that's what the javascript changes.  
	//    The src's for item and user need to match the drop-down button,
	//		which sucks too much.  So I'll change the javascript

	// ok to condense/erase these notes

	// To get the images working, integrate with 
	//  sites/default/themes/vibio/js/mb_menu.js
	// Untested: make the source of the sub-menus correspond to
	//	the main like this:       .attr("src", $(this).attr("src") + "_main")



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
  <span class="button">Item</span>				
</td>
			</tr></table>
		</td>
	</tr></table>
</div>

<div id='search_type_submenu'>
	<?php echo $submenu; ?>
</div>
