<div class="inner-pages">
	<div class="sourcing-view-detail-page">
		<div class="container">
			<div class="sourcing-viewdetail-header">
				<p class="pull-left">Sourcing Style : Store's</p>
				<p class="ng-binding">Sourcing ID : <?php echo $results[0]['id'];?></p>
			</div>
			<div class="sourcing-manager-box">
				<div class="sourcing-name-date">
					<div class="sourcing-user-name">
						<p>S</p>
						<?php
						$user_name = $this->session->userdata('login')->username;  ?>
						<span class="manager-span">User：</span>
						<span class="name-span"><?php echo $user_name; ?></span>
					</div>
					<div class="sourcing-user-date">
						<span id="sdc-time" class="manager-time"><?php echo $results[0]['created_date']; ?></span>
					</div>
				</div>
				<div class="sourcing-manager-detail">
					<ul>
						<li class="sourcing-p-name">
							<span class="gray-span detail-span1">Product Name:</span>
							<span class="light-span"><?php echo $results[0]['product_title']; ?></span>
						</li>
						<li class="sourcing-shop-name">
							<span class="gray-span">Shop Name:</span>
							<span class="light-span"><?php echo $results[0]['store_url']; ?></span>
						</li>
						<li class="sourcing-target-name">
							<div class="sourcing-target-left">
								<span class="gray-span">Target Price:</span>
								<span class="light-span"><?php echo $results[0]['product_price']; ?></span>
							</div>
							<div class="sourcing-target-right">
								<span class="gray-span">Included Shipping to Main Country：</span>
								<span class="light-span"><?php echo $results[0]['country']; ?></span>
							</div>
						</li>
						<li class="sourcing-url-name">
							<span class="gray-span">Sourcing URL:</span>
							<span class="light-span"><?php echo $results[0]['sourcing_url']; ?></span>
							
						</li>
						<li class="sourcing-image-li">
							<span class="gray-span">Images:</span>
							<div class="sourcing-image-li-img">
								<ul>
									<li><?php									
  								$myString   = $results[0]['sourcing_image'];  							
								$sourcing_image = explode(',', $myString);
								//echo "<pre>";print_r($sourcing_image);echo "</pre>";	
									/* if(empty($results[0]['sourcing_image'])){?>
										<img class="first-img" src="<?php echo $results[0]['sourcing_image_url'];?>">
								  <?php }else */
								    if(empty($sourcing_image[0])){}else{
									  foreach($sourcing_image as $images){
									  ?>
									  <img class="first-img" src="<?php echo base_url('uploads/sourcing/'.ltrim($images))?>">
									  <?php }
									  }?>
								</li>
								<li>
								<?php 
								$image_meta = $results[0]['all_product_images'];
								if($image_meta!=""){
									$image_meta = explode(',', $image_meta);
									 foreach($image_meta as $images_metas){ ?>
									 <img class="first-img" src="<?php echo $images_metas;?>">
									<?php }
								}
								?>
								</li>
								</ul>
							</div>
						</li>
						<li class="sourcing-view-descr">
							<span class="gray-span">Description:</span>
							<span class="light-span detail-span2 ng-binding"><?php echo $results[0]['note']; ?></span>
							
						</li>
					</ul>
				</div>
			</div>
			<div class="manager-box-down">
				<div class="sourcing-status">
				 
		 <?php
		 
		  $status = $results[0]['status'];
		  
		  if($status==0){
			   $product_status = "On Sourcing";
			   $msg ="Thanks for post a sourcing request to us, we are doing the research for this product, will let you know once we have any updates. Please be patient!";
		  }elseif($status==1){
			   $product_status = "Sourcing Success";
			   $msg ="Congratulation, we got your expected product. We can start the dropshipping of this product now, you can also contact our representative if you have any questions about this product.";
		  }else{
			   $product_status = "Sourcing Fail";
			   $msg ="Unfortunately, we had tried our best to source the product you interested, but failed. You can either post a new sourcing request or source similar ones from our marketplace.";
		  }
		 
		 ?>
		<span>Sourcing Status</span>
				</div>
				<div class="sourcing-status-detail">
					<h3 class="center-block"><?php echo $product_status;?></h3>
					<h4 class="center-block"><?php echo $msg;?></h4>
				</div>
			</div>
		</div>		
	</div>
</div>