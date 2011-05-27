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
	
	var badge_info = {};
	$(".badge_get_info").live("click", function()
	{
		var href = $(this).attr("href");
		
		if (badge_info[href])
		{
			vibio_dialog.create(badge_info[href]);
		}
		else
		{
			vibio_utility.dialog_busy();
			
			$.ajax({
				url: href,
				success: function(html, stat)
				{
					badge_info[href] = html;
					vibio_utility.dialog_unbusy(badge_info[href]);
				}
			})
		}
		
		return false;
	});
});