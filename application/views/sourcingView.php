<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script> 
<script>
$(document).ready(function() {	
  var filter_ByShop = $("#filter_ByShop").val();
      $("#store_url").val(filter_ByShop);
	$( "#filter_ByShop" ).change(function(){
		$("#form_filter_ByShop").submit();		
	});	
});
</script>
<?php
$last = $this->uri->total_segments();
$record = $this->uri->segment($last);
if($record=='create-sourcing-product'){
?>
<script> 
$(document).ready(function() {
	$( "#sourcingbtn2" ).prop("checked", true).trigger("click");
	$("#sourcing2").show();
	$("#sourcing1").hide();
});
</script>
<?php }	?>
<div class="inner-pages">
	<?php if($this->session->flashdata('success_msg')){ ?>
		<div class="successmsg" id="source_saves">
			  <?php echo $this->session->flashdata('success_msg');  ?>
				<script>						  
					setTimeout(function() {
					   $("#source_saves").hide('blind', {}, 500)
					   window.location.href = "<?=base_url()?>products-sourcing-list";
					}, 500);
				 </script>
		</div>					
	<?php }?>
	<div class="site-sourcing-page" id="productsourcing-request-page">	  
		<div class="container">		     
			<div class="addproducts-con">
				<div class="addpro-title">
					<p>Sourcing Form</p>
				</div>
				    <div class="existed-indv-product">
						<span>Select Sourcing Type:</span>
						<label class="demo-label">
							<input checked id="sourcingbtn1" class="demo-radio ng-untouched ng-valid ng-not-empty ng-dirty ng-valid-parse" name="type" ng-model="commoditytype" ng-change="radioFun1()" value="store" type="radio">
							<span class="demo-radioInput"></span>Store Existed Product 
						</label>
						<label class="demo-label">
							<input id="sourcingbtn2" class="demo-radio ng-untouched ng-valid ng-not-empty ng-dirty" name="type" ng-model="commoditytype" ng-change="radioFun2()" value="individual" type="radio">
							<span class="demo-radioInput"></span>Individual Product
						</label>
				    </div>					
				<div class="sourcing-addpro-main" id="sourcing1">					
					<div class="addpro-detail">
						<ul class="store-existed">
							<li>
								<div class="sourcing-left-label">
									<span>Select Store:</span>
								</div>		
								<form method="get" id="form_filter_ByShop">
									<select name="shop_name" id="filter_ByShop">
											<?php foreach($shops as $store){ ?>
												<option value="<?php echo $store->shop_name;?>"
												<?php if(isset($_GET['shop_name']) && $_GET['shop_name']== $store->shop_name){
														echo 'selected';
												} ?>> <?php echo $store->shop_name;?>
												</option>
											<?php }?>
									</select> 
								</form>
								<a class="update" href="#">Update</a>
							</li>
							<li>
								<div class="sourcing-left-label">
									<span>Product Name:</span>
								</div>
								<div class="input-group search-box">
									<input class="form-control ng-pristine ng-valid ng-empty ng-touched"  name="ajaxproduct"  id="ajaxproduct" placeholder="Enter product name">
									<button id="ajxproductsearch" class="btn btn-default" href="javascript:void(0)">Search</button>
								</div>
							</li>
						</ul>						
					</div>
