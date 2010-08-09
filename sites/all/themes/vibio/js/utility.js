var vibio_utility = {};

$(document).ready(function()
{
	vibio_utility.get_a_get_arg = function(a, target_arg)
	{
		var get_args = a.attr("href").split("?")[1].split("&");
		var value = 0;
		
		$.each(get_args, function(i, e)
		{
			var arg = e.split("=");
			if (arg[0] == target_arg)
			{
				value = arg[1];
			}
		});
		
		return value;
	}
});