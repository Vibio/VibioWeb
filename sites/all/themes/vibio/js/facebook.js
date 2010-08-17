$(document).ready(function()
{
	FB.init({
		appId: fb_settings.app_id,
		status: true,
		cookie: true,
		xfbml: true
	});
	
	var fb_next_action;
	var fb_next_action_args;
	var fb_login_callbacks = {
		"signup": function(res)
		{
			if (res.session)
			{
				window.location = "/facebook/signup?destination="+window.location.pathname.substring(1);
			}
		},
		"link": function(res)
		{
			if (res.session)
			{
				window.location = "/facebook/link-account?destination="+window.location.pathname.substring(1);
			}
		},
		"refresh": function(res)
		{
			vibio_dialog.dialog.dialog("close");
			
			if (res.session)
			{
				if (res.session.uid == fb_settings.fb_uid)
				{
					fb_next_action(fb_next_action_args);
				}
				else
				{
					FB.logout();
					incorrect_login();
				}
			}
		}
	};
	
	$(".fb_login").live("click", function()
	{
		var fb_callback = $(this).hasClass("fb_refresh") ? fb_login_callbacks.refresh : fb_login_callbacks.signup;
		FB.login(fb_callback, { perms: fb_settings.perms });
		return false;
	});
	
	$(".fb_link_account").click(function()
	{
		FB.login(fb_login_callbacks.link, { perms: fb_settings.perms });
		return false;
	});
	
	$(".fb_remove_account").click(function()
	{
		var fb_id = $(this).siblings(".account_id").html();
		window.location = "/facebook/remove-account/"+fb_id+"?destination="+window.location.pathname.substring(1);
		return false;
	});
	
	$(".fb_share").click(function()
	{
		var params = JSON.parse($(this).siblings(".fb_share_params").text()) || {};
		share(params);
		
		return false;
	});
	
	var share = function(post_params)
	{
		post_params = post_params || {};

		FB.api('/me/feed', 'post', post_params, function(response)
		{
			if (!response || response.error)
			{
				prompt_login();
				fb_next_action = share;
				fb_next_action_args = post_params;
			}
			else
			{
				share_success();
			}
		});
		
		return false;
	}
	
	var prompt_login = function()
	{
		vibio_dialog.create($("#facebook_login_prompt").html());
		vibio_dialog.set_options({"dialogClass": "fb_popup"});
	}
	
	var incorrect_login = function()
	{
		vibio_dialog.create($("#facebook_wrong_login").html());
		vibio_dialog.set_options({"dialogClass": "fb_popup"});
	}
	
	var share_success = function()
	{
		vibio_dialog.create($("#facebook_share_success").html());
		vibio_dialog.set_options({"dialogClass": "fb_popup"});
	}
});