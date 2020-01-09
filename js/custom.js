$(document).ready(function(e){	

	/****** Jquery Checkbox check all  ****/
	
	$("#checkAll").click(function () {
	   $('input:checkbox').not(this).prop('checked', this.checked);
	});
 
 
 
 $(".listing-content-product").hover(
  function () {
    $(this).addClass("result_hover");
  },
  function () {
    $(this).removeClass("result_hover");
  }
);

$(".product-left-first-image:first").addClass("result_hover_detail");

 $(".product-left-first-image").hover(
		
		
  function () {
    $(this).addClass("result_hover_detail");
  },
  function () {
    $(this).removeClass("result_hover_detail");
  }
);



/*	$(".delete_product").click(function (e) { 
		var supplier_id = $(this).attr('id').match(/\d+/);
				$.ajax({
						type: "POST",
						url: '/app/importapp/AllSupplier/delete',
						data: {supplier_id: supplier_id},
						success: function (res) {	
						$("#supplier_"+supplier_id).text("");
						$("#supplier_"+supplier_id).text("Record has been deleted successfully.");
						setTimeout(function() { $("#supplier_"+supplier_id).hide(); }, 3000);
						},
					error: function(){
						console.log('error in update page setting.');
					}
					})
					
    });*/
	
/*	$(".edit_product").click(function (e) { 
		var product_id = $(this).attr('data-id').match(/\d+/);
				$.ajax({
						type: "POST",
						url: '/app/importapp/AllSupplier/edit',
						data: {product_id: product_id},
						success: function (res) {
						},
					error: function(){
						console.log('error in update page setting.');
					}
					})
					
    });*/
	
    $("#users_products_list").click(function(e){ 
		var selected_category= $("#category_name option:selected").val(); 
		var keywords= $("#keywords").val();
		if((selected_category=="") && (keywords=="")){
	    	var url ="/app/importapp/user/products";	
		}else if(keywords=="" && selected_category !=""){ 
			var url ="/app/importapp/user/products/" +  selected_category;
		}
		else{
			var url ="/app/importapp/user/products/" +  selected_category + "?keywords=" + keywords;	
		}

	     window.location = url;				
    });
	
	$("#sidebar_categories li a").click(function(e){
		var cat_id = $(this).attr('id'); 
		if(cat_id==null){
			var cat_id =0;
		}		
		if(cat_id==0){
	    	var url ="/app/importapp/user/products/";	
		}else{
			var url ="/app/importapp/user/products/" + cat_id;
		}
		window.location = url;
	});
	
	$("select#products_sorting").change(function(e){ 
		var sorting_val= $("#products_sorting option:selected").val(); 
		var sorting_id = $(this).children(":selected").attr("id");
		var url ="?" +  sorting_id+"&value="+sorting_val;  
		window.location = url;				
    });
	
	$("select#users_sorting").change(function(e){ 
		var sorting_val= $("#users_sorting option:selected").val(); 
		var sorting_id = $(this).children(":selected").attr("id");
		var url ="?" +  sorting_id+"&value="+sorting_val;  
		window.location = url;				
    });
});
