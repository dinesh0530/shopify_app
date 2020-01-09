<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!---------Site-Slider-Section----->		
		<div id="site-slider-section">
			<div class="main_slider">
				<div>
					<img src="<?php echo base_url("assets/images/slider.jpg")?>" width="100%">
				</div>
				<div>
					<img src="<?php echo base_url("assets/images/slider.jpg")?>" width="100%">
				</div>
				<div>
					<img src="<?php echo base_url("assets/images/slider.jpg")?>" width="100%">
				</div> 
			</div>
			<div class="container">
				<a class="slider-button" href="<?php echo base_url('install'); ?>">Add import App to <img src="<?php echo base_url("assets/images/button-logo.png") ?>"></a>
			</div>
		</div>
<!---------Site-Section-1----->		
		<div id="site-section-1">
			<h3 class="section-heading">
				How does import works
			</h3>
			<div class="section-1-content-area">
				<div class="container">
					<div class="content-area-part">
						<div class="content-area-part-img">
							<img src="<?php echo base_url("assets/images//1.png") ?>">
						</div>
						<div class="content-area-part-text">
							<h4>
								1. Import Products
							</h4>
							<p>
								Lorem ipsum dolor sit consectetur adipiscing elit aliqua.
							</p>
						</div>
					</div>
					<div class="content-area-part">
						<div class="content-area-part-img">
							<img src="<?php echo base_url("assets/images//2.png")?>">
						</div>
						<div class="content-area-part-text">
							<h4>
								2. Make a sale
							</h4>
							<p>
								Lorem ipsum dolor sit consectetur adipiscing elit aliqua.
							</p>
						</div>
					</div>
					<div class="content-area-part">
						<div class="content-area-part-img">
							<img src="<?php echo base_url("assets/images/3.png")?>">
						</div>
						<div class="content-area-part-text">
							<h4>
								3. Place an Order
							</h4>
							<p>
								Lorem ipsum dolor sit consectetur adipiscing elit aliqua.
							</p>
						</div>
					</div>
					<div class="content-area-part">
						<div class="content-area-part-img">
							<img src="<?php echo base_url("assets/images/4.png") ?>">
						</div>
						<div class="content-area-part-text">
							<h4>
								4. Supplier Ships the product 
							</h4>
							<p>
								Lorem ipsum dolor sit consectetur adipiscing elit aliqua.
							</p>
						</div>
					</div>
				</div>
			</div>
	  </div>
	  
<!---------Site-Section-2----->
		<div id ="site-section-2">
			<div class="container">
				<h2>
					Start using Import App today
				</h2>
				<p>
					All accounts have access to the forever free Starter Plan 
				</p>
				<p class="sign-up-button">
					<input type="button" value="Sign Up free" />
				</p>
				
			</div>
		</div>
<!---------Site-Section-3----->				
		<div id ="site-section-3">
			<div class="container">
				<div class="site-slider-2">
					<div><img src="<?php echo base_url("assets/images/1-slider-2.jpg") ?>"></div>
					<div><img src="<?php echo base_url("assets/images/1-slider-2.jpg") ?>"></div>
				</div>
			</div>
		</div>		
<!---------Site-Section-4----->			
		<div id ="site-section-4">
			<div class="site-slider-3">
				<div><img src="<?php echo base_url("assets/images/slider-4.1.jpg") ?>"></div>
				<div><img src="<?php echo base_url("assets/images/slider-4.1.jpg") ?>"></div>
			</div>
			<div class="site-slider-3-text">
				<div class="container">
					<p>
						<img src="<?php echo base_url("assets/images/double-quote.png") ?>">Lorem was very professional, offered good solutions for our moulding needs, was on time, ... really went above & beyond. We're very happy!
					</p>
					<p class="bottom-text">
						Lorem Ipusm
					</p>
				</div>
			</div>
		</div>
		 <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">

		<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

		<script type="text/javascript">
		 jQuery(document).ready(function($){
			    $('.site-slider-2').bxSlider({
				   auto: true
			    });
			  $('.site-slider-3').bxSlider({
				   auto: true,
				    controls: false
			  });
			 
			   $('.main_slider').bxSlider({
				  auto: true,
				  controls: false,
				   pager: false
			    });
		 });
		</script>