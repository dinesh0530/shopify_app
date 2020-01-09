<div class="profile user-profile-view" id="view-user-profile">
  <div class="container">
     <div class="edit_profile">
        <h2>User profile view</h2> 
			<div class="edit_profile-form-field-left">	
				<div class="form-field image_field"> 
					<?php 
					if(empty($show[0]->image)){
						$base = base_url("/uploads/users/default.png");
						echo "<img src ='".$base."'>";
					}else{
						$base_url = base_url("/uploads/users/");
						 echo "<img src ='".$base_url.$show[0]->image."'>";
					}	
					?>		
				</div>
			</div>		
		<div class="edit_profile-form-field-right">
			<div class="form-field firstname">
			   <label for="edit_uname">User name</label><?php  echo $show[0]->username;    ?>
			</div>
			
			<div class="form-field">
			   <label for="edit_fname">First name</label><?php  echo $show[0]->firstname;    ?>
			</div>	
			
			<div class="form-field">
			  <label for="edit_lname">Last name</label><?php  echo $show[0]->lastname;    ?>
			</div>	
			
			<div class="form-field">
			   <label for="edit_email">Email</label><?php  echo $show[0]->email;    ?>
			</div>   
			
			<div class="form-field">
			   <label for="edit_email">Phone</label><?php  echo $show[0]->phone;    ?>
			</div>   
			
			<?php if(!empty($show[0]->city)){?>
			<div class="form-field">
			   <label for="edit_email">City</label><?php  echo $show[0]->city;    ?>
			</div> 
			<?php }?>
			
			<?php if(!empty($show[0]->post_code)){?>
			<div class="form-field">
			   <label for="edit_email">Post Code</label><?php  echo $show[0]->post_code;    ?>
			</div> 
			<?php }?>
			
			
			<?php if(!empty($show[0]->job_location)){?>
			<div class="form-field">
			   <label for="edit_email">	Job location </label><?php  echo $show[0]->job_location;    ?>
			</div> 
			<?php }?>
			
			<?php if(!empty($show[0]->wechat_id)){?>
			<div class="form-field">
			   <label for="edit_email">	 Wechat id </label><?php  echo $show[0]->wechat_id;    ?>
			</div> 
			<?php }?>
			
			<?php if(!empty($show[0]->address)){?>
			<div class="form-field">
			   <label for="edit_email">	  Address </label><?php  echo $show[0]->address;    ?>
			</div> 
			<?php }
			
			if(!empty($stores)){
				$total_store = count($stores);
				if($total_store == 1){
				$store_label = "Store name";
				}else{
				$store_label = "Stores name";	
				}
			?>
			<div class="form-field store_name">
			   <label for="edit_email"> 
			   <?php echo $store_label;?> </label> 
				<?php foreach($stores as $store){
					  echo "<span>".$store->shop_name."</span>";
				} ?>
			</div>   
			<?php }?>
		</div>
	</div>
  </div>
</div>