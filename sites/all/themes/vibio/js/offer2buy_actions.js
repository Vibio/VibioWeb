$(document).ready(function()
{
	$(".offer2buy_action_complete_form").submit(function()
	{
		return confirm("are you sure you want to mark this action as done?");
	});
});