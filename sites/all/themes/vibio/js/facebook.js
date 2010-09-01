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
					if (typeof fb_next_action != "undefined")
					{
						fb_next_action(fb_next_action_args);
					}
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
	
	$(".fb_share").live("click", function()
	{
		var params = JSON.parse($(this).siblings(".fb_share_params").text()) || {};
		verify_login(share, params);
		
		return false;
	});
	
	var verify_login = function(action, params)
	{
		if (!fb_settings.fb_uid)
		{
			prompt_account_link();
			return;
		}
		
		FB.getLoginStatus(function(res)
		{
			if (res.session)
			{
				if (res.session.uid == fb_settings.fb_uid)
				{
					action(params);
				}
				else
				{
					incorrect_login();
				}
			}
			else
			{
				prompt_login();
			}
		});
	}
	
	var share = function(post_params)
	{	
		var defaults = {
			method: "stream.publish",
			display: "popup"
		};
		post_params = $.extend({}, defaults, post_params);
		
		FB.ui(post_params, function(response)
		{
			if (response && response.post_id)
			{
				share_success();
			}
			else
			{
				//what should we do here?
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
	
	var prompt_account_link = function()
	{
		vibio_dialog.create($("#facebook_prompt_account_link").html());
		vibio_dialog.set_options({"dialogClass": "fb_popup"});
	}
});