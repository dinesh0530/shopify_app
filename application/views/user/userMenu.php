<?php
	$date1 = date("Y-m-d h:i:s");
	$datetime1 = new DateTime($date1);
	$datetime2 = new DateTime($expiry_date[0]['expiry_date']);
?>
<li><a class="<?php if(isset($active) && $active=="home") echo "active"; ?>" href="<?php echo site_url('user/dashboard'); ?>">Dashboard</a></li>
<li><a class="<?php if(isset($active) && $active=="products") echo "active"; ?>" href="<?php echo site_url('user/products'); ?>">Products</a></li>
<li><a class="<?php if(isset($active) && $active=="mylist") echo "active"; ?>" href="<?php echo site_url('user/mylist'); ?>">My List</a></li>
<li><a class="<?php if(isset($active) && $active=="orders") echo "active"; ?>" href="<?php echo site_url('user/orders'); ?>">My Orders</a></li>
<?php if(empty($user_pay_status) || $user_pay_status[0]['subscription_plan'] == "Premium plan" || $datetime1 > $datetime2){ ?>
<li><a class="<?php if(isset($active) && $active=="video-training") echo "active"; ?>">Video Training</a></li>
<li><a class="<?php if(isset($active) && $active=="sourcing") echo "active"; ?>" href="<?php echo site_url('sourcing'); ?>"> Sourcing</a></li>
<li><a class="<?php if(isset($active) && $active=="sourcing-list") echo "active"; ?>" href="<?php echo site_url('products-sourcing-list'); ?>"> Sourcing list</a></li>
<?php } ?>
<li><a class="<?php if(isset($active) && $active=="contact") echo "active"; ?>" href="<?php echo site_url('user/contact-us')?>">Contact Us</a></li>
<li><a class="<?php if(isset($active) && $active=="privacy-policy") echo "active"; ?>" href="<?php echo site_url('privacy-policy'); ?>"> Privacy Policy</a></li>