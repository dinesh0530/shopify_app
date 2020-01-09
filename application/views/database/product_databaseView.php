<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- all product listing View for admin --->

<div class="inner-pages">

	<section class="listing-section">
		<div class="successmsg" id="sudddddces">
	 <?php echo $this->session->flashdata('delete_cat');  ?>
	</div>	
		<div class="container">				
			<div class="listing-content">
				<div class="main-heading">
					<!-- <a href="">Import</a>
					<a href="">Export</a>
					<a href="">Download Template</a> -->
					<a class='createItem' href="<?php echo site_url('create-product'); ?>">Add new product</a>
				</div>
				<div class="main-container">
				<?php 
				$splr="";
				$ctgry="";
					if($_GET){
						$splr = intval($_GET['supplier']);  
						$ctgry = intval($_GET['category']); 			
					}  ?>
				<?php 
			//	if (!empty($results)){  ?>
					<div class="left-container">
						<div class="doctor-filter">
			    	<?php //echo form_open(uri_string(), array('class' => 'login-form','action' => '/all-products', "method" => "get")); ?>
					<form method ="get" action="<?php echo base_url('all-products'); ?>" class="login-form">
					
				<p class="Supplier">
					<select id="supplier" name="supplier" style="width: 230px; height: 40px;" >
					<option value="" selected> Select supplier</option>
					<?php foreach ($suppliers as $sup) { ?>
					
					<option 
					<?php  if($splr == $sup->id) echo'selected'?>
					value="<?php echo $sup->id; ?>" <?php echo set_select('user', $sup->id, false); ?> ><?php echo ucfirst($sup->name);	
								
					?> </option> 
					<?php } ?>
					</select>
				 </p>
				
						
				<p class="Category">
					<select id="category" name="category" style="width: 230px; height: 40px;" >
					<option value="" selected> Select category</option>
					<?php foreach ($categories as $row) { ?>
					<option 
					<?php  if($ctgry == $row->id) echo'selected'?>
					value="<?php echo $row->id; ?>" <?php echo set_select('user', $row->id, false); ?> ><?php echo ucfirst($row->category_name); ?> </option> 
					<?php } ?>
					</select>
				 </p>
						 
				 <p class="CategorySearch">
			    	<?php echo form_submit(array("value"=>"Search")); ?>
				</p>
					<div class="clear"></div>
				<?php echo form_close(); ?>
				
					    </div>					
				     </div>							
				</div>
				<?php //}?>
				</div>
				<div class="allproducts all-listing">
					<div class="allproducts-inner all-listing-inner">
					<?php if (!empty($results)){  ?>
						<table class="products-list lists">
							<tr>								
								<th>Name</th>								
								<th>Category</th>
								<th>Supplier</th>
								<th>Price</th>
								<th>Stock levels</th>
								<th>Actions</th>
							</tr>
							<?php 
								foreach($results as $product){ ?>
								<tr id="supplier_<?php echo $product->id; ?>">									
									<td class="product-name"><?php echo $product->product_title ; ?></td>
									<td class="product-category">
										<?php
									    $x = false;
										foreach($categories as $category){
											
											if($category->id == $product->product_category)
											{
												echo $category->category_name ;
												 $x=true;
											}											
										} 	
										if(!$x){											
											echo "No category";											
										}
											
										?>
									</td>
									<td class="product-supplier">
										<?php 
										
										//echo "";print_r();die("called");
										$y = false;
										foreach($suppliers as $supplier){
											if($supplier->id == $product->product_supplier){
												echo $supplier->name ;
												$y = true;
												}	
											}
											if(!$y){											
											echo "No Supplier";											
										}							
											
											?>										
											
									</td>
									<td class="product-price"> $<?php echo $product->product_price ; ?></td>
									<td class="product-price">   <?php echo $product->product_stock ; ?></td>
									<?php $user_id = $this->session->userdata('login')->id; 
									$role_id = $this->session->userdata('login')->role_id; 
									if($role_id == '1' || $user_id == $product->product_add_by){ ?>
										<td><a class="edit edit_product" href="<?php echo base_url('product/edit-product/'.$product->id); ?>">Edit</a>
										
									<?php } else { ?>
									<a  title="Edit" class="edit non_edit_product" href="" role-id="<?php echo $role_id; ?>" user-id="<?php echo $product->product_add_by; ?>" logged-userId="<?php echo $user_id; ?>" data-id="<?php echo $product->id ; ?>">Edit</a>
									<?php } ?>
								
									<a title="Delete" style="cursor:pointer" class="delete delete_product" role-id="<?php echo $role_id; ?>" user-id="<?php echo $product->product_add_by; ?>" logged-userId="<?php echo $user_id; ?>" data-id="<?php echo $product->id; ?>">Delete</a></td>
								</tr>
								<?php } ?>
						</table>
						
					</div>
				</div>
				<div class="supplier_pagination"><?php echo (isset($links) ? $links : ''); ?></div>
					<?php } 
						else
						{  ?>
							<div class="all-categories-error">No Product Found</div>
					<?php	}
					?>
            </div>
        </div>
    </section>
</div>

<div class="overlay" style="display:none;"></div>
<!-- Html to Show confirmation msg in popup on click of delete Product-->
<div id="dlt_product" class="common_popup" class="delete_popup" title="Delete product" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Do you really want to delete this product ?</p>
</div>
<!--- <div id="dlt_product_confirm" class="common_popup" class="delete_popup" title="Delete Product" style="display:none;">   
  <p><span class="ui-icon ui-icon-alert"></span>Product Deleted Successfully</p>
</div> -->
<!-- Html to Show msg of delete product authority -->
<div id="no_dlt_product" class="common_popup" title="Delete Product" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Sorry!!! You have no authority to delete this product.</p>
</div>
<!-- Product edit modal -->
<div id="edit-product-modal" class="common_popup" title="Edit Product" style="display:none;"></div>
<!-- Html to Show msg of edit product authority -->
<div id="no_edit_product" class="common_popup" title="Edit Product" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Sorry!!! You have no authority to edit this product.</p>
</div>

<script>
   $(document).ready(function(){
    $("#sudddddces").delay(5000).fadeOut();
   });
   
</script>