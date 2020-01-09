<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!-- all product Grid View for admin --->		
	<div id="site-product-listing-page">
		<div class="container">
			<div class="product-listing-sidebar">
				<div class="side-bar-top">
					<fieldset>
						<select name="product_category" id="category_name">
							<option value="">Search Products</option>
							<option value="2">Camping</option>
							<option value="3">Toys &amp; Kids</option>
							<option value="4">Homeware</option>
							<option value="5">Health and Fitness</option>
							<option value="5">Health and Fitness</option>
						</select>
					</fieldset>
				</div>
				<div class="side-bar-bottom">
					<h3 class="side-bar-category_name">Categories</h3>
					<ul class="side-bar-bottom-nav" id="sidebar_categories">
						<?php foreach($categories as $cate){ ?>
						<li>
							<a href="javascript:void(0);" id="<?php echo $cate->id ; ?>"><?php echo $cate->category_name ; ?></a>
						</li>
						<?php } ?> 
						<li>
							<a href="javascript:void(0);" id="">All Products</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="product-listing-content">
				<div class="listing-content-top">
				<?php if(isset($cat_name) && $cat_name!=""){?>
					<h2 class="listing-content-heading"><?php echo $cat_name[0]['category_name'];?> Products</h2>
				<?php }else{?>
					<h2 class="listing-content-heading">latest Products</h2>
				<?php }?>
					<fieldset>
						<select name="product_category" id="category_name">
							<option value="">Sort-Button</option>
							<option value="2">Camping</option>
							<option value="3">Toys &amp; Kids</option>
							<option value="4">Homeware</option>
							<option value="5">Health and Fitness</option>
						</select>
				`	</fieldset>
				</div>
				<div class="listing-content-bottom">
				
				 <?php foreach($products as $product){ ?>
					<div class="listing-content-product">
						<div class="content-product-image">
						<?php	foreach($productsImg as $img){						
							if($img->product_id == $product['id']  ){	?>						
							<img src="<?php echo base_url();?>uploads/product<?php echo $product['id'];?>/<?php echo $img->src; ?>">							
						<?php }	
						} ?>
						</div>
						<div class="content-product-image-text">
							<h5 class="content-product-heading"><?php echo $product['product_title'] ; ?></h5>
							<div class="content-product-price">
								<span class="product-price">Price</span>
								<span class="price"><?php echo $product['product_price'] ; ?></span>
							</div>
							<a class="add to list" href="#">Add To List</a>
						</div>
					</div>
				 <?php } ?>
					
				</div>
			</div>
		</div>
	</div>


