<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 

<link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap-tagsinput.css");?>" />
<script src="<?php echo base_url("assets/js/bootstrap-tagsinput.js");?>"></script>
<script src="<?php echo base_url("assets/js/jquery.combinations.1.0.min.js");?>"></script>

<div class="inner-pages">      
	<section class="listing-section">
		<div class="container">			
		
		<?php echo form_open_multipart(uri_string(), array('class' => 'create-item-form')); ?>
			<div class="create-product">
			
			        <div class="buttons-form">
					<!--	<a href="">Upload Template</a>			
						<a href="">Download Template</a>	-->
                        <?php //echo form_button(array( "class" => "cancel" , "type" => "submit" , "value" => "Cancel" , "content" => "Cancel" )); ?>						
						<?php echo form_button(array( "id" => "saveItem" , "type" => "submit" , "value" => "Save" , "content" => "Save" )); ?>						
                    </div>	
					<div class="form-inner createItem-form">
						<div class="form-detail-outer">					
							<div class="form-detail">
								<h2>Details Show</h2>
								<fieldset>						
									<?php echo form_label('Name', 'product_title'); ?>
									<div class="prod_title">
										<?php echo form_input(array( "name" => "product_title" , "id" => "product_name" , "placeholder" => "Enter Product Name" )); ?>
										<?php echo form_error('product_title'); ?>
									</div>
								</fieldset>
								<fieldset>
									<?php echo form_label('Category', 'product_category'); ?>
									<div class="prod_category">
									<select name="product_category" id="category_names">
										<option value="">Select Category</option>
										<?php foreach($categories as $category){
											echo '<option value="'.$category->id.'">'.$category->category_name.'</option>';
										}?>
									</select>
									<?php echo form_error('product_category'); ?>
								</div>
									<?php  if($role_id == 1){ ?>
										<a id="create_category" class="create_category" data-id="" href="">Add new category</a>
									<?php  } ?>
								</fieldset>
								<fieldset>
									<?php echo form_label('Description', 'product_desc'); ?>
									<?php echo form_textarea(array("name" => "product_desc" , "id" => "product_desc" , "placeholder" => "Product Description", "rows" => "10" , "column" => "50"));	?>
								</fieldset>
								<fieldset>
									<?php echo form_label('Supplier', 'product_supplier'); ?>
									<div class="prod_supplier">
									<select name="product_supplier" id="supplier_name">
										<option value="">Select Supplier</option>
										<?php foreach($suppliers as $supplier){
											echo '<option value="'.$supplier->id.'">'.$supplier->name.'</option>';
										}?>
									</select>
									<?php echo form_error('product_supplier'); ?>
								</div>
									<?php if($role_id == 1 || $role_id == 3){ ?>
										<a id="add_supplier" class="add_supplier" data-id="" href="">Add new supplier</a>
									<?php } ?>
									
								</fieldset>
								
							</div>
							
							<div class="form-price-inventory">
								<h2>Price and Inventory</h2>
								<fieldset>						
									<?php echo form_label('Price', 'product_price'); ?>
									<div class="price_inventory_input">
									<?php echo form_input(array( "type" =>  "number" ,"name" => "product_price" , "id" => "product_price" , "placeholder" => "Enter Price" )); ?>
									<?php echo form_error('product_price'); ?>
								</div>
								</fieldset>
								<fieldset>
									<?php echo form_label('SKU', 'product_sku'); ?>
									<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "product_sku" , "id" => "product_sku" , "placeholder" => "Enter SKU" )); ?>
									<?php echo form_error('product_sku'); ?>
								</div>
								</fieldset>
								<fieldset>
									<?php echo form_label('Stock Levels', 'product_stock'); ?>
									<div class="price_inventory_input">
										<?php echo form_input(array("type" => "number", "name" => "product_stock" , "id" => "product_stock" , "placeholder" => "Stock Available"));	?>
									</div>
								</fieldset>
								<fieldset>
									<?php echo form_label('Wait Time', 'product_wait_time'); ?>
									<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "product_wait_time" , "id" => "wait_time" , "placeholder" => "No of Days for Stock Available" )); ?>
								</div>
								</fieldset>
								
								<fieldset>
									<?php echo form_label('Weight', 'product_weight'); ?>
									<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "product_weight" , "id" => "product_weight" , "placeholder" => "Grams" )); ?>
								</div>
								</fieldset>
								
					<?php
						$role_id = $this->session->userdata('login')->role_id;
						if($role_id==1){								
					?>
						<fieldset>
								<?php echo form_label('Price form Vendor', 'price_form_vendor'); ?>
								<div class="price_inventory_input">
								<?php echo form_input(array( "name" => "price_form_vendor" , "id" => "price_form_vendor" , "placeholder" => "Price form Vendor" )); ?>
							</div>
						</fieldset>
						<?php }?>	

						<fieldset>
								<?php echo form_label('Shipping Price', 'shipping_price'); ?>
								<div class="price_inventory_input">
								<?php echo form_input(array( "name" => "shipping_price" , "id" => "shipping_price" , "placeholder" => "Shipping price" )); ?>
							</div>
						</fieldset>
						
							</div>						
						</div>					
						<div class="form-image-video">
							<fieldset>	
							<span>Add Image</span>
							<div class="prod_img1">					
								<?php echo form_input(array( "class" => "product_img" , "name" => "img_src[]" , "accept" => "image/*","type" => "file" , "onchange" => "preview_image1(event)" )); ?>
								<?php echo form_error('img_src'); ?>
								 <img id="output_image1"/>
							</div>
							</fieldset>
							<fieldset>
							<span>Add Image</span>
								<div class="prod_img2">
								<?php echo form_input(array( "class" => "product_img" , "name" => "img_src[]" , "accept" => "image/*", "type" => "file" ,"onchange" => "preview_image2(event)")); ?>
								<?php echo form_error('img_src'); ?>
								 <img id="output_image2"/>
							</div>
							</fieldset>
							<fieldset>
							<span>Add Video</span>
								<?php echo form_input(array( "class" => "product_video file_multi_video", "name" => "img_src[]" , "accept" => "video/*", "type" => "file")); ?>
								<video width="250" controls  id="addproduct_video" style="display:none;">
								<source id="video_here">
								Your browser does not support HTML5 video.
								</video>
							</fieldset>
						</div>
					</div>
				 <div class="input_fields_wrap">
				   <div class="varint-options-names">
					  <fieldset class="form-input variants-input">
						 <div class="add_new_variants">
							<a style="float:right;" href="javascript:void(0)"  data-id="" class="add_new_variant"> Add variant </a> 
							<a style="float:right;display:none;" href="javascript:void(0)"  class="canel_new_variant"> Hide options </a>
							<div>
							   <h3> Variants </h3>
							   <br>
							   <p>Add variants if this product comes in multiple versions, like different sizes or colors. </p>
							   <br>
							</div>
						 </div>
						 <div class="variants-data-parts" style="display:none;">
							<div class="variants-input-columns">
							   <span> Option name </span><span> Option values </span>
							</div>
							<div class="varian">
							   <div>
								  <div class="variantoption1">	
									 <input type="text" class="" placeholder="Size" name="options-name[]" value="Size"> 
								  </div>
								  <div class="variantoption">	
									 <input type="text" data-role="tagsinput" class="combinationvar" placeholder="Separate options with a comma" id="variant_option_value1" name="options-values[]">               
								  </div>
							   </div>
							</div>
							<button class="add_more_field_button">Add another option </button>	   
						 </div>
					  </fieldset>
					  <div class="added-varintss">
						 <br> 
						 <p> Modify the variants to be created: </p>
						 <br>
						 <table class="table" border="1">
							<thead>
							   <th> Variant </th>
							   <th> Price </th>
							   <th> SKU </th>
							   <th> Barcode </th>
							   <th> Inventory </th>
							</thead>
							<tbody id="varints-data-columns"></tbody>
						 </table>
					  </div>
				   </div>
				</div> 
					
				<!--	<div class="input_fields_wrap">						
					</div>
					<button class="add_field_button">Add Variants</button> -->
		
			<!-- <a href="" class="addVariants" id="addVariants">Add Variations</a> -->
			</div>
			<?php echo form_button(array( "id" => "saveItem","class" => "secondsaveItem" , "type" => "submit" , "value" => "Save" , "content" => "Save" )); 
			 echo form_close(); ?>
        </div>
    </section>
