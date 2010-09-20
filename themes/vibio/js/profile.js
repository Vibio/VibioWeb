$(document).ready(function()
{
	$("#profile_tabs").tabs();
	
	if (typeof Drupal.settings.profile_ext != "undefined" && Drupal.settings.profile_ext.selected_tab)
	{
		$("a[href='#profiletab_"+Drupal.settings.profile_ext.selected_tab+"']").click();
	}
});