<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<style>
.gallery img{ width: 150px;  padding-left: 14px;  height: 111px }
.size-limit{font-size: 11px;color: #333;}
</style>
<link rel="stylesheet" href="<?php   echo base_url("assets/css/bootstrap-tagsinput.css");?>" />
<script src="<?php echo base_url("assets/js/bootstrap-tagsinput.js");?>"></script>
<script src="<?php echo base_url("assets/js/jquery.combinations.1.0.min.js");?>"></script>
<div class="inner-pages">
   <section class="listing-section">
   <?php if($this->session->flashdata('delete_variant')){ ?>
				<div class="successmsg" id="suces">
				      <?php echo $this->session->flashdata('delete_variant');  ?>
             	</div>	
		<?php }?>
      <div class="container">
         <div class="create-product">
            <?php echo form_open_multipart(uri_string(), array('class' => 'create-item-form')); ?>	
            
			<?php
			
               if(isset($saved))
               {				
               	echo '<div class="successmsg sucess">'.$saved.'</div>';
				?>
				<script>
				$(".sucess").delay(5000).fadeOut();
				</script>
				<?php 
               };
               	?>
			
            <div class="buttons-form">
               <?php echo form_button(array( "id" => "saveItem" , "type" => "submit" , "value" => "Save" , "content" => "Save" )); ?>	 
            </div>
            <div class="form-inner createItem-form">
               <div class="form-detail-outer">
                  <div class="form-detail">
                     <h2>Details</h2>
                     <fieldset>
                        <?php echo form_label('Name', 'product_title'); ?>									
                        <div class="prod_title">			
                           <?php echo form_input(array( "name" => "product_title" , "id" => "product_name" , "placeholder" => "Enter product name","value" => $product_data[0]['product_title']	 )); ?>
                           <?php echo form_error('product_title'); ?>
                        </div>
                     </fieldset>
                     <fieldset>
                        <?php echo form_label('Category', 'product_category'); ?>									
                        <div class="prod_category">
                           <select name="product_category" id="category_names">
                              <option value="">Select category</option>
                              <?php foreach($categories as $category){	?>
                              <option value="<?php echo $category->id;?>" <?php if ($product_data[0]['product_category']==$category->id) { ?>selected="selected"<?php } ?> ><?php echo $category->category_name;?> </option>
                              <?php  }?>									
                           </select>
                           <?php echo form_error('product_category'); ?>								
                        </div>
                        <?php if($role_id == 1){ ?>										<a id="create_category" class="create_category" data-id="" href="">Add new category</a>		
                        <?php } ?>								
                     </fieldset>
                     <fieldset>				
                        <?php echo form_label('Description', 'product_desc'); ?>				
                        <?php echo form_textarea(array("name" => "product_desc" , "id" => "product_desc" , "placeholder" => "Product description", "rows" => "10" , "column" => "50" , "value" => $product_data[0]['product_desc'] ));	?>					
                     </fieldset>
                     <fieldset>
                        <?php echo form_label('Supplier', 'product_supplier'); ?>									
                        <div class="prod_supplier">
                           <select name="product_supplier" id="supplier_name">
                              <option value="">Select supplier</option>
                              <?php foreach($suppliers as $supplier){	?>							  
                              <option value="<?php echo $supplier->id;?>" <?php if ($product_data[0]['product_supplier']==$supplier->id) { ?>selected="selected"<?php } ?> >
                                 <?php echo $supplier->name;?> 
                              </option>
                              <?php	}?>									
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
                        <?php echo form_label('Compare Price', 'product_price'); ?>
                        <div class="price_inventory_input">	
							<?php echo form_input(array( "name" => "product_price" , "id" => "product_price" , "placeholder" => "Enter price", "value" => $product_data[0]['product_price'] ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')"  )); ?>	
							<?php echo form_error('product_price'); ?>
						</div>
                     </fieldset>
                     <fieldset>
                        <?php echo form_label('SKU', 'product_sku'); ?>									
                        <div class="price_inventory_input">									<?php echo form_input(array( "name" => "product_sku" , "id" => "product_sku" , "placeholder" => "Enter SKU" ,"value" => $product_data[0]['product_sku'])); ?>									<?php echo form_error('product_sku'); ?>								</div>
                     </fieldset>
                     <fieldset>
                        <?php echo form_label('Stock levels', 'product_stock'); ?>									
                        <div class="price_inventory_input">										<?php echo form_input(array("name" => "product_stock" , "id" => "product_stock" , "placeholder" => "Stock available" , "value" => $product_data[0]['product_stock'],"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')"   ));	?>									</div>
                     </fieldset>
                     <fieldset>
                        <?php echo form_label('Wait time', 'product_wait_time'); ?>									
                        <div class="price_inventory_input">									<?php echo form_input(array( "name" => "product_wait_time" , "id" => "wait_time" , "placeholder" => "No of days for stock available" , "value" => $product_data[0]['product_wait_time'])); ?>
						</div>
                     </fieldset>
					 
					  <fieldset>
                        <?php echo form_label('Weight', 'product_weight'); ?> 
		<div class="price_inventory_input">		
		<?php echo form_input(array( "name" => "product_weight" , "id" => "product_weight" , "placeholder" => "Grams" , "value" => $product_data[0]['product_weight'],"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')")); ?>
		
		</div>
                     </fieldset>
					 
					 
					 <?php
						$role_id = $this->session->userdata('login')->role_id;
						if($role_id==1){								
					?>
					
					  <fieldset>
                        <?php echo form_label('Price from vendor', 'price_form_vendor'); ?>
                        <div class="price_inventory_input">									<?php echo form_input(array( "name" => "price_form_vendor" , "id" => "price_form_vendor" , "placeholder" => "Price form Vendor" , "value" => $product_data[0]['price_form_vendor'] ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')"  )); ?>
						</div>
                     </fieldset>
						<?php }?>
						<fieldset>
								<?php echo form_label('Wholesale price', 'wholesale_price'); ?>
								<div class="price_inventory_input">
								<?php echo form_input(array( "name" => "wholesale_price" , "id" => "wholesale_price" , "placeholder" => "Wholesale price" ,
								 "value" => $product_data[0]['wholesale_price'] ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')"  )); ?>
								 <?php echo form_error('wholesale_price'); ?>
							</div>
						</fieldset>	
						
						<fieldset>
								<?php echo form_label('Suggested price', 'default_variants_price'); ?>
								<div class="price_inventory_input">
								<?php echo form_input(array( "name" => "default_variants_price" ,  "value" => $product_data[0]['default_variants_price'], "id" => "default_variants_price" , "placeholder" => "Suggested price" ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')")); ?>
							</div>
						</fieldset>	
						<?php if($product_data[0]['status'] == 0){ ?>
							<fieldset>
								<?php echo form_label('Product live in (Days)', 'live_in'); ?>
								<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "live_in" , "id" => "live_in" , "placeholder" => "Product Live in(Days)" ,"oninput" => "this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" ,"value" => ( isset($product_data[0]['live_in']) )? $product_data[0]['live_in'] : "" )); ?>
								</div>
							</fieldset>
						<?php } ?>
						<?php if($role == 1 && $product_data[0]['publically_status'] > 0){ ?>
							<fieldset>
									<?php echo form_label('Product Visibility', 'publically_status'); ?>
									<div class="price_inventory_input">
										<select class="publically_status" name="publically_status" id="publically_status">
											<option value="1" <?php if($product_data[0]['publically_status'] == 1) echo "selected"; ?>>Private</option>
											<option value="0" <?php if($product_data[0]['publically_status'] == 0) echo "selected"; ?>>Publicly</option>
										</select>
									</div>
							</fieldset>
						<?php } ?>
				 </div>
               </div>
               <div class="form-image-video" id="product_">
                  <div class="top-image-video">
                     <?php   
                        if(!empty($product_Img)){
                        foreach($product_Img as $product_Imgs){ 
						
						$filename = $product_Imgs['src'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);
						if($ext=="mp4"){ ?>
						 <div class="top-image-video-gallery">
                        <a  id="example<?php echo $product_Imgs['id'];?>" class="example" href="<?php echo base_url().'uploads/product'.$product_Imgs['product_id'].'/'.$product_Imgs['src'];?>">
						
			<video width='100' height='70' controls>
			<source src='<?php echo base_url().'uploads/product'.$product_Imgs['product_id'].'/'.$product_Imgs['src'];?>' type='video/mp4'>
			<source src='mov_bbb.ogg' type='video/ogg'>
			Your browser does not support HTML5 video.
			</video>  </a>						
                        <span class="delete_main_img" id="delete_main_img<?php echo $product_Imgs['id'];?>"></span>
                     </div>
					 <?php				
							
						}else{
						?>
                     <div class="top-image-video-gallery">
                        <a  id="example<?php echo $product_Imgs['id'];?>" class="example" href="<?php echo base_url().'uploads/product'.$product_Imgs['product_id'].'/'.$product_Imgs['src'];?>">
                        <img alt="example<?php echo $product_Imgs['product_id'];?>" class="myImg" data-id="myImg<?php echo $product_Imgs['product_id'];?>" id="myImg<?php echo $product_Imgs['product_id'];?>" src="<?php echo base_url().'uploads/product'.$product_Imgs['product_id'].'/'.$product_Imgs['src'];?>"style="width:100%;max-width:300px">
                        </a>						
                        <span class="delete_main_img" id="delete_main_img<?php echo $product_Imgs['id'];?>"></span>
                     </div>
                     <?php 
						  }
						}
                        } ?>
                  </div>
                  <fieldset>
                     <div class="prod_img1">
					 <span> Add image </span>
                        <?php echo form_input(array( "class" => "product_img" , "id" => "gallery-photo-add" , "accept" => "image/*" ,"name" => "main_img_src[]" , "type" => "file")); ?>	
                        <?php echo form_error('main_img_src'); ?>	
						<span class="size-limit">(Minimum size should be 450px * 450px.)</span>
                           <div class="gallery"> </div>		 				
                        <div id="main_img_error" style="display:none"> Please upload at least one image before delete the image.</div>
                     </div>
                  </fieldset>
				 <fieldset>
                     <div class="prod_video1">
					 <span> Add video </span>
                        <?php echo form_input(array( "class" => "file_multi_video" , "id" => "prod_video1" , "accept" => "video/*" ,"name" => "main_img_src[]" , "type" => "file")); ?>	                	
                     </div>
					 <video width="250" controls  id="addproduct_video" style="display:none;">
								<source id="video_here">
								Your browser does not support HTML5 video.
								</video>
                  </fieldset>
				  
				  
               </div>
            </div>
		
		<?php
			if(empty($product_variants)){
				?>
				<div class="input_fields_wrap">
				   <div class="varint-options-names">
					  <fieldset class="form-input variants-input">
						 <div class="add_new_variants">
							<a style="float:right;" href="javascript:void(0)"  data-id="" class="add_new_variant"> Add variant </a> 
							<a style="float:right;display:none;" href="javascript:void(0)"  class="canel_new_variant"> Hide options </a>
							<div>
							   <h3> Variants </h3>
							   <br>
							   <p> Add variants if this product comes in multiple versions, like different sizes or colors. </p>
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
			<?php	
			} else {
				
				
			?>
            <div class="input_fields_wrap">
		   
		   <div class="varint-options-names">   
		
	<fieldset class="form-input variants-input">
	<div class="add_new_variants">
		   <a style="float:right;" href="javascript:void(0)"  class="delete-all-product-variants"> Delete all variants </a>
		   
	</div>	   
	  </fieldset>
	  
	  <div class="added-varintss">
	   <br> <p> Modify the variants to be created: </p> <br>
		  <table class="table" border="1">
			<thead>			  
				<th> Variant </th>
				<?php
				
					if( isset($variant_options->option1_name) && !empty($variant_options->option1_name)){
						echo "<th>".$variant_options->option1_name."</th>";	
					}
					if( isset($variant_options->option2_name) && !empty($variant_options->option2_name)){
						echo "<th>".$variant_options->option2_name."</th>";	
					}
					if( isset($variant_options->option3_name) && !empty($variant_options->option3_name)){
						echo "<th>".$variant_options->option3_name."</th>";	
					}

				?>
				
				
				<th> Price </th>
				<th> SKU </th>
				<th> Barcode </th>
				<th> Inventory </th>
				<th></th>
			</thead>
			<tbody id="varints-data-columns">
			<?php
				if(!empty($product_variants)){
			foreach($product_variants as $pv){
				
				
				?>
	<tr>
				<td>
					<input type="hidden" value="<?=$product_data[0]['id']?>" id="product-ids">
					<input type="file"  id= "<?=$pv['product_varinat_ids']?>" class="variant_img1" value="" name="variant_images[]">
					<?php
					if(!empty($pv['src'])){
						?>
					<img id="<?=$pv['product_varinat_ids']?>" src = "<?php echo base_url().'uploads/product'.$product_Imgs['product_id'].'/'.$pv['src'];?>" class="variant-table-image varints-select-imagelast<?=$pv['product_varinat_ids']?>">	
				
					<?php
					} else {
						?>
						<img id="<?=$pv['product_varinat_ids']?>" src ="<?php echo base_url('images/no-image-128x128.png');?>" class="variant-table-default-image varints-select-image varints-select-imagelast<?=$pv['product_varinat_ids']?>">
							<div class="varintsgallery"> </div>
						<?php
					}
					?>	
				
				</td>
				<?php 
				if(!empty($pv['option1'])){
					
					echo "<td><span class='option-name-span1'>".$pv['option1']."</span></td>";
				 }				 
				 if(!empty($pv['option2'])){
				
					echo "<td><span class='option-name-span2'>".$pv['option2']."</span></td>";
				 }
				 
				 if(!empty($pv['option3'])){
				
					echo "<td><span class='option-name-span3'>".$pv['option3']."</span></td>";
				 }
				
				?>
						<td>
							<input type="text" name ="var[<?=$pv['product_varinat_ids']?>][price]" value="<?php echo $pv['price']; ?>">
						</td>
						
					 <td>
						<input type="text" value="<?php echo $pv['sku']; ?>" name = "var[<?=$pv['product_varinat_ids']?>][sku]">
					</td>
					
					<td>
							<input type="text" value="<?php echo $pv['barcode']; ?>" name ="var[<?=$pv['product_varinat_ids']?>][barcode]">
					</td>
					
					<td>
							<input type="text" value="<?php echo $pv['inventory_quantity']; ?>" name = "var[<?=$pv['product_varinat_ids']?>][inventory]">
					</td>
					
					<td>
								<span class="variant-delete-btns" id="deleteProductVariant" data-id="
									<?=$pv['product_varinat_ids']?>">
								</span>
					</td>
			</tr>
				
				<?php	} } ?>
			
			
			</tbody>
		  </table>	  
	  </div> 
	  
  </div>           	
         </div>
		<?php 
			}
			?>
		 
		  <div class="buttons-form bottom-save-button">	                        
               <?php echo form_button(array( "id" => "saveItems" , "type" => "submit" , "value" => "Save" , "content" => "Save" )); ?>	 
            </div>
      </div>
   </section>
</div>
<div class="overlay" style="display:none;"></div>
<div id="category_modal" class="category_modal" title="Categories" style="display:none;"></div>
<div id="supplier_modal" class="supplier_modal" title="Suppliers" style="display:none;"></div>
<div id="variants_modal" title="Variants" style="display:none;"></div>
<div id="variants_delete_modal" class="common_popup delete_popup variant_modal_delete" title="Delete variant?" style="display:none;">
<p><span class="ui-icon ui-icon-alert"></span>Do you really want to delete this variant?</p>
</div>

<div id="all-variants_delete_modal" class="common_popup delete_popup all_variant_modal_delete" title="Delete all variants?" style="display:none;">
<p><span class="ui-icon ui-icon-alert"></span>Are you sure you want to delete all the variants? <br> This action cannot be reversed.</p>
</div>

<script>
	
	$(document).on("change", ".file_multi_video", function(evt) {
	  $("#addproduct_video").css("display","block");
	  var $source = $('#video_here');
	  $source[0].src = URL.createObjectURL(this.files[0]);
	  $source.parent()[0].load();
});

	function readURL(input,vid) {  
	  if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {		
			$(".varints-select-imagelast"+vid).attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	  }
   }

	$(document).ready(function(){
	$(".variant_img1").change(function() {	
	 var vid = $( this ).attr("id");  
	  readURL(this,vid);
		});
	});


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
	 
    $('.remove_field').click(function (e) {	
   	var product_id = $(this).attr('data-id'); 
   	if(product_id!=""){	
   		 	$.ajax({
   					type: "POST",
   					url: '/app/importapp/products/AllProducts/delete_product_variants',
   					data: {product_id: product_id},
   					success: function (res) {
   						$("#variant_row"+product_id).parent().remove();
   					},
   					error: function(){
   					console.log('error in update page setting.');
   					}
   		})	
		
       }		
   });
   
   $('.delete_main_img').click(function (e){ 
      var myImg = $(".myImg").length;
      console.log(myImg);
   	  if(myImg < 2){
   		  $("#main_img_error").css("display","block");
   		  return false;
   	  }
   	var img_id = $(this).attr('id').match(/\d+/);
   	
	if(img_id!=""){	 
   		 	$.ajax({
   					type: "POST",
   					url: '/app/importapp/products/AllProducts/delete_product_Img',
   					data: {img_id: img_id},
   					success: function (res) {
   						$("#example"+img_id).parent().remove(); 
   					},
   					error: function(){
   					console.log('error in update page setting.');
   					}
   		})	
       }		
   });
   
   
   $('#addVariants').click(function (event) {
   	event.preventDefault();
   	$.post('<?php echo base_url(); ?>products/AllProducts/addVariants', function (response) {
   		$('#variants_modal').html(response);
   	});
   	$("#variants_modal").dialog();
   	var modal = $('#variants_modal').dialog('isOpen');
   	if (modal == true) {
   		$('.overlay').show();
   	}
   	$('#variants_modal').on('dialogclose', function (event) {
   		$('.overlay').hide();
   	});
   });
   
   // Multiple images preview in browser
   $(function() { 

	setTimeout(function() {
    	$("#suces").text("")
	}, 3000);
	
    var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }

    };
    $('#gallery-photo-add').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });

  


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
						variant_name = variant_name+"<input type='hidden' name = 'vars["+i+"][opt"+counter+"]' value='"+sss+"'> <span class='option-name-span"+counter+"'>"+sss+"</span>"; 
						
					});
					  $("#varints-data-columns").append('<tr><td>'+variant_name+'</td><td><input type="text" name = "vars['+i+'][price]" value="<?php  echo isset($product_data[0]['product_price'])?$product_data[0]['product_price']:0; ?>"></td><td><input type="text" value="" name = "vars['+i+'][sku]"></td><td><input type="text" value="" name = "vars['+i+'][barcode]"></td><td><input type="text" value="0" name = "vars['+i+'][inventory]"></td></tr>');

				});			
			}		
	}	
	$('body').on('change', '.combinationvar', function (e) {		
			create_variants_table();		
	});
		
		var maxfields      = 3;
		var wrappers   		= $(".varian");
		var add_more_button      = $(".add_more_field_button");
		
		var x = 1;
		$(add_more_button).click(function(e){
			e.preventDefault();
			if(x < maxfields){
				x++;
				if( x == 2){
					var opt_val = "Color";
				} else {
					var opt_val = "Material";
				}
				$(wrappers).append('<div><div class="variantoption1"><input type="text" placeholder="Name" id="variant_option'+x+'" name="options-name[]" value="'+opt_val+'"></div><div class="variantoption test'+x+'"><input class="combinationvar" type="text" data-role="tagsinput" placeholder="Separate options with a comma" id="variant_option_value'+x+'" name="options-values[]"><a href="#" class="remove_field"></a></div></div>');
			    $('.test'+x+' input').tagsinput('refresh'); 
			}
		});
		
		$(wrappers).on("click",".remove_field", function(e){
			e.preventDefault(); $(this).parent().parent().remove(); x--;
			create_variants_table();
		})
	});
$(function() {
    setTimeout(function() {
        $("#cat_saves").text("")
    }, 3000);
}); 
	$(function() {
    setTimeout(function() {
        $("#add_sup").text("")
    }, 3000);
}); 
</script>
