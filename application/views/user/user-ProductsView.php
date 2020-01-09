<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$uid = $this->session->userdata('login')->id;
?>	
	<div class="product-list-body-part">
		<div class="container">
			<div class="product-list-top">
				<div class="side-bar-top-header">
					<div>
						<span>Search for products</span>
					</div>
				</div>
				<div class="side-bar-top-body">
					<div class="input-block">
						<label for="keywords" class="-visually-hidden">Keywords</label>
						<div class="input-group">
							<input placeholder="Enter keywords..." name="keywords"  id="keywords" type="text" value="<?php if(isset($_GET['keywords'])) { echo $_GET['keywords']; } ?>">
							<div class="dropdown">
								<span>
									<fieldset>
									<select name="product_category" id="category_name">
									<option value="">Search products</option>
									<?php foreach($product_categories as $product_category){?>						
									<option value="<?php echo $product_category['id'];?>"><?php echo ucfirst($product_category['category_name']);?></option>
								<?php }?>
								</select>
								</fieldset>
								</span>
								
							</div>
						</div>
						
					</div>
					<button class="btn btn-primary fixed-width" id="users_products_list">Search</button>
				</div>
				
			</div>
			<div class="product-list-bottom">
		<div id="site-product-listing-page">
			<div class="sortbar-latest-product-main">
				<?php if(isset($cat_name) && $cat_name!=""){?>
						<h2 class="listing-content-heading"><?php echo ucfirst($cat_name[0]['category_name']);?> products</h2>
					<?php } else if($all_prdct == "prdcts"){  ?>					
						<h3 class="listing-content-heading">All products</h3>
					<?php }	else{ ?>
						<h3 class="listing-content-heading">Best Sellers</h3>
					<?php }?>
						<div class="listing-content-top">
						
						<fieldset>
						
							<select name="products_sorting" id="products_sorting">
								<option id="filter=date" value="DESC">Newest items first</option>
								<option id="filter=product_price" value="ASC"> Sort by price:low to high </option>
								<option id="filter=product_price" value="DESC"> Sort by price:high to low </option>
								<option id="filter=product_title" value="ASC"> Sort by name:A to Z </option>
								<option id="filter=product_title" value="DESC"> Sort by name:Z to A </option>
							</select>
							<span class="sort-by">Sort by:</span>
					</fieldset>
					</div>
			</div>
			<!-- <div class="container"> -->
				<div class="product-list-category-main">
				<div class="product-listing-sidebar">
					
					
					<div class="side-bar-bottom">					
						<h3 class="side-bar-category_name">Categories</h3>					
						<ul class="side-bar-bottom-nav" id="sidebar_categories">
							<li class="<?php if(isset($active_category) && $active_category == 0) echo 'active'; ?>">
								<a href="<?php echo base_url('user/all-products')?>" id="">All products</a>
							</li>							
							<?php foreach($categories as $cate){ ?>
							<li class="<?php if(isset($active_category) && $active_category == $cate->id) echo 'active'; ?>">
								<a href="javascript:void(0);" id="<?php echo $cate->id ; ?>"><?php echo $cate->category_name ; ?></a>
							</li>
							<?php } ?>
							<li class="<?php if(isset($sourced) && $sourced == "sourced") echo 'active'; ?>">
								<a href="<?php echo base_url('user/sourced-products')?>" id="">Sourced Products</a>
							</li>							
						</ul>
					</div>
				</div>
				<div class="product-listing-content">					
					<div class="success_msg">
					</div>					
					<div class="listing-content-bottom">
					 <?php 
					 if (!empty($products)){
					 foreach($products as $product){ ?>
						<div class="listing-content-product">
							<div class="content-product-image">
					<?php	
						$k=1;
					    foreach($productsImg as $img){
							if($img->product_id == $product->id && $k < 2 ){   
							
					?>						
				<a href="<?php echo base_url('product/product-details/'.$product->id);?>">
				<?php
					
				 $file_exist ="uploads/product".$product->id.'/thumb_'.$img->src; 
				 if (file_exists($file_exist) == true) { ?>
				<img src="<?php echo base_url();?>uploads/product<?php echo $product->id;?>/thumb_<?php echo $img->src; ?>">
					   <?php }else{?>
				<img class="img_withoutthumbs" src="<?php echo base_url();?>uploads/product<?php echo $product->id;?>/<?php echo $img->src; ?>">						 
					 <?php  }  ?>
						</a>
						<?php 
							$k++;
							}	
					}

							?>
							</div>
							<div class="content-product-image-text">
								<a href="<?php echo base_url("product/product-details/$product->id");?>">
								<h5 class="content-product-heading"><?php echo $product->product_title ; ?></h5></a>
						<a href="<?php echo base_url("product/product-details/$product->id");?>">
						<div class="content-product-price">
						<?php if(!empty($product->wholesale_price)){?>
							<span class="product-price">Wholesale price:</span>
							<span class="price"><?php  ?>$<?php echo $product->wholesale_price ;?> 
							</span>
							<?php }?>
						</div>
						</a>
						
						<a href="<?php echo base_url("product/product-details/$product->id");?>">
						<div class="content-product-price">
							<span class="product-price">Suggested Price: </span>
							<span class="price"><?php if(!empty($product->default_variants_price)){ ?>
							$<?php echo $product->default_variants_price ; } ?></span>
						</div>
						</a>
						
						
								<div class="added-remove-links">
								<?php 
							$list_prod_ids = array_map('current', $p_ids);
							if(in_array($product->id, $list_prod_ids)){ 
							$display ="display:block";
							$addtolist = "display:none";
							}else{ 
							$display ="display:none";
							$addtolist = "display:block";
							}
							?>
									<div id="whis_<?php echo $product->id;?>" style="<?php echo $display;?>">
										<a class="added-to-list">Added</a>
										<a id="remove_<?php echo $product->id;?>" href="javaScript:void(0);" class="remove-product ">Remove</a>									
									</div>
								
									<a id="p_<?php echo $product->id;?>" style="<?php echo $addtolist;?>" class="add-to-list" href="javaScript:void(0);" >Add to list</a>
									</div>						
							</div>
						</div>
					 <?php 					 
					 }?>

				 <div class="supplier_pagination"><?php echo (isset($links) ? $links : ''); ?></div>
            </div>
					<?php }else{
						 echo "<h2> There are no products in this category. </h2>";
						 
					 } ?>					
					</div>
				</div>
			 </div> 
		</div>
	</div>		
		</div>
	</div>
	
	
<script>
	$(".add-to-list").click(function (e) { 
		var product_id = $(this).attr('id').match(/\d+/);
				$.ajax({
						type: "POST",
						url: "<?php echo base_url('Mylist/AddToList');?>",						
						data: {product_id: product_id},
						success: function (res) {							
							$("#p_"+product_id).css("display","none");
							$("#whis_"+product_id).css("display","block");
						},
					error: function(){
						console.log('error in update page setting.');
					}
					})
					
    });
	
	$(".remove-product").click(function (e) { 
	     
		var product_id = $(this).attr('id').match(/\d+/);
				$.ajax({
						type: "POST",
						url: "<?php echo base_url('Mylist/remove_product');?>",						
						data: {product_id: product_id},
						success: function (res) {							
							$("#whis_"+product_id).css("display","none");
							$("#p_"+product_id).css("display","block");
						},
					error: function(){
						console.log('error in update page setting.');
					}
					})
					
    });	
</script>