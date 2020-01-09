<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--All Suppliers listing Form-->
<style>
.Export-button{ padding: 8px 30px;text-decoration: none;background: #0083c4;color: #fff;border: none;cursor: pointer;border-radius: 2px;font-size: 14px;}
.listing-content .main-heading form {width: auto; float: left;}
</style>
<div class="inner-pages">
	<section class="listing-section">
		<div class="container">
			<?php if($this->session->flashdata('success')){ ?>
				<div class="successmsg" id="del_sup">  
					<?php echo $this->session->flashdata('success');?>
				</div>
			<?php }?>
			
			<?php if($this->session->flashdata('successImport')){ ?>
				<div class="successmsg" id="successImport">  
					<?php echo $this->session->flashdata('successImport');?>
				</div>
			<?php }?>
			
			<?php if($this->session->flashdata('editt')){ ?>
				<div class="successmsg" id="edit_sup">  
					<?php echo $this->session->flashdata('editt');?>
				</div>
			<?php }?>
			<?php if($this->session->flashdata('saves')){ ?>
				<div class="successmsg" id="add_sup">  
					<?php echo $this->session->flashdata('saves');?>
				</div>
			<?php }?>					
		
			<div class="listing-content allsupplier-views">
				<div class="main-heading">
					<div>
						<style>
							#file-upload{
							display: none;
							}
						
							.custom-file-upload{
								padding: 8px 30px;
								text-decoration: none;
								background: #0083c4;
								color: #fff;
								border: none;
								cursor: pointer; 
								border-radius: 2px;
								font-size: 14px;
							}
						</style>
						<form method="post" name="upload_excel" enctype="multipart/form-data">
							<fieldset>            
								<label for="file-upload" class="custom-file-upload">
								  Import
								</label>                      
								<input type="file" name="file" id="file-upload" class="Export-button" >
							 </fieldset>
						</form>		
					
						<form method="post" enctype="multipart/form-data">
							<input type="submit" name="Export" class="Export-button" value="Export"/>
						</form>
						<a id="add_supplier" class="add_supplier" data-id="" href="">Add new supplier</a>
					</div>
				</div>	
				<div class="main-container">
					<div class="left-container">
						<div class="doctor-filter">
							<?php echo form_open(uri_string(), array('class' => 'login-form','action' => 'form', "method" => "get")); ?>
							
							<p class="keyword">		   
							<?php echo form_input(array( "name" => "keyword" , "id" => "keyword" , "placeholder" => "Enter supplier name" , "value" => set_value('keyword') )); ?>
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
				<?php if($this->session->flashdata('errorImport')){ ?>
					<div class="error errorImport" id="errorImport">  
						<?php echo $this->session->flashdata('errorImport');?>
					</div>
				<?php }?>
			</div>
			<div class="allsuppliers all-listing">
				<div class="allsuppliers-inner all-listing-inner">
				
				<?php
						if(!empty($supplier)){ ?>
					<table class="supplier_list lists">
					<tr>
					
						<th>Name</th>
						<th>Email</th>
						<th>Category</th>
						<th>Contact person</th>
						<th>Country</th>
						<th>Actions</th>						
					</tr>
					<?php foreach($supplier as $supplierssss){ 
				//	echo "<pre>";
				//	print_r($supplier);  die;
					?>
						<div class="supplier">
							<tr class="supplier_tr">								
								<td class="supplier_name"><?php echo $supplierssss->name; ?></td>
								<td class="supplier_email"><?php echo $supplierssss->email; ?></td>
								<td class="supplier_category"><?php 							
								if(empty($supplierssss->category_name)){echo "No Category";}
								else{
								echo $supplierssss->category_name;}?></td>			
								
								<td class="supplier_contact_person"><?php echo $supplierssss->contact_person; ?></td>
								<td class="supplier_address"><?php 
								if(!empty($supplierssss->country_name)){
							echo $supplierssss->country_name; }
								else{
									echo "Please select country";
								}																
								?></td>
								<td><a href="" data-id="<?php echo $supplierssss->sup_id; ?>" class="edit add_supplier">Edit</a>
								<a href="" class="delete delete_supplier" data-id="<?php echo $supplierssss->sup_id; ?>">Delete</a></td>
							</tr>
						</div>
					<?php  } ?>
					</table>
					
				</div>	
			</div><div class="supplier_pagination"><?php echo (isset($links) ? $links : '');?></div>
						<?php } 
						else
						{  ?>
							<div class="all-categories-error">No Supplier Found</div>
					<?php	}
					?>
			
		
	</section>
</div>
<!-- Html to show Pop up on click of add supplier -->
<div class="overlay delete_popup" style="display:none;"></div>
<div id="supplier_modal" class="common_popup delete_popup"  title="Add New Suppliers" style="display:none;"></div>

<div class="overlay" style="display:none;"></div>
<!-- Html to Show confirmation msg in popup on click of delete supplier-->
<div id="dlt_supplier" class="common_popup delete_popup"  title="Delete supplier" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Do you really want to delete this Supplier ?</p>

<div id="dlt_supplier_confirm" class="common_popup delete_popup" title="Delete" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Delete Successfully</p>
</div>
</div>
<script>
 $(function() {
    setTimeout(function() {
        $("#del_sup").text("")
    }, 3000);
	
	 setTimeout(function() {
        $("#edit_sup").text("")
    }, 3000);
	
	setTimeout(function() {
        $("#add_sup").text("")
    }, 3000);
	
	$("#successImport").delay(5000).fadeOut();
	$("#errorImport").delay(5000).fadeOut();
	$("#file-upload").change(function() {
		 this.form.submit();
	});
}); 
</script>