</div>
<div class="overlay" style="display:none;"></div>
<div id="category_modal" class="category_modal" title="Categories" style="display:none;"></div>
<div id="supplier_modal" class="supplier_modal" title="Suppliers" style="display:none;"></div>
<div id="variants_modal" title="Variants" style="display:none;"></div>
<script>

function preview_image1(event)  
{
 var reader = new FileReader();
 reader.onload = function()
 {
   var output = document.getElementById('output_image1');
   $("#output_image1").css({"border":"1px solid #e1e8f0","padding":"10px","height":"100px ","width":"auto","margin-top":"48px","display":"table"});
 
  output.src = reader.result;
 }
 reader.readAsDataURL(event.target.files[0]);
}

function preview_image2(event) 
{
 var reader = new FileReader();
 reader.onload = function()
 {
   var output = document.getElementById('output_image2');
   $("#output_image2").css({"border":"1px solid #e1e8f0","padding":"10px","height":"100px ","width":"auto","margin-top":"48px","display":"table"});
   output.src = reader.result;
 }
 reader.readAsDataURL(event.target.files[0]);
}

/* 	function preview_image3(event) 
	{
		
	 var reader = new FileReader();
	 reader.onload = function()
	 {
	   var output = document.getElementById('output_image3');
	   $("#output_image3").css({"border":"1px solid #e1e8f0","padding":"10px","height":"100px ","width":"auto","margin-top":"48px","display":"table"});
	   output.src = reader.result;
	 }
	 reader.readAsDataURL(event.target.files[0]);
	 
	 
	}
 */

 
 $(document).on("change", ".file_multi_video", function(evt) {
	  $("#addproduct_video").css("display","block");
	  var $source = $('#video_here');
	  $source[0].src = URL.createObjectURL(this.files[0]);
	  $source.parent()[0].load();
});

