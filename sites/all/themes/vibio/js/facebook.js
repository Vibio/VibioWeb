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
		
		return false;
	});
	
	$(".fb_link_account").click(function()
	{
		FB.login(function(res)
		{
			if (res.session)
			{
				window.location = "/facebook/link-account?destination="+window.location.pathname.substring(1);
			}
		}, { perms: fb_app_perms });
		
		return false;
	});
	
	$(".fb_remove_account").click(function()
	{
		var fb_id = $(this).siblings(".account_id").html();
		window.location = "/facebook/remove-account/"+fb_id+"?destination="+window.location.pathname.substring(1);
		
		return false;
	});
	
	var share = function(post_params)
	{
		var default_post_params = {
			message: "Wall Post from vibio.com!",
			picture: "http://beta.vibio.com/mod/snocat/image/biglogo.png"
		};
		
		$.extend(post_params, default_post_params);

		FB.api('/me/feed', 'post', post_params, function(response)
		{
			if (!response || response.error)
			{
				//prompt_login();
			}
			else
			{
				console.log(response);
			}
		});
	}
	
	var prompt_login = function()
	{
		vibio_dialog.create($("#facebook_login_prompt").html());
	}
});