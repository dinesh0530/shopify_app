<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 // die("dfgdhfjtyjktyj");
/* Edit User form */
if(!empty($userId)){ 
	foreach($Users as $user){
		if($user->id == $userId){
/****** User Form ******/ ?>
<div class="inner-pages">
	<section class="user-form-section">
		<div class="container">
	
			<div class=" user-form-content">
				<div class="main-heading">
					<h2>Add new user</h2>
				</div>
			<div class="error">
		    
	        </div>
			
				<div class="user-form-inner">
				<?php //echo validation_errors(); ?>
				<?php echo form_open(uri_string(),  array('id' => 'user_form',"class" => "add_user_form", 'name' =>'user_form')); ?>
					<div class="alert_user user-success">
						<?php echo $this->session->flashdata('mail_success_msg'); ?>					
					</div>  
					<div class="user_form_input">					
						
						<div class="user_fields fname_field">
						<?php echo form_label('First name', 'first_name');?>
						<div class="user_fields_input">
						<?php echo form_input(array('id' => 'fname', 'name' => 'firstname', 'placeholder' => 'First name' )); ?>
						<?php echo form_error('firstname', '<div class="error">', '</div>'); ?>
						</div>
						</div>
						<div class="user_fields lname_field">
						<?php echo form_label('Last name', 'last_name');?>
						<div class="user_fields_input">
						<?php echo form_input(array('id' => 'lname', 'name' => 'lastname', 'placeholder' => 'Last name')); ?>
						<?php echo form_error('lastname', '<div class="error">', '</div>'); ?></div>
						</div>
						<div class="user_fields uemail_field">
						<?php echo form_label('Email', 'user_email', array('class' => 'user_mail', 'id' => 'userMail_label' ));?>
						<div class="user_fields_input">
						<?php echo form_input(array('id' => 'user_email', 'name' => 'email', 'placeholder' => 'Email address')); ?>
						<?php echo form_error('email', '<div class="error">', '</div>'); ?></div>
						</div>
						<div class="user_fields role_field">
						<?php echo form_label('User roles', 'user_roles'); ?>
						<div class="user_fields_input">
							<select name="role_id" id="role_id">
								<option value="">Select role</option>
								<?php foreach($users as $user){
									 if($user->id != 1){
										echo '<option value="'.$user->id.'">'.ucfirst($user->role).'</option>'; 
									 }
									 else{
									 echo '';}
								}?>
							</select>
							<?php echo form_error('role_id', '<div class="error">', '</div>'); ?>
							</div>
						</div>
						<div class="user_form_btn">
							<?php echo form_button(array( "id" => "add_user" , "type" => "submit" , "name" => "add_user" , "value" => "true", "content" => "Add new user" )); ?>
						</div>
											</div>
					
					      

					<?php echo form_close(); ?>
				</div>
				
			</div>
		</div>
		
	</section>
</div>
<?php }
	}
}else{ 

/****** User Form ******/ ?>
<div class="inner-pages">
	<section class="user-form-section">
		<div class="container">
	
			<div class=" user-form-content">
				<div class="main-heading">
					<h2>Add new user</h2>
				</div>
			<div class="error">
		    
	        </div>
			
				<div class="user-form-inner">
				<?php //echo validation_errors(); ?>
				<?php echo form_open(uri_string(),  array('id' => 'user_form',"class" => "add_user_form", 'name' =>'user_form')); ?>
					<div class="alert_user user-success">

						<?php echo $this->session->flashdata('mail_success_msg'); ?>

					</div>  
					<div class="user_form_input">
					
					<div class="user_fields uname_field">
						<?php echo form_label('User name', 'user_name');?>
						<div class="user_fields_input">
						<?php echo form_input(array('id' => 'uname', 'name' => 'username', 'placeholder' => 'User name' )); ?>
						<?php echo form_error('username', '<div class="error">', '</div>'); ?>
						</div>
						</div>						
					
						<div class="user_fields fname_field">
						<?php echo form_label('First name', 'first_name');?>
						<div class="user_fields_input">
						<?php echo form_input(array('id' => 'fname', 'name' => 'firstname', 'placeholder' => 'First name' )); ?>
						<?php echo form_error('firstname', '<div class="error">', '</div>'); ?>
						</div>
						</div>
						<div class="user_fields lname_field">
						<?php echo form_label('Last name', 'last_name');?>
						<div class="user_fields_input">
						<?php echo form_input(array('id' => 'lname', 'name' => 'lastname', 'placeholder' => 'Last name')); ?>
						<?php echo form_error('lastname', '<div class="error">', '</div>'); ?></div>
						</div>
						<div class="user_fields uemail_field">
						<?php echo form_label('Email', 'user_email', array('class' => 'user_mail', 'id' => 'userMail_label' ));?>
						<div class="user_fields_input">
						<?php echo form_input(array('id' => 'user_email', 'name' => 'email', 'placeholder' => 'Email address')); ?>
						<?php echo form_error('email', '<div class="error">', '</div>'); ?></div>
						</div>
						<div class="user_fields role_field">
						<?php echo form_label('User roles', 'user_roles'); ?>
						<div class="user_fields_input">
							<select name="role_id" id="role_id">
								<option value="">Select role</option>
								<?php foreach($users as $user){
									//print_r($users);  die;
									 if($user->id != 1){
										echo '<option value="'.$user->id.'">'.ucfirst($user->role).'</option>'; 
									 }
									 else{
									 echo '';}
								}?>
							</select>
							<?php echo form_error('role_id', '<div class="error">', '</div>'); ?>
							</div>
						</div>
						<div class="user_form_btn">
							<?php echo form_button(array( "id" => "add_user" , "type" => "submit" , "name" => "add_user" , "value" => "true", "content" => "Add new user" )); ?>
						</div>
						
					</div>
					
					      

					<?php echo form_close(); ?>
				</div>
				
			</div>
		</div>
		
	</section>
</div>
<?php } ?>