$('#addVariants').click(function(event){
	event.preventDefault();
	$.post('<?php echo base_url(); ?>products/AllProducts/addVariants', function(response){
		$('#variants_modal').html(response);
	});	
	$("#variants_modal").dialog();
	var modal = $('#variants_modal').dialog('isOpen');
	if(modal == true){
		$('.overlay').show();
	} 
	$('#variants_modal').on('dialogclose', function(event) {
		$('.overlay').hide();
	});
	
});
$(document).ready(function() {
   
	
    var max_fields      = 10;
    var wrapper         = $(".input_fields_wrap");
    var add_button      = $(".add_field_button");   
    var x = 1;
    $(add_button).click(function(e){
        e.preventDefault();
        if(x < max_fields){
            x++;
            $(wrapper).append('<fieldset class="form-input variants-input"><input type="file" class="variant_img" value="" name="img_src[]"><?php echo form_error('img_src'); ?><input type="text" placeholder="Variant Name" id="variant_name" value="" name="variant_name[]"><?php echo form_error('variant_name'); ?><input type="number" placeholder="Variant Price" id="variant_price" value="" name="variant_price[]"><?php echo form_error('variant_price'); ?><input type="text" placeholder="Variant SKU" id="variant_sku" value="" name="variant_sku[]"><?php echo form_error('variant_sku'); ?><select name="variant_supplier[]" id="variant_supplier"><?php echo form_error('variant_supplier'); ?><option value="">Select Supplier</option><?php foreach($suppliers as $supplier){?><option value="<?php echo $supplier->id; ?>"><?php echo $supplier->name; ?></option><?php } ?></select><a href="#" class="remove_field">Remove</a></fieldset>');
        }
    });   
    $(wrapper).on("click",".remove_field", function(e){
        e.preventDefault(); $(this).parent('fieldset').remove(); x--;
    })
});
</script>

