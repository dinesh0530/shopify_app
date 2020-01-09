<!-- menus for admin -->
<li><a  class="<?php if(isset($active) && $active=="dashboard") echo "active"; ?>" href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
<li><a class="<?php if(isset($active) && $active=="orders") echo "active"; ?>" href="<?php echo site_url('admin/orders'); ?>">Order</a></li>
<li><a class="<?php if(isset($active) && $active=="all-products") echo "active"; ?>" href="<?php echo site_url('all-products'); ?>">All Products</a></li>
<li><a class="<?php if(isset($active) && $active=="all-categories") echo "active"; ?>" href="<?php echo site_url('admin/all-categories'); ?>">All Categories</a></li>
<li><a class="<?php if(isset($active) && $active=="all-suppliers") echo "active"; ?>" href="<?php echo site_url('all-suppliers'); ?>">All Suppliers</a></li>
<li><a class="<?php if(isset($active) && $active=="all-users") echo "active"; ?>" href="<?php echo site_url('all-users'); ?>">Users</a></li>
<!-- <li><a class="<?php if(isset($active) && $active=="sourcing") echo "active"; ?>" href="<?php echo site_url('sourcing'); ?>"> Sourcing</a></li> -->
<li><a class="<?php if(isset($active) && $active=="sourcing-list") echo "active"; ?>" href="<?php echo site_url('products-sourcing-list'); ?>"> Sourcing list</a></li>
<li><a class="<?php if(isset($active) && $active=="contact") echo "active"; ?>" href="<?php echo site_url('databases'); ?>">Database</a></li>