<img id="loading-image" style="display:none;" src="<?=base_url('assets/images')?>/load_2.gif">
				<div id='store-products-tables'>
							<div class="store-products-tables-inner">
								<div class="sourcing-orders-list">
									<table class="sourcing-orders-table">
										<thead>
											<tr>
												<th>
													<input id="selectall-checkbox" type="checkbox" style="display:block">
												</th>
												<th class="sourcing-list-images">Images</th>
												<th>Sourcing ID</th>
												<th>Store Url</th>
												<th>Product Name</th>
												<th>URL</th>
												<th>Price</th>                               
												<th>Note</th>
											</tr>
										</thead>
										<tbody id="employeeList">		
										</tbody>									
									</table>					
								</div>
							</div>
						</div>	
						
						<div id="recordCount" style="display:none">
						<?php 
							if($recordCount==1){
								echo $recordCount.' record found';
							}else{
								echo $recordCount.' records found';
							}
						?>
					</div>
					 <div id='pagination'></div>
					<div class="addpro-buttons">
						<a class="add-pro-cancel" href="#">cancel</a>
						<a class="add-pro-submit" id="submitsource" href='javascript:void(0)'>submit</a>
					</div>
					</form>	
					</div>			
		
				<form method="post" action="create-sourcing-product"  enctype="multipart/form-data">	
			    <div class="sourcing-addpro-main" id="sourcing2" style="display:none;">					
				  <div class="addpro-detail indiv-prdct">					
		           <ul class="individual-product">
					  <li>
						<div class="sourcing-left-label">
								<span>Product Name:</span>
						</div>
						   <div class="input-group search-box ng-hide">
							 <input name="product_title" class="form-control">
							 <?php echo form_error('product_title'); ?>
						   </div>
					  </li>					  
					  <li>
						<div class="sourcing-left-label">
							<span>Target Price:</span>
						</div>
					   <div class="usd-pr"><span>USD</span>
						   <div class="input-group">
							  <input name="product_price" id="p-target" class="small-inp" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
							  <?php echo form_error('product_price'); ?>
						   </div>
					   </div>
					   <div class="usd-cntry">
						   <div class="sourcing-left-label country">
								<span>Included Shipping to Main Country </span>
							</div>
							<div class="sourcing-cntry-option">
							<select class="small-inp" id="quick-setup" name="country">
							<option value="" class="" selected="selected">Please Select</option>
							<option value="Afghanistan">Afghanistan</option>
							<option value="Aland Islands">Aland Islands</option>
							<option value="Albania">Albania</option>
							<option value="Algeria">Algeria</option>
							<option value="American Samoa">American Samoa</option>
							<option value="Andorra">Andorra</option>
							<option value="Angola">Angola</option>
							<option value="Anguilla">Anguilla</option>
							<option value="Antarctica">Antarctica</option>
							<option value="Antigua and Barbuda">Antigua and Barbuda</option>
							<option value="Argentina">Argentina</option>
							<option value="Armenia">Armenia</option>
							<option value="Aruba">Aruba</option>
							<option value="Australia">Australia</option>
							<option value="Austria">Austria</option>
							<option value="Azerbaijan">Azerbaijan</option>
							<option value="Bahamas">Bahamas</option>
							<option value="Bahrain">Bahrain</option>
							<option value="Bangladesh">Bangladesh</option>
							<option value="Barbados">Barbados</option>
							<option value="Belarus">Belarus</option>
							<option value="Belgium">Belgium</option>
							<option value="Belize">Belize</option>
							<option value="Benin">Benin</option>
							<option value="Bermuda">Bermuda</option>
							<option value="Bhutan">Bhutan</option>
							<option value="Bolivia (Plurinational State of)">Bolivia (Plurinational State of)</option>
							<option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
							<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
							<option value="Botswana">Botswana</option>
							<option value="Bouvet Island">Bouvet Island</option>
							<option value="Brazil">Brazil</option>
							<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
							<option value="Brunei Darussalam">Brunei Darussalam</option>
							<option value="Bulgaria">Bulgaria</option>
							<option value="Burkina Faso">Burkina Faso</option>
							<option value="Burundi">Burundi</option>
							<option value="Cambodia">Cambodia</option>
							<option value="Cameroon">Cameroon</option>
							<option value="Canada">Canada</option>
							<option value="Cabo Verde">Cabo Verde</option>
							<option value="Cayman Islands">Cayman Islands</option>
							<option value="Central African Republic">Central African Republic</option>
							<option value="Chad">Chad</option>
							<option value="Chile">Chile</option>
							<option value="China">China</option>
							<option value="Christmas Island">Christmas Island</option>
							<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
							<option value="Colombia">Colombia</option>
							<option value="Comoros">Comoros</option>
							<option value="Congo">Congo</option>
							<option value="Congo (Democratic Republic of the)">Congo (Democratic Republic of the)</option>
							<option value="Cook Islands">Cook Islands</option>
							<option value="Costa Rica">Costa Rica</option>
							<option value="Côte d'Ivoire">Côte d'Ivoire</option>
							<option value="Croatia">Croatia</option>
							<option value="Cuba">Cuba</option>
							<option value="Curaçao">Curaçao</option>
							<option value="Cyprus">Cyprus</option>
							<option value="Czech Republic">Czech Republic</option>
							<option value="Denmark">Denmark</option>
							<option value="Djibouti">Djibouti</option>
							<option value="Dominica">Dominica</option>
							<option value="Dominican Republic">Dominican Republic</option>
							<option value="Ecuador">Ecuador</option>
							<option value="Egypt">Egypt</option>
							<option value="El Salvador">El Salvador</option>
							<option value="Equatorial Guinea">Equatorial Guinea</option>
							<option value="Eritrea">Eritrea</option>
							<option value="Estonia">Estonia</option>
							<option value="Ethiopia">Ethiopia</option>
							<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
							<option value="Faroe Islands">Faroe Islands</option>
							<option value="Fiji">Fiji</option>
							<option value="Finland">Finland</option>
							<option value="France">France</option>
							<option value="French Guiana">French Guiana</option>
							<option value="French Polynesia">French Polynesia</option>
							<option value="French Southern Territories">French Southern Territories</option>
							<option value="Gabon">Gabon</option>
							<option value="Gambia">Gambia</option>
							<option value="Georgia">Georgia</option>
							<option value="Germany">Germany</option>
							<option value="Ghana">Ghana</option>
							<option value="Gibraltar">Gibraltar</option>
							<option value="Greece">Greece</option>
							<option value="Greenland">Greenland</option>
							<option value="Grenada">Grenada</option>
							<option value="Guadeloupe">Guadeloupe</option>
							<option value="Guam">Guam</option>
							<option value="Guatemala">Guatemala</option>
							<option value="Guernsey">Guernsey</option>
							<option value="Guinea">Guinea</option>
							<option value="Guinea-Bissau">Guinea-Bissau</option>
							<option value="Guyana">Guyana</option>
							<option value="Haiti">Haiti</option>
							<option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
							<option value="Holy See">Holy See</option>
							<option value="Honduras">Honduras</option>
							<option value="Hong Kong">Hong Kong</option>
							<option value="Hungary">Hungary</option>
							<option value="Iceland">Iceland</option>
							<option value="India">India</option>
							<option value="Indonesia">Indonesia</option>
							<option value="Iran (Islamic Republic of)">Iran (Islamic Republic of)</option>
							<option value="Iraq">Iraq</option>
							<option value="Ireland">Ireland</option>
							<option value="Isle of Man">Isle of Man</option>
							<option value="Israel">Israel</option>
							<option value="Italy">Italy</option>
							<option value="Jamaica">Jamaica</option>
							<option value="Japan">Japan</option>
							<option value="Jersey">Jersey</option>
							<option value="Jordan">Jordan</option>
							<option value="Kazakhstan">Kazakhstan</option>
							<option value="Kenya">Kenya</option>
							<option value="Kiribati">Kiribati</option>
							<option value="Korea (Democratic People's Republic of)">Korea (Democratic People's Republic of)</option>
							<option value="Korea (Republic of)">Korea (Republic of)</option>
							<option value="Kuwait">Kuwait</option>
							<option value="Kyrgyzstan">Kyrgyzstan</option>
							<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
							<option value="Latvia">Latvia</option>
							<option value="Lebanon">Lebanon</option>
							<option value="Lesotho">Lesotho</option>
							<option value="Liberia">Liberia</option>
							<option value="Libya">Libya</option>
							<option value="Liechtenstein">Liechtenstein</option>
							<option value="Lithuania">Lithuania</option>
							<option value="Luxembourg">Luxembourg</option>
							<option value="Macao">Macao</option>
							<option value="Macedonia (the former Yugoslav Republic of)">Macedonia (the former Yugoslav Republic of)</option>
							<option value="Madagascar">Madagascar</option>
							<option value="Malawi">Malawi</option>
							<option value="Malaysia">Malaysia</option>
							<option value="Maldives">Maldives</option>
							<option value="Mali">Mali</option>
							<option value="Malta">Malta</option>
							<option value="Marshall Islands">Marshall Islands</option>
							<option value="Martinique">Martinique</option>
							<option value="Mauritania">Mauritania</option>
							<option value="Mauritius">Mauritius</option>
							<option value="Mayotte">Mayotte</option>
							<option value="Mexico">Mexico</option>
							<option value="Micronesia (Federated States of)">Micronesia (Federated States of)</option>
							<option value="Moldova (Republic of)">Moldova (Republic of)</option>
							<option value="Monaco">Monaco</option>
							<option value="Mongolia">Mongolia</option>
							<option value="Montenegro">Montenegro</option>
							<option value="Montserrat">Montserrat</option>
							<option value="Morocco">Morocco</option>
							<option value="Mozambique">Mozambique</option>
							<option value="Myanmar">Myanmar</option>
							<option value="Namibia">Namibia</option>
							<option value="Nauru">Nauru</option>
							<option value="Nepal">Nepal</option>
							<option value="Netherlands">Netherlands</option>
							<option value="New Caledonia">New Caledonia</option>
							<option value="New Zealand">New Zealand</option>
							<option value="Nicaragua">Nicaragua</option>
							<option value="Niger">Niger</option>
							<option value="Nigeria">Nigeria</option>
							<option value="Niue">Niue</option>
							<option value="Norfolk Island">Norfolk Island</option>
							<option value="Northern Mariana Islands">Northern Mariana Islands</option>
							<option value="Norway">Norway</option>
							<option value="Oman">Oman</option>
							<option value="Pakistan">Pakistan</option>
							<option value="Palau">Palau</option>
							<option value="Palestine, State of">Palestine, State of</option>
							<option value="Panama">Panama</option>
							<option value="Papua New Guinea">Papua New Guinea</option>
							<option value="Paraguay">Paraguay</option>
							<option value="Peru">Peru</option>
							<option value="Philippines">Philippines</option>
							<option value="Pitcairn">Pitcairn</option>
							<option value="Poland">Poland</option>
							<option value="Portugal">Portugal</option>
							<option value="Puerto Rico">Puerto Rico</option>
							<option value="Qatar">Qatar</option>
							<option value="Réunion">Réunion</option>
							<option value="Romania">Romania</option>
							<option value="Russian Federation">Russian Federation</option>
							<option value="Rwanda">Rwanda</option>
							<option value="Saint Barthélemy">Saint Barthélemy</option>
							<option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
							<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
							<option value="Saint Lucia">Saint Lucia</option>
							<option value="Saint Martin (French part)">Saint Martin (French part)</option>
							<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
							<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
							<option value="Samoa">Samoa</option>
							<option value="San Marino">San Marino</option>
							<option value="Sao Tome and Principe">Sao Tome and Principe</option>
							<option value="Saudi Arabia">Saudi Arabia</option>
							<option value="Senegal">Senegal</option>
							<option value="Serbia">Serbia</option>
							<option value="Seychelles">Seychelles</option>
							<option value="Sierra Leone">Sierra Leone</option>
							<option value="Singapore">Singapore</option>
							<option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
							<option value="Slovakia">Slovakia</option>
							<option value="Slovenia">Slovenia</option>
							<option value="Solomon Islands">Solomon Islands</option>
							<option value="Somalia">Somalia</option>
							<option value="South Africa">South Africa</option>
							<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
							<option value="South Sudan">South Sudan</option>
							<option value="Spain">Spain</option>
							<option value="Sri Lanka">Sri Lanka</option>
							<option value="Sudan">Sudan</option>
							<option value="Suriname">Suriname</option>
							<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
							<option value="Swaziland">Swaziland</option>
							<option value="Sweden">Sweden</option>
							<option value="Switzerland">Switzerland</option>
							<option value="Syrian Arab Republic">Syrian Arab Republic</option>
							<option value="Taiwan, Province of China">Taiwan, Province of China</option>
							<option value="Tajikistan">Tajikistan</option>
							<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
							<option value="Thailand">Thailand</option>
							<option value="Timor-Leste">Timor-Leste</option>
							<option value="Togo">Togo</option>
							<option value="Tokelau">Tokelau</option>
							<option value="Tonga">Tonga</option>
							<option value="Trinidad and Tobago">Trinidad and Tobago</option>
							<option value="Tunisia">Tunisia</option>
							<option value="Turkey">Turkey</option>
							<option value="Turkmenistan">Turkmenistan</option>
							<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
							<option value="Tuvalu">Tuvalu</option>
							<option value="Uganda">Uganda</option>
							<option value="Ukraine">Ukraine</option>
							<option value="United Arab Emirates">United Arab Emirates</option>
							<option value="United Kingdom of Great Britain and Northern Ireland">United Kingdom of Great Britain and Northern Ireland</option>
							<option value="United States of America">United States of America</option>
							<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
							<option value="Uruguay">Uruguay</option>
							<option value="Uzbekistan">Uzbekistan</option>
							<option value="Vanuatu">Vanuatu</option>
							<option value="Venezuela (Bolivarian Republic of)">Venezuela (Bolivarian Republic of)</option>
							<option value="Viet Nam">Viet Nam</option>
							<option value="Virgin Islands (British)">Virgin Islands (British)</option>
							<option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option>
							<option value="Wallis and Futuna">Wallis and Futuna</option>
							<option value="Western Sahara">Western Sahara</option>
							<option value="Yemen">Yemen</option>
							<option value="Zambia">Zambia</option>
							<option value="Zimbabwe">Zimbabwe</option>
                        </select>
						  <?php echo form_error('country'); ?>
						  </div>
						</div>
					  </li>
					  
					  <li>
						<div class="sourcing-left-label">
							<span>Sourcing URL:</span>
						</div>
					   <div class="input-group">
					   <input id="store_url" class="big-inp" name="store_url" type="hidden"> 
<input id="ap-surl" class="big-inp" pattern="https://.*" name="sourcing_url" placeholder="https://example.com"> 
					   	  <?php echo form_error('sourcing_url'); ?>
					   </div>
					  </li>
					  
			  <li class="images-list">
				
				<div class="sourcing-left-label">
					<span>Images:</span>
				</div>          
				
				<?php /*<div class="input-group">
				<input type="file" class="upload-inp" name="sourcing_image[]" accept="image/*" id="gallery-photo-add" multiple>
				 <img src="<?=base_url('assets/images')?>/iconplus.png">
				 <?php echo form_error('sourcing_image'); ?> 
					 <div class="review-image gallery"></div>
				</div>  */?>
					<div class="input-group">
						<input type="file" class="upload-inp" name="sourcing_image" accept="image/*" onchange="preview_image(event)">
					     <img src="<?=base_url('assets/images')?>/iconplus.png">
						 <?php echo form_error('sourcing_image'); ?> 
						   	 <div class="review-image"> <img id="product_output_image1"> </div>
						</div>	
			  </li>
					  
					  <li class="description-list">
						<div class="sourcing-left-label">
							<span>Description:</span>
						</div>
					   <div class="sourceEditor-container">
						<script type="text/javascript"> CKEDITOR.replace( '#ckeditor' ); </script>
				    	<textarea  id="ckeditor" name="description" class="ckeditor custom-description"></textarea>
					   <?php echo form_error('description'); ?>
					   </div>
					  </li>
                  </ul>
				  <div class="addpro-buttons">
						<a class="add-pro-cancel" href="#">cancel</a>
						<input type="submit" class="add-pro-submit" name="submit" value="submit">
					</div>
				</div>
			</div>			
			</form>
			
		</div>
	</div>
</div>


<div class="overlay" style="display:none;"></div>
	<div id="delete_category" class="common_popup"  title="Sourcing request" style="display:none;">
	   <p><img id="loadingimg" style="width:20px;display:none;" src="<?=base_url('assets/images/')?>/load_2.gif">Are you sure to send us the sourcing request? </p>
	</div>
  <div id="delete_category_confirm" class="common_popup delete_popup" title="Add sourcing request" style="display:none;">  
     <p><span class="ui-icon ui-icon-alert"></span>Product sourcing request has been send successfull</p>
  </div>
  
<script>
 function preview_image(event)  
	{
		
		 var reader = new FileReader();
		 reader.onload = function()
		 {
		   var output = document.getElementById('product_output_image1');
		  // $("#product_output_image1").css({"border":"1px solid #e1e8f0","padding":"10px","height":"100px ","width":"auto","margin-top":"48px","display":"table"});
		    output.src = reader.result;
		 }
		 reader.readAsDataURL(event.target.files[0]);
	}
	
	$(document).ready(function(){
	
	/************ Image preview before uploding image *************/
		
    /*  var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }
                reader.readAsDataURL(input.files[i]);            }
        }
    };
    $('#gallery-photo-add').on('change', function() {   
        imagesPreview(this, 'div.gallery');
    }); */
	
	/***************************** End Here *******************************/
		
		$("#sourcingbtn2").click(function(){
			$("#sourcing2").show();
			$("#sourcing1").hide();
		});
		 $("#sourcingbtn1").click(function(){
			$("#sourcing1").show();
			$("#sourcing2").hide();
		});	
	});

    $(document).ready(function() {
	   createPagination(0);
	    $('#pagination').on('click','a',function(e){
			e.preventDefault(); 
			var pageNum = $(this).attr('data-ci-pagination-page');
			createPagination(pageNum);
	    });
		
	  function createPagination(pageNum){
		  	$("#loading-image").show();
			var shop_name = $("#filter_ByShop").val();		
			$.ajax({
				url: '<?=base_url()?>sourcing/loadData/'+pageNum,
				type: 'get',
				data:{shop_name:shop_name},
				dataType: 'json',
				success: function(responseData){
					$("#loading-image").hide();
					$('#pagination').html(responseData.pagination);
					paginationData(responseData.empData);
				}
			});
	   }
	   
	   function paginationData(data) {
		var store_url = $("#filter_ByShop").val();
		$('#employeeList').empty();		
		/* for(emp in data){
        var img_url=data[emp].images;

	 //   var empRow = "<div class='store-product-listing'>";
		var empRow = "<div id="+data[emp].id+" class='listing-content-product sourcing_image_url'><div class='content-product-image'>";
	    if(img_url==""){ 
			empRow += "<img src='<?=base_url('assets/images')?>/default-placeholder.png'></div>";
		}else{
		    empRow += "<img src="+data[emp].images[0].src+"></div>"; 
		}
	    
		empRow += "<div  class='product_title content-product-image-text'><h5 class='content-product-heading'>"+ data[emp].title +"</h5>";
	    empRow += "<div class='content-product-price'><span class='price product_price'> $ "+ data[emp].variants[0].price +"</span></div>";
	    empRow += "</div></div>";
	  //  empRow += "<div class='body_html' style='display:none'>"+ data[emp].body_html +"</div>";
	  //  empRow += "<div class='product_image' style='display:none'>"+ data[emp].id +"</div>";
	 //   empRow += "</div>";	
		$('#employeeList').append(empRow); 		
    	/* 	empRow += "<img src='<?=base_url('uploads/product')?>"+data[emp].product_img_id+"/"+data[emp].src+"'></div>";
			empRow += "<div class='content-product-image-text'><h5 class='content-product-heading'>"+ data[emp].product_title +"</h5>";
			empRow += "<div class='content-product-price'><span class='product-price'>RRP:</span><span class='price'>"+ data[emp].product_price +"</span></div>";
			empRow += "</div></div>"; 
		//$('#employeeList').append(empRow);				
		} */
		for(emp in data){ 
			var img_url=data[emp].images;
			var notes = data[emp].body_html;
			var regX = /(<([^>]+)>)/ig;
			var note =  notes.replace(regX, ""); 
          var empRow ="<tr>";
               empRow +="<td> <input class='sourcing_image_url' id="+data[emp].id+" type='checkbox'> </td>";
         if(img_url==""){ 
		empRow += "<td><img src='<?=base_url('assets/images')?>/default-placeholder.png'></td>";
		}else{
		empRow += "<td><img src="+data[emp].images[0].src+"></td>"; 	
		}
		empRow +="<td><a href='#'>"+data[emp].id+"</a></td>"; 
		empRow +="<td>https://"+ store_url +"</td><td>"+data[emp].title+"</td>";
		empRow +="<td><a href=https://"+ store_url+"/products/"+data[emp].handle+" target='_blank'> https://"+ store_url+"/products/"+data[emp].handle+"</td><td>$ "+ data[emp].variants[0].price +"</td>";
		empRow +="<td><p>"+ note.substring(0,50) +"...</p></td></tr>";
      $('#employeeList').append(empRow);
		}
		  
      $("#recordCount").show();
	}
	
/* 	function paginationData1(data) {
			$('#employeeList').empty();
			$("#recordCount").text("");
			$("#loading-image").hide();
			for(emp in data){
				var img_url=data[emp].images;
				
			var empRow = "<div id="+data[emp].id+" class='listing-content-product sourcing_image_url'><div class='content-product-image'>";
           // empRow += "<img id="+data[emp].id+" src="+data[emp].images[0].src+"></div>";
			if(img_url==""){ 
			  empRow += "<img src='<?=base_url('assets/images')?>/default-placeholder.png'></div>";
			}else{
			 empRow += "<img src="+data[emp].images[0].src+"></div>"; 
			}
			empRow += "<div class='content-product-image-text'><h5 class='content-product-heading'>"+ data[emp].title +"</h5>";
			empRow += "<div class='content-product-price'><span class='price'>$"+ data[emp].variants[0].price +"</span></div>";
			empRow += "</div></div>";
				$('#employeeList').append(empRow);				
			}
			var count_array = data.length;
			if(data.length==1){
			$("#recordCount").text(data.length+' record found');
			}else{
			$("#recordCount").text(data.length+' records found');
			}
	}
	 */	function paginationData1(data) {
			var store_url = $("#filter_ByShop").val();
				$('#employeeList').empty();	
				$("#recordCount").text("");
				$("#loading-image").hide();		
			for(emp in data){ 
			var img_url=data[emp].images;
			var notes = data[emp].body_html;
			var regX = /(<([^>]+)>)/ig;
			var note =  notes.replace(regX, ""); 
          var empRow ="<tr>";
               empRow +="<td> <input class='sourcing_image_url' id="+data[emp].id+" type='checkbox'> </td>";
         if(img_url==""){ 
		empRow += "<td><img src='<?=base_url('assets/images')?>/default-placeholder.png'></td>";
		}else{
		empRow += "<td><img src="+data[emp].images[0].src+"></td>"; 	
		}
		empRow +="<td><a href='#'>"+data[emp].id+"</a></td>"; 
		empRow +="<td>https://"+ store_url +"</td><td>"+data[emp].title+"</td>";
		empRow +="<td><a href=https://"+ store_url+"/products/"+data[emp].handle+" target='_blank'> https://"+ store_url+"/products/"+data[emp].handle+"</td><td>$ "+ data[emp].variants[0].price +"</td>";
		empRow +="<td><p>"+ note.substring(0,50) +"...</p></td></tr>";
      $('#employeeList').append(empRow);
		}
		  
         	var count_array = data.length;
			if(data.length==1){
			$("#recordCount").text(data.length+' record found');
			}else{
			$("#recordCount").text(data.length+' records found');
			}
	}
	
	
	/*************************************/
	
	$("#ajxproductsearch").click(function (e) { 
		var str = $("#ajaxproduct").val();
		var product_name = str.split(' ').join('+');
		var shop_name = $("#filter_ByShop").val();
		var pageNum =0;
		$("#loading-image").show();
	    $.ajax({
			type: "get",	
			url: '<?=base_url()?>sourcing/product_search/',
			data: {product_name: product_name,shop_name: shop_name},
			dataType: 'json',
			success: function(responseData){
					$('#pagination').html(responseData.pagination);
				paginationData1(responseData.empData);				
		     }			
		 });
	});
		
/****************** Add for sourcing ****************/
		$("body").on("click", ".sourcing_image_url", function(event){
		$( this ).toggleClass("active_source_img");
	    });
		
		$("#selectall-checkbox").change(function(){
			 $(".sourcing_image_url").toggleClass("active_source_img").attr("checked",$(this).prop("checked"));
		});

		$("body").on("click", "#submitsource", function(event){ 
		event.preventDefault();
		if ($(".sourcing_image_url").hasClass("active_source_img")) {					
		var shopname = $('#filter_ByShop').val();		
		 var arr = [];
		  i = 0;
		  $( ".active_source_img" ).each(function() {
           arr[i++] = $(this).attr('id');
	         }); 
		 	// var myJsonString = JSON.stringify(arr);
		
		///////////////////////
		$("#delete_category").dialog({
		  modal: true,
		  buttons: {
			"Yes": function() {
				$("#loadingimg").show();
				$.ajax({
					type: "get",	
					url: '<?=base_url()?>sourcing/insert_source_product/',
					data: {product_ids: arr,shop_name: shopname},
					dataType: 'json',
					success: function(data){
						$("#loadingimg").hide();	   
					   //setTimeout( function(){
						   
							window.location.replace('<?php echo base_url(); ?>products-sourcing-list');
						//}, 1000); 			
					},
                   error: function(data){	
				 		  
				   }				   
				});
			},
			Cancel: function() {
			  $(this).dialog( "close" );
			}
		  }
		});	
		
		var modal = $('#delete_category').dialog('isOpen');
		if(modal == true){
			$('.ui-dialog').addClass('delete_popup');
			$('.overlay').show();
		} 
		$('#delete_category').on('dialogclose', function(event) {
			$('.ui-dialog').removeClass('delete_popup');
    	$('.overlay').hide();
 		});
		
		///////////////////////
		
		
		/*  $.ajax({
			type: "POST",	
			url: '<?=base_url()?>sourcing/insert_source_product/',
			data: {product_ids: arr,shop_name: shopname},
			dataType: 'json',
			success: function(responseData){
					$('#pagination').html(responseData.pagination);
				paginationData1(responseData.empData);				
		     }			
		 });
		 
	   }); */
	}
		});
		
	
	
/******************** End Here **********************/
});
</script>


