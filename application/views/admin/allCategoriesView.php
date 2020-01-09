<?php defined('BASEPATH') OR exit('No direct script access allowed');  ?><!-- all categories view ---->
<div class="inner-pages">
	<section class="listing-section">
		<div class="container">
		<?php if($this->session->flashdata('delete_cat')){ ?>
				<div class="successmsg" id="suces">
				      <?php echo $this->session->flashdata('delete_cat');  ?>
             	</div>					
		<?php }?>
		<?php if($this->session->flashdata('save_cat')){ ?>
				<div class="successmsg" id="saves">
				      <?php echo $this->session->flashdata('save_cat');  ?>
             	</div>					
		<?php }?>
		<?php if($this->session->flashdata('update_cat')){ ?>
				<div class="successmsg" id="editt">
				      <?php echo $this->session->flashdata('update_cat');  ?>
             	</div>					
		<?php }?>	
		
			<div class="listing-content">
				<div class="main-heading">
	
					<!-- <a href="">Import</a>
					<a href="">Export</a>
					<a href="">Download Template</a> -->
		
					<a id="create_category" class="create_category" data-id="" href=""> Add new category </a> <!-- popup open to create catrgory ---->
				</div>
				<div class="main-container">				
				<?php	//if (!empty($results)){  ?>
				<div class="left-container">
						<div class="doctor-filter">
				<?php echo form_open(uri_string(), array('class' => 'login-form','action' => 'form', "method" => "get")); ?>
		<p class="keyword">		   
		 <?php echo form_input(array( "name" => "keyword" , "id" => "keyword" , "placeholder" => "Enter category name" , "value" => set_value('keyword') )); ?>
		<?php echo form_error('keyword'); ?>
				</p>				
					<p class="CategorySearch">
					<?php echo form_submit(array("name"=>"search", "value"=>"Search")); ?>
					</p>
					<div class="clear"></div>
				<?php echo form_close(); ?>
				
						</div>
					</div>
				</div>
				<?php  //}  ?>
				<!-- <div class="main-container">
					<div class="left-container">
						<?php /* echo form_open(uri_string(), array('class' => 'login-form')); ?>
								<?php echo form_label(); ?>
								<div class="">
									<p>
									   <?php echo form_label(); ?>
									</p>
								</div>
							<?php echo form_submit(array("name"=>"search", "value"=>"Search")); ?>
							<div class="clear"></div>
						<?php echo form_close(); */?>
					</div>
				</div>  -->
            </div>			<!-- Listing of category --->
            <?php //if(isset($success_msg)){
            	//echo $success_msg;
            	//if($this->session->flashdata('success_msg')){ 
    						//echo $this->session->flashdata('success_msg'); 
            //} ?>
			<?php  
			// echo "<pre>";
			// print_r($data);  die;
			// if(isset($saved)){
					// echo "Added succesfully";
					
			// }
				?>
			<div class="allcategories all-listing">
				<div class="allcategories-inner all-listing-inner">
				<?php if(isset($results))  { ?>
				    <table class="category-list lists">
						<tr>
							<!--th>Sr No.</th-->
							<th>Category name</th>
							<th>Actions</th>									
						</tr>
						<?php 
								foreach($results as  $category){ ?>
                            <tr>
								
								<td class="category-name"><?php echo $category->category_name ; ?></td>
								<td><a class="edit create_category" href="" data-id="<?php echo $category->id ; ?>">Edit</a> <!-- edit current category -->
								<a class="delete delete-category" href="" data-id="<?php echo $category->id ; ?>">Delete</a></td><!-- Delete current category -->
						    </tr>
						<?php }  ?>
						</table>
						
				</div>
			</div>
			<div class="supplier_pagination"><?php echo (isset($links) ? $links : '');?></div>
				<?php } 
						else
						{  ?>
							<div class="all-categories-error">No category Found</div>
					<?php	}
					?>
        </div>
    </section>
</div><!-- add category and edit category --->

<div class="overlay" style="display:none;"></div>
<div id="category_modal" class="common_popup delete_popup" title="Add new category" style="display:none;"></div><!-- Delete popup message -->
<div class="overlay" style="display:none;"></div>
<div id="delete_category" class="common_popup"  title="Delete category" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Are you really want to delete this category ?</p>
  </div>
  <div id="delete_category_confirm" class="common_popup delete_popup" title="Delete category" style="display:none;">  
  <p><span class="ui-icon ui-icon-alert"></span>Category Delete Successfully</p>
</div>
<script>
 $(function() {
    setTimeout(function() {
        $("#suces").text("")
    }, 3000);
}); 
</script>
<script>
 $(function() {
    setTimeout(function() {
        $("#saves").text("")
    }, 3000);
}); 
</script>
<script>
 $(function() {
    setTimeout(function() {
        $("#editt").text("")
    }, 3000);
}); 
</script>