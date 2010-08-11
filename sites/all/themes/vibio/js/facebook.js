$(document).ready(function()
{
	FB.init({
		appId: fb_app_id,
		status: true,
		cookie: true,
		xfbml: true
	});
	$(".fb_login").click(function()
	{
		FB.login(function(res)
		{
			if (res.session)
			{
				window.location = "/facebook/signup?destination="+window.location.pathname.substring(1);
			}
		}, { perms: fb_app_perms });
	});
});