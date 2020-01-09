<li><a class="<?php if(isset($active) && $active=="home") echo "active"; ?>" href="<?php echo site_url(); ?>">Home</a></li>
<li><a class="<?php if(isset($active) && $active=="about") echo "active"; ?>" href = "<?php echo site_url('about-us'); ?>">About Us</a></li>
<li><a class="<?php if(isset($active) && $active=="services") echo "active"; ?>">Services</a></li>
<li><a class="<?php if(isset($active) && $active=="contact") echo "active"; ?>" href="<?php echo site_url('contact-us'); ?>">Contact Us</a></li>
<li><a class="<?php if(isset($active) && $active=="faq") echo "active"; ?>">Faq</a></li>		
<li><a class="<?php if(isset($active) && $active=="privacy-policy") echo "active"; ?>" href="<?php echo site_url('privacy-policy'); ?>"> Privacy Policy</a></li>
<!--<li><a class="<?php //if(isset($active) && $active=="sourcing") echo "active"; ?>" href="<?php //echo site_url('sourcing'); ?>"> Sourcing</a></li>
<li><a class="<?php //if(isset($active) && $active=="sourcing-list") echo "active"; ?>" href="<?php //echo site_url('products-sourcing-list'); ?>"> Sourcing list</a></li> -->
			
