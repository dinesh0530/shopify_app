 <?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 
 <?php
   if(!empty($_GET)){
		 $sel = $_GET['value'];
		 if(isset($_GET['filter'])){
			$filter = $_GET['filter'];
		 }
 }else{
	 $sel = "";
	 $filter = "";
 }
 
 ?>
 <!--- Users Listing -->
 <style> #suces{ margin-top:-35px; }</style>
<div class="inner-pages">
	<section class="user-listing-section">
	<?php if($this->session->flashdata('delete_user')){ ?>
				<div class="successmsg" id="suces">				
				      <?php echo $this->session->flashdata('delete_user');  ?>
             	</div>	
		<?php }?>	
	
		<div class="container">
		
			<div class="user-listing-section-top">
				<div class="user-sorting-bar">
					<select name="users_sorting" id="vendor_users_sorting">
						
						<option id="filter=date" value="DESC" <?php echo ($filter == "date") ? "selected":"";?> >Sort by latest users</option>
						<option id="filter=firstname" value="ASC" <?php echo ($sel == "ASC") ? "selected":"";?> > Sort by name:A to Z </option>
						<option id="filter=firstname" value="DESC" <?php echo ($filter == "firstname") && ($sel == "DESC") ? "selected":"";?>> Sort by name:Z to A </option>
					</select>
						
						<div class="doctor-filter">
				<?php echo form_open(uri_string(), array('class' => 'login-form','action' => 'form' )); ?>
		<p class="keyword">		   
		 <?php echo form_input(array( "name" => "keyword" , "id" => "keyword" , "placeholder" => "Enter user name" , "value" => "" )); ?>
		<?php echo form_error('keyword'); ?>
				</p>				
					<p class="CategorySearch">
					<?php echo form_submit(array("name"=>"search", "value"=>"Search")); ?>
					</p>
					<div class="clear"></div>
				<?php echo form_close(); ?>
				
						</div>
				
			
				
				</div>
				<div class="main-heading">		
				
							
				<!--	<a href="">Import</a>					
					<a href="">Export</a>					
					<a href="">Download Template</a> -->
					<a id="add_user" class="add_user" data-id="" href="<?php echo base_url();?>admin/AddUser">Add new user</a>
				</div>				
			</div>
			<div class="user-listing-section-bottom">
			<div class="users_sidebar">
				<div class="category-bar">
					<h3 class="side-bar-category_name">Categories</h3>
					<ul class="side-bar-bottom-nav" id="sidebar_categories">
						<?php 
						$total = count($categories);
						
						foreach($categories as $category){ ?>
						<li><a href="#">
							<?php echo $category->category_name ; ?></a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="listing-users">
				
				<div class="listing-user-data">        
				<?php if(!empty($users)){   	
				      foreach($users as $user){      ?>
				<div class="single-user-detail">
					<div class="user-image">	
					<?php if(empty($user->image)){ 					
					$uid = $user->id; 					
					?>					
					<div class="use-image-del">				
						<a href="" class="delete_users" data-id="<?php echo $user->id ?>"></a>
					</div>					
					
						<a href="<?php echo base_url("user/user-profile/$uid");?>">
						<img src="<?php echo base_url();?>uploads/users/default.png"> </a>
					<?php 
					
					}	else{ 
					    $uid = $user->id; 
					?>					
					<div class="use-image-del">				
						<a href="" class="delete_users" data-id="<?php echo $user->id ?>"></a>
					</div>
					<div>
					<a href="<?php echo base_url("user/user-profile/$uid");?>">
						<img src="<?php echo base_url();?>uploads/users/<?php echo $user->image; ?>"> </a>
						</div>
						
					<?php }	?>						
					</div>
					<div class="user-name">
					<?php $total_shop = count($user->stores);?>
						<ul class="user-name">
					<li>  Store:  <span><?php if($total_shop==0){
						echo "No store";
					}else{
						echo $user->stores[0]->shop_name;
						
					}?> </span>
					<?php if($total_shop > 0){ ?>
					<a href="<?php echo base_url("user/user-profile/$uid");?>">
					<span>
					<?php $more_shop =$total_shop-1;
					if($more_shop > 1){
					      echo "<span class='more-store'>".$more_shop."More shop </span>";
					  }
					}?>
					</span> 
					</a>
					</li>
					<li>Level: <span> Basic </span></li>
					<li>Total revenue: <span> </span></li>
					<li>Store credits: <span>  </span> </li>
				</ul>
					</div>
					
				</div>
			<?php } 	
				}else{  ?>
				<div class="all-categories-error">No user found</div>	
			        <?php	}?>			
	  	</div>
	  	</div>		
		<div class="supplier_pagination"><?php echo (isset($links) ? $links : ''); ?></div>
		</div>
		</div>
  </section>
</div>

<div id="dlt_user" class="common_popup delete_popup" title="Delete user" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Do you really want to delete this user ?</p>
</div>

<script>
 $(function() {
	 $("select#vendor_users_sorting").change(function(e){ 
		var sorting_val= $("#vendor_users_sorting option:selected").val(); 
		var sorting_id = $(this).children(":selected").attr("id");
		var url ="?" +  sorting_id+"&value="+sorting_val;  
		window.location = url;				
    });
	
    setTimeout(function() {
        $("#suces").text("")
    }, 3000);
}); 
</script>