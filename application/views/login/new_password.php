<div class="reset_pass_main">
<div class="container">
  <div class="new_pass_form">
  <?php 
			if($this->uri->segment(2)=="reset_password"){
  ?>
				 <h2>Reset password</h2>  
  <?php  
			}
			else{
  ?>
    <h2>Create new password</h2>  
			<?php }
	//echo $this->uri->segment(2);  //  print_r($_GET); 
	//die("drhuhrgergeryg");
    $fattr = array('class' => 'new_password');
    //echo form_open(base_url().'login/reset_password/'.$token, $fattr); 
    echo form_open(uri_string(), $fattr ); ?>
    <div class="alert alert-msg">
        <?php echo $this->session->flashdata('flash_message'); ?>
    </div> 
    <div class="form-group">
      <?php echo form_password(array('name'=>'password', 'id'=> 'password', 'placeholder'=>'Password', 'class'=>'form-control', 'value' => set_value('password'))); ?>
      <?php echo form_error('password') ?>
    </div>
    <div class="form-group">
      <?php echo form_password(array('name'=>'passconf', 'id'=> 'passconf', 'placeholder'=>'Confirm password', 'class'=>'form-control', 'value'=> set_value('passconf'))); ?>
      <?php echo form_error('passconf') ?>
    </div>
    <?php //echo form_hidden('id', $user_id);?>
    <?php echo form_submit(array('value'=>'Submit', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
    <?php echo form_close(); ?>  
    </div>
</div>
</div>