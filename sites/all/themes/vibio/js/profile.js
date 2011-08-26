var profile_actions;

$(document).ready(function()
{
	$("#profile_tabs").tabs();
	
	if (typeof Drupal.settings.profile_ext != "undefined" && Drupal.settings.profile_ext.selected_tab)
	{
		$("a[href='#profiletab_"+Drupal.settings.profile_ext.selected_tab+"']").click();
	}
	
	profile_actions = {
		show_busy: function(element)
		{
			element.closest(".profile_notification").find(".profile_busy_indicator img").show();
		},
		hide_busy: function(element)
		{
			element.closest(".profile_notification").find(".profile_busy_indicator img").hide();
		},
		remove_notification: function(element)
		{
			element.closest(".profile_notification").remove();
		}
	};
});