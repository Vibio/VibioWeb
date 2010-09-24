var vibio_dialog;

$(document).ready(function()
{
	vibio_dialog = {
		dialog: false,
		dialog_options: {},
		create: function(content)
		{	
			if (!this.dialog)
			{
				this.init();
			}
			
			this.dialog
				.html(content)
				.dialog("open");
			
		},
		init: function()
		{
			this.reset_options();
			this.dialog = $("<div id='vibio_dialog'></div>")
				.prependTo("body")
				.dialog(this.dialog_options);
		},
		reset_options: function()
		{
			this.dialog_options = {
				modal: true,
				resizeable: false,
				draggable: false,
				autoOpen: false,
				width: 550,
			};
		},
		set_options: function(options)
		{
			this.dialog.dialog("option", options);
		}
	};
});
