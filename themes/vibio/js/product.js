$(document).ready(function()
{
	$(".product_owners_results .pager a").live("click", function()
	{
		var page = vibio_utility.get_a_get_arg($(this), "page");
		var type = $(this).closest(".product_owners_type_container").attr("id").split("product_type_")[1]
		var nid = window.location.pathname.split("/")[2];
		
		$.ajax({
			url: "/product/get-owners",
			type: "post",
			data: {
				product: nid,
				type: type,
				page: page,
				ajax: true
			},
			success: function(html, stat)
			{
				$("#product_type_"+type)
					.find(".product_owners_results")
						.html(html);
			}
		});
		
		return false;
	});
	
	$(".search-results.vibio_item a").each(function()
	{
		var href = $(this).attr("href")+"?searchcrumb="+unescape(window.location.pathname)+window.location.search;
		
		$(this).attr("href", href);
	});
	
    $(".inventory_add").live("click", function()
    {
      var nid = $(this).attr("id").split("inventory_add_")[1];
      vibio_utility.dialog_busy();

      if(nid){
        have(nid);
        return false;
      }else{
        var asin = $(this).attr("asin");
        var url = "/product-from-asin/" + asin + "/js";
        $.ajax({
          url: url,
          type: "post",
          dataType: "json",
          success: function (data){
            have(data.nid);
          }
        });
        return false;
      }
      return false;
    });



    $(".inventory_want").live("click", function()
    {
      var nid = $(this).attr("id").split("inventory_want_")[1];
      vibio_utility.dialog_busy();

      if(nid){
        want(nid);
      }else{
        var asin = $(this).attr("asin");
        var url = "/product-from-asin/" + asin + "/js";
        $.ajax({
          url: url,
          type: "post",
          dataType: "json",
          success: function (data){
            want(data.nid);
          }
        });
        return false;
      }
      return false;
    });
	
	$("#product-ajax-add-form #edit-posting-type").livequery("change", function()
	{
		var val = $(this).val();
		var e = $("#product-ajax-add-form .inventory_add_price");
		
		val == 1 ? e.hide() : e.show();
	});
	
	$("#product-ajax-add-form").livequery("submit", function()
	{
		var node_vals = {}
		$(this).find(":input").each(function()
		{
			var e = $(this);
			var name = e.attr("name");
			var val = e.val();
			node_vals[name] = val;
		});
		
		vibio_dialog.dialog.dialog("close");
		vibio_utility.dialog_busy();
		
		$.ajax({
			url: "/product/ajax/inventory-add/save",
			type: "post",
			data: node_vals,
			success: function(html, stat)
			{
				vibio_dialog.dialog.dialog("close");
				vibio_dialog.create(html);
			}
		});
		
		return false;
	});
	
	$(".product_snippet_link").click(function() {
	  $(".product_snippet").toggle();
	  
	  return false;
	});

    function want(nid){
    $.ajax({
            url: "/product/ajax/inventory-add",
            type: "post",
            data: {
              nid: nid,
              possess: "want"
            },
            success: function(html, stat)
            {
              /* remove close button? vibio_dialog.dialog.dialog("close"); */
              vibio_dialog.create(html);
            }
          });
  }

  function have(nid){
   		$.ajax({
			url: "/product/ajax/inventory-add",
			type: "post",
			data: {nid: nid},
			success: function(html, stat)
			{
				/* remove close button? vibio_dialog.dialog.dialog("close"); */
				vibio_dialog.create(html);
			}
		});
  }

});
