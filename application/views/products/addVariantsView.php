<div class="variants_popup">
	<?php echo form_open(uri_string(),  array('id' => 'variant_form',"class" => "", 'name' =>'variant_form')); ?>
	    <div class="input_fields_wrap">
			<fieldset class="form-input">
				<?php echo form_input(array( "class" => "variant_img" , "name" => "img_src[]" , "type" => "file" )); ?>
				<?php echo form_input(array('id' => 'variant_name', 'name' => 'variant_name[]', 'placeholder' => 'Variant Name')); ?>
				<?php echo form_input(array('type' => 'number' , 'id' => 'variant_price[]', 'name' => 'variant_price', 'placeholder' => 'Variant Price')); ?>
				<?php echo form_input(array('id' => 'variant_sku', 'name' => 'variant_sku[]', 'placeholder' => 'Street Variant SKU')); ?>
				<select name="variant_supplier[]" id="variant_supplier">
					<option value="">Select Supplier</option>
					<?php foreach($suppliers as $supplier){
						echo '<option value="'.$supplier->id.'">'.$supplier->name.'</option>';
					}?>
				</select>
			</fieldset>
		</div>
		<button class="add_field_button">Add Variants</button>
		<fieldset class="form-buttons">
			<?php echo form_button(array( "id" => "var_cancel" , "class" => "cancel", "type" => "submit" , "name" => "var_cancel", "content" => "Cancel"));
				  echo form_button(array( "id" => "var_save" , "type" => "submit" , "name" => "var_save" , "value" => "Save", "content" => "Save" )); ?>				  
		</fieldset>
    <?php	echo form_close(); ?>
</div>
<script>
$(document).ready(function() {
    var max_fields      = 10;
    var wrapper         = $(".input_fields_wrap");
    var add_button      = $(".add_field_button");   
    var x = 1;
    $(add_button).click(function(e){
        e.preventDefault();
        if(x < max_fields){
            x++;
            $(wrapper).append('<fieldset class="form-input"><input type="file" class="variant_img" value="" name="img_src[]"><input type="text" placeholder="Variant Name" id="variant_name" value="" name="variant_name[]"><input type="number" placeholder="Variant Price" id="variant_price" value="" name="variant_price[]"><input type="text" placeholder="Street Variant SKU" id="variant_sku" value="" name="variant_sku[]"><select name="variant_supplier[]" id="variant_supplier"><option value="">Select Supplier</option><?php foreach($suppliers as $supplier){?><option value="<?php echo $supplier->id; ?>"><?php echo $supplier->name; ?></option><?php } ?></select><a href="#" class="remove_field">Remove</a></fieldset>');
        }
    });   
    $(wrapper).on("click",".remove_field", function(e){
        e.preventDefault(); $(this).parent('fieldset').remove(); x--;
    })
});
</script>