<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script> 
<style>
.disconnected-msg{width: 100%; float: left; color: green; text-align: center; position: relative; height: 0; font-size: 18px; margin-top: 4px;}
</style>
<?php if($this->session->flashdata('disconnected-msg')){ ?>
		<div class="disconnected-msg" id="suces"><?php echo $this->session->flashdata('disconnected-msg');?></div>					
<?php }?>	
<div class="My-List-Page">
	<div class="container">
	<div id="store_connected_product"> 	 
	     <a id="site-listed-products" class="listme active-site-product" href="javaScript:void(0);"> Listed </a>
		 <a id="store-listed-products" class="my-linked-product" href="javaScript:void(0);"> Connected </a>  
	</div>
	<div class="My-List-Page1">
		<div id="loader"></div>
		<div class="successmsg sucess" id="sucess"></div>		
	 <?php if(!empty($product_details)){ ?>
	<div class="bulk-actions">
	   <div class="bulk-actions-options">
		  <label for="16 products selected" class="btn bulk-actions__item--active bulk-actions__item">
		  <input id="bulk-selector" class="bulk-actions--checkbox" type="checkbox">
	   	  <span class="selected-count"> 0 Products selected</span>	   	  
		  <button id="import-button"class="btn bulk-actions__item toggle-bulk-actions" disabled>
		  Import all to store
		  </button>
		  <button id ="bulk-delete-action" class="btn bulk-actions__item toggle-bulk-actions">
		  Remove all from import list
		  </button>
	   </div>
	</div>
	<form>
	  <?php  
	  foreach($product_details as $k => $product){ 
	  $myList_data = $product['myList_data'];
	  ?>       
		<div class="main-tab-div" data-id="<?php echo $product['product_id']; ?>" id="tabs<?php echo $product['product_id']; ?>">
		<div class="errormsg error" id="error"></div>	
            <div id="data-added-sucessfully" style="display:none;"> Data saved successfully </div>  		
			<?php echo form_open_multipart(uri_string(), array('class' => 'mylist_form')); ?>
			<div class="My-List-Page-inner">
				<div class="tabs">
				 <?php /*
				 <ul>
					<li><a href-"#"><span class="for-checkbox">
					<input class="import-products variant-selector" name="mylist_chkbox[]" type="checkbox" value="<?php echo $product['product_id'];?>"></span></a></li>
					<li><a href="#tabs-1"> Product </a></li>
					<li><a href="#tabs-2"> Description </a></li>
					
				 	<li><a href="#tabs-3"> Variants <span class="badge badge-tab margin-small-left variants-count"><?php  $i = count($product['product_variants']); echo $i<1? '1':$i; ?></span></a></li> 
					<li><a href="#tabs-4"> Images </a></li>
				</ul> <?php */ ?>				
			<ul role="tablist" class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
			   <li><a href-"#"><span class="for-checkbox">
				  <input class="import-products variant-selector" name="mylist_chkbox[]" type="checkbox" value="<?php echo $product['product_id'];?>"></span></a>
			   </li>
			   <li role="tab" tabindex="0" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab ui-tabs-active ui-state-active" aria-controls="tabs-1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="#tabs-1" role="presentation" tabindex="-1" class="ui-tabs-anchor" id="ui-id-1"> Product </a></li>
			   <li role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="tabs-2" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#tabs-2" role="presentation" tabindex="-1" class="ui-tabs-anchor" id="ui-id-2"> Description </a></li>
			   <li role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="tabs-3" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#tabs-3" role="presentation" tabindex="-1" class="ui-tabs-anchor" id="ui-id-3"> Pricing & Variants <span class="badge badge-tab margin-small-left variants-count"><?php  $i = count($product['product_variants']); echo $i<1? '1':$i; ?></span></a></li>
			   <li role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="tabs-4" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="#tabs-4" role="presentation" tabindex="-1" class="ui-tabs-anchor" id="ui-id-4"> Images </a></li>
			</ul>
		<script>
		$( function(){ 
		   $( "#tabs<?php echo $product['product_id']; ?>" ).tabs(); 
		});
		</script>
			</div>
			<div class="remove added-remove-links">
			    <a href="javaScript:void(0);" class="save_mylist-page save_mylist-pagedata">Save</a>
				<a id="remove_<?php echo $product['product_id'];?>" href="javaScript:void(0);" class="remove-product ">Removes</a>
			</div>    

			<?php  if(!empty($myList_data)){ ?>			
			<div class="tabs-body" id="tabs-1">
				<div class="product-img">
					<img src="<?php echo base_url();?>uploads/product<?php echo $product['product_id'];?>/<?php echo $product['src']; ?>">
				</div>
				<div class="tabs-data">
					<div class="tabs-data-first">
						<div class="tabs-data-second"> 
							<strong class="tabs-data-first-part" >Original title:</strong>
							<span><?php			echo $product['product_title'];	?></span>
						</div>
						<div class="tabs-data-first-part">
							<div>
								<a href="<?php echo base_url();?>product/product-details/<?php echo $product['product_id'];?>" target="_blank" class="chevron-link">View original product
								</a>
							</div>
						</div>						
					<div class="all-pricess-products">
						<div class="all-pricess-productss">					
							<label>RRP:</label>
							<span>$<?=$product['product_price'];?></span>
						</div>
						<div class="all-pricess-productss pricewhole">				   
						   <label>Wholesale price:</label>
						<span> $<?=$product['wholesale_price'];?></span>
						</div>
					</div>
					
						<div class="tabs-data-fourth">
						<div class="titleand_Collections">
							<div class="tabs-data-fourth-first">
								<label>Change title:</label>
								<input type="text" name="title" maxlength="255" class="form-control custom-title" value="<?php 
								
								$myList_title = $product['myList_data'][0]['product_title'];
								if(!empty($myList_title)){
								  echo $myList_title;
								}else{
                                  echo $product['product_title'];
								}
								 ?>">
								<input type="hidden" name="my_product_id" value="<?php echo $product['product_id']; ?>">
								<input type="hidden" name="uid" value="<?php echo $this->session->userdata('login')->id; ?>">
							<input type="hidden" name="vendor_id" value="<?php echo $product['product_add_by'];?>">
							</div>
							<div class="tabs-data-build-wrapper-top">
									<label>Collections:</label>
									<div class="chosen-container chosen-container-multi" style="width: 100%;">
										<ul class="chosen-choices">
											<li class="search-field">
								<?php
									$myList_collections = $product['myList_data'][0]['collections'];
								if(!empty($myList_collections)){ ?>
								
						<input type="text" value="<?php echo $myList_collections;?>" placeholder="Choose collections" class="custom-collection" name="colections">
								<?php }else{?>
                                  <input type="text" placeholder="Choose collections" class="custom-collection" name="colections">
								<?php }			
								  ?>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div>							
								
								<div class="tabs-data-build-wrapper-bottom">
									<div class="build-wrapper-bottom-part">
										<label>Type:</label>
										<div class="chosen-container chosen-container-single" style="width: 100%;">
										 <fieldset>
											<select name="product_category" id="category_name" class="custom-type">
											
											<?php 	
											
						foreach($categories as $category){												
							$myList_category = $product['myList_data'][0]['product_category'];
							$myList_type = $product['myList_data'][0]['product_type'];
							$selected = '';
							if(!empty($myList_category) && $myList_category == $category->id ){
								$selected = 'selected';

							} else {
								$selected = ($category->id == $product['product_category']  ? 'selected' : '');
							}		
							?>
						<option name="type" value="<?php echo $category->id; ?>" <?php echo $selected; ?>><?php echo ucfirst($category->category_name); ?></option>
						<?php
						}
                        ?>
											</select>
										  </fieldset>
										</div>
										<select data-placeholder="Choose Type" class="form-control unvisible">
											<option value=""></option>
										</select>
									</div>
									<div class="build-wrapper-bottom-part">
										<label>Tags:</label>
										<div class="chosen-container chosen-container-multi" style="width: 100%;">
											<ul class="chosen-choices">
												<li class="search-field">
								<?php $myList_tags= $product['myList_data'][0]['tags'];
								if(!empty($myList_tags)){?>
								<input type="text" value="<?php echo $myList_tags;?>" name="tags" placeholder="Insert Tags Here" autocomplete="off" class="default dummy-input custom-tags">
									<?php
								}else{?>
								<input type="text" name="tags" placeholder="Insert Tags Here" autocomplete="off" class="default dummy-input custom-tags">
								<?php
								}
								?>
													</li>
											</ul>
										</div>
										<select multiple="multiple" name="tags" placeholder="Enter Tags" class="save-on-change-tags form-control unvisible"></select>
									</div>				
								
															
								</div>
							</div>
							<div class="title-save-links">
						    	<a id="title_savep_<?=$product['product_category'];?>" href="javaScript:void(0);" class="save-product ">save</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<?php }else{ ?>
			
			<div class="tabs-body" id="tabs-1">
				<div class="product-img">
					<img src="<?php echo base_url();?>uploads/product<?php echo $product['product_id'];?>/<?php echo $product['src']; ?>">
				</div>
				<div class="tabs-data">
					<div class="tabs-data-first">
						<div class="tabs-data-second"> 
							<strong class="tabs-data-first-part" >Original title:</strong>
							<span><?php echo $product['product_title']; ?></span>
						</div>
						<div class="tabs-data-first-part">
							<div>
								<a href="<?php echo base_url();?>product/product-details/<?php echo $product['product_id'];?>" target="_blank" class="chevron-link">View original product
								</a>
							</div>
						</div>
						<!-- <div class="tabs-data-second"></div> -->
						<?php /* ?>
						<div class="tabs-data-third">
							<div class="product-supplier">
								<?php foreach($suppliers as $supplier){ 
									if($supplier->id == $product['product_supplier']) { ?>
									<span>by <a href="#"><?php echo $supplier->name; ?></a></span> 
									<?php }
								} ?>
								<!-- <span>by <a href="/suppliers/60/products">ROYAL GIRL - SUNGLASSES</a></span>  -->
							</div>
						</div> <?php */?>
					<div class="all-pricess-products">
						<div class="all-pricess-productss">					
							<label>RRP:</label>
							<span>$<?=$product['product_price'];?></span>
						</div>
						<div class="all-pricess-productss pricewhole">				   
						   <label>Wholesale price:</label>
						<span> $<?=$product['wholesale_price'];?></span>
						</div>
					</div>
					
						<div class="tabs-data-fourth">
						<div class="titleand_Collections">
							<div class="tabs-data-fourth-first">
								<label>Change title:</label>
								<input type="text" name="title" maxlength="255" class="form-control custom-title" value="<?php echo $product['product_title']; ?>">
								<input type="hidden" name="my_product_id" value="<?php echo $product['product_id']; ?>">
								<input type="hidden" name="uid" value="<?php echo $this->session->userdata('login')->id; ?>">
							<input type="hidden" name="vendor_id" value="<?php echo $product['product_add_by'];?>">
							</div>
							<div class="tabs-data-build-wrapper-top">
									<label>Collections:</label>
									<div class="chosen-container chosen-container-multi" style="width: 100%;">
										<ul class="chosen-choices">
											<li class="search-field">
											<input type="text" placeholder="Choose collections" class="custom-collection" name="colections">
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div>							
								
								<div class="tabs-data-build-wrapper-bottom">
									<div class="build-wrapper-bottom-part">
										<label>Type:</label>
										<div class="chosen-container chosen-container-single" style="width: 100%;">
										 <fieldset>
											<select name="product_category" id="category_name" class="custom-type">
											
											<?php foreach($categories as $category){
												$selected = ($category->id == $product['product_category']  ? 'selected' : '');  ?>
												<option name="type" value="<?php echo $category->id; ?>" <?php echo $selected; ?>><?php echo ucfirst($category->category_name); ?></option>
												<?php } ?>
											</select>
										  </fieldset>
										</div>
										<select data-placeholder="Choose Type" class="form-control unvisible">
											<option value=""></option>
										</select>
									</div>
									<div class="build-wrapper-bottom-part">
										<label>Tags:</label>
										<div class="chosen-container chosen-container-multi" style="width: 100%;">
											<ul class="chosen-choices">
												<li class="search-field">
													<input type="text" name="tags" placeholder="Insert Tags Here" autocomplete="off" class="default dummy-input custom-tags">
												</li>
											</ul>
										</div>
										<select multiple="multiple" name="tags" placeholder="Enter Tags" class="save-on-change-tags form-control unvisible"></select>
									</div>				
								
															
								</div>
							</div>
							<div class="title-save-links">
						    	<a id="title_savep_<?=$product['product_category'];?>" href="javaScript:void(0);" class="save-product ">save</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
			
			
			
			
			
			
			

			<!-- MY_LIST_DESCRIPTION_TAB-->
    <?php 
	 $myList_description = $product['myList_data'];
	if(!empty($myList_description)){
		 $myList_description = $product['myList_data'][0]['description'];
	?>
	<div class="my-list-description-inner" id="tabs-2">
				<div class="list-description-content">
				
				<script type="text/javascript"> CKEDITOR.replace( '#ckeditor<?php echo $product['id']; ?>' ); </script>
					<textarea  data-id="ckeditors<?php echo $product['product_id']; ?>" id="ckeditor<?php echo $product['id']; ?>" name="description<?php echo $product['id']; ?>" class="ckeditor custom-description">
	       		<?php echo $myList_description; ?>
	  			</textarea>
				<div class="description-save-links">
					<a id="description_savep_<?=$product['product_category'];?>" href="javaScript:void(0);" class="save-product save-product_descriptions">save</a>
				</div>
  			</div>
			</div>
								<?php }else{ ?>
			<div class="my-list-description-inner" id="tabs-2">
				<div class="list-description-content">
				
				<script type="text/javascript"> CKEDITOR.replace( '#ckeditor<?php echo $product['id']; ?>' ); </script>
					<textarea  data-id="ckeditors<?php echo $product['product_id']; ?>" id="ckeditor<?php echo $product['id']; ?>" name="description<?php echo $product['id']; ?>" class="ckeditor custom-description">
	       		<?php echo $product['product_desc']; ?>
	  			</textarea>
				<div class="description-save-links">
					<a id="description_savep_<?=$product['product_category'];?>" href="javaScript:void(0);" class="save-product save-product_descriptions">save</a>
				</div>
  			</div>
			</div>
	   <?php }?>
			<!---MY_LIST_VARIANT_TAB-->		
			<div class="my-list-variants-inner" id="tabs-3">
				<div class="list-variants-head">
					<div class="list-variants-content">
						<div class="list-variants-content-1">
						
							<div class="ml5 mr10">								
								<strong>Suggested price: <?php if(!empty($product['default_variants_price'])){ ?>$<?php echo $product['default_variants_price']; } ?></strong>
								<br>
								Businesses in your industry are selling these products for a similar price.
							</div>
						</div>
						<div class="list-variants-content-2">
						<div class="variants-save-links">
					           <a id="variants_savep_<?=$product['product_category'];?>" href="javaScript:void(0);" class="save-product ">save</a>
				        </div>
							<div class="ml5 mr10">
							<?php if(!empty($product['default_variants_price'])){?>
								<a target="_self" class="btn btn-default float-right setDfault-prices" id='setDfault-prices<?=$product['id'];?>'>
									Set $<?php echo $product['default_variants_price']; ?> price
								</a>
							<?php }?>
							</div>
						</div>
					</div>
				</div>
				<div class="list-variants-body">
					<table class="table-condensed variants-table" id="bulk-suggested-price<?=$product['id'];?>">
						<thead>
							<tr>
								<th colspan="2" class="nowrap">
									<input type="checkbox" class="bulk-variant-selector" checked="checked">
									Use all
								</th>
							<th>SKU</th> 
								<?php
								if($i > 0){
									if(!empty($product['variantOptions'])){		
										for($i=1; $i<4; $i++){
													
											if(!empty($product['variantOptions']['option'.$i.'_name']) && !empty($product['variantOptions']['option'.$i.'_value']) ){
														
														echo "<th>".$product['variantOptions']['option'.$i.'_name']."</th>";					
													}
													
												}
									}
								}
								?>
								<th>
								   Wholesale price
								</th>
								<th>
									Price
								</th>
								
								<th>
									Weight
								</th>
								
								<th class="mylist-At-Price">
									Your Price

								</th>
								<th>
									Profit
								</th>
								<th class="Compared-At-Price">
									Compared At Price
								</th>
								<th>
									Inventory
								</th>
							</tr>						
						</thead>
						<tbody>
						<?php 		
						$variant_counter =0;
						if(count($product['product_variants']) > 0){							
						foreach ($product['product_variants'] as $k => $variant){ 
						if(!empty($product['product_mylist_variants'])){
						  $variant_your_price = $product['product_mylist_variants'][$k]['variant_your_price'];
						  $compared_at_price = $product['product_mylist_variants'][$k]['compared_at_price'];
						}
						else{
						  $variant_your_price = $variant['price']+150;
						  $compared_at_price = $variant['price']+150;
						}
					    //$myList_description = $product['myList_data'][0]['description'];
					?>	
							
							
							
							
							
							<tr class="single-variant-row row-enabled" data-id="variants-<?=$variant_counter?>">
								<td>
									<input type="checkbox" value="1" checked="checked">
									<input type="hidden" class="vid" value="<?php echo $variant['product_variant_ids']; ?>">
									<input type="hidden" class="pro_ID" id="pro_ID<?php echo $product['product_id'];?>">
								</td>
								<td>
									<div class="img-container">
									<?php if(!empty($variant['src'])) {?>
										<img src="<?php echo base_url();?>uploads/product<?php echo $variant['product_id'];?>/<?php echo $variant['src']; ?>">
									<?php }	?>
									</div>
								</td>
							<td>
									<input type="text" class="form-control product-sku" value="<?php echo $variant['sku']; ?>" disabled>
								</td>  
							<?php if(!empty($variant['option1'])){
								
								echo "<td><input class='form-control product-option1' type='text' value='".$variant['option1']."' disabled></td>";
							} ?>
							<?php if(!empty($variant['option2'])){
								
								echo "<td><input class='form-control product-option2' type='text' value='".$variant['option2']."' disabled></td>";
							} ?>
							<?php if(!empty($variant['option3'])){
								
								echo "<td><input class='form-control product-option3' type='text' value='".$variant['option3']."' disabled></td>";
							} ?>
								
		<td>
			 <input class="form-control variant-price" type="text" placeholder="Wholesale Price" name="var[<?php echo $variant['product_variant_ids'];?>][wholesaleprice]" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="$<?php echo $product['wholesale_price']; ?>" disabled>
		</td>
		<td>		
         <input class="form-control variant-price" type="text" placeholder="RRP" name="var[<?php echo $variant['product_variant_ids'];?>][weight]" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="$<?php echo $variant['price']; ?>" disabled> 
		</td>

		
			<td>	
		<div class="input-group">
			 <input class="form-control variant-weight" type="text" placeholder="Grams" name="var[<?php echo $variant['product_variant_ids'];?>][grams]" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php echo $product['product_weight']; ?>" disabled>
			  <span class="input-group-addon">Grams</span>
	   </div>
			</td>	
			<?php
			$myList_data = $product['myList_data'];
			if(!empty($myList_data)){
			$myList_data[0]['variants_id'];	
			}
			?>

			
		<td>		
			<div class="input-group">
			  <input type="text" name="product_price" class="form-control product_price" value="<?php echo $variant_your_price;?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
			  <span class="input-group-addon">USD</span>
			</div>
		</td>
		
		<td>
			<div class="input-group">
				<input type="text" style="color:#0083c4" class="form-control" value="$150" disabled>
				<span class="input-group-addon">USD</span>
			</div>
		</td>
		
		<td>
			<div class="input-group">
				<input type="text" name="product_compare_price" class="form-control product_compare_price" value="<?php echo $compared_at_price;?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
				<span class="input-group-addon">USD</span>
			</div>
		</td>
		
		
		
		
		
		
		
		
		<td style="display: none;">
			<span class="input-group" style="width: 150px;">
				<input type="text" class="form-control">
				<span class="input-group-addon">kg</span>
			</span>
		</td>
		
		<td>
		    <input type="text" class="form-control product_inventory" value="<?php echo $variant['inventory_quantity']; ?>" disabled>
		</td>
		
		</tr>
							<?php 
							$variant_counter++;
							}
							
							} else {   
							?>	
					<tr class="no-variant-row row-enabled" data-id="variants-0">
								<td>
									<input type="checkbox" value="1" checked="checked">
									<input type="hidden" class="pro_ID" id="pro_ID<?php echo $product['product_id'];?>">
								</td>
								<td>
									<div class="img-container">									
										<img src="<?php echo base_url();?>uploads/product<?php echo $product['product_id'];?>/<?php  echo $product['src']; ?>">
									</div>
								</td>
								<td>
									<input type="text" class="form-control product-sku" value="<?php echo $product['product_sku']; ?>" disabled>
								</td>						
						<td>
							<input class="form-control variant-price" type="text" name="wholesale_price" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php echo $product['wholesale_price']; ?>" disabled>
						</td>
								
						<td>							
						   <div class="input-group">
								<input class="form-control variant-price" type="text" name="product_price" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php echo $product['product_price']; ?>" disabled>
						    </div>							
						</td>
							
						<td>
							<div class="input-group">
							<input class="form-control variant-weight" type="text" placeholder="Grams" name="product_weight" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php echo $product['product_weight']; ?>" disabled>
							</div>
						</td>
							
							
						<td>
							<div class="input-group">
								<input type="text" name="product_price" class="form-control product_price" value="<?php echo $product['product_price']+150; ?>">
								<span class="input-group-addon">USD</span>
							</div>
						</td>
						<td>
							<!-- <span class="variants-table__column--positive">150</span>-->
							<div class="input-group">
							 <input type="text" style="color:#0083c4" class="form-control" value="$150" disabled="">
							 <span class="input-group-addon">USD</span>
							</div>
						</td>
						<td>
							<div class="input-group">
								<input type="text" name="product_compare_price" class="form-control product_compare_price" value="<?php echo $product['product_compare_price']; ?>">
								<span class="input-group-addon">USD</span>
							</div>
						</td>
						<td style="display: none;">
							<span class="input-group" style="width: 150px;">
								<input type="text" class="form-control">
								<span class="input-group-addon">kg</span>
							</span>
						</td>
						<td><input type="text" class="form-control product_inventory" value="<?php echo $product['product_stock']; ?>" disabled></td>
					</tr>								
						<?php		
							} ?>
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
						</tbody>
					</table>
				</div>
			</div>
			
			<!---MY_LIST_Images_TAB-->
			
			<div class="my-list-images-tab" id="tabs-4">
				<div class="my-list-images-inner">  <!---->
				<div class="image-selector">
		<?php if( count($product['product_images']) > 0 ){ 
	          	echo "<ul class='image-selector__draggable'>";
					foreach($product['product_images'] as $productImage){
					$filename = $productImage['src'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					 if($ext=="mp4" || $ext=="3gp" || $ext=="mov"){ 
					 }else{ ?>
						   <li class="image-selector__thumbnail">
							   <div class="product-image product-image--is-selected">
								<div class="product-image__overlay">
								 <div class="product-image__actions">
								  <!----><button class="image-selector__product-image-button"><svg class="icon-base"></svg></button>
								 </div>
								</div>
								<div class="product-image__corner-marker">
								 <span class="product-image__status-icon"></span>
								</div>
							
								<img src="<?php echo base_url();?>uploads/product<?php echo $product['product_id'];?>/<?php echo $productImage['src']; ?>" alt="product image" class="product-image__img">
							
							   </div>
						  </li>
								<?php }
								} 
		echo "</ul>";
			}
		?>					
		</div>		
				</div>
			</div>
		</div>
		<?php //echo form_close(); ?>
		</div>
		<?php } 
		} else{ ?>
			<h2> No product in the list. </h2>
		<?php }?>
		</form>
		
		</div>
		
		<div class="store-listed-products-metadata" style="display:none;">
		<?php if(!empty($linked_products)){?>
	 	 <table class="table-condensed variants-table">
		   <thead>
			  <tr>
				 <th>Image</th>
				 <th>Store Name</th>
				 <th>Your Product</th>
				 <th>Wholesale Price</th>
				 <th>Action</th>
				<!-- <th>Weight</th>  -->
				 <th class="mylist-At-Price"> Your Price </th>
			  </tr>
		   </thead>
			<tbody id="employeeList">
			<?php 
			/*
			//echo "<pre>";print_r($linked_products);die("called"); 
			foreach($linked_products as $store){?>
				<tr class="single-variant-row row-enabled">
				 <td><div class="img-container"> 
				 <?php //print_r($store);
				 if(!empty($store['images'][0]['src'])){ ?>
					<img src="<?=$store['images'][0]['src']?>"> 
				<?php }?>			  
				 </div></td>		 
				 <td><?php 
				 $session_data = $this->session->userdata('login');
		         $shop_name = $session_data->shop;
				 echo $shop_name;?></td>
				 <td><?=$store['title']?></td>
				 <td><?=$store['variants'][0]['price']?></td>				 
				 <td> <span class="store-connection"><a href="javascript:void(0);" id="unlink-product<?=$store['id']?>" class="remove-product-from-store"> Connected </a> </span></td>				 
				 <td><?=$store['variants'][0]['weight']?></td>		 
				 <td><span class="variants-table__column--positive"><?=$store['variants'][0]['price']?></span> </td>
				</tr>
				
				
			<?php }
			
						foreach($linked_products as $store){?>
				<tr class="single-variant-row row-enabled">
				 <td><div class="img-container"> <img src="<?=base_url()?>uploads/product<?=$store['product_id']?>/<?=$store['src']?>"> </div></td>		 
				 <td><?=$store['shop_name']?></td>
				 <td><?=$store['p_title']?></td>
				 <td><?=$store['product_price']?></td>				 
				 <td> <span class="store-connection"><a href="javascript:void(0);" id="unlink-product<?=$store['shopify_product_id']?>" class="remove-product-from-store"> Connected </a> </span></td>				 
				 <td><?=$store['product_weight']?></td>		 
				 <td><span class="variants-table__column--positive"><?=$store['product_price']?></span> </td>
				</tr>
			<?php } */?>
			
			
				</tbody>
			</table>
			<img id="loading-image" style="display:none;" src="<?=base_url('assets/images')?>/load_2.gif">		
<div id='pagination'></div>			
		<?php }else{?>
		<h2> No product in the list. </h2> 
		<?php }?>
	       </div>
	</div>	
</div>


<div class="overlay" style="display:none;"></div>
<!-- Delete popup message -->
<div class="overlay" style="display:none;"></div>
<div id="delete_category" class="common_popup"  title="Disconnect Product" style="display:none;">
<img id="link-loading-image" style="width:20px;display:none;" src="<?=base_url('assets/images/')?>/load_2.gif">
  <p><span class="ui-icon ui-icon-alert"></span>Are you really want to disconnect this product?</p>
  </div>
  <div id="delete_category_confirm" class="common_popup delete_popup" title="Disconnect Product" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Product has been deleted successfull</p>
</div>




<script>

/************** Store connected products Js code ************/
     $(document).ready(function() {
	   createPagination(0);
	    $('#pagination').on('click','a',function(e){
			e.preventDefault(); 
			var pageNum = $(this).attr('data-ci-pagination-page');
			createPagination(pageNum);
	  });

 function createPagination(pageNum){
		  	$("#loading-image").show();
		<?php	
		$session_data = $this->session->userdata('login');
		$shop_name = "$session_data->shop";	
		?>
			var shop_name ="<?=$shop_name?>";		
			$.ajax({
				url: '<?=base_url()?>sourcing/loadData/'+pageNum,
				type: 'get',
				data:{shop_name:shop_name},
				dataType: 'json',
				success: function(responseData){
					console.log(responseData);
					$("#loading-image").hide();
					$('#pagination').html(responseData.pagination);
					paginationData(responseData.empData);
				}
			});
	   }
	   
	   function paginationData(data) {
		   <?php	
		$session_data = $this->session->userdata('login');
		$store_url ="$session_data->shop";	
		?>
		var store_url ="<?=$store_url?>";
		$('#employeeList').empty();	        	
		for(emp in data){ 
			var img_url=data[emp].images;
			var notes = data[emp].body_html;
			var regX = /(<([^>]+)>)/ig;
			var note =  notes.replace(regX, ""); 
			var empRow ="<tr class='single-variant-row row-enabled'>";            
			if(img_url==""){ 
			empRow += "<td><div class='img-container'> <img src='<?=base_url('assets/images')?>/default-placeholder.png'></div></td>";
			}else{
			empRow += "<td><div class='img-container'><img src="+data[emp].images[0].src+"></div></td>"; 	
			}
			empRow +="<td>"+store_url+"</td>"; 
			empRow +="<td class='your-product'>"+data[emp].title+"</td>";
			empRow +="<td> $"+ data[emp].wholesale.wholesale_price +"</td>";
			empRow +="<td> <span class='store-connection'><a href='javascript:void(0);' class='remove-product-from-store' id='unlink-product"+data[emp].id+"'>Connected</a></span></td>";
			//empRow +="<td>"+data[emp].variants[0].weight+" Gram </td>";
			empRow +="<td> $"+data[emp].variants[0].price+"</td>";
			$('#employeeList').append(empRow);
		}		  
    ///  $("#recordCount").show();
	}
	 });
/**************************** Show/hide the connected and listed products **************/
	/* $(document).ready(function(){
		$( ".active-site-product" ).trigger( "click" );
	}); */

	setTimeout(function() {
			$(".disconnected-msg").text("") 
	}, 3000);	

  	$(".setDfault-prices").click(function (e){

	     var setDfault_prices_id = $(this).attr('id').match(/\d+/);
		 var default_price = $(this).text().match(/\d+/);
	//	$(this).parents().eq(5).addClass('All-variants-Metadata');	
		$( "#bulk-suggested-price"+setDfault_prices_id+" .product_price").each(function() {
		  $( this ).val(default_price);
		});
		
		$( "#bulk-suggested-price"+setDfault_prices_id+" .product_compare_price" ).each(function() {
		  $( this ).val(default_price);
		});	
		
	 }); 
	
	$("#store-listed-products").click(function (e) {
		$("#site-listed-products").removeClass("active-site-product");
		$(this).addClass("active-site-product");		
		$(".My-List-Page1").hide();
		$(".store-listed-products-metadata").show();
	});

    $("#site-listed-products").click(function (e) {
		$("#store-listed-products").removeClass("active-site-product");
		$(this).addClass("active-site-product");		
		$(".store-listed-products-metadata").hide();
		$(".My-List-Page1").show();
	});
	
	/************************************* Remove product from Store **************************/
	//$(".remove-product-from-store").click(function (e) { 
	$("body").on("click", ".remove-product-from-store", function(e){
	    var product_id = $(this).attr('id').match(/\d+/); 
        $("#delete_category").dialog({
		  modal: true,
		  buttons: { 
			"Confirm": function() {
			 $("#link-loading-image").show();	
				$.ajax({
					type: "POST",
					url: "<?=base_url()?>/Import/remove_linked_product",	
					data: {product_id: product_id},
					success: function(result){	
                      $("#delete_category").dialog( "close" );					
	                   $("#link-loading-image").hide();
					   window.location.replace('<?=base_url('user/mylist'); ?>');
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

	/****************************** End Here **********************************/
	
	
	$( "body" ).load(function() {
		if ($('.main-tab-div').length < 1) {  
		   $(".noproducts").css("display","block");
		} 
	});

$(".remove-product").click(function (e) {	
	var product_id = $(this).attr('id').match(/\d+/);				
	$.ajax({
	type: "POST",
	url: "<?php echo base_url('Mylist/remove_product');?>",												
	data: {product_id: product_id},
	success: function (res) {
		$("#tabs"+product_id).remove();	
		 if ($('.main-tab-div').length < 1) {  
             location.reload(true);	
            window.location.replace("<?php echo base_url('user/mylist');?>");			 
        }      
		 
		},	
	error: function(){
		console.log('error in update page setting.');
		}	
		})
		
    });	
	

  $("#bulk-delete-action").click(function (e) {
	var product_id = [];
	$(".selected").each(function(){
	var id = $(this).find('input[type="checkbox"]').val();
	product_id.push(id);
	});

	$.ajax({
	type: "POST",
	url: "<?php echo base_url('Mylist/remove_allproducts');?>",												
	data: {product_id: product_id},
	success: function (res) {
            location.reload(true);	
            window.location.replace("<?php echo base_url('user/mylist');?>");			 
	},
	error: function(){
		console.log('error in update page setting.');
		}	
		}) 		
    });	
	
	
	function getSelectedCheckboxCount() {
		return $('input.import-products').filter(":checked").length;
	}
	function getAllProductsCount() {
		return $('input.import-products').length;
	}	
$('.import-products:checkbox').on("change", function(e){
	
		if($(this).prop("checked")){

				$('.toggle-bulk-actions').show();
				$(this).closest('.main-tab-div').addClass('selected');
				var count = getSelectedCheckboxCount();
				$('.selected-count').text(count+' Products selected');
				$('#import-button').removeAttr("disabled");
			} 
			else {
			
				$(this).closest('.main-tab-div').removeClass('selected');				
				var count = getSelectedCheckboxCount();
				if(count > 0){
					$('#import-button').removeAttr("disabled");
					$('.toggle-bulk-actions').show();	
					var count = getSelectedCheckboxCount();
					$('.selected-count').text(count+' Products selected');	
					
				} else {
					  $('#import-button').prop("disabled", true);
					$('#bulk-selector:checkbox').prop('checked', false);
					var count = getAllProductsCount();
					$('.selected-count').text('0 Products selected');
				}
				
			}
		
	});	
	
$('#bulk-selector:checkbox').on("change", function(e){	

			if($(this).prop("checked")){
				$('#import-button').removeAttr("disabled");
				$('input:checkbox.variant-selector').prop('checked',true);
				$('.toggle-bulk-actions').show();
				
				 $('.main-tab-div').each(function(i){
					$(this).addClass('selected');
				});
				var count = getSelectedCheckboxCount();
				$('.selected-count').text(count+' Products selected');
			} 
			else {
				 $('#import-button').prop("disabled", true);
				$('.main-tab-div').each(function(i){
					$(this).removeClass('selected');
				});
				$('input:checkbox.variant-selector').prop('checked', false);
			//	$('.toggle-bulk-actions').hide();
				var count = getAllProductsCount();
				//$('.selected-count').text('Showing '+count+' products');
				$('.selected-count').text('0 Products selected');
			}	
	
});		


		$('.bulk-variant-selector:checkbox').on("change", function(e){				
			if($(this).prop("checked")){				
				var table= $(e.target).closest('table');
				$('tbody tr ',table).removeClass('row-disabled'); 
				$('tbody tr ',table).addClass('row-enabled'); 
				$('tbody td input:checkbox',table).prop('checked',this.checked);
			} 
			else {
				var table= $(e.target).closest('table');
				$('tbody tr',table).not(':first').removeClass('row-enabled');
				$('tbody tr ',table).not(':first').addClass('row-disabled');
				$('td input:checkbox',table).not(':first').prop('checked',false);
			}	

		});

$('.single-variant-row input:checkbox').on("change", function(e){
			var table= $(e.target).closest('table');
			var selectedCheckbox = $('tbody tr input:checkbox',table).filter(":checked").length;			
			if($(this).prop("checked")){				
				$(this).closest("tr").addClass('row-enabled');		
			} else {
				
					 if(selectedCheckbox > 0){					 
					 $(this).closest("tr").removeClass('row-enabled');
					} 
					else {				
						$(this).prop('checked', true);
					}		
			}	
});


$('.no-variant-row input:checkbox').on("change", function(e){			
		$(this).prop('checked', true);	
});

    function getValueUsingClass(){	
		var chkArray = [];
		$(".import-products:checked").each(function() {
			chkArray.push($(this).val());
		});	
		var selected;
		selected = chkArray.join(',');	
    }

  $('#import-button').on("click", function(e){
	getValueUsingClass();
	var getdata = [];
	var numeric = [];
	$(".selected").each(function(){
		var wholesale = $(this).find('.pricewhole span').text().match(/\d+/);
	    var val = $(this).find('input[name="title"]').val();
	    var vendor_id = $(this).find('input[name="vendor_id"]').val();
	    var collect= $(this).find('input[name="colections"]').val();
	    var val_type= $(this).find('select.custom-type option:selected ').html();		
		var tag=$(this).find('input[name="tags"]').val();
		var id = $(this).find('input[type="checkbox"]').val();
	    var mylist_desc_id =  $(this).find('.ckeditor').attr('id');	
	    var objEditor1 = CKEDITOR.instances[mylist_desc_id];
		var desc = objEditor1.getData();
		var variantAll = []; 
		$(this).find('.row-enabled').each(function () {
			var variant_order_id = $(this).attr('data-id').match(/\d+/);
			var variant_image = $(this).find('img').attr('src');
			var variant_id = $(this).find('input.vid').val();
			var variant_sku = $(this).find('input.product-sku').val();
			var variant_option1 = $(this).find('input.product-option1').val();
			var variant_option2 = $(this).find('input.product-option2').val();
			var variant_option3 = $(this).find('input.product-option3').val();
			var variant_price = $(this).find('input.product_price').val();
			var variant_weight = $(this).find('input.variant-weight').val();
			var variant_comparePrice = $(this).find('input.product_compare_price').val();
			var variant_product_inventory = $(this).find('input.product_inventory').val();		
			variantAll.push({'variant_id':variant_id,'variant_order_id':variant_order_id,'variant_image':variant_image,'variant_sku':variant_sku,'variant_option1':variant_option1 ,'variant_option2':variant_option2 ,'variant_option3':variant_option3,'variant_price':variant_price,'variant_weight':variant_weight,'variant_comparePrice':variant_comparePrice,'variant_product_inventory':variant_product_inventory});
		})
		var productImages = [];
		$(this).find('.product-image--is-selected img').each(function () {			
			var imagesSource = $(this).attr('src');
			productImages.push({'src':imagesSource});	
		})
		
		numeric.push({'id':id,'wholesale':wholesale,'title':val,'vendor_id':vendor_id,'collection':collect ,'type':val_type ,'tags':tag.replace(/\s+/g, " ").trim(),'description':desc,'variants':variantAll,'images':productImages});
		var desc =  $(this).find('tr[name="description"]').val();
			
	});
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('import-products');?>",
		data: {product_data: numeric},
		success: function (res) {
			value = res.response;
			// console.log(value);
			// console.log(res);
			if (res !== ""){
				$('.selected').each( function(){
					pid = $(this).attr('data-id');
					for (i2=0; i2<value.length; i2++){
						if(value[i2] == pid){
							$(this).removeClass('selected');
							$('.error').html('Product with this name already exists on shopify store!!').fadeIn();
							$(".error").show();
						}
					}
				});
			}
			
			var divLength = $("div.selected").length;
			if(divLength > 0){
				$("div.selected").remove();
				$('.sucess').html('Product(s) imported successfully !!').fadeIn();
				$(".sucess").show();
			}
			
			setTimeout(function() {	
				$(".sucess").hide();					
			}, 5000);
			var products = $('.main-tab-div').length;
			var count = getAllProductsCount();
			if(count > 0 ){
				$('.selected-count').text('Showing '+count+' products');
			} else {
				$('.bulk-actions').remove();
				$( "<h2> No product in the list. </h2>" ).insertAfter( ".successmsg" );
			}				
		},beforeSend: function(){
				$('#loader').html("<center><img class='loading-image' src='<?php echo base_url('images/loading.gif'); ?>' /></center>");
				$('#loader').addClass('loader');
				$('#loader').show();
		},complete: function(){
				$('.main-tab-div').addClass('selected');
				$('#loader').hide();
		},error: function(){						
			console.log('error in update page setting.');					
		}	
		}); 
	});

		//////// image tab //////////
		/* $('.product-image__overlay').on("click", function(e){	
				$(this).parent().toggleClass("product-image--is-selected");	
		}); */
		$('.product-image').on("click", function(e){	
				$(this).toggleClass("product-image--is-selected");	
		});

           /************ Save myList product Data *****************/
		   
		   
		/******** Mylist Title tab data *******************/
		
	  /*  $('.title-save-links a').on("click", function(e){	
				var product_id = $(this).attr('data-id');
				var val = $(this).parents('.tabs-data-fourth');
				var product_title = $(val).find('input[name="title"]').val();	
				var uid = $(val).find('input[name="uid"]').val();	
				var my_product_id = $(val).find('input[name="my_product_id"]').val();	
				var colections = $(val).find('input[name="colections"]').val();	
				var product_category = $(val).find('select[name="product_category"]').val();				
				var product_type = $("select[name='product_category'] option:selected").html();

				var tags = $(val).find('input[name="tags"]').val();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('Mylist/save_Mylist_data');?>",	 											
					data: {product_title: product_title,user_id: uid,product_type: product_type,my_product_id: my_product_id,colections: colections,product_category: product_category,tags: tags},
					success: function (res) { 
                   $('#data-added-sucessfully').show();					
                    setTimeout(function(){ $('#data-added-sucessfully').hide();}, 2000);			
					},
					error: function(){
					  console.log('error in update page setting.');
					}	
				})
			}); */ 
		/******** Mylist  Description tab data *******************/
		
	  /*  $('.description-save-links a.save-product_descriptions').on("click", function(e){	
			
			var val = $(this).parents('.list-description-content');	
            var mylist_desc_id =  $(val).find('.ckeditor').attr('id');
			var objEditor1 = CKEDITOR.instances[mylist_desc_id];
			var desc = objEditor1.getData();
			var product_id = $(val).find('.ckeditor').attr('data-id').match(/\d+/);	
			$.ajax({
					type: "POST",
					url: "<?php echo base_url('Mylist/save_Mylist_des_data');?>",	 											
					data: {product_id: product_id,desc: desc},
					success: function (res) {
					 $('#data-added-sucessfully').show();					
                    setTimeout(function(){ $('#data-added-sucessfully').hide();}, 2000);			 
					},
					error: function(){
					  console.log('error in update page setting.');
					}	
				}) 
			});  */
		/******** Mylist  Varients tab data *******************/
		
	  /*  $('.variants-save-links a').on("click", function(e){	
			
			var val = $(this).parents('.my-list-variants-inner');	
			var numeric = [];	
            $(val).find('table.variants-table tbody tr.single-variant-row').each(function () {
				
				var pro_ID = $(this).find('input.pro_ID').attr('id').match(/\d+/);	
				var variant_id = $(this).find('input.vid').val();	
				var variant_price = $(this).find('input.product_price').val();
				var variant_comparePrice = $(this).find('input.product_compare_price').val();
				numeric.push({'variant_id':variant_id,'product_id':pro_ID,'variant_price':variant_price,'variant_comparePrice':variant_comparePrice
				});
	        });
			$.ajax({
					type: "POST",
					url: "<?php echo base_url('Mylist/save_Mylist_verient_data');?>",	 											
					data: {product_data: numeric},
					success: function (res) {	
					$('#data-added-sucessfully').show();					
					setTimeout(function(){ $('#data-added-sucessfully').hide();}, 2000);						
					},
					error: function(){
					  console.log('error in update page setting.');
					}	
				}) 
			});  */
			
			
			/*==================================================================*/
			
		$('.save_mylist-pagedata').on("click", function(e){
	
		var savedata = $(this).parents('.main-tab-div').addClass("save-selected-data");
		var getdata = [];
		var numeric = [];
	    var maindiv = $(this).parents('.save-selected-data');
	    var val = $(maindiv).find('input[name="title"]').val();
	    var collect= $(maindiv).find('input[name="colections"]').val();
	    var val_type= $(maindiv).find('select.custom-type option:selected ').html();		
	    var category= $(maindiv).find('select.custom-type option:selected ').val();		
		var tag=$(maindiv).find('input[name="tags"]').val();
		var id = $(maindiv).find('input[type="checkbox"]').val();
	    var mylist_desc_id =  $(maindiv).find('.ckeditor').attr('id');	
	    var objEditor1 = CKEDITOR.instances[mylist_desc_id];
		var desc = objEditor1.getData(); 
        var variants_table =  $(this).parents('.save-selected-data').find('.variants-table tr.single-variant-row');
		var variantAll = []; 
		$(variants_table).each(function () {
		  var variant_id = $(this).find('input.vid').val(); 		
		  var variant_price = $(this).find('input.product_price').val();
		  var variant_comparePrice = $(this).find('input.product_compare_price').val();	
		  variantAll.push({'variant_id':variant_id,'variant_price':variant_price,'variant_comparePrice':variant_comparePrice
		  });
		});		
		
		numeric.push({'id':id,'title':val,'collection':collect ,'type':val_type ,'tags':tag.replace(/\s+/g, " ").trim(),
		'description':desc,'variants':variantAll});	
		$.ajax({
				type: "POST",
				url: "<?php echo base_url('Mylist/save_Mylist_data');?>",
				data: {product_data: numeric},
				success: function (res) {
					value = res.response;						
				},
				error: function(){						
					console.log('error in update page setting.');					
				}	
			}); 
		});


			
		   /*********************** End Here ***************************/
 </script>