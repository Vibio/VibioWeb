// jQuery csb (custom selectbox) plugin
// version 1.0
// Author Mihkel Oviir

(function($){
	
	$.fn.csb = function(options) {
		$(this).hide();

		var style = options.style;
		var mode = options.mode;

		var thisid = $(this).attr('id');
		var selected = this.find("option[selected]");

		if (mode == 'link') var selects = this.find("option:not([selected])");
		else var selects = this.find("option");
		$(this).parent().append('<dl id="'+thisid+'" class="'+style+'"></dl>');
		$('dl#'+thisid).append('<dt><a name="' + selected.val() + '">' + selected.text() + '</a></dt>');
		$('dl#'+thisid).append('<dd><ul></ul></dd>');
		selects.each(function(){
			$('dl#'+ thisid + " dd ul").append('<li><a name="' + $(this).val() + '">' + $(this).text() + '</a></li>');
		});

		$('.'+style+' dt').click(function() {
			$('.'+style+' dd ul').toggle();
			return false;
		});

		$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if (! $clicked.parents().hasClass(style)) $('.'+style+' dd ul').hide();
		});

		if (mode == 'link') {
			$('.'+style+' dd ul li a').live('click',function() {
				$('.'+style+' dd ul').hide();
				var functvar = $(this).attr('name');
				
				// now call a callback function
				if(typeof options.callback == 'function'){
					options.callback(functvar);
				}
				return false;
			});
		}
		else {
			$('.'+style+' dd ul li').live('click',function() {
				var text = $(this).find("a").html();
				$('.'+style+' dt a').html(text);
				$('.'+style+' dd ul').hide();

				var source = $('select#'+thisid);
				var functvar = $(this).find("a").attr('name');
				source.val(functvar);
				
				// now call a callback function
				if(typeof options.callback == 'function'){
					options.callback(functvar);
				}
				return false;
			});
		}	
	}
})(jQuery); // plugina lõpp