$(document).ready(function()
{
	if (Drupal.settings.badge.popup_html)
	{
		vibio_dialog.create(Drupal.settings.badge.popup_html);
	}
});