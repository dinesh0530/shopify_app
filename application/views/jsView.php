<script src="<?php //echo base_url("assets/plugins/js/jquery-3.2.1.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/js/bootstrap.min.js"); ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?php echo base_url("js/custom.js"); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	<!-- customer support -->
		$('#customer-support-btn').click(function(event){
		event.preventDefault();        
		$("#customer_supplier_modal").dialog({title: 'Edit supplier'});		
		});
	<!-- End Here -->
	
	var site_url = window.location.protocol +'//'+ window.location.hostname + window.location.pathname;
	$('.create_category').click(function(event){
		event.preventDefault();
		var catId = $(this).attr('data-id');
		if(catId !== ""){
			$("#category_modal").dialog({title: 'Edit category'});
			$.post('<?php echo base_url(); ?>admin/Categories/editcategory?catId='+catId, function(response){
				$('#category_modal').html(response);
			});	
		}else{
			$("#category_modal").dialog({title: 'Add new category'});
			$.post('<?php echo base_url(); ?>admin/Categories/createcategory', function(response){
				$('#category_modal').html(response);
			});	
		}
		var modal = $('#category_modal').dialog('isOpen');
		if(modal == true){
				$('.overlay').show();
		} 
		$('#category_modal').on('dialogclose', function(event) {
    	$('.overlay').hide();
 		});
		
	});	 

	$('.delete-category').click(function(event){
		event.preventDefault();
        var catId = $(this).attr('data-id');
        $("#delete_category").dialog({
		  modal: true,
		  buttons: {
			"Yes": function() {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>admin/Categories/deleteCategory",
					data: {
							catId: catId,
						  },
					success: function(result){						
						setTimeout( function(){
							window.location.replace('<?php echo base_url(); ?>admin/all-categories');
						}, 800);		
					} 
				});
			},
			Cancel: function() {
			  $(this).dialog( "close" );
			}
		  }
		});	
		
   
		var modal = $('#delete_category').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('delete_popup');
			$('.overlay').show();
		} 
		$('#delete_category').on('dialogclose', function(event) {
			$('.ui-dialog').removeClass('delete_popup');
    	$('.overlay').hide();
 		});	
	});
	
	$('.add_supplier').click(function(event){
		event.preventDefault();
		var supplierId = $(this).attr('data-id');
		if(supplierId !== ""){
			$("#supplier_modal").dialog({title: 'Edit supplier'});
			$.post('<?php echo base_url(); ?>Suppliers/edit_supplier?supplierId='+supplierId, function(response){
				$('#supplier_modal').html(response);
				$('#sup_state').html('<option value="">Select State</option>');
			});
		} else {
			$("#supplier_modal").dialog({title: 'Add new supplier'});
			$.post('<?php echo base_url(); ?>Suppliers/addSupplier', function(response){          
				$('#supplier_modal').html(response);			
			});
		}
		$("#supplier_modal").dialog();
		var modal = $('#supplier_modal').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('supplier_popup');
			$('.overlay').show();
		} 
		$('#supplier_modal').on('dialogclose', function(event) {
    	$('.overlay').hide();
    	$('.ui-dialog').removeClass('supplier_popup');
 		});
	});
	
	$('.delete_supplier').click(function(event){
		event.preventDefault();
        var supplierId = $(this).attr('data-id');
		var set=true;
        $("#dlt_supplier").dialog({
		  modal: true,
		  buttons: {
			"Yes": function() {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>Suppliers/deleteSupplier",
					data: {
							supplierId: supplierId,
						  },
					success: function(result){
							$("#dlt_supplier").dialog("close");
							
						 	 	setTimeout( function(){
							 window.location.replace('<?php echo base_url(); ?>all-suppliers');
						}, 500); 				
						 				
					} 
				});
			/*	setTimeout( function(){
					window.location.replace(site_url);
				}, 1000);*/
				
			},
			Cancel: function() {
			  $(this).dialog( "close" );
			}			
		  }
		});		
	
				
        //popup_overlay();
		var modal = $('#dlt_supplier').dialog('isOpen');
		if(modal == true){			
			$('.ui-dialog').addClass('delete_popup');
				$('.overlay').show();
		} 
		$('#dlt_supplier').on('dialogclose', function(event) {			
			$('.ui-dialog').removeClass('delete_popup');
			
    	$('.overlay').hide();
 		});	
	});