<script>
	 $('.add_new_variant').click(function (e) {
	   $(this).hide();	 
	   $('.variants-data-parts').show();
	   $('.canel_new_variant').show();   
	 });
  
	  $('.canel_new_variant').click(function (e) {
	   $(this).hide();	 
	   $('.add_new_variant').show();
	   $('.variants-data-parts').hide();   
	 });
   
   // Multiple images preview in browser
   $(function() { 
    
	function create_variants_table(){
		
		var obj1 = $("#variant_option_value1").tagsinput('items');			
		var obj2 = $("#variant_option_value2").tagsinput('items');			
		var obj3 = $("#variant_option_value3").tagsinput('items');	
		var arr1 = $.makeArray( obj1 );
		var arr2 = $.makeArray( obj2 );
		var arr3 = $.makeArray( obj3 );
		
		if(arr1.length > 0  && arr2.length  > 0 && arr3.length > 0 ){
			
			var arr = $.combinations([arr1, arr2, arr3]);

		}
		else if(arr1.length > 0  && arr2.length  > 0 && arr3.length == 0 ){
			
			var arr = $.combinations([arr1, arr2]);
			
		} else if(arr1.length == 0  && arr2.length  > 0 && arr3.length > 0 ){
			
			var arr = $.combinations([arr2, arr3]);
		
		} else if(arr1.length > 0  && arr2.length  == 0 && arr3.length > 0){
			
			var arr = $.combinations([arr1, arr3]);
			
		} else if(arr1.length > 0  && arr2.length  == 0 && arr3.length == 0){
			
			var arr = $.combinations([arr1]);
			
		} else if(arr1.length == 0  && arr2.length  > 0 && arr3.length == 0){
			
			var arr = $.combinations([arr2]);
			
		}else if(arr1.length == 0  && arr2.length  == 0 && arr3.length > 0){
			
			var arr = $.combinations([arr3]);
			
		}
		$("#varints-data-columns").html('');
		if (arr === undefined || arr.length == 0) {
			// array empty or does not exist
			} else {
			
				jQuery(arr).each(function(i, obj) {
					var variant_name = '';
					jQuery(obj).each(function(j, sss) {
						var counter = j+1;
						variant_name = variant_name+"<input type='hidden' name = 'var["+i+"][opt"+counter+"]' value='"+sss+"'> <span class='option-name-span"+counter+"'>"+sss+"</span>"; 
						
					});
					  $("#varints-data-columns").append('<tr><td>'+variant_name+'</td><td><input type="text" name = "var['+i+'][price]" value="0.00"></td><td><input type="text" value="" name = "var['+i+'][sku]"></td><td><input type="text" value="" name = "var['+i+'][barcode]"></td><td><input type="text" value="0" name = "var['+i+'][inventory]"></td></tr>');

				});
			
			}
		
		
		
	}
	
		$('body').on('change', '.combinationvar', function (e) {		
		 create_variants_table();	
		});
		
		var maxfields      = 3; //maximum input boxes allowed
		var wrappers   		= $(".varian"); //Fields wrapper
		var add_more_button      = $(".add_more_field_button"); //Add button ID		
		var x = 1; //initlal text box count
		$(add_more_button).click(function(e){ //on add input button click
			e.preventDefault();
			if(x < maxfields){ //max input box allowed
				x++; //text box increment
				if( x == 2){
					var opt_val = "Color";
				} else {
					var opt_val = "Material";
				}
				$(wrappers).append('<div><div class="variantoption1"><input type="text" placeholder="Name" id="variant_option'+x+'" name="options-name[]" value="'+opt_val+'"></div><div class="variantoption test'+x+'"><input class="combinationvar" type="text" data-role="tagsinput" placeholder="Separate options with a comma" id="variant_option_value'+x+'" name="options-values[]"><a href="#" class="remove_field"></a></div></div>'); //add input box
			    $('.test'+x+' input').tagsinput('refresh'); 
			}
		});
		
		$(wrappers).on("click",".remove_field", function(e){ //user click on remove text
			e.preventDefault(); $(this).parent().parent().remove(); x--;
			create_variants_table();
			//$('.variantoption input').tagsinput('refresh');
		})
	});

</script>
