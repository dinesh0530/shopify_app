<?php defined('BASEPATH') OR exit('No direct script access allowed'); 	
		foreach($categories as $import_category){		
			$jquery_category[] = $import_category->category_name;		
	}
	
	foreach($suppliers as $import_supplier){		
			$jquery_supplier[] = $import_supplier->name;		
	}
?> 

<style> .size-limit{font-size: 11px;color: #333;} </style>

<link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap-tagsinput.css");?>" />
<script src="<?php echo base_url("assets/js/bootstrap-tagsinput.js");?>"></script>
<script src="<?php echo base_url("assets/js/jquery.combinations.1.0.min.js");?>"></script>

<div class="inner-pages">
	<section class="listing-section">
		<div class="container">
		<?php echo form_open_multipart(uri_string(), array('class' => 'create-item-form')); ?>
			<div class="create-product">
				<div class="buttons-form">
					<div class="buttons-form-inner">
						<span class="upload_template">
							<a href="">Upload Template</a>	
							<input type="file" name="filename" id="filename"> 
						</span>							
						<span class="dn_temp"><a href="javascript:void(0)">Download Template</a></span>
						<span class="cancel-save_button">
							<a href="all-products" class="cancel">Cancel</a>				
							<?php echo form_button(array( "id" => "saveItem" , "type" => "submit" , "value" => "Save" , "content" => "Save" )); ?>	
						</span>
					</div>
				</div>	
				<div class="form-inner createItem-form">
					<div class="form-detail-outer">					
						<div class="form-detail">
						    <?php 
								if(isset($values_error['product_title'])){
									$titile_val = $values_error['product_title'];
								}else if(isset($src_pro->product_title)){
									$titile_val = $src_pro->product_title;
								}else{
									$titile_val = "";
								}
                                if(isset($src_pro->u_id)){
									$src_u_id = $src_pro->u_id;
								}else if(isset($values_error['requested_by'])){
									$src_u_id = $values_error['requested_by'];
								}else{
									$src_u_id = 0;
								}
								 if(isset($src_pro->id)){
									$src_id = $src_pro->id;
								}else if(isset($values_error['sourcing_id'])){
									$src_id = $values_error['sourcing_id'];
								}else{
									$src_id = 0;
								}
							?>
						    <?php echo form_input(array( "type" => "hidden" , "name" => "sourcing_id" , "value" => $src_id )); ?>
							<?php echo form_input(array( "type" => "hidden" , "name" => "requested_by" , "value" => $src_u_id )); ?>
							<h2>Details</h2>
							<fieldset>						
								<?php echo form_label('Name', 'product_title'); ?>
								<div class="prod_title">
									<?php echo form_input(array( "name" => "product_title" ,"name2" => "product_title2" , "id" => "product_name" , "placeholder" => "Enter product name", "value" => $titile_val) ); ?>
									<?php echo form_error('product_title'); ?>							
								</div>
							</fieldset>
							<fieldset>
								<?php echo form_label('Category', 'product_category'); ?>
								<div class="prod_category">
									<select class="catgory_name" name="product_category" id="category_names">
										<option value="">Select category</option>
										<?php 
										foreach($categories as $category){ ?>
											<option value="<?php echo $category->id; ?>" <?php if(isset($values_error['product_category']) && $values_error['product_category'] == $category->id){ 
												echo "selected" ;  
											 } ?>><?php echo $category->category_name; ?></option>
										<?php }?>
									</select>
									<?php echo form_error('product_category'); ?>
								</div>
								<?php if($role_id == 1){ ?>
									<a id="create_category" class="create_category" data-id="" href="">Add new category</a>
								<?php } ?>
							</fieldset>
							<fieldset>
								<?php echo form_label('Description', 'product_desc'); ?>
								<?php echo form_textarea(array("name" => "product_desc" , "id" => "product_desc" , "placeholder" => "Product description", "rows" => "10" , "column" => "50","value" => ( isset($values_error['product_desc']) )? $values_error['product_desc'] : ""));	?>
							</fieldset>
							<fieldset>
								<?php echo form_label('Supplier', 'product_supplier'); ?>
								<div class="prod_supplier">
								<select class="supplier_names" name="product_supplier" id="supplier_name">
									<option value="">Select supplier</option>
									<?php foreach($suppliers as $supplier){ ?>
										<option value="<?php echo $supplier->id; ?>" <?php if(isset($values_error['product_supplier']) && $values_error['product_supplier'] == $supplier->id){ 
											echo "selected" ;  
										 } ?>><?php echo $supplier->name; ?></option>
									<?php }?>
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
								<?php echo form_input(array( "name" => "product_price" , "id" => "product_price" , "placeholder" => "Enter price" ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" ,"value" => ( isset($values_error['product_price']) )? $values_error['product_price'] : "" )); ?>
								<?php echo form_error('product_price'); ?>
							</div>
							</fieldset>
							<fieldset>
								<?php echo form_label('SKU', 'product_sku'); ?>
								<div class="price_inventory_input">
								<?php echo form_input(array( "name" => "product_sku" , "id" => "product_sku" , "placeholder" => "Enter SKU" ,"value" => ( isset($values_error['product_sku']) )? $values_error['product_sku'] : "")); ?>
								<?php echo form_error('product_sku'); ?>
							</div>
							</fieldset>
							<fieldset>
								<?php echo form_label('Stock levels', 'product_stock'); ?>
								<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "product_stock" , "id" => "product_stock" , "placeholder" => "Stock available" ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" ,"value" => ( isset($values_error['product_stock']) )? $values_error['product_stock'] : ""));	?>
								</div>
							</fieldset>
							<fieldset>
								<?php echo form_label('Wait time', 'product_wait_time'); ?>
								<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "product_wait_time" , "id" => "wait_time" , "placeholder" => "No of days for stock available" ,"value" => ( isset($values_error['product_wait_time']) )? $values_error['product_wait_time'] : "")); ?>
								</div>
							</fieldset>
							<fieldset>
								<?php echo form_label('Weight', 'product_weight'); ?>
								<div class="price_inventory_input">
								<?php echo form_input(array( "name" => "product_weight" , "id" => "product_weight" , "placeholder" => "Grams" ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" ,"value" => ( isset($values_error['product_weight']) )? $values_error['product_weight'] : ""    )); ?>
							</div>
							</fieldset>
							<?php $role_id = $this->session->userdata('login')->role_id;
							if($role_id==1){ ?>
							<fieldset>
									<?php echo form_label('Price from vendor', 'price_form_vendor'); ?>
									<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "price_form_vendor" , "id" => "price_form_vendor" , "placeholder" => "Price form vendor" , "oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" ,"value" => ( isset($values_error['price_form_vendor']) )? $values_error['price_form_vendor'] : "")); ?>
								</div>
							</fieldset>						
							<?php }?>	
							<fieldset>
									<?php echo form_label('Wholesale price', 'wholesale_price'); ?>
									<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "wholesale_price" , "id" => "wholesale_price" , "placeholder" => "Wholesale price" ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" ,"value" => ( isset($values_error['wholesale_price']) )? $values_error['wholesale_price'] : "")); ?>
									<?php echo form_error('wholesale_price'); ?>
								</div>
							</fieldset>
							<fieldset>
									<?php echo form_label('Suggested price', 'default_variants_price'); ?>
									<div class="price_inventory_input">
									<?php echo form_input(array( "name" => "default_variants_price" , "id" => "default_variants_price" , "placeholder" => "Suggested price" ,"oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" ,"value" => ( isset($values_error['default_variants_price']) )? $values_error['default_variants_price'] : ""    )); ?>
								</div>
							</fieldset>
							<fieldset>
									<?php echo form_label('Product live in (Days)', 'live_in'); ?>
									<div class="price_inventory_input">
										<?php echo form_input(array( "name" => "live_in" , "id" => "live_in" , "placeholder" => "Product live in (Days)" ,"oninput" => "this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" ,"value" => ( isset($values_error['live_in']) )? $values_error['live_in'] : "" )); ?>
									</div>
							</fieldset>
							<?php if($role == 1 && $src_id > 0){ ?>
								<fieldset>
										<?php echo form_label('Product Visibility', 'publically_status'); ?>
										<div class="price_inventory_input">
											<select class="publically_status" name="publically_status" id="publically_status">
													<option value="1">Private</option>
													<option value="0">Publicly</option>
											</select>
										</div>
								</fieldset>
							<?php } ?>
						</div>						
					</div>					
					<div class="form-image-video">
						<fieldset>	
							<span>Add image</span>
							<div class="prod_img1">					
								<?php echo form_input(array( "class" => "product_img" , "name" => "img_src[]" , "accept" => "image/*","type" => "file" , "onchange" => "preview_image1(event)" )); ?>
								<?php echo form_error('img_src'); ?>
								<span class="size-limit">(Minimum size should be 450px * 450px.)</span>
								<img id="output_image1"/>
							</div>
						</fieldset>
						<fieldset>
							<span>Add video</span>
							<?php echo form_input(array( "class" => "product_video file_multi_video", "name" => "img_src[]" , "accept" => "video/mp4,video/x-m4v,video/3gp,video/*", "type" => "file")); ?>
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
<div id="variant_alert" class="delete_popup common_popup " title="Variants" style="display:none;">
  <p>Firstly you have to save this product then you can add variants.</p>
</div>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<script>
	$("#filename").change(function(e) {
		var ext = $("input#filename").val().split(".").pop().toLowerCase();
		if($.inArray(ext, ["csv"]) == -1) {
			alert('Upload CSV');
			return false;
		}
		if (e.target.files != undefined) {
			var reader = new FileReader();
			reader.onload = function(e) {
				var csvval=e.target.result.split("\n");
				var csvvalue=csvval[1].split(",");
				var inputrad="";
				var jquerycat = <?php echo json_encode($jquery_category); ?>;
				var jquerysup = <?php echo json_encode($jquery_supplier); ?>;
				for(var i=0;i<csvvalue.length;i++){
					var temp=csvvalue[i];
					
					if(i == 0){
						$("#product_name").val(csvvalue[0]);
					}
					else if(i == 1){
						if ($.inArray(csvvalue[1], jquerycat) != -1)
						{
							$("#category_names option:selected").text(csvvalue[1]) ;
						}
						else{
							$("#category_names option:selected").text("Select category") ;
						}
					}
					else if(i == 2){		
							$("#product_desc").val(csvvalue[2]);
					}
					else if(i == 3){
						if($.inArray(csvvalue[3], jquerysup) != -1){
							$("#supplier_name option:selected").text(csvvalue[3]) ;	
						}else{
							$("#supplier_name option:selected").text("Select supplier") ;
						}
					}
					else if(i == 4){
						$("#product_price").val(csvvalue[4]);
					}
					else if(i == 5){
						$("#product_sku").val(csvvalue[5]);
					}
					else if(i == 6){
						$("#product_stock").val(csvvalue[6]);
					}
					else if(i == 7){
						$("#wait_time").val(csvvalue[7]);
					}
					else if(i == 8){
						$("#product_weight").val(csvvalue[8]);
					}
					else if(i == 9){
						$("#price_form_vendor").val(csvvalue[9]);
					}
					else if(i == 10){
						$("#wholesale_price").val(csvvalue[10]);
					}	
					else if(i == 11){
						$('#default_variants_price').val(csvvalue[11]);
					}
					else if(i == 12){ 
						$('#output_image1').attr('src', csvvalue[12]);		
					}
					else{
						$('#output_image2').attr('src', csvvalue[13]);  
					}

					var inputrad=inputrad+","+temp;
				}
			};
			reader.readAsText(e.target.files.item(0));
		}
		return false;
	});	
	
	$( ".dn_temp" ).click(function() {
		var title = $('#product_name').val();		
        var category =  $('#category_names option:selected').text();
		var desc = $('#product_desc').val();
		var supplier =  $('#supplier_name option:selected').text();
		var price = $('#product_price').val();
		var sku = $('#product_sku').val();
		var stock = $('#product_stock').val();
		var wait_time = $('#wait_time').val();
		var weight = $('#product_weight').val();
		var vendor_price = $('#price_form_vendor').val();
		var wholesale = $('#wholesale_price').val();
		var suggest_price = $('#default_variants_price').val();
	
		var titles = ["Name","Category","Description","Supplier","Price","SKU","Stock","Waiting time","Weight","Price from vendor","Wholesale price","Suggest Price"];	  
		if(title != ''){		  
			var data = [title,category,desc,supplier,price,sku,stock,wait_time,weight,vendor_price,wholesale,suggest_price];	 	  
			var CSVString = prepCSVRow(titles, titles.length, '');
				CSVString = prepCSVRow(data, titles.length, CSVString);
			var downloadLink = document.createElement("a");
			var blob = new Blob(["\ufeff", CSVString]);
			var url = URL.createObjectURL(blob);
				downloadLink.href = url;
				downloadLink.download = "product_template.csv";
				document.body.appendChild(downloadLink);
				downloadLink.click();
				document.body.removeChild(downloadLink);
		}else{ exit(); }
	});	
	
	function prepCSVRow(arr, columnCount, initial) {
		var row = ''; 
		var delimeter = ','; 
		var newLine = '\r\n'; 
		function splitArray(_arr, _count) {
			var splitted = [];
			var result = [];
			_arr.forEach(function(item, idx) {
				if ((idx + 1) % _count === 0) {
					splitted.push(item);
					result.push(splitted);
					splitted = [];
				} else {
					splitted.push(item);
				}
			});
			return result;
		}
		var plainArr = splitArray(arr, columnCount);  
		plainArr.forEach(function(arrItem) {
			arrItem.forEach(function(item, idx) {
				row += item + ((idx + 1) === arrItem.length ? '' : delimeter);
			});
			row += newLine;
		});
		return initial + row;
	}
		
	function preview_image1(event){
		var reader = new FileReader();
		reader.onload = function(){
		   var output = document.getElementById('output_image1');
		   $("#output_image1").css({"border":"1px solid #e1e8f0","padding":"10px","height":"100px ","width":"auto","margin-top":"48px","display":"table"});
		   output.src = reader.result;
		}
		reader.readAsDataURL(event.target.files[0]);
	}

	function preview_image2(event){
		var reader = new FileReader();
		reader.onload = function(){
			var output = document.getElementById('output_image2');
			$("#output_image2").css({"border":"1px solid #e1e8f0","padding":"10px","height":"100px ","width":"auto","margin-top":"48px","display":"table"});
			output.src = reader.result;
		}
		reader.readAsDataURL(event.target.files[0]);
	}
 
	$(document).on("change", ".file_multi_video", function(evt) {
		$("#addproduct_video").css("display","block");
		var $source = $('#video_here');
		$source[0].src = URL.createObjectURL(this.files[0]);
		$source.parent()[0].load();
	});
	
	$(document).on("click", ".add_new_variant", function(evt) {
		$( "#variant_alert" ).dialog({
			resizable: false,
			height: "auto",
			width: 400,
			modal: true,
			buttons: {
				"Ok": function() {
					$( this ).dialog( "close" );
				}
			}
		});
		
		var modal = $('#variant_alert').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('delete_popup');
			$('.overlay').show();
		} 
		$('#variant_alert').on('dialogclose', function(event) {
			$('.ui-dialog').removeClass('delete_popup');
			$('.overlay').hide();
 		});
	});

	$(function() {
		setTimeout(function() {
			$("#cat_saves").text("");
			$("#add_sup").text("");
		}, 3000);
	});	
</script>