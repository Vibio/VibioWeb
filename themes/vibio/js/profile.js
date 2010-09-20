$(document).ready(function()
{
	$("#profile_tabs").tabs();
	
	var select_tab = vibio_utility.get_get_arg("selectedtab");
	if (select_tab)
	{
		$("a[href='#profiletab_"+select_tab+"']").click();
	}
});