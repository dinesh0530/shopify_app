<div class="profile">
   <div class="container">
      <div class="edit_profile">
         <div class="successmsg" id="sucess">  
            <?php 		
			echo $this->session->flashdata('success');?>
         </div>
		
         <h1>Edit your profile</h1>
         <?php echo form_open_multipart(uri_string(), array('class' => 'profile_edit') ); ?> 
         <?php //if($role_id == 3){ ?>
         <div class="edit_profile-form-field-left">
            <div class="form-field image_field">
               <?php
			   
				echo form_input(array( "class" => "profile_img" , "name" => "profile_img" , "type" => "file" )); 
					if(empty($result->image)){
						$base = base_url("/uploads/users/default.png");
						echo "<img name='profile_img' src ='".$base."'>";
					}else{
				
				 $base_url = base_url("/uploads/users/");	
				 /* $file_exist ="uploads/users/thumb_".$result->image; 
				 if (file_exists($file_exist) == true) {
						 echo "<img  name='profile_img' src ='".$base_url.'thumb_'.$result->image."'>";
				 }else{ */
					  echo "<img  name='profile_img' src ='".$base_url.$result->image."'>";
				 //}	
				}
					?>			
			</div>
         </div>
         <?php //} ?>	
         <div class="edit_profile-form-field-right">
		 
			<div class="edit_profile-top">
				<div class="form-field firstname">
				   <?php echo form_label('User name', 'edit_uname' );?>
				   <?php echo form_input(array('name'=>'edit_uname', 'id'=> 'edit_uname', 'placeholder'=>'User name', 'value' => $result->username)); ?>
				</div>
				<div class="form-field">
				   <?php echo form_label('First name', 'edit_fname' );?>
				   <?php echo form_input(array('name'=>'edit_fname', 'id'=> 'edit_fname', 'placeholder'=>'First name', 'value' => $result->firstname)); ?>
				</div>
				<div class="form-field">
				   <?php echo form_label('Last name', 'edit_lname' );?>
				   <?php echo form_input(array('name'=>'edit_lname', 'id'=> 'edit_lname', 'placeholder'=>'Last name', 'value' => $result->lastname)); ?>
				</div>
				<div class="form-field">
				   <?php echo form_label('Email', 'edit_email' );?>
				   <?php echo form_input(array('name'=>'edit_email', 'id'=> 'edit_email', 'placeholder'=>'Email', 'value' => $result->email)); ?>
				</div>
				<div class="form-field">
				   <?php echo form_label('Phone', 'edit_phone' );?>
				   <?php echo form_input(array('name'=>'edit_phone', 'id'=> 'edit_phone', 'placeholder'=>'Phone', "oninput" => "this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" ,'value' => $result->phone)); ?>
				</div>
				<?php
					$role_id =  $this->session->userdata('login')->role_id;
					if($role_id!=3){
						if($role_id == 2){
					?>
				<div class="form-field">
				   <?php 
				   echo form_label('Store connected to shopify', 'edit_store' );
				    $toatl_store =array();
						 if(!empty($store)){	
							foreach($store as $stores){						 
							   $toatl_store[] = $stores->shop_name;
							}							
						}
				//   echo form_input(array('name'=>'edit_store', 'id'=> 'edit_store', 'placeholder'=>'Store connected to shopify', 'value'=>implode(",",$toatl_store))); ?>
				<select name="per1" id="per1">
				<option selected="selected">Select store</option>
				<?php 
			
				foreach($toatl_store as $store_ttl)  { 
				$selected = (($store_ttl == $_SESSION['shop'])  ? 'selected' : ''); ?>
				  <option value="<?= $store_ttl ?>" <?php echo $selected; ?>><? echo $store_ttl ?></option>
				<?php   } ?>
				</select>				
				</div>
				<div class="edit_profile_button">
					<a id="shopify_connect" target="_blank" href="<?php echo site_url('install') ?>">CONNECT MY STORE<img src="<?php echo base_url('images/shopify-white.png') ?>"></a>			
				</div>
				<?php } }else{ ?>
		
			<div class="form-field">
			   <?php echo form_label('City', 'city' );?>
				   <?php echo form_input(array('name'=>'city', 'id'=> 'city', 'placeholder'=>'City', 'value' => $result->city)); ?>
			</div>			
			<div class="form-field">
			   <?php echo form_label('Post code', 'post_code' );?>
				   <?php echo form_input(array('name'=>'post_code', 'id'=> 'post_code', 'placeholder'=>'Post code', 'value' => $result->post_code)); ?>
			</div>		
			<div class="form-field">
			   <?php echo form_label('Job Location', 'job_location' );?>
				   <?php echo form_input(array('name'=>'job_location', 'id'=> 'job_location', 'placeholder'=>'Job Location', 'value' => $result->job_location)); ?>
			</div>			
				
			<div class="form-field">
			   <?php echo form_label('Wechat Id', 'job_location' );?>
				   <?php echo form_input(array('name'=>'wechat_id', 'id'=> 'wechat_id', 'placeholder'=>'Wechat Id', 'value' => $result->wechat_id)); ?>
			</div>	
			
				
			</div>
				<div class="edit_profile-middle">
					<div class="form-field">
					<?php
					echo form_label('Address', 'Address' );
					$data = array(
					'name'        => 'address',
					'id'          => 'address',
					'value'       => $result->address,
					'rows'        => '5',
					'cols'        => '5',
					'style'       => 'width:49%',
					'placeholder' => 'Address'
					);

					echo form_textarea($data);
			?>
					</div>
				</div>
						<?php }?>
			<?php /*
			<div class="edit_profile-middle">
				<div class="form-field">
				   <?php echo form_label('Subscription level', 'edit_subscription' );?>
				   <?php echo form_input(array('name'=>'edit_subscription', 'id'=> 'edit_subscription', 'placeholder'=>'Subscription level', 'value' => "")); ?>
				</div>
				<div class="form-field">
				   <?php echo form_label('Payment method', 'edit_payment' );?>
				   <?php echo form_input(array('name'=>'edit_payment', 'id'=> 'edit_payment', 'placeholder'=>'Payment method', 'value' => "")); ?>
				</div>
				<div class="form-field">
				   <?php echo form_label('Payment detail', 'edit_pdetail' );?>
				   <?php echo form_input(array('name'=>'edit_pdetail', 'id'=> 'edit_pdetail', 'placeholder'=>'Payment detail', 'value' => "")); ?>
				</div>
				<div class="edit_profile_button">
					<a id="payment-shopify-connect" target="_blank" href="#"> Change </a>	
				   <?php //echo form_button(array( "id" => "change" , "type" => "submit" , "name" => "change" , "value" => "true" , "content" => "change" )); ?>
				</div>
			</div> 
			<div class="edit_profile-bottom">
				<div class="form-field">
				   <?php echo form_label('Card type', 'edit_card' );?>
				   <?php echo form_input(array('name'=>'edit_card', 'id'=> 'edit_card', 'placeholder'=>'Card type', 'value' => "")); ?>
				</div>
				<div class="form-field">
				   <?php echo form_label('Name on card', 'edit_cardname' );?>
				   <?php echo form_input(array('name'=>'edit_cardname', 'id'=> 'edit_cardname', 'placeholder'=>'Name on card', 'value' => "")); ?>
				</div>
				<div class="form-field">
				   <?php echo form_label('Card no. - expiry ', 'edit_expiry' );?>
				   <?php echo form_input(array('name'=>'edit_expiry', 'id'=> 'edit_expiry', 'placeholder'=>'Card no. - expiry', 'value' => "")); ?>
				</div>
			
						
            <!--<div class="form-field">
               <?php //echo form_label('Password', 'edit_pass' );?>
               <?php// echo form_password(array('name'=>'edit_pass', 'id'=> 'edit_pass', 'placeholder'=>'Password')); ?>
               </div>	-->
				
			</div>
			<?php */ ?>
			<div class="edit_profile_button">
				   <?php echo form_button(array( "id" => "editProfile" , "type" => "submit" , "name" => "submit" , "value" => "true" , "content" => "Change" )); ?>
				  <a class="canceledits" href="javascript:window.history.go(-1);"> Cancel </a>
				  <?php //echo form_button(array( "class" => "canceledit" , "type" => "submit" , "name" => "submit" , "value" => "Cancel" , "content" => "Cancel" )); ?>
				   <?php echo form_close(); ?>  
				</div>
         </div>
      </div>
   </div>
</div>
</div>

<script>
   var prev_url = document.referrer;
   $(document).ready(function(){
		 $(document).on('click', '.canceledit', function(e){
			e.preventDefault();
			window.location.replace(prev_url);
		 });  
    });
	$("#sucess").delay(5000).fadeOut();
</script>