$('.delete_product').click(function(event){
		event.preventDefault();
	  var productId = $(this).attr('data-id');
	  var roleId = $(this).attr('role-id');
	  var userId = $(this).attr('user-id');
	  var logId = $(this).attr('logged-userId');
	  if((userId == logId) || (roleId == '1') ){
      $("#dlt_product").dialog({
		  modal: true,
		  buttons: {
			"Yes": function() {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>products/AllProducts/deleteProducts",
					data: {
							productId: productId,
						  },
					dataType: "text", 
					success: function(result){ 
						$("#dlt_product").dialog("close");
						$("#dlt_product_confirm").dialog("open");
						if(result=='true'){ 
						$("#sudddddces").css('margin-top',"-35px");	
						$("#sudddddces").text('Product has been deleted successfully');	
						}
							
						setTimeout( function(){
							window.location.replace('<?php echo base_url(); ?>all-products');
						}, 2000);   		
					} 
				});
				/*setTimeout( function(){
					window.location.replace(site_url);
				}, 1000);*/
			},
			Cancel: function() {
			  $(this).dialog( "close" );
			}
	  	}
		});
		
		$("#dlt_product_confirm").dialog({
        autoOpen: false,
        modal: true,
        title: "Details",
        buttons: {
            ok: function () {
                $(this).dialog('close');
            }
        }
    });
		
		
		} else {
			$("#no_dlt_product").dialog();
			auth_modal = $('#no_dlt_product').dialog('isOpen');
			if(auth_modal == true){
				$('.ui-dialog').addClass('auth_popup');
				$('.overlay').show();
			} 
			$('#no_dlt_product').on('dialogclose', function(event) {
				$('.ui-dialog').removeClass('auth_popup');
	    	$('.overlay').hide();
	 		});
		} 
		var modal = $('#dlt_product').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('delete_popup');
				$('.overlay').show();
		} 
		$('#dlt_product').on('dialogclose', function(event) {
			$('.ui-dialog').removeClass('delete_popup');
    	$('.overlay').hide();
 		});	
	});

  $('.non_edit_product').click(function(e){
		event.preventDefault();
		var productId = $(this).attr('data-id');
	  var roleId = $(this).attr('role-id');
	  var userId = $(this).attr('user-id');
	  var logId = $(this).attr('logged-userId');
		if((userId != logId) && (roleId != '1') ){
			$("#no_edit_product").dialog();
			auth_modal = $('#no_edit_product').dialog('isOpen');
			if(auth_modal == true){
				$('.ui-dialog').addClass('auth_popup');
				$('.overlay').show();
			} 
			$('#no_edit_product').on('dialogclose', function(event) {
				$('.ui-dialog').removeClass('auth_popup');
	    	$('.overlay').hide();
	 		});
		}
	});
	
	
	
	
	$('.delete_users').click(function(event){
		event.preventDefault();
        var userid = $(this).attr('data-id');		
        $("#dlt_user").dialog({			
		  modal: true,
		  buttons: {
			"Yes": function() {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>admin/delete-user/"+userid,
					data: {
							userid: userid,
						  },
					success: function(result){	
						//	alert();
						 setTimeout( function(){
							window.location.replace('<?php echo base_url(); ?>all-users');
						}, 500); 				
					} 
				});
			/*	setTimeout( function(){
					window.location.replace(site_url);
				}, 1000);*/
			},
			Cancel: function() {
			  $(this).dialog( "close" );
			}
		  }
		});	
        //popup_overlay();
		var modal = $('#dlt_user').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('delete_popup');
				$('.overlay').show();
		} 
		$('#dlt_supplier').on('dialogclose', function(event) {
			$('.ui-dialog').removeClass('delete_popup');
    	$('.overlay').hide();
 		});	
	});
	
	
	$('.variant-delete-btns').click(function(event){
		event.preventDefault();
        var variantId = $(this).attr('data-id');		
        var productId = $('#product-ids').val();		
      // alert(variantId);
        $("#variants_delete_modal").dialog({			
		  modal: true,
		  buttons: {
			"Yes": function() {
				
				
				  $.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>products/AllProducts/delete_variant/"+variantId,
					data: {
							variantId: variantId,
							productId: productId,
						  },
					success: function(result){	
					     window.location.replace('<?php echo base_url(); ?>product/edit-product/'+productId);   	
					} 
				});  
			
			},
			Cancel: function() {
			  $(this).dialog( "close" );
			}
		  }
		});	
        //popup_overlay();
		var modal = $('#variants_delete_modal').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('delete_popup');
				//$('.overlay').show();
		} 
		 $('#variant_modal_delete').on('dialogclose', function(event) {
			$('.ui-dialog').removeClass('delete_popup');
    	//$('.overlay').hide();
 		});
		
	});
	
	$('.delete-all-product-variants').click(function(event){
		event.preventDefault();
        //var variantId = $(this).attr('data-id');		
        var productId = $('#product-ids').val();		
        $("#all-variants_delete_modal").dialog({			
		  modal: true,
		  buttons: {
			"Yes": function() {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>products/AllProducts/deleteAllVariants/"+productId,
					data: {
							productId: productId,
						  },
					success: function(result){	
						window.location.replace('<?php echo base_url(); ?>product/edit-product/'+productId);						
						window.location.replace('<?php echo base_url(); ?>product/edit-product/'+productId);						
					} 
				}); 
			
			},
			Cancel: function() {
			  $(this).dialog( "close" );
			}
		  }
		});	
        //popup_overlay();
		var modal = $('#all-variants_delete_modal').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('delete_popup');
				//$('.overlay').show();
		} 
		 $('#all_variant_modal_delete').on('dialogclose', function(event) {
			$('.ui-dialog').removeClass('delete_popup');
    	//$('.overlay').hide();
 		});
		
	});
	
	/********** Delete sourcing product **********/
	

		$("body").on("click", ".sourcing-product", function(event){
		event.preventDefault();
        var catId = $(this).attr('data-id');
        $("#delete_category").dialog({
		  modal: true,
		  buttons: {
			"Confirm": function() {
				$("#loadingimg").show();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>Sourcinglist/delete_products",
					data: {
							catId: catId,
						  },
					success: function(result){	                        			
						 setTimeout( function(){
							window.location.replace('<?php echo base_url(); ?>products-sourcing-list');
						}, 1000); 	
					} 
				});
			},
			Cancel: function() {
			  $(this).dialog( "close" );
			}
		  }
		});	
		
   
		var modal = $('#delete_category').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('delete_popup');
			$('.overlay').show();
		} 
		$('#delete_category').on('dialogclose', function(event) {
			$('.ui-dialog').removeClass('delete_popup');
    	$('.overlay').hide();
 		});	
	});
	//////////////////
	
}); 
</script>