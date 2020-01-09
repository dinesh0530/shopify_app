<?php
if(!empty($supplierId)){    
	foreach($suppliers as $supplier){
		if($supplier->id == $supplierId){ ?>
	<div class="add_suppliers_form popup-forms">
	<div class="succesful-sup successmsg"></div>
	<form id="edit_supplier_form" class="popup-form-inner" name="edit_supplier_form" data-id ="<?php echo $supplier->id; ?>">
	    <fieldset class="form-input">			
			<div class="sup_fields name_field">
				<?php echo form_label('Name', 'sup_name');?>
				<?php echo form_input(array('id' => 'edit_sup_name', 'class' => 'edit_sup_field' , 'name' => 'sup_name', 'placeholder' => 'Supplier name', "value" => $supplier->name)); ?>
				
			</div>
			<div class="sup_fields email_field">
				<?php echo form_label('Email', 'sup_email', array('class' => 'sup_label', 'class' => 'edit_sup_field' , 'id' => 'email_label' ));?>
				<span class='email_valid error'></span>
				<?php echo form_input(array('id' => 'edit_sup_email', 'class' => 'edit_sup_field' , 'name' => 'sup_email', 'placeholder' => 'Email address', "value" => $supplier->email )); ?>
				<?php echo form_input(array('type' => 'hidden' , 'id' => 'edit_sup_emails', "value" => $supplier->email )); ?>
				
			</div>
			<div class="sup_fields address1_field">
				<?php echo form_label('Shop no.', 'sup_shop_name');?>
				<?php echo form_input(array('id' => 'edit_sup_shop_name', 'class' => 'edit_sup_field' , 'name' => 'sup_shop_name', 'placeholder' => 'Address', "value" => $supplier->address1)); ?>
				
			</div>
			<div class="sup_fields address2_field">
				<?php echo form_label('Street address', 'sup_street_add');?>
				<?php echo form_input(array('id' => 'edit_sup_street_add', 'class' => 'edit_sup_field' , 'name' => 'sup_street_add', 'placeholder' => 'Street address', "value" => $supplier->address2)); ?>
				
			</div>
			<div class="sup_fields country_field">
				<?php echo form_label('Country', 'sup_country');?>
				<select name="sup_country" id="edit_sup_country" class = "edit_sup_field">
					<option value="">Select country</option>
					<?php foreach($countries as $newcountry){			 
						  $selected = ($supplier->country == $newcountry->id  ? 'selected' : ''); 	
					?>
						<option value="<?php echo $newcountry->id; ?>" <?php echo $selected; ?>><?php echo $newcountry->country_name; ?></option>
				<?php }?>
				</select>				
			</div>
			<div class="sup_fields state_field">			
				<?php echo form_label('State', 'sup_state'); ?>	
				<select name="sup_state" id="edit_sup_state" class = "edit_sup_field">
						<?php foreach($states as $newstate){ 
							$selected = ($supplier->state == $newstate->id  ? 'selected' : ''); ?>
							<option value="<?php echo $newstate->id; ?>" <?php echo $selected; ?>><?php echo $newstate->state_name; ?></option>				
						<?php } ?>
				</select>				
			</div>
			<div class="sup_fields city_field">
				<?php echo form_label('City', 'sup_city'); ?>	
				<?php echo form_input(array('id' => 'edit_sup_city', 'class' => 'edit_sup_field' , 'name' => 'sup_city', 'placeholder' => 'City', "value" => $supplier->city)); ?>
				
			</div>
			<div class="sup_fields zip_field">
				<?php echo form_label('ZIP', 'sup_zip', array('class' => 'sup_label', 'id' => 'zip_label' )); ?>	
				<?php echo form_input(array('id' => 'edit_sup_zip', 'class' => 'edit_sup_field' , 'name' => 'sup_zip', 'placeholder' => 'Zip', "value" => $supplier->zip)); ?>
				
			</div>
			<div class="sup_fields phone_field">
				<?php echo form_label('Phone', 'sup_phone', array('class' => 'sup_label', 'id' => 'phone_label')); ?>	
				<?php echo form_input(array('id' => 'edit_sup_phone', 'class' => 'edit_sup_field' , 'name' => 'sup_phone', 'placeholder' => 'Phone', "value" => $supplier->phone)); ?>
				
			</div>
			<div class="sup_fields whatsapp_field">
				<?php echo form_label('Whatsapp', 'sup_whatsapp', array('class' => 'sup_label', 'id' => 'wtsapp_label' )); ?>	
				<?php echo form_input(array('id' => 'edit_sup_whatsapp', 'class' => 'edit_sup_field' , 'name' => 'sup_whatsapp', 'placeholder' => 'Whatsapp', "value" => $supplier->whatsapp)); ?>
				
			</div>
			<div class="sup_fields skype_field">
				<?php echo form_label('Skype', 'sup_skype'); ?>
				<?php echo form_input(array('id' => 'edit_sup_skype', 'class' => 'edit_sup_field' , 'name' => 'sup_skype', 'placeholder' => 'Skype', "value" => $supplier->skype )); ?>
				
			</div>
			<div class="sup_fields site_field">
				<?php echo form_label('Website', 'sup_site'); ?>
				<?php echo form_input(array('id' => 'edit_sup_site', 'class' => 'edit_sup_field' , 'name' => 'sup_site', 'placeholder' => 'Website', "value" => $supplier->website )); ?>
				
			</div>
			<div class="sup_fields cperson_field">
				<?php echo form_label('Contact person', 'sup_con_person'); ?>
				<?php echo form_input(array('id' => 'edit_sup_con_person', 'class' => 'edit_sup_field' , 'name' => 'sup_con_person', 'placeholder' => 'Contact person', "value" => $supplier->contact_person));?>
				
			</div>
			<div class="sup_fields category_field">
			<?php echo form_label('Category', 'sup_category'); ?>
				<select name="sup_category" id="edit_sup_category" class = "edit_sup_field">
				<option value="">Select category</option>
					<?php foreach($categories as $category){
						$selected = ($supplier->category == $category->id  ? 'selected' : '');  ?>
						<option value="<?php echo $category->id; ?>" <?php echo $selected; ?>><?php echo ucfirst($category->category_name); ?></option>
					<?php } ?>
				</select>
				
			</div>
			<input type="hidden" class="run-sup">
		</fieldset>
		<fieldset class="form-buttons">			
			<?php echo form_button(array( "id" => "editSupplier" , "type" => "submit" , "name" => "submit" , "value" => "Update" , "content" => "Update")); ?>
			<?php echo form_button(array( "class" => "cancel sup_cancel" , "type" => "submit" , "name" => "submit" , "value" => "Cancel" , "content" => "Cancel" )); ?>
		</fieldset>
<?php	echo form_close(); ?>
</div>
<?php }
	}
}else{?>
<div class="add_suppliers_form popup-forms">
    <div class="succesful-sup successmsg"></div>
	
	<div class="login-form-supplier">
	<a href="javascript:void(0)" id="Search-suppliers" class="Search-supplier" >Search supplier</a>
	<form style="display:none;" action="all-suppliers" id="login-form-supplier"  class="login-form-supplier" method="get" accept-charset="utf-8">
		<p class="keyword">		   
		 <input type="text" name="keyword" value="" id="keyword" placeholder="Enter supplier name">
		</p>	
		
		<input type="submit" class="CategorySearch" name="search" value="Search">		
		<div class="clear"></div>
</form>
	
	</div>
	
	<form id="supplier_form" class="popup-form-inner" name="supplier_form">
	    <fieldset class="form-input">			    
			<div class="sup_fields name_field">
				<?php echo form_label('Name', 'sup_name'); ?>
				<?php echo form_input(array('id' => 'sup_name', 'name' => 'sup_name', 'placeholder' => 'Supplier name')); ?>
				
			</div>
			<div class="sup_fields email_field">
				<label for='sup_email' class='sup_label' id='email_label'>Email</label><span class='email_valid error'></span>
				<?php echo form_input(array('id' => 'sup_email', 'name' => 'sup_email', 'placeholder' => 'Email address')); ?>
				
			</div>
			<div class="sup_fields address1_field">
				<?php echo form_label('Shop no.', 'sup_shop_name'); ?>
				<?php echo form_input(array('id' => 'sup_shop_name', 'name' => 'sup_shop_name', 'placeholder' => 'Address')); ?>
				
			</div>
			<div class="sup_fields address2_field">
				<?php echo form_label('Street address', 'sup_street_add'); ?>
				<?php echo form_input(array('id' => 'sup_street_add', 'name' => 'sup_street_add', 'placeholder' => 'Street address')); ?>
				
			</div>
			<div class="sup_fields country_field">
				<label for='sup_country' class='sup_label' id='country_label'>Country</label>
				<select name="sup_country" id="sup_country">
					<option value="">Select country</option>
					<?php foreach($countries as $country){
						echo '<option value="'.$country->id.'">'.$country->country_name.'</option>';
					}?>
				</select>
				
			</div>
			<div class="sup_fields state_field">
				<label for='sup_state' class='sup_label' id='state_label'>State</label>
				<select name="sup_state" id="sup_state">
					<option value="">Select state</option>
				</select>
				
			</div>
			<div class="sup_fields city_field">
				<?php echo form_label('City', 'sup_city'); ?>
				<?php echo form_input(array('id' => 'sup_city', 'name' => 'sup_city', 'placeholder' => 'City')); ?>
				
			</div>
			<div class="sup_fields zip_field">
				<label for='sup_zip' class='zip_label' id='zip_label'>Zip</label>
				<?php echo form_input(array('id' => 'sup_zip', 'name' => 'sup_zip', 'placeholder' => 'Zip')); ?>
				
			</div>
			<div class="sup_fields phone_field">
			 <label for='sup_phone' class='sup_label' id='phone_label'>Phone</label>
				<?php echo form_input(array('id' => 'sup_phone', 'name' => 'sup_phone', 'placeholder' => 'Phone')); ?>
				
			</div>
			<div class="sup_fields whatsapp_field">
				<label for='sup_whatsapp' class='sup_label' id='wtsapp_label'>Whatsapp</label>
				<?php echo form_input(array('id' => 'sup_whatsapp', 'name' => 'sup_whatsapp', 'placeholder' => 'Whatsapp')); ?>
				
			</div>
			<div class="sup_fields skype_field">
				<?php echo form_label('Skype', 'sup_skype'); ?>
				<?php echo form_input(array('id' => 'sup_skype', 'name' => 'sup_skype', 'placeholder' => 'Skype')); ?>
				
			</div>
			<div class="sup_fields site_field">
				<?php echo form_label('Website', 'sup_site'); ?>
				<?php echo form_input(array('id' => 'sup_site', 'name' => 'sup_site', 'placeholder' => 'Website')); ?>
				
			</div>
			<div class="sup_fields cperson_field">
				<?php echo form_label('Contact person', 'sup_con_person'); ?>
				<?php echo form_input(array('id' => 'sup_con_person', 'name' => 'sup_con_person', 'placeholder' => 'Contact person'));?>
				
			</div>
			<div class="sup_fields category_field">
				<label for='sup_category' class='category_label' id='category_label'>Category</label>
				<select name="sup_category" id="sup_category">
					<option value="">Select category</option>
					<?php foreach($categories as $category){
						echo '<option value="'.$category->id.'">'.ucfirst($category->category_name).'</option>';
					}?>
				</select>
				
			</div>
			<input type="hidden" class="run-sup">
		</fieldset>
		<fieldset class="form-buttons">
			<?php echo form_button(array( "id" => "sup_cancel" , "class" => "cancel sup_cancel", "type" => "button" , "name" => "sup_cancel" , "value" => "true", "content" => "Cancel"));
				  echo form_button(array( "id" => "sup_save" , "type" => "submit" , "name" => "sup_save" , "value" => "true", "content" => "Save" ));
				  echo form_button(array( "id" => "add_sups" , "type" => "submit" , "name" => "add_sup" , "value" => "true", "content" => "Add new" )); ?>
		</fieldset>
<?php	echo form_close(); ?>
</div>
<?php } ?>
<script>
$(document).ready(function () {
	var site_url = window.location.protocol +'//'+ window.location.hostname + window.location.pathname;
	$('#sup_country').change(function(){
		var country_id = $('#sup_country').val();
		if(country_id != '')
		{
			$.ajax({
				url:"<?php echo base_url(); ?>Suppliers/fetch_state",
				method:"POST",
				data:{country_id:country_id},
				success:function(data)
				{
					$('#sup_state').html(data);
				}
			});
		}
		else
		{
			$('#sup_state').html('<option value="">Select State</option>');
		}
	});
	$('#edit_sup_country').change(function(){
		var country_id = $('#edit_sup_country').val();
		var state = $('#edit_sup_state option:selected').text();
		if(country_id != '')
		{
			$.ajax({
				url:"<?php echo base_url(); ?>Suppliers/fetch_state",
				method:"POST",
				data:{country_id:country_id},
				success:function(data)
				{
					$('#edit_sup_state').html(data);
				}
			});
		}
		else
		{
			$('#edit_sup_state').html(state);
		}
	});
	$("#sup_save").click(function(e){
		vlidateAddform();
		setTimeout( function(){
			if($('.run-sup').val() == "Yes"){
                var url = window.location.href;
                if (url.indexOf("create-product") > -1 || url.indexOf("edit-product") > -1){ 
                    setTimeout( function (){
                        $.ajax({
							url: '<?php echo base_url();?>Suppliers/get_sup',
							type: 'POST',
							data: {
									sup_name : $('#sup_email').val()
								},
							success: function(result) {
                                var response = jQuery.parseJSON(result.response);
								if(response !== false){
									$('#supplier_name').append($("<option selected></option>").attr("value",response[0].id).text(response[0].name));
							    }
							}
						});
						$('#supplier_modal').dialog( "close" );
					}, 500);
                }else{ 
					setTimeout( function(){
						window.location.replace(site_url);
					}, 500);
                }
			}
		}, 500);
	});
	
	$("#add_sups").click(function(e){
		vlidateAddform();
		setTimeout( function(){
			if($('.run-sup').val() == "Yes"){
				$('.succesful-sup').html('Supplier has been added successfully');
				setTimeout( function(){
					$('#add_supplier').click();
				}, 2000);
			}
		}, 1500);
	});
	
	$("#editSupplier").click(function(e){
		validateEditsup();
		setTimeout( function(){
			if($('.run-sup').val() == "Yes"){
				setTimeout( function(){
					window.location.replace(site_url);					
				}, 500);
			}
		}, 500);
	});
	
	$(".sup_cancel").click(function(e){
		e.preventDefault();
		window.location.replace(site_url);
	});
	
	$(".edit_sup_field").change(function(){
		$('#editSupplier').removeAttr('disabled');
	});
	
	function vlidateAddform(){
		$("form[name='supplier_form']").validate({
			rules: {
				sup_name: "required",
				sup_email: {
					required: true,
					email: true
				},
				sup_shop_name: "required",
				sup_street_add: "required",
				sup_country: "required",
				sup_state: "required",
				sup_city: "required",
				sup_zip: {
					required: true,
					number: true,
					minlength: 5,
					maxlength: 6,
				},
				sup_phone: {
					required: true,
					number: true,
					minlength: 10,
					maxlength: 10,
				},
				sup_whatsapp: {
					required: true,
					number: true,
					minlength: 10,
					maxlength: 10,
				},
				sup_skype: "required",
				sup_site: "required",
				sup_con_person: "required",
				sup_category: "required"      
			},
			messages: {
				sup_name: "Please enter supplier name",
				sup_email: "Invalid email address",
				sup_shop_name: "Please enter shop no.",
				sup_street_add: "Please enter street address",
				sup_country: "Please select country",
				sup_state: "Please select state",
				sup_city: "Please enter city",
				sup_zip: "Invalid zip code",
				sup_phone: "Invalid phone number",
				sup_whatsapp: "Invalid whatsapp number",
				sup_skype: "Please enter skype name",
				sup_site: "Please enter website",
				sup_con_person: "Please enter contact person",
				sup_category: "Please select category"  
			},
			submitHandler: function(form) {				
				$.ajax({
					url: '<?php echo base_url();?>Suppliers/email_check',
					type: 'POST',
					data:   {
								validEmail : $('#sup_email').val()
							},
					success: function(data) {
						if(data > 0){
							$('.email_valid').html('Email already exist.');
							return false;
							$('.run-sup').val('No');
						}else{
							saveSupplier();
							$('.run-sup').val('Yes');							
						} 
					}
				});
			}
		});
	}
	
	function saveSupplier(){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>add-Supplier",
			data: {
				sup_name:        $('#sup_name').val(),
				sup_shop_name:   $('#sup_shop_name').val(),
				sup_street_add:  $('#sup_street_add').val(),
				sup_country:     $('#sup_country option:selected').val(),
				sup_state:       $('#sup_state option:selected').val(),
				sup_city:        $('#sup_city').val(),
				sup_zip:         $('#sup_zip').val(),
				sup_phone:       $('#sup_phone').val(),
				sup_whatsapp:    $('#sup_whatsapp').val(),
				sup_skype:       $('#sup_skype').val(),
				sup_email:       $('#sup_email').val(),
				sup_site:		 $('#sup_site').val(),
				sup_con_person:	 $('#sup_con_person').val(),
				sup_category:	 $('#sup_category option:selected').val(),     
			},
			success: function(result){
			} 
		});
	}
	
	function validateEditsup(){
		$("form[name='edit_supplier_form']").validate({
			rules: {
				sup_name: "required",
				sup_email: {
					required: true,
					email: true
				},
				sup_shop_name: "required",
				sup_street_add: "required",
				sup_country: "required",
				sup_state: "required",
				sup_city: "required",
				sup_zip: {
					required: true,
					number: true,
					minlength: 5,
					maxlength: 6,
				},
				sup_phone: {
					required: true,
					number: true,
					minlength: 10,
					maxlength: 10,
				},
				sup_whatsapp: {
					required: true,
					number: true,
					minlength: 10,
					maxlength: 10,
				},
				sup_skype: "required",
				sup_site: "required",
				sup_con_person: "required",
				sup_category: "required"      
			},
			messages: {
				sup_name: "Please enter supplier name",
				sup_email: "Invalid email address",
				sup_shop_name: "Please enter shop no.",
				sup_street_add: "Please enter street address",
				sup_country: "Please select country",
				sup_state: "Please select state",
				sup_city: "Please enter city",
				sup_zip: "Invalid zip code",
				sup_phone: "Invalid phone number",
				sup_whatsapp: "Invalid whatsapp number",
				sup_skype: "Please enter skype name",
				sup_site: "Please enter website",
				sup_con_person: "Please enter contact person",
				sup_category: "Please select category" 
			},
			submitHandler: function(form) {
                var email1 = $('#edit_sup_emails').val();
			    var email2 = $('#edit_sup_email').val();
				if(email1 == email2){
					editSupplier();
					$('.run-sup').val('Yes');
				}else{
					$.ajax({
						url: '<?php echo base_url();?>Suppliers/email_check',
						type: 'POST',
						data:   {
									validEmail : $('#edit_sup_email').val()
								},
						success: function(data) {
							if(data > 0){
								$('.email_valid').html('Email already exist.');
								$('.run-sup').val('No');
								return false;							
							}else{
								editSupplier();
								$('.run-sup').val('Yes');							
							} 
						}
					});
				}
			}
		});
	}
	
	function editSupplier(){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>Suppliers/edit_supplier",
			data: {
				sup_name:        $('#edit_sup_name').val(),
				sup_shop_name:   $('#edit_sup_shop_name').val(),
				sup_street_add:  $('#edit_sup_street_add').val(),
				sup_country:     $('#edit_sup_country option:selected').val(),
				sup_state:       $('#edit_sup_state option:selected').val(),
				sup_city:        $('#edit_sup_city').val(),
				sup_zip:         $('#edit_sup_zip').val(),
				sup_phone:       $('#edit_sup_phone').val(),
				sup_whatsapp:    $('#edit_sup_whatsapp').val(),
				sup_skype:       $('#edit_sup_skype').val(),
				sup_email:       $('#edit_sup_email').val(),
				sup_site:		 $('#edit_sup_site').val(),
				sup_con_person:	 $('#edit_sup_con_person').val(),
				sup_category:	 $('#edit_sup_category option:selected').val(), 
                supplierId:      $('#edit_supplier_form').attr('data-id')				
			},
			success: function(result){
			} 
		});
	}
	
});



$(document).ready(function(){
    $("#Search-suppliers").click(function(){
		 $(this).text($(this).text() == 'Search supplier' ? 'Hide search' : 'Search supplier');
        $("#login-form-supplier").toggle();
    });
});

</script>