 

<?php  /**** Edit Category Form ****/

 if(!empty($catId)){
	foreach($categories as $category){
		if($category->id == $catId){?>
			<div class="category-form popup-forms ">
				<?php echo form_open(uri_string(), array('class' => 'popup-form-inner')); ?>
					<fieldset class="form-input">					    
						<?php if(isset($error)) echo $error; ?>
						<div class="successful-cat successmsg"></div>
						<?php echo form_input(array( "name" => "category" , "id" => "editCategory" ,"class" => "edit-Cat-box" , "placeholder" => "Enter category" , "value" => $category->category_name , "data-id" => $category->id )); ?>
						<div class="error error-category"></div>
						<input type="hidden" class="run-cat" id="run" name="run">
					</fieldset>
					<fieldset class="form-buttons buttons">
						<?php echo form_button(array( "id" => "editCat" , "type" => "submit" , "name" => "submit" , "value" => "Update" , "content" => "Update" )); ?>
						<?php echo form_button(array( "class" => "cancel" , "type" => "submit" , "name" => "submit" , "value" => "Cancel" , "content" => "Cancel" )); ?>
					</fieldset>
				<?php echo form_close(); ?>
			</div>
		<?php	}
	}
 }else{ /**** Create Category Form ****/ ?>
	<div class="category-form popup-forms">
		<?php echo form_open(uri_string(), array('class' => 'popup-form-inner')); ?>
		    <fieldset class="form-input">
				<?php if(isset($error)) echo $error; ?>
				<div class="successful-cat successmsg" id="mnop"></div>
				<div>				    
					<?php echo form_input(array( "name" => "category" , "id" => "category" ,"class" => "edit-Cat-box", "placeholder" => "Enter category" , "data-id" => "" )); ?>
					<div class="error error-category"></div>
					<input type="hidden" class="run-cat" id="run" name="run">
				</div>
			</fieldset>
            <fieldset class="form-buttons buttons">
				<?php echo form_button(array( "id" => "addnew" , "type" => "submit" , "name" => "submit" , "value" => "Add new category" , "content" => "Add new category" )); ?>
				<?php echo form_button(array( "id" => "savecategory" , "type" => "submit" , "name" => "submit" , "value" => "Save"  , "content" => "Save" )); ?>
				<?php echo form_button(array( "id" => "returnList" , "class" => "cancel" , "type" => "submit" , "name" => "submit" , "value" => "Return To List"  ,  "content" => "Return to list" )); ?>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
<?php } ?>

<script type="text/javascript">
var site_url = window.location.protocol +'//'+ window.location.hostname + window.location.pathname;
$("#addnew").click(function(e){
	e.preventDefault();
    value = $('#category').val();
    values(value);
	if(value !== ""){
		setTimeout( function(){		
			if($('.run-cat').val() == "Yes"){
				$(".successful-cat").html("<span style='font-size:14px'>Category has been added successfully.</span>");			
				setTimeout( function (){				
					$('#create_category').click();
				}, 2000);
			}
		}, 1500);
	}
});
 $(function () {
$("#savecategory").bind("click",function(e){
	e.preventDefault();
	value = $('#category').val();
    values(value);	
	
	if(value !== ""){
		setTimeout( function(){
			if($('.run-cat').val() == "Yes"){
                 var url = window.location.href;
                 if (url.indexOf("create-product") > -1 || url.indexOf("edit-product") > -1){ 
                    setTimeout( function (){
						getCategorys(value);
						$('#category_modal').dialog( "close" );
					}, 500);
                 }else{ 
					setTimeout( function (){
						window.location.replace(site_url);
					}, 500);
                }
			}
		}, 500);
	}
 });   });
$("#editCat").click(function(e){
	e.preventDefault();
    value = $('#editCategory').val();
    cat_Id = $('#editCategory').attr('data-id');
	editvalues(value , cat_Id);
	if(value !== ""){
		setTimeout( function(){
			if($('.run-cat').val() == "Yes"){
				// $(".successful-cat").html("<span style='font-size:14px'>Category has been updated successfully.</span>");
				setTimeout( function (){
					window.location.replace(site_url);
				}, 500);
			}
		}, 500);
	}
});
$(".cancel").click(function(e){
	e.preventDefault();
	window.location.replace(site_url);
});

function values(value){
	if(value !== ""){
        getCategorys(value);
		setTimeout( function(){
			if($('.run-cat').val() == "Yes"){				
				ajaxCategories(value);
			}
		}, 700);
	}else{
		$('.error-category').html('Category is required.');
	}
}

function editvalues(value , cat_Id){
	if(value !== ""){
        getCategorys(value);
		setTimeout( function(){
			if($('.run-cat').val() == "Yes"){				
				editCategories(value , cat_Id);
			}
		}, 700);
	}else{
		$('.error-category').html('Category is required.');
	}
}

function ajaxCategories(value){
	$.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>create-category",
		data: {
				value: value,
			  },
		success: function(result){
		}
	});
}

	function editCategories(value , cat_Id){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>admin/Categories/editcategory",
			data: {
					value: value,
					cat_Id: cat_Id,
				  },
			success: function(result){
			}
		});
	}

	function getCategorys(value){
	    var value = value.toLowerCase();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>admin/Categories/get_category",
			data: {
			   cat_name : value,
			},
			success: function(result){      
		
			   var response = jQuery.parseJSON(result.response);		  
			   if(response !== false){
					var url = window.location.href;
					if (url.indexOf("create-product") > -1 || url.indexOf("edit-product") > -1){ 
						$('#category_names').append($("<option selected></option>").attr("value",response[0].id).text(response[0].category_name)); 
                    }else{		   
						if(response[0].category_name == value){
							$('.error-category').html('Category already exist.');
							$('.run-cat').val('No');
							setTimeout( function (){				
								$('.error-category').text("");
							}, 2000);
						}
                    }
			   }else{		
				   $('.run-cat').val('Yes');
			   }
			}
		});
	}

</script>