<!-- Menu's List -->
<?php if(isset($login)): ?>
	<?php if($login->role_id == 1): ?>
		  <?php $this->load->view('admin/adminMenu' , $this->data); ?> <!-- Admin menu -->
	<?php elseif($login->role_id == 2): ?>
		  <?php $this->load->view('user/userMenu' , $this->data); ?><!-- User Menu  -->
	<?php elseif($login->role_id == 3): ?>
		  <?php $this->load->view('vendor/vendorMenu' , $this->data); ?><!-- Vendor Menu  -->
	<?php endif; ?>						
<?php else: ?>
	<?php $this->load->view('menus/mainMenu' , $this->data); ?><!-- Without Login Menu  -->
<?php endif; ?>