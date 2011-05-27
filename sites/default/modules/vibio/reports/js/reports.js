$(document).ready(function()
{
	var options = $.extend({}, Drupal.settings.flot.merge_defaults ? options_default() : {}, Drupal.settings.flot.is_pie ? pie_options() : {});
	var placeholder = $("#reports_flot");
	var plot = $.plot(placeholder, Drupal.settings.flot.data, options);
	var tooltip;
	
	reset_plot();
	
	function reset_plot()
	{
		if (Drupal.settings.flot.is_pie)
		{
			display_pie_total();
		}
		else
		{
			$("#reports_clear_selection")
				.show()
				.click(function()
				{
					plot.clearSelection();
					plot = $.plot(placeholder, Drupal.settings.flot.data, options);
				});
		}
		
		if (Drupal.settings.flot.merge_defaults)
		{
			tooltip = $("<div id='reports_tooltip'></div>").appendTo("body");
			placeholder.bind("plotselected", function(event, ranges)
			{
				var new_options = $.extend(true, {}, options, {
					xaxis: {
						min: ranges.xaxis.from,
						max: ranges.xaxis.to
					}
				});
				plot = $.plot(placeholder, Drupal.settings.flot.data, new_options);
			});
			
			placeholder.bind("plothover", function(event, pos, item)
			{
				if (item)
				{
					tooltip
						.css({
							top: item.pageY + 10 + "px",
							left: item.pageX + 10 + "px"
						})
						.html(item.datapoint[1])
						.show();
				}
				else
				{
					tooltip.hide();
				}
			});
		}
	}
	
	function options_default()
	{
		var defaults = {
			series: {
				points: { show: true },
				lines: { show: true }
			},
			yaxis: {
				tickFormatter: function(val, axis)
				{
					if (val > 1000000)
					{
						return (val/1000000).toFixed(axis.tickDecimals)+"M";
					}
					else if (val > 1000)
					{
						return (val/1000).toFixed(axis.tickDecimals)+"K";
					}
					
					return val.toFixed(axis.tickDecimals);
				},
				tickDecimals: 1
			},
			xaxis: {
				mode: "time",
				minTickSize: [1, "day"],
				timeformat: "%y-%m-%d"
			},
			selection: {
				mode: "x"
			},
			grid: {
				hoverable: true
			}
		};
		
		return $.extend({}, Drupal.settings.flot.options || {}, defaults);
	}
	
	function pie_options()
	{
		var options = Drupal.settings.flot.options;
		
		options.legend = {
			labelFormatter: function(label, series)
			{
				return label + " ("+series.data[0][1]+")";
			}
		};
		options.series.pie.label.formatter = function(label, slice)
		{
			var new_label = label.replace(/(\s)\(([0-9]+)\)/, "");
			return '<div style="font-size:x-small;text-align:center;padding:2px;color:'+slice.color+';">'+new_label+'<br/>'+Math.round(slice.percent)+'%</div>';
		};
		
		return options;
	}
	
	function display_pie_total()
	{
		var total = 0;
		$.each (plot.getData(), function(i, e)
		{
			total += e.data[0][1];
		});
		
		$("#reports_flot table").append("<tr><td colspan=2>---------------------</td></tr><tr><td colspan=2>Total: "+total+"</td></tr>");
	}
});