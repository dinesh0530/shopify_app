<li><a class="<?php if(isset($active) && $active=="dashboard") echo "active"; ?>" href="<?php echo site_url('vendor/dashboard'); ?>">Dashboard</a></li>
<li><a class="<?php if(isset($active) && $active=="all-products") echo "active"; ?>" href="<?php echo site_url('all-products'); ?>">All Products</a></li>
<li><a class="<?php if(isset($active) && $active=="all-suppliers") echo "active"; ?>" href="<?php echo site_url('all-suppliers'); ?>">All Suppliers</a></li>
<li><a class="<?php if(isset($active) && $active=="orders") echo "active"; ?>" href="<?php echo site_url('vendor/orders'); ?>">Orders</a></li>
<!-- <li><a class="<?php if(isset($active) && $active=="sourcing") echo "active"; ?>" href="<?php echo site_url('sourcing'); ?>"> Sourcing</a></li> -->
<li><a class="<?php if(isset($active) && $active=="sourcing-list") echo "active"; ?>" href="<?php echo site_url('products-sourcing-list'); ?>"> Sourcing list</a></li>

<li><a class="<?php if(isset($active) && $active=="privacy-policy") echo "active"; ?>" href="<?php echo site_url('privacy-policy'); ?>"> Privacy Policy</a></li>