<div class="Contact_page">
<div class="alert alert-success" id="successMessage" style="margin-bottom: 20px;">
        <?php 	 echo $this->session->flashdata('success_msg');	?>
    </div> 
<div class="container">
   
    <?php 
      $attributes = array('id' => 'contact_form','name' => 'contact_form',	  'enctype' => 'multipart/form-data');	  
	  echo form_open('contact-us', $attributes); 
	 ?>


	
     <div class="error">
	    <strong><?php echo $this->session->flashdata('captcha-err');?></strong>
     </div>
	 
        <h4 class="title">Contact us</h4>

    <div class="main_fields">
        <div class="fname_field">
	        	<span class="error"> <?php echo form_error('fname'); ?> </span>
            <?php echo form_input(array('id' => 'fname', 'name' => 'fname', 'placeholder' => 'First Name')); ?>
        </div>
        <div class="lname_field">
		 	<span class="error"> <?php echo form_error('lname'); ?> </span>
            <?php echo form_input(array('id' => 'lname', 'name' => 'lname', 'placeholder' => 'Last Name')); ?>
        </div>
        <div class="cemail_field">
		 <span class="error"> <?php echo form_error('email'); ?> </span>
            <?php echo form_input(array('id' => 'email', 'name' => 'email', 'placeholder' => 'Email')); ?>
        </div>
        <div class="contact_field">
		   <span class="error"> <?php echo form_error('phone'); ?> </span>
            <?php echo form_input(array('id' => 'phone', 'name' => 'phone', 'placeholder' => 'Phone')); ?>
        </div>
    </div>
    <div class="request_field">
	<span class="error"> <?php echo form_error('request'); ?> </span>
        <select name="request" id="request">
            <option value=""> Nature of Request</option>
            <option value="Product Request">Product Request</option>
            <option value="Billing">Billing</option>
            <option value="Technical Support">Technical Support</option>
            <option value="OEM-Custom Branding">OEM-Custom Branding</option>
            <option value="Bulk Orders">Bulk Orders</option>
            <option value="Other">Other</option>
        </select>
    </div>
	
	 <div class="msg_field">
	<span class="error"> <?php echo form_error('message'); ?> </span>
        <?php echo form_textarea(array('id' => 'message', 'name' => 'message', 'placeholder' => 'Message', 'rows' => 10, 'cols' => 50)); ?>
    </div>
	
	 <div class="upload_field">	 
        <div class="attach_a_file">	 
        <?php echo form_upload(array('type' => 'file', 'id' => 'file_upload', 'name' => 'file_upload', 'placeholder' => 'Attach a File')); ?>
		<label for="f02">Attach a File - max size</label>
		</div>
		<div class="captcha">  
           <?php echo $this->recaptcha->getWidget(); ?>
           <?php echo $this->recaptcha->getScriptTag(); ?>
       </div>	
	
    </div>
   
    
	<div class="contact_form-button">
		<?php echo form_button(array( "id" => "submit_form" , "class" => "submit_form", "type" => "submit" , "name" => "submit_form" , "value" => "true", "content" => "Submit")); ?>
		<?php echo form_button(array( "id" => "cancel_form" , "class" => "cancel_form", "type" => "submit" , "name" => "cancel_form" , "value" => "true", "content" => "Cancel")); ?>
	</div>
    
    <?php echo form_close(); ?>
</div>
</div>
<script type="text/javascript">
var site_url = window.location.protocol +'//'+ window.location.hostname + window.location.pathname;
$(".cancel_form").click(function(e){
    e.preventDefault();
    window.location.replace(site_url);
});

$("[type=file]").on("change", function(){
  // Name of file and placeholder
  var file = this.files[0].name;
  var dflt = $(this).attr("placeholder");
  if($(this).val()!=""){
    $(this).next().text(file);
  } else {
    $(this).next().text(dflt);
  }
});

	$(function() {
		$("#successMessage").fadeOut(5000);  
	});		
</script>