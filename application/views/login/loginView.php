<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/****** Login Form ******/ ?>
<div class="inner-pages">
	<section class="login-form-section">
		<div class="container">
	
			<div class=" login-form-content">
				<div class="main-heading">
					<h2>Login</h2>
				</div>
			<div class="error">
		      <?php echo $this->session->flashdata('flash_message'); ?>
	        </div>
			<div class="flash_reset_pass">
		      <?php echo $this->session->flashdata('flash_reset_pass'); ?>
	        </div>
			
				<div class="login-form-inner">
					<?php echo form_open(uri_string(), array('class' => 'login-form')); ?>
					<?php if(isset($error)) echo $error; ?>
					<div class="">
						<div class="email_field">
						<?php echo form_input(array( "name" => "email" , "id" => "email" , "placeholder" => "Enter email" , "value" => set_value('email') )); ?>
						<?php echo form_error('email'); ?>
					</div>
					<div class="pass_field">
						<?php echo form_password(array( "name" => "password" , "id" => "password" , "placeholder" => "Enter password" )); ?>
						<?php echo form_error('password'); ?>
					</div>
					<div class="login_form_btn">
						<?php echo form_button(array( "id" => "login" , "type" => "submit" , "content" => "Login" )); ?>
					</div>
						<div class="rem-check"><span class="remeber-me-check">
							<?php echo form_checkbox(array( "name" => "remember" , "id" => "remember" , "value" => "1" )); ?> 
							<span class="remember">Remember me</span></span>
							<span class="psw"><a href="" id="forgot_pswd">Forgot password?</a></span>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>

<div id="forgot_pass_modal" title="Forgot password" style="display:none;"></div>

<script>
$('#forgot_pswd').click(function(event){
		event.preventDefault();
		$.post('<?php echo base_url(); ?>Login/forgot_pswd_view', 
			function(response){           
			$('#forgot_pass_modal').html(response);
		});
		$("#forgot_pass_modal").dialog();
	});
</script>