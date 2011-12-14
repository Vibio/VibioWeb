fb_ajax_actions = {};

$(document).ready(function()
{
	if (typeof FB == "undefined")
	{
		return;
	}
	
	FB.init({
		appId: fb_settings.app_id,
		status: true,
		cookie: true,
		xfbml: true
	});
	
	var fb_next_action, fb_next_action_args, fb_do_reload = false;
	var fb_login_callbacks = {
		"signup": function(res)
		{
			if (res.session)
			{
				var destination = vibio_utility.get_get_arg("destination");
				destination = destination ? destination : window.location.pathname.substring(1);
				window.location = "/facebook/signup?destination="+destination
			}
		},
		"link": function(res)
		{
			if (res.session)
			{
				window.location = "/facebook/link-account?destination="+window.location.pathname.substring(1);
			}
		},
		"link_ajax": function(res)
		{
			if (res.session)
			{
				$.ajax({
					url: "/facebook/link-account-ajax",
					dataType: "json",
					success: function(json, stat)
					{
						vibio_utility.set_message(json.message, json.status);
						
						if (json.status != "error")
						{
							fb_settings.fb_uid = json.fb_uid;
							vibio_utility.invoke(fb_ajax_actions.ajax_link);
						}
						else
						{
							FB.logout();
						}
					}
				});
			}
		},
		"refresh": function(res)
		{
			if (vibio_dialog.dialog)
			{
				vibio_dialog.dialog.dialog("close");
			}
			
			if (res.session)
			{
				if (res.session.uid == fb_settings.fb_uid)
				{
					if (fb_do_reload)
					{
						window.location.reload();
					}
					else if (typeof fb_next_action != "undefined")
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
		
		if ($(this).hasClass("fb_reload"))
		{
			fb_do_reload = true;
		}
		
		FB.getLoginStatus(function(res)
		{
			if (res.session)
			{
				fb_callback(res);
			}
			else
			{

/* first fix, creates new errors:
	//http://developers.facebook.com/docs/reference/javascript/FB.login/
				FB.login(fb_callback, { scope: fb_settings.perms });
*/
/* notes from web:
    $url = $facebook->getLoginUrl(array(
        'canvas' => 1,
        'fbconnect' => 0
    ));

    echo "<script type='text/javascript'>top.location.href = '$url';</script>";

				//FB.getLoginUrl();
FB.Connect.requireSession();
*/
			}
		});
		
		return false;
	});
	
	$(".fb_link_account").live("click", function()
	{
		var callback = $(this).hasClass("fb_link_account_ajax") ? "link_ajax" : "link";
		FB.login(fb_login_callbacks[callback], { scope: fb_settings.perms });
		return false;
	});
	
	$(".fb_remove_account").live("click", function()
	{
		var href = $(this).attr("href")+"?destination="+window.location.pathname.substring(1);
		window.location = href;
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
				//share_success();
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
	
	if (typeof fb_share_prompts != "undefined")
	{
		if (!fb_settings.fb_uid)
		{
			return;
		}
		
		$.each (fb_share_prompts, function(i, share_params)
		{
			FB.getLoginStatus(function(res)
			{
				if (res.session && res.session.uid == fb_settings.fb_uid)
				{
					share(share_params);
				}
			});
		});
	}
});
