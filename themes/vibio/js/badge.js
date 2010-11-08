$(document).ready(function()
{
	Drupal.settings.badge = Drupal.settings.badge || {};
	
	if (Drupal.settings.badge.popup_html)
	{
		vibio_dialog.create(Drupal.settings.badge.popup_html);
	}
	
	var unearned_badges = false;
	$(".badges_unearned").click(function()
	{		
		if (!unearned_badges)
		{
			vibio_utility.dialog_busy();
			
			$.ajax({
				url: $(this).attr("href"),
				success: function(html, stat)
				{
					unearned_badges = html;
					vibio_utility.dialog_unbusy(unearned_badges);
				}
			});
		}
		else
		{
			vibio_dialog.create(unearned_badges);
		}
		
		return false;
	});
});