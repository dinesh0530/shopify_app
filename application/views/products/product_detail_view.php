 <?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link href="<?php echo base_url();?>css/style.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo base_url();?>css/glightbox.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/glightbox.min.js"></script>

<style>
#single-user-product .single-user-product-right .sku {
	margin-top: 0;
	padding: 0;
}
</style>
<script> 
	$(function(){
		$('.product-left-first-image').picEyes();
	});
</script>
<div id="single-user-product">
	<div class="container">
		<div class="single-user-product-top">
			<div class="simpleLens-gallery-container" id="demo-1">
				<div class="single-user-product-left">
					<div id="imgDetail">
						<div class="userProduct-left-first">	
						<?php foreach($product_details as $key => $val) {
								$file = $val['src'];
								 $exts = pathinfo($file, PATHINFO_EXTENSION);
								 if($exts=="mp4"){
									$p_item = $product_details[$key];
									 
									 array_push($product_details, $p_item); 
									 unset($product_details[$key]);
									 break;
								}
							} ?>
			
							
						<?php foreach($product_details as $details){	
							$filename = $details['src'];
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							if($ext=="mp4"){ ?>
								<a href="<?php echo base_url();?>uploads/product<?php echo $details['product_id'];?>/<?php echo $details['src']; ?>" class="glightbox3 gallery_img gal_imgs" id="video">		   
								<img src="<?php echo base_url();?>uploads/video-thumbnail/thumbnail-overlay.png" alt="image">
								</a>
							<?php }else{ ?>
								<a href="<?php echo base_url();?>uploads/product<?php echo $details['product_id'];?>/<?php echo $details['src']; ?>" class="glightbox3 gallery_img gal_imgs" id="slider-img">
									<img src="<?php echo base_url();?>uploads/product<?php echo $details['product_id'];?>/<?php echo $details['src']; ?>" alt="image" class='img-holder gal_src'>
								</a>
							<?php }
						} ?>
				
						</div>	
						<div class="userProduct-left-second">	
							<ul class="box-container">
							
								
							
								<?php 	foreach($product_details as $details){							
								$filename = $details['src'];
								$ext = pathinfo($filename, PATHINFO_EXTENSION);?>
								<?php
								if($ext=="mp4"){?>
									<li class="box video-thumb" data-source='<?php echo base_url();?>uploads/product<?php echo $details['product_id'];?>/<?php echo $details['src']; ?>'>
									  <a href="<?php echo base_url();?>uploads/product<?php echo $details['product_id'];?>/<?php echo $details['src']; ?>" src="<?php echo base_url();?>" class="glightbox4" id="video_tag">
									   
									<img src="<?php echo base_url();?>uploads/video-thumbnail/thumbnail-overlay.png" alt="image" width="100" height="100">
									  </a>
								</li>
								<?php }else{?>	
									<li class="box img-thumb" data-source='<?php echo base_url();?>uploads/product<?php echo $details['product_id'];?>/<?php echo $details['src']; ?>'>
									  <a href="<?php echo base_url();?>uploads/product<?php echo $details['product_id'];?>/<?php echo $details['src']; ?>" class="glightbox4" data-glightbox="width: 900;" id="galery_thumb">
										<img src="<?php echo base_url();?>uploads/product<?php echo $details['product_id'];?>/<?php echo $details['src']; ?>" alt="image" width="100" height="100">
									  </a>
									</li>
								<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>
					
				</div>
				<div class="single-user-product-right">
					<h2> <?php echo $product_details[0]['product_title']; ?></h2>
					<?php if(!empty($product_details[0]['wholesale_price'])){?> 
					<span class="sku"><span> Wholesale price:</span><b> $<?php echo $product_details[0]['wholesale_price']; ?></b> </span>
					 <?php }?>
					<?php if(!empty($product_details[0]['product_sku'])){?> 
					<span class="sku"><span> SKU :</span> <span class="s-bold"> <?php echo $product_details[0]['product_sku']; ?> </span></span> <?php }?>
					<?php if(!empty($product_details[0]['product_wait_time'])){
						 if($product_details[0]['product_wait_time'] > 1){
							 $no_of_days = "Day";
						 }else{
							  $no_of_days = "Days";							  
						 }
						?> 
					<span class="sku"><span>Processing Time:</span> <span class="s-bold">
					<?php echo $product_details[0]['product_wait_time'].' '. $no_of_days ;?></span>					
					</span> <?php }?>					 
					<?php if(!empty($product_details[0]['product_stock'])){?> 
					<span class="sku"> <span>Inventory:</span> <span class="s-bold"><?php echo $product_details[0]['product_stock']; ?></span></span> <?php }?>	
					<div class="single-use-button">						
						<?php 
								$list_prod_ids = array_map('current', $p_ids);
								if(in_array($product_details[0]['product_id'], $list_prod_ids)){ 
								$display ="display:block";
								$addtolist = "display:none";
								}else{ 
								$display ="display:none";
								$addtolist = "display:block";
								}
							?>
						<div id="whis_<?php echo $product_details[0]['product_id'];?>" style="<?php echo $display;?>">
							<span>  Added to my list </span>
							<a class="added-to-list" href="<?php echo site_url();?>user/mylist">Edit on my list</a>
							<a id="remove_<?php echo $product_details[0]['product_id'];?>" href="javaScript:void(0);" class="remove-product ">Remove</a>	

								
						</div>
					
						<a id="p_<?php echo $product_details[0]['product_id'];?>" style="<?php echo $addtolist;?>" class="add-to-list" href="javaScript:void(0);" >Add to list</a>
					</div>		
					<div class="p-details_meta">
						<div class="product-options">
							<div class="product-options__row">
								<span class="key-price">Suggested Price</span>
								<span class="product-options__value1"><!---->
									 <h2 class="product-options__price" id="<?php echo $product_details[0]['product_id']; ?>"><span class="price">$<?php echo $product_details[0]['default_variants_price']; ?></span> </h2>
								</span>	
							</div>						
						<?php if( isset($variant_options->option1_name) && !empty($variant_options->option1_name)){ ?>
							<div class="product-options__row">
								<span class="key-price"><?php echo $variant_options->option1_name;?></span>
								<span class="product-options__value1"><!---->
									<select name="size_options" id="v_option1" class="vartions-optins-data">
										 <?php 
										$size_options_array = $product_variants_options[0]->option1_value; 
										$size_options = explode(",",$size_options_array);
									
										foreach($size_options as $size_option){ ?>	
										<option value="<?php echo $size_option;?>">
											  <?php echo ucfirst($size_option);?></option>
										<?php }?>
									</select>
										 
									</span>	
								</div>		
					<?php }
					if( isset($variant_options->option2_name) && !empty($variant_options->option2_name)){ ?>
							<div class="product-options__row">
									<span class="key-price"><?php echo $variant_options->option2_name;?></span>
									<span class="product-options__value1"><!---->
										 <select name="color_options" id="v_option2" class="vartions-optins-data">												 
										  <?php 
										$color_options_array = $product_variants_options[0]->option2_value; 
										$color_options = explode(",",$color_options_array);
										foreach($color_options as $color_option){ ?>
											  <option value="<?php echo $color_option;?>">
											  <?php echo ucfirst($color_option);?></option>
										<?php }?>										
										 </select>
										 
									</span>	
								</div>		
					<?php }
					if( isset($variant_options->option3_name) && !empty($variant_options->option3_name)){ ?>
							<div class="product-options__row">
									<span class="key-price"><?php echo $variant_options->option3_name;?></span>
									<span class="product-options__value1"><!---->
										 <select name="material_option" id="v_option3" class="vartions-optins-data">
										 <?php 
										$material_options_array = $product_variants_options[0]->option3_value; 
										$material_options = explode(",",$material_options_array);
										foreach($material_options as $material_option){ ?>
											 <option value="<?php echo $material_option;?>">
											 <?php echo ucfirst($material_option);?>
											 </option>
										<?php }?>	
										 </select>												 
									</span>	
								</div>		
					<?php }?>
											
						   <div style="clear:both"></div>
							
						</div>
					</div>
				</div>					
			</div>
		</div>
		<?php if(!empty($product_details[0]['product_desc'])){?>
		<div class="single-user-product-middle">
			<h2 class="product-detaildescription-title">Product Description</h2>
			<p class="product-detaildescription"> <?php echo $product_details[0]['product_desc']; ?></p>
		</div>
	<?php }
		if(count(array_filter($related_product))!= 0){	
			if(count(array_filter($related_product)) > 4){		
			  $slider_class = "popular";
			}else{
			  $slider_class = "simple-popular-slides";
			} ?>
		<div class="single-user-product-bottom">
			<h2>Similar Products </h2>
			<div class="single-user-product-bottom-images <?php echo $slider_class;?>">
				<?php foreach($related_product as $related_products){
					$filename = $related_products['src'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if($ext!="mp4"){?>
				<div class="product-bottom-image item">
				<?php	
				$file_exist ="uploads/product".$related_products['product_id'].'/thumb_'.$related_products['src']; 
				if (file_exists($file_exist) == true) { ?>
				 <a href="<?php echo base_url("product/product-details/".$related_products['product_id']);?>">
				<img src="<?php echo base_url();?>uploads/product<?php echo $related_products['product_id'];?>/thumb_<?php echo $related_products['src']; ?>"> </a>
					 <?php }else{?>
					 <a href="<?php echo base_url("product/product-details/".$related_products['product_id']);?>">
					<img src="<?php echo base_url();?>uploads/product<?php echo $related_products['product_id'];?>/<?php echo $related_products['src']; ?>">	</a>	
					 <?php  }  ?>
					<div class="product-bottom-image-card-body">  
						<h2> <a href="<?php echo base_url("product/product-details/".$related_products['product_id']);?>"><?php echo $related_products['product_title']; ?> </a></h2>
						<span> $ <?php echo $related_products['product_price']; ?> </span>
						<div>
							<?php 
								$list_prod_ids1 = array_map('current', $p_ids);
								if(in_array($related_products['product_id'], $list_prod_ids1)){ 
								$display1 ="display:block";
								$addtolist1 = "display:none";
								}else{ 
								$display1 ="display:none";
								$addtolist1 = "display:block";
								}
							?>
							<div class="added<?php echo $related_products['product_id']; ?>" style="<?php echo $display1;?>">
								<a class="added-to-list" href="<?php echo site_url();?>user/mylist">Edit on my list</a>
							</div> 
							<a id="pro-<?php echo $related_products['product_id'];?>" style="<?php echo $addtolist1;?>" class="rel-add-to-list add<?php echo $related_products['product_id']; ?>" href="javaScript:void(0);" >Add to list</a>
						</div>
					</div>
				</div>	
				<?php } }?>
			</div>
		</div>
		<?php }?>
	</div>
</div>			
<div id="customer_supplier_modal" class="common_popup delete_popup"  title="Add New Suppliers" style="display:none;"></div>
<script src="<?php echo base_url("js/jquery.simpleGallery.js"); ?>"></script>
<script src="<?php echo base_url("js/jquery.simpleLens.js"); ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">

		<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

		<script type="text/javascript">
		 jQuery(document).ready(function($){
			    $('.popular').bxSlider({
				    auto: false,
					autoControls: false,
					pager: true,
					speed: 500,
					slideSelector: 'div.item',
					minSlides: 4,
					maxSlides: 4,
					moveSlides: 2,
					slideWidth: 300
			    });
			 
		 });
		</script>

<script>
	$(document).ready(function(){	
		$( ".product-left-first-image" ).hover(function() {
			var product_id = $(this).attr('id').match(/\d+/);
			$(".simpleLens-lens-image").removeAttr( "id" );
			$(".simpleLens-lens-image").attr("id", "v-slider-image_"+product_id);
		});
		$(document).on('click','.simpleLens-lens-image',function(e){	
			var product_id = $(this).attr('id').match(/\d+/);	  
			$( "#v-left-slider-image_"+ product_id).trigger( "click" );
		});	
		$('#demo-1 .simpleLens-thumbnails-container img').simpleGallery({
		});
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
		$(".rel-add-to-list").click(function (e) { 
			var product_id = $(this).attr('id').match(/\d+/);
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('Mylist/AddToList');?>",						
				data: {product_id: product_id},
				success: function (data) {	
					$(".add"+product_id).hide();
					$(".added"+product_id).show();
				},
				error: function(){
					console.log('error in update page setting.');
				}
			})				
		}); 
		
		
	
	/************* Variants price ********/
	/**** intial variant price *****/
	
	 var v_option1	= $("#v_option1").val();	
	  var v_option2	= $("#v_option2").val();	
	  var v_option3	= $("#v_option3").val();	
	  
      var pid = $(".product-options__price").attr('id');
	  
	  $.ajax({
				type: "POST",
				url: "<?php echo base_url('products/AllProducts/get_variant_price');?>",						
				data: {product_id: pid,option1: v_option1,option2: v_option2,option3: v_option3},
				dataType: "text",
				success: function (result) {					
				
				var obj = jQuery.parseJSON(result);
				console.log(obj);
                var option_price = obj.option_price;	
				
				var varinat_img = obj.option_image;
				var option_image ="<?php echo base_url();?>uploads/product<?php echo $product_details[0]['product_id'];?>/"+obj.option_image;				
				//$(".product-options__price .price").text("$"+option_price);
				if(varinat_img==""){			
				}else{
						$(".simpleLens-big-image").attr("src",option_image);
				}				
				},
				error: function(){
					console.log('error in update page setting.');
				}
			});	
			/************** End Here ***********/
	$(".vartions-optins-data").change(function (e) {
      var v_option1	= $("#v_option1").val();	
	  var v_option2	= $("#v_option2").val();	
	  var v_option3	= $("#v_option3").val();	
	  
      var pid = $(".product-options__price").attr('id');
	  
	  $.ajax({
				type: "POST",
				url: "<?php echo base_url('products/AllProducts/get_variant_price');?>",						
				data: {product_id: pid,option1: v_option1,option2: v_option2,option3: v_option3},
				dataType: "text",
				success: function (result) {					
				
				var obj = jQuery.parseJSON(result);
                var option_price = obj.option_price;
				var varinat_image =obj.option_image;
				var option_image ="<?php echo base_url();?>uploads/product<?php echo $product_details[0]['product_id'];?>/"+obj.option_image;				
				//$(".product-options__price .price").text("$"+option_price);
				
				if(varinat_image==""){
				}else{
				$(".simpleLens-big-image").attr("src",option_image);	
				}				
				},
				error: function(){
					console.log('error in update page setting.');
				}
			})	
			
	}); 
	

 /***************** End Here ****************/
	}); 
	
	
	/* -------Product Image,video Gallery Slider POPUP-------*/
	
	jQuery( document ).ready(function($) {
		 $('.first_slide').css("display", "block");
		 $('.userProduct-left-first a:first').addClass('first_slide');
		 $('.userProduct-left-first #slider-img:first').addClass('first_img');
		 $('#slider-img').removeClass('gal_imgs');
		 $('.gal_imgs').css("display", "none");
		 
		$(".box-container li").mouseover(function() {
			var x = $(this).index();
			$('.userProduct-left-first a').hide().each( function(i, v) {
				if (i == x) {
					$(this).show();   
				}
			});
		});
	});
  
    var lightbox = GLightbox();
    var lightboxDescription = GLightbox({
      selector: 'glightbox2'
    });
    var lightboxVideo = GLightbox({
      selector: 'glightbox3',
      jwplayer: {
        api: 'https://content.jwplatform.com/libraries/QzXs2BlW.js',
        licenseKey: 'imB2/QF0crMqHks7/tAxcTRRjnqA9ZwxWQ2N1A=='
      }
    });
    var lightboxInlineIframe = GLightbox({
      'selector': 'glightbox4'
    });
  
 
	/*----------Popup End Here */
</script>

<style>
.single-user-product-left .bx-wrapper .bx-prev {
  left: calc(50% - 16px) !important;
  top: -2em !important;
  -webkit-transform: rotate(90deg);
  transform: rotate(90deg);
  margin-top: 15px !important;
      margin-left: 8px !important;
}

.single-user-product-left .bx-wrapper .bx-next {
  right: calc(50% - 16px) !important;
  top: calc(100% + 2em) !important;
  -webkit-transform: rotate(90deg);
  transform: rotate(90deg);
      margin-top: -25px !important;
	      margin-right: 6px;
}
.single-user-product-left .bx-wrapper, .bx-viewport {
    height: 512px !important;
}
.single-user-product-left .bx-wrapper .bx-controls-direction a {width: 20px !important; height: 20px !important;

 <!----Proucts popup---->
img {
    cursor:pointer;
}
#video {
    display:none;
}
.video_main {
    display:none;
}
#slider-img.glightbox3.gallery_img.gal_imgs {
display: none !important; }

</style>