<div class="forgotPass">
<div class="forgotten_password_form">
<div id="email-error"></div>
		<?php echo $this->session->flashdata('err'); ?>
	 	<?php echo form_open(base_url('Login/forgotten_password'),array('id' => 'forgot_pswd_form',"class" => "popup-form-inner", 'name' =>'forgot_pswd_form')); ?>  	
		<div class="forgot_password">
			<div class="info_req">
				<?php echo form_label('Email', 'email');?>
				<div>
				<?php echo form_input(array('id' => 'forgot_password_email', 'name' => 'forgot_password_email', 'placeholder' => 'Enter your email')); ?>

				<div class="error_msg success-msg"><?php echo form_error('forgot_password_email'); ?>
				<span class="error success-msg"></span>
				<span class="success success-msg"></span>
				</div></div>

			</div>
			<div class="send_email_button">
				<?php echo form_submit(array( "id" => "send_forgotten_password" , "type" => "submit" , "name" => "send_forgotten_password" , "value" => "Send email"));?>
			</div>
		</div>	
	<?php echo form_close();?>
</div>
</div>
	<script>
		$(document).ready(function() {
			$("#send_forgotten_password").click(function(e){
				e.preventDefault();
				
				$("span.error").text("");
				$(".success").text("");
				var email = $('#forgot_password_email').val();
				var regEx = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
				var validEmail = regEx.test(email);
				if (email == "") {
					$('span.error').text('Email is required');
				} else if (!validEmail) {
					$('span.error').text('Invalid Email');
				} else {
					$.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>Login/forgotten_password",
						data: {
							email: email,
						},
						success: function(result) {
							$('span.success-msg').show();
							if (result == "FALSE") {
								$('span.error').text("Your email address does not exist.Please try with a different email address.");
							} else {
								$('span.success').text("Please check your email to reset your password.");
							}
						}
					});
				}
			});
			setTimeout(function() {
				$(".success-msg").hide();
			}, 5000);
		});
	</script>