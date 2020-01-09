<?php $role_id = $this->session->userdata('login')->role_id; ?>
	<style>
		.sourcing-orders-table .sourcing-note {width: 150px;}
	</style>
	<div class="inner-pages">	
		<?php if($this->session->flashdata('delete_cat')){ ?>
			<div class="successmsg" id="suces">
				  <?php echo $this->session->flashdata('delete_cat');  ?>
			</div>
		<?php }?>		
		<?php if($this->session->flashdata('success_msg')){ ?>
			<div class="successmsg" id="source_saves">
				  <?php echo $this->session->flashdata('success_msg');  ?>						 
			</div>
		<?php }?>
		<?php if($this->session->flashdata('status-successmsg')){ ?>
			<div class="successmsg" id="suces"> 
				  <?php echo $this->session->flashdata('status-successmsg');  ?>						 
			</div>
		<?php }?>		
		<div class="site-sourcing-page soucingpageTable">	
			<div class="container">	
				<div class="sourcing-detail-table">
					<div class="sourcing-detail-search">
						<div class="sourcing-search-inp">
							<input id="ajaxproduct" class="search-inp" placeholder="Input product name">
							<a class="search-btn" href="javascript:void(0)" id="ajxproductsearch">Search</a>
						</div>
						<select class="select-search-stu" id="select-status">
							<option value=" ">All Status</option>
							<option value="0">On Sourcing</option>
							<option value="1">Sourcing Success</option>
							<option value="2">Sourcing Fail</option>
							
						</select>
						<?php if($role_id == 2){ ?>
							<a class="sourcing-export-orders" href="<?=base_url()?>add-sourcing">Post Sourcing Request</a>
						<?php } ?>
					</div>
					<div class="sourcing-orders-list">
						<table class="sourcing-orders-table">
							<thead>
								<tr>
									<th>
										<span>Updated Date</span>
									</th>
									<th>Sourcing ID</th>
									<th>Store Url</th>
									<th>Status</th>
									<th>Product Name</th>
									<th>Images</th>
									<th>URL</th>
									<th>Price</th>                               
									<th>Note</th>
									<th>Action</th>
									<?php if($role_id==1 || $role_id==3){ ?>
										<th>Add Product</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody id='employeeList1'></tbody>					    
						</table>
						<div id="sourcing-list-empty" style="text-align: center;width: 100%;float: left;margin-top: 20px;font-size: 20px;color: #0083c4;"></div>					
					</div>
				</div>
				<div id='pagination'></div> <br>
			</div>
		</div>
	</div>

	<div class="overlay" style="display:none;"></div>
	<div id="delete_category" class="common_popup"  title="Delete product" style="display:none;">
	    <img id="loadingimg" style="width:20px;display:none;" src="<?=base_url('assets/images/')?>/load_2.gif">
	   <p><span class=""></span>Are you sure to delete this product from sourcing list permanently?</p>
	</div>
	<div id="delete_category_confirm" class="common_popup delete_popup" title="Delete category" style="display:none;">  
		<p><span class="ui-icon ui-icon-alert"></span>Product has been deleted successfull.</p>
	</div>
	
	<script>
		$(function() {
			setTimeout(function() {
				$("#suces").text("") 
			}, 3000);
			 setTimeout(function() {
				$(".status-successmsg").text("") 
			}, 3000);
		}); 
		
		$(document).ready(function() {
			var siteurl = "<?php echo base_url();?>";
			createPagination(0);
			$('#pagination').on('click','a',function(e){
				e.preventDefault();
				var pageNum = $(this).attr('data-ci-pagination-page');
				var product_status = $("#select-status").val();
				if(product_status==" "){
					createPagination(pageNum);
				}else{
					createPagination_by_staus(pageNum,product_status);
				} 
			});
			
			function createPagination(pageNum){
				$.ajax({
					url: '<?=base_url()?>sourcinglist/loadData/'+pageNum,
					type: 'get',
					dataType: 'json',
					success: function(responseData){
						$('#pagination').html(responseData.pagination);
						paginationData(responseData.empData);
					}
				});
			}
			
			function paginationData(data) {
				$('#employeeList1').empty();
				var counter =0;				
				for(emp in data){
				    var img_url=data[emp].sourcing_image;	
					var parsed_img_url = img_url.split(',');
					var notes = data[emp].note;
					var regX = /(<([^>]+)>)/ig;
					var note =  notes.replace(regX, "");
					var status = data[emp].status;
					if (status==0){
						status ="On Sourcing"; 
					}else if(status ==1 ){
						status ="Sourcing Success"; 
					}else{
						status ="Sourcing Fail"; 
					}
					<?php if($role_id==1){ ?>
						var role = "<a class='src-detail-button req-approve' id="+ data[emp].id +" title='Approve'>  <i class='fa fa-check' aria-hidden='true'></i> </a><a class='src-detail-button decline-req' id="+ data[emp].id+" href='javascript:void(0)' title='Decline'><i class='fa fa-ban' aria-hidden='true'></i> </a>";				  
					<?php }else{ ?>
						var role = "";
					<?php } ?>				
					<?php if($role_id == 1 || $role_id == 3){ ?>
						if(data[emp].admin_status == 0){
						  var linkaddpro = "<a class='add-pro-button' title='Add Product' href='"+siteurl+"create-product?src_id="+data[emp].id+"&req_by="+data[emp].u_id+"'>Add Product</a>";
						}else{
						  var linkaddpro = "Added";
						}
						var addproduct = "<td class='add-product'>"+ linkaddpro +"</td>";
					<?php }else{ ?>
						var addproduct = "";
					<?php } ?>
					var date_created = moment(data[emp].created_date).format('DD-MM-YYYY');
					var empRow = "<tr class='s-order-detail bugrow sourcing"+ data[emp].id +"'>";
						empRow += "<td class='sourcing-order-time'><div class='sourcing-time-div'><span class='sourcing-binding'>"+ date_created +"</span>";
						empRow += "<td class=''><p class='sourcing-order-scope'>"+ data[emp].id +"</p></td>";
						empRow += "<td><p class='sourcing-order-scopes'><span style='color: #ccc;'>"+ data[emp].store_url  + "</span></p></td>";
						empRow += "<td class='status'><p class='sourcing-order-scopes'><span>"+ status +"</span></p></td>";
						empRow += "<td><p class='sourcing-order-scope'><span>"+ data[emp].product_title +"</span></p></td>";
						
						if(img_url==""){
							empRow += "<td class='source-product-showing'><img class='small-img-source' src='"+ data[emp].sourcing_image_url +"'></td>'";
						}else{
							  empRow += "<td class='source-product-showing'><img class='small-img-source' src='<?=base_url('uploads/sourcing/')?>"+ parsed_img_url[0] +"'></td>'";
						}
				
						empRow += "<td class='source-link-id' style='width:100px;'><a href="+ data[emp].sourcing_url+" target='_blank'>"+data[emp].sourcing_url+"</a></td>";
						empRow += " <td class='source-product-pr'><p class='sourcing-order-scope'>USD"+ data[emp].product_price +"</p></td>";	
						empRow += "<td class='sourcing-note'><p>"+ note.substring(0,30) +"<a target='_blank' href='<?=base_url('sourcinglist/product-detail-page/')?>"+ data[emp].id +"'>...</a></p></td>";		  
						empRow += "<td class='table-view-del-btn'><a class='src-detail-button' target='_blank' title='View details' href='<?=base_url('sourcinglist/product-detail-page/')?>"+ data[emp].id +"'><i class='fa fa-eye'></i></a><a class='src-del-button sourcing-product' href='javascript:void(0)' data-id="+ data[emp].id +" title='Delete'><i class='fa fa-trash' aria-hidden='true'></i></a>"+ role +"</td>";						
						empRow += addproduct;						
						empRow += "</tr>";	
					$('#employeeList1').append(empRow); 					
				}       
				$(".sourcing-orders-table .table-view-del-btn").css("width","70px");
				$(".sourcing-orders-table .source-link-id").css("width", "150px");
				$(".sourcing-orders-table .sourcing-order-time").css("width", "110px");
				if($('#employeeList1').text()==""){
				   $('#sourcing-list-empty').html("<h3 style='text-align:center;'>No product in the sourcing list.</h3>")
				}
			}
			function paginationData1(data) {	
			
				$('#employeeList1').empty();  
				for(emp in data){
					var notes = data[emp].note;
					var regX = /(<([^>]+)>)/ig;
					var note =  notes.replace(regX, "");
					var img_url=data[emp].sourcing_image;	
					var parsed_img_url = img_url.split(',');
					var status = data[emp].status;
					var count_record = data[emp].id;				
					if (status==0){
						status ="On Sourcing"; 
					}else if(status ==1 ){
						status ="Sourcing Success"; 
					}else{
						status ="Sourcing Fail"; 
					}
					<?php  $role_id = $this->session->userdata('login')->role_id;
						if($role_id==1){ ?>
							var role = "<a class='src-detail-button req-approve' id="+ data[emp].id +" title='Approve'> <i class='fa fa-check' aria-hidden='true'></i> </a><a class='src-detail-button decline-req' id="+ data[emp].id+" href='javascript:void(0)' title='Decline'><i class='fa fa-ban' aria-hidden='true'></i> </a>";
					<?php }else{?>
							var role = "";	
					<?php } ?>
					<?php if($role_id == 1 || $role_id == 3){ ?>
						if(data[emp].admin_status == 0){
						  var linkaddpro = "<a class='add-pro-button' title='Add Product' href='"+siteurl+"create-product?src_id="+data[emp].id+"&req_by="+data[emp].u_id+"'>Add Product</a>";
						}else{
						  var linkaddpro = "Added";
						}
						var addproduct = "<td class='add-product'>"+ linkaddpro +"</td>";
					<?php }else{ ?>
						var addproduct = "";
					<?php } ?>
					var date_created = moment(data[emp].created_date).format('DD-MM-YYYY');
					var empRow = "<tr class='s-order-detail bugrow sourcing"+ data[emp].id +"'>";
						empRow += "<td class='sourcing-order-time'><div class='sourcing-time-div'><span class='sourcing-binding'>"+ date_created +"</span>";                                      
						empRow += "<td class=''><p class='sourcing-order-scope'>"+ data[emp].id +"</p></td>";
						empRow += "<td><p class='sourcing-order-scopes'><span style='color: #ccc;'>"+ data[emp].store_url  + "</span></p></td>";
						empRow += "<td class='status'><p class='sourcing-order-scopes'><span>"+ status +"</span></p></td>";
						empRow += " <td><p class='sourcing-order-scope'><span>"+ data[emp].product_title +"</span></p></td>";
						if(img_url==""){
							empRow += "<td class='source-product-showing'><img class='small-img-source' src='"+ data[emp].sourcing_image_url +"'></td>'";
						}else{
							  empRow += "<td class='source-product-showing'><img class='small-img-source' src='<?=base_url('uploads/sourcing/')?>"+ parsed_img_url[0] +"'></td>'";
						}
						empRow += "<td class='source-link-id'><a target='_blank' href='"+ data[emp].sourcing_url +"'>"+ data[emp].sourcing_url +"</a></td>";
						empRow += " <td class='source-product-pr'><p class='sourcing-order-scope'>USD"+ data[emp].product_price +"</p></td>";	
						empRow += "<td class='sourcing-note'><p>"+ note.substring(0,30) +"<a target='_blank' href='<?=base_url('sourcinglist/product-detail-page/')?>"+ data[emp].id +"'>...</a></p></td>";		  
						empRow += "<td class='table-view-del-btn'><a class='src-detail-button' href='<?=base_url('sourcinglist/product-detail-page/')?>"+ data[emp].id +"'><i class='fa fa-eye'></i></a><a class='src-del-button sourcing-product' href='javascript:void(0)' data-id="+ data[emp].id +"><i class='fa fa-trash' aria-hidden='true'></i></a>"+ role +"</td>";
						empRow += addproduct;
						empRow += "</tr>";	
					$('#employeeList1').append(empRow);		
				}
			    if($('#employeeList1').text()==""){
				   $('#sourcing-list-empty').html("<h3 style='text-align:center;'>No product in the sourcing list.</h3>");
			    }
			}
			function paginationData_by_status(data) {
				$('#employeeList1').empty();
				for(emp in data){
					var notes = data[emp].note;
					var regX = /(<([^>]+)>)/ig;
					var note =  notes.replace(regX, "");
					var img_url=data[emp].sourcing_image;
					var status = data[emp].status;
					if (status==0){
						status ="Pending"; 
					}else if(status ==1 ){
						status ="Approved"; 
					}else{
						status ="Decline"; 
					}
				
					<?php $role_id = $this->session->userdata('login')->role_id;
						    if($role_id==1){ ?>
								var role = "<a class='src-detail-button req-approve' id="+ data[emp].id +" title='Approve'> <i class='fa fa-check' aria-hidden='true'></i> </a><a class='src-detail-button decline-req' id="+ data[emp].id+" href='javascript:void(0)' title='Decline'><i class='fa fa-ban' aria-hidden='true'></i> </a>";
					<?php   }else{?>
						var role = "";
					<?php } ?>
					<?php if($role_id == 1 || $role_id == 3){ ?>
						if(data[emp].admin_status == 0){
						  var linkaddpro = "<a class='add-pro-button' title='Add Product' href='"+siteurl+"create-product?src_id="+data[emp].id+"&req_by="+data[emp].u_id+"'>Add Product</a>";
						}else{
						  var linkaddpro = "Added";
						}
						var addproduct = "<td class='add-product'>"+ linkaddpro +"</td>";
					<?php }else{ ?>
						var addproduct = "";
					<?php } ?>
				var date_created = moment(data[emp].created_date).format('DD-MM-YYYY');
					var empRow = "<tr class='s-order-detail bugrow sourcing"+ data[emp].id +"'>";
						empRow += "<td class='sourcing-order-time'><div class='sourcing-time-div'><span class='sourcing-binding'>"+ date_created +"</span>";
						empRow += "<td class=''><p class='sourcing-order-scope'>"+ data[emp].id +"</p></td>";
						empRow += "<td><p class='sourcing-order-scopes'><span style='color: #ccc;'>"+ data[emp].store_url  + "</span></p></td>";
						empRow += "<td class='status'><p class='sourcing-order-scopes'><span>"+ status +"</span></p></td>";
						empRow += " <td><p class='sourcing-order-scope'><span>"+ data[emp].product_title +"</span></p></td>";
						if(img_url==""){
							empRow += "<td class='source-product-showing'><img class='small-img-source' src='"+ data[emp].sourcing_image_url +"'></td>'";
						}else{
							empRow += "<td class='source-product-showing'><img class='small-img-source' src='<?=base_url('uploads/sourcing/')?>"+ data[emp].sourcing_image +"'></td>'";
						}
						empRow += "<td class='source-link-id'><a target='_blank' href='"+ data[emp].sourcing_url +"'>"+ data[emp].sourcing_url +"</a></td>";
						empRow += " <td class='source-product-pr'><p class='sourcing-order-scope'>USD"+ data[emp].product_price +"</p></td>";	
						empRow += "<td class='sourcing-note'><p>"+ note.substring(0,30) +"<a target='_blank' href='<?=base_url('sourcinglist/product-detail-page/')?>"+ data[emp].id +"'>...</a></p></td>";	  
						empRow += "<td class='table-view-del-btn'><a class='src-detail-button' href='<?=base_url('sourcinglist/product-detail-page/')?>"+ data[emp].id +"'><i class='fa fa-eye'></i></a><a class='src-del-button sourcing-product' href='javascript:void(0)' data-id="+ data[emp].id +"><i class='fa fa-trash' aria-hidden='true'></i></a>"+ role +"</td>";
						empRow += addproduct;
						empRow += "</tr>";	
					$('#employeeList1').append(empRow);
				}
			}
			
			/*************************************/
			
			$("body").on("click", "#ajxproductsearch", function(event){
				event.preventDefault();
				var product_name = $("#ajaxproduct").val();
				$.ajax({
					type: "POST",	
					url: '<?=base_url()?>sourcinglist/product_search/',
					data: {product_name: product_name},
					dataType: 'json',
					success: function(responseData){
						$('#pagination').html(responseData.pagination);
						paginationData1(responseData.empData);				
					}
				});
			});
			
			/*********************Approve the sourcing request ******************/
			
			$("body").on("click", ".req-approve", function(event){
				event.preventDefault();
				var product_id = $(this).attr('id').match(/\d+/);
				$.ajax({
					type: "POST",	
					url: '<?=base_url()?>sourcinglist/approve_product_status/',
					data: {product_id: product_id},
					dataType: 'json',
					success: function(responseData){
						  window.location.replace('<?php echo base_url(); ?>products-sourcing-list');
					}
				});
			});
			
			/*********************Decline the sourcing request ******************/			
			$("body").on("click", ".decline-req", function(event){
				event.preventDefault();
				var product_id = $(this).attr('id').match(/\d+/);
				$.ajax({ 
					type: "POST",
					url: '<?=base_url()?>sourcinglist/decline_product_status/',
					data: {product_id: product_id},
					dataType: 'json',
					success: function(responseData){
						  window.location.replace('<?php echo base_url(); ?>products-sourcing-list');
					}
				});
			});
			
			$("body").on("change", "#select-status", function(event){
				var product_status =   $(this).val();
				var pageNum =0;
				createPagination_by_staus(0,product_status);		
			});
				
		    function createPagination_by_staus(pageNum,product_status){
				$.ajax({
					url: '<?=base_url()?>sourcinglist/get_product_bystatus/'+pageNum,
					type: 'POST',
					data: {product_status: product_status},
					dataType: 'json',
					success: function(responseData){
						$('#pagination').html(responseData.pagination);
						paginationData(responseData.empData);
					}
				});
			}
			
			setTimeout(function() {
				$("#source_saves").hide('blind', {}, 500)
			}, 5000);
		});
</script>