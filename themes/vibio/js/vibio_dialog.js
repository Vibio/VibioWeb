var vibio_dialog = {
	dialog : false,
	dialog_options : {},
	create : function(content) {
		if(!this.dialog) {
			this.init();
		}
		content = "<div class='vibio_dialog_content'><div class='vibio_dialog_close_button vibio_dialog_close_link'><img src='/themes/vibio/images/close_button_dialog.png' /></div>" + content + "</div>";

		this.dialog.html(content).dialog("open");
		this.dialog.html(content).dialog({
			position : ['center', 30]
		});
		//Change Form labels
		$("#edit-collections-wrapper label").html("Add to Collection:");
		$("#edit-collections-new-wrapper label").html("OR create a new collection");
		$("#edit-privacy-wrapper label").html("Item Can Be Viewed By:");
		$("#edit-privacy-wrapper").insertBefore("#edit-body-wrapper");
		//If yes is selected on for sale input
		var flo = $('select#edit-posting-type').val();
$("#product-ajax-add-form input#edit-submit").css("bottom", (flo == 2) ? "85px" : "36px");
	},
	init : function() {
		this.reset_options();
		this.dialog = $("<div id='vibio_dialog'></div>").prependTo("body").dialog(this.dialog_options);
	},
	reset_options : function() {
		this.dialog_options = {
			modal : true,
			resizeable : false,
			draggable : false,
			autoOpen : false,
			width : 638,
			open : function() {
				vibio_dialog.center();
			}
		};
	},
	set_options : function(options) {
		
	},
	center : function() {
		
	}
};

$(document).ready(function() {
	$(".vibio_dialog_close_link").live("click", function() {
		vibio_dialog.dialog.dialog("close");
		return false;
	});
});
//