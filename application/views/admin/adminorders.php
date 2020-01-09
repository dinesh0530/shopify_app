<script>
	$(document).ready(function() {
	$('#check_myorder').click(function(event) { 
	   if($('#check_myorder').is(':checked')){ 
		 $('.all_myOrder').each(function() {	
					this.checked = true;					
                });
			}
			else
			{
                $('.all_myOrder').each(function() {
                    this.checked = false; 
                });        
            }
		});
			$('.all_myOrder').click(function(event){ 
				if($('#check_myorder').prop("checked") == true){
					$('#check_myorder').prop('checked', false);
				}
				var i = 0;
				var numItems = $('.all_myOrder').length;
				$('.all_myOrder').each(function() {
					if($(this).prop("checked") == true){
						i++;
					}
				
				});
				if(i == numItems){
					$('#check_myorder').prop('checked', true);
				}
            });
			
	});

</script>
<?php defined('BASEPATH') OR exit('No direct script access allowed');
 if(!empty($end_date)){
	$end_date = $end_date;
}else{
	$end_date = date("Y-m-d");
}

if(!empty($start_date)){
	$start_date = $start_date;
}else{
	$start_date = date("Y-m-d", strtotime("-7 days"));
} 
?>
    <div class="My-orders-Page">
	    <div class="container">		
			<div class="order-tabs">
				
			</div>			
			<div class="cal-search-delete-order admin">
				<div class="cal-search-delete-order-inner">
				    <?php echo form_open('admin/orders'); ?>
						<div class="startdate">
							<input type="text" name="start_date" id="start_date" value="<?=$start_date?>">
						</div>
						<div class="enddate">
							<input type="text" name="end_date" id="end_date" value="<?=$end_date?>">
						</div>
						<div class="order_search">
							<input type="text" name="order_search" id="order_search" placeholder="Enter order number" oninput = "this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1')" value="<?=$order_no?>">
						</div>
						<button type="submit" class="btn start-date">Search</button>
					<?php echo form_close(); ?>
					<div class="import-export-order">
					<a id="export_order" href="JavaScript:void(0);">Export Orders</a>
				</div>
					<a id="delete_order" href="JavaScript:void(0);">Delete</a>
				</div>
			</div>
			<?php if($this->session->flashdata('delete_Order')){ ?>
				<div class="successmsg" id="orders_dlt_msg">  
					<?php echo $this->session->flashdata('delete_Order');?>
				</div>
			<?php }?>
			<div class="admin-orders">	
				<?php if(!empty($orders)){?>
				<div class="order-page-table my_orders_table">
					<div class="order-page-head">
						<div class="order-page-heading checkbox">
							<input id="check_myorder" type="checkbox">
						</div>
						<div class="order-page-heading order-no">
							Order no.
						</div>
						<div class="order-page-heading transaction_id">
							Transaction id
						</div>
						<div class="order-page-heading date">
							Date
						</div>
						<div class="order-page-heading customer">
							Customer Name
						</div>
						<div class="order-page-heading total-amount">
							Total amount
						</div>
						<div class="order-page-heading status">
							Status
						</div>
					</div>
					<div class="order-page-body ">
						<?php
							$i = 1;
						foreach ($orders as $order) {?>
						<div class = "tabledata">
							<div class="order-page-row">								
								<div class="order-page-col checkbox"><input class="all_myOrder orders_id" id = "ordrId_<?php echo $i; ?>" type="checkbox" data-id="<?php echo $order['id']; ?>"></div>
								<div class="order-page-col orderno"><span class="order-no"><a class="order_number order_no1" href="#"><?php echo $order['id']; ?></a></span></div>
								<div class="order-page-col transaction_id"><?php echo $order['transaction_id']; ?></div>
								<div class="order-page-col date crtdate">
								<?php $cre_date = explode(' ', $order['created_at']);									
								$newDate = date("jS F, Y", strtotime($cre_date[0]));
								$time = explode('-', $cre_date[1]);  ?>
								<span class = "dateset"><?php 
								echo $newDate."  ".$time[0]; 
								?></span>
								</div>
								<div class="order-page-col customer customername1"><?php echo $order['firstname']." ".$order['lastname'];?></div>
								<div class="order-page-col total-amount amount1">$<?php echo$order['amount'];?>
								</div>
								<div class="order-page-col status"><?php echo$order['status'];?>
								</div>
							</div>
							<div class="order-page-pro-row" style="display:none;">
								<div class="order-page-head">
									<div class="order-page-heading pro-img">
										Product Image
									</div>
									<div class="order-page-heading date">
										Title
									</div>
									<div class="order-page-heading customer">
										SKU
									</div>
									<div class="order-page-heading fulfillment-status">
										Quantity
									</div>
									<div class="order-page-heading wholesale-price">
										Wholesale price
									</div>
									<div class="order-page-heading total-price">
										Total price
									</div>
								</div>
								<div class="order-page-body">
									<?php  $ab = 1;
										foreach($order[0] as $orderPro){
									?>
									<div class = "poptab11">
										<div class="order-page-row ordered-product poptab1" id ="pop1Id_<?php echo $ab; ?>" data-id="<?php echo $order['id']; ?>">
											<div class="order-page-col product-item pro-img imgsorce1"><img   src="<?php echo site_url('uploads/product'.$orderPro[0]->id.'/'.$orderPro[0]->src); ?>"/></div>
											<div class="order-page-col product-item pro-name nme1"><?php echo $orderPro[0]->product_title ; ?></div>
											<div class="order-page-col product-item pro-sku SKU1"><?php if(!empty($orderPro[0]->product_sku)){
															echo $orderPro[0]->product_sku;
														}else{
															echo "____";
														} ?></div>
											<div class="order-page-col product-item pro-qty quanty1"><?php echo $orderPro['or_qty']; ?></div>
											<div class="order-page-col product-item wholesale-price">$<?php echo $orderPro[0]->wholesale_price; ?></div>
											<div class="order-page-col product-item total-price">$<?php echo $orderPro[0]->wholesale_price*$orderPro['or_qty']; ?></div>
										</div>
									<?php   $ab++; 
									?> </div> <?php 
									} ?>
								</div>
							</div>
						</div>
						<?php  $i++;  } ?>
					</div>
				</div>
				<div class="supplier_pagination"><?php echo (isset($links) ? $links : ''); ?></div>
				<?php }else{ ?>
				   <div class="no-orders">No orders found</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="modal orders-products-models"></div>
	<div id="alert_products" class="common_popup delete_popup"  title="Delete products" style="display:none;">
		<p><span class="ui-icon ui-icon-alert"></span>Are you want to delete products?</p>
	</div>
	<script>
		$(document).ready(function() {
			var siteurl = "<?php echo base_url();?>";
			$( "#end_date" ).datepicker({
				showOn: "button",
				buttonImage: "<?php echo base_url();?>assets/images/calendar.png",
				dateFormat: "yy-mm-dd"
			});
			
			$( "#start_date" ).datepicker({
				showOn: "button",
				buttonImage: "<?php echo base_url();?>assets/images/calendar.png",
				dateFormat: "yy-mm-dd"
			});
		
			$('a.order_number').click( function(event){
				event.preventDefault();
				var html = $(this).parent().parent().parent('div.order-page-row').next('div.order-page-pro-row').html();
				$('.orders-products-models').html(html);
				$('.orders-products-models').modal({ fadeDuration: 100 });
			});
			
			/////////////////////  Export admin orders ////////////////////////////

			$('#export_order').click(function(event){
				var CSVString = "";			
				var data = [];   
				var i = 0; 			
				var p = 1;		
				var numItems = $('.poptab1').length;	
				var titles = ["Order no.","Transaction id","Date","Customer name","Total amount","Status","product images","product names","Product SKU","Quantity","Wholesale price","Total price"];
				$('.tabledata').each(function(){ 
					if($('#ordrId_'+p).is(':checked')){
						var orderid = $('#ordrId_'+p).attr('data-id'); 
						var number = $(this).find('.order_no1').text(); 				
						var transaction = $(this).find('.transaction_id').text(); 				
						var date = $(this).find('.dateset').text().replace(",",' ');  
						var customer = $(this).find('.customername1').text();
						var amount = $(this).find('.amount1').text();
						var status = $(this).find('.status').text();
						var get_class = $('#ordrId_'+p).parent().parent('.order-page-row').next('.order-page-pro-row').children('.order-page-body').children('.poptab11');
					
						var img1 = new Array();   var name1 = new Array();   var sk1 = new Array();   var quat1 = new Array();  var pri1 = new Array(); var wholesale = new Array();  var akp = 0;
						$(get_class).each(function(){				  
							img1[akp] = $(this).find('.imgsorce1 img').attr('src');
							name1[akp] = $(this).find('.nme1').text();
							sk1[akp] = $(this).find('.SKU1').text();
							quat1[akp] = $(this).find('.quanty1').text();
							wholesale[akp] = $(this).find('.wholesale-price').text();
							pri1[akp] = $(this).find('.total-price').text();				
							akp++;
						});
						var pr = img1.toString();
						var product_images = pr.replace(/,/g,'     ');
						var product_names = name1.toString().replace(/,/g,"     ");
						var product_sku = sk1.toString().replace(/,/g,'     ');
						var product_quantis = quat1.toString().replace(/,/g,'     ');
						var product_wholesale = wholesale.toString().replace(/,/g,'     ');
						var product_prices = pri1.toString().replace(/,/g,'     ');	
						data[i] = [orderid,transaction,date,customer,amount,status,product_images,product_names,product_sku,product_quantis,product_wholesale,product_prices];
					}
					p++; i++;
				});
				CSVString = prepCSVRow(titles, titles.length,'');
				var j = 0;
				$(data).each(function(){   
					if(data[j]){  
						_arr = data[j];
						CSVString = prepCSVRow(_arr, titles.length, CSVString);	
					}
					j++;
				});
				var downloadLink = document.createElement("a");
				var blob = new Blob(["\ufeff", CSVString]);
				var url = URL.createObjectURL(blob);
				downloadLink.href = url;
				downloadLink.download = "my_order.csv";
				document.body.appendChild(downloadLink);
				downloadLink.click();
				document.body.removeChild(downloadLink); 	
			});
			
	
			function prepCSVRow(arr, columnCount, initial) {
				var row = ''; 
				var delimeter = ','; 
				var newLine = '\r\n'; 
				function splitArray(_arr, _count) {
					var splitted = [];
					var result = [];
					_arr.forEach(function(item, idx) {
						if ((idx + 1) % _count === 0) {
							splitted.push(item);
							result.push(splitted);
							splitted = [];
						} else {
							splitted.push(item);
						}
					});
					return result;
				}
				var plainArr = splitArray(arr, columnCount);  
				plainArr.forEach(function(arrItem) {
					arrItem.forEach(function(item, idx) {
					  row += item + ((idx + 1) === arrItem.length ? '' : delimeter);
					});
					row += newLine;
				});
				return initial + row;
			}
		
			////////////////////// Delete otrders ////////////////////////

			$('#delete_order').click(function(){
				var oderId = [];
				var i = 0;
				var p = 1;
				$('.tabledata').each(function() {
					if ($('#ordrId_'+p).is(':checked')){					
						oderId[i] = $('#ordrId_'+p).attr('data-id');					
					}
					i++;  p++;
				});
				$("#alert_products").dialog({
					modal: true,
					buttons: { 
						"Confirm": function() {
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('admin/Adminorders/delete_orders');?>",
								data: {"oderId": oderId },
								success: function(data){
									window.location.replace(siteurl+"admin/orders");
								}
							});
						},
						Cancel: function(){
							$(this).dialog( "close" );
						}
					}
				});	
					
				var modal = $('#alert_products').dialog('isOpen');
				if(modal == true){
					$('.ui-dialog').addClass('delete_popup');
					$('.overlay').show();
				} 
				$('#delete_category').on('dialogclose', function(event) {
					$('.ui-dialog').removeClass('delete_popup');
				$('.overlay').hide();
				});	
			});
			setTimeout( function(){
				$('#orders_dlt_msg').css('display' , 'none');
			}, 5000);		
	    });	
	</script>
