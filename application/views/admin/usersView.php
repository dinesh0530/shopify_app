<?php defined('BASEPATH') OR exit('No direct script access allowed');  ?>
<!--- Users Listing -->
<div class="inner-pages">
	<section class="listing-section">
		<div class="container">
			<div class="listing-content">
				<div class="main-heading">					
					<a href="">Import</a>					
					<a href="">Export</a>					
					<a href="">Download Template</a>
					<a id="add_user" class="add_user" data-id="" href="<?php echo base_url();?>admin/AddUser">Add New User</a>
				</div>
				<div class="main-container">
					<div class="left-container">
						<?php echo form_open(uri_string(), array('class' => 'login-form')); ?>
								<?php echo form_label(); ?>
								<div class="doctor-filter">
									<p>
									   <?php echo form_label(); ?>
									</p>								
								</div>
							<?php echo form_submit(array("name"=>"search", "value"=>"Search")); ?>
							<div class="clear"></div>
						<?php echo form_close(); ?>
					</div>
				</div>
            </div>
			<div class="allusers all-listing">
				<div class="allusers-inner all-listing-inner">
					<table class="users_list lists">
						<tr>
						<th>S.no.</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
					<?php $i = 1; foreach($users as $user){ ?>
						<div class="user">
							<tr>
								<td class="user_sr list-sr"><?php echo $i; ?></td>
								<td class="user_fname"><?php echo $user->firstname; ?></td>
								<td class="user_lname"><?php echo $user->lastname; ?></td>
								<td class="user_email"><?php echo $user->email; ?></td>
								<td><a href="<?php //echo base_url() . "admin/edit-user/" . $user->id; ?>" data-id="<?php echo $user->id; ?>" class="edit">Edit</a></td>
								<td><a href="<?php echo base_url() . "admin/delete-user/" . $user->id; ?>" class="delete" data-id="<?php echo $user->id; ?>">Delete</a></td>
								
							</tr>
						</div>
					<?php $i++; } ?>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>