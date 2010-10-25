var vibio_dialog = {
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
			open: function()
			{
				$(".ui-dialog-titlebar span.ui-icon").html("<img src='/themes/vibio/images/close_button.png' />");
			}
		};
	},
	set_options: function(options)
	{
		options = $.extend({}, options, { position: "center" });
		this.dialog.dialog("option", options);
	}
};

$(document).ready(function()
{
	$(".vibio_dialog_close_link").live("click", function()
	{
		vibio_dialog.dialog.dialog("close");
		
		return false;
	});
});
