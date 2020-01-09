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
	$allOrders1 = $allOrders2 = $allOrders3  = $allOrders4 = 0; 
	$className1 = $className2 = $className3 = $className4 = 1; 
?>
<script type="text/javascript">
	$(document).ready(function() {		
		var siteurl = "<?php echo base_url();?>";
		$( "#tabs" ).tabs();		
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
		
		$('#check_myorder').click(function(event) {  
            if(this.checked) { 
                $('.all_myOrder').each(function() {
					var check = $(this).parent().parent().parent('div.tabledata').attr('style');
				    if(check == "display: block;"){	
						this.checked = true; 
                    }						
                });
				$('.check_incomplete').each(function() {
                    this.checked = false; 
                });
				$('.check_canceled').each(function() {
                    this.checked = false; 
                });
				$('.check_orderstatus').each(function() {
                    this.checked = false; 
                });
				
                $("#incomplete_order").prop('checked', false);
				$('#canceled_order').prop('checked', false);
				$('#orderstatus_order').prop('checked', false);
            }
			else
				
			{
                $('.all_myOrder').each(function() {
                    this.checked = false; 
                });        
            }
        });	 
		$('.orders_id').click(function(event){
			if($('#check_myorder').is(':checked')){ 
					$('#check_myorder').prop('checked', false);
			}
			else if($('#incomplete_order').is(':checked')){ 
					$('#incomplete_order').prop('checked', false);
			}
			else if($('#canceled_order').is(':checked')){ 
					$('#canceled_order').prop('checked', false);
			}
			else{ 
					$('#orderstatus_order').prop('checked', false);
			}
		}); 
		$('#incomplete_order').click(function(event) {  
            if(this.checked) { 
                $('.check_incomplete').each(function() {
                    var check = $(this).parent().parent().parent('div.tabledata1').attr('style');
				    if(check == "display: block;"){					
						this.checked = true;
					}
                });
				$('.all_myOrder').each(function() { 
                    this.checked = false;     
                });
				$('.check_canceled').each(function() {
                    this.checked = false; 
                });
				$('.check_orderstatus').each(function() {
                    this.checked = false; 
                });
				
				$('#check_myorder').prop('checked', false);
				$('#canceled_order').prop('checked', false);
				$('#orderstatus_order').prop('checked', false);
            }else{
                $('.check_incomplete').each(function() {
                    this.checked = false; 
                });        
            }
        });
		
		$('#canceled_order').click(function(event) {  
            if(this.checked) { 
                $('.check_canceled').each(function() {
					var check = $(this).parent().parent().parent('div.tabledata2').attr('style');
				    if(check == "display: block;"){
						this.checked = true;
                    }						
                });
				$('.all_myOrder').each(function() { 
                    this.checked = false;     
                });
				$('.check_incomplete').each(function() {
                    this.checked = false; 
                });
				$('.check_orderstatus').each(function() {
                    this.checked = false;
                });	
				
				$('#check_myorder').prop('checked', false);
                $("#incomplete_order").prop('checked', false);
				$('#orderstatus_order').prop('checked', false);				
            }else{
                $('.check_canceled').each(function() {
                    this.checked = false; 
                });        
            }
        });
		
		$('#orderstatus_order').click(function(event) {  
            if(this.checked) { 
                $('.check_orderstatus').each(function() {
					var check = $(this).parent().parent().parent('div.tabledata3').attr('style');
				    if(check == "display: block;"){
						this.checked = true;
                    }						
                });
				 $('.check_canceled').each(function() { 
                    this.checked = false;     
                });
				$('.all_myOrder').each(function() { 
                    this.checked = false;     
                });
				$('.check_incomplete').each(function() {
                    this.checked = false; 
                });
				
				$('#check_myorder').prop('checked', false);
                $("#incomplete_order").prop('checked', false);
				$('#canceled_order').prop('checked', false);
            }else{
                $('.check_orderstatus').each(function() {
                    this.checked = false; 
                });        
            }
        });
		
		$("#delete_order").click( function(event){
			event.preventDefault();
			var oderId = [];
			var i = 0;
			$('.orders_id').each(function() {
				if ($(this).is(':checked')){					
			        oderId[i] = $(this).attr('data-id');
					i++;
				}
			});
			
			if(oderId.length > 0){
				$("#dlt_orders").dialog({
					modal: true,
					buttons: {
						"Yes": function() {
							$.ajax({
								type: "POST",
								url: siteurl+"orders/delete_order",
								data: {
										orderid: oderId,
										shop_name: $('#shop_orders').val(),
									  },
								success: function(result){
									window.location.replace(siteurl+"user/orders?shopname="+$('#shop_orders').val());
								},
								error: function(){
									console.log('Error in delete order.');
								}
							});
							
						},
						Cancel: function() {
						  $(this).dialog( "close" );
						}			
					}
				});	
				var modal = $('#dlt_orders').dialog('isOpen');
				if(modal == true){			
					$('.ui-dialog').addClass('delete_popup');
						$('.overlay').show();
				} 
				$('#dlt_orders').on('dialogclose', function(event) {			
					$('.ui-dialog').removeClass('delete_popup');
					
				$('.overlay').hide();
				});
			}
		});
		
		setTimeout( function(){
			$('#orders_dlt_msg').css('display' , 'none');
		}, 5000);
		
		$('a.order_number').click( function(event){
			event.preventDefault();
			var html = $(this).parent().parent().parent('div.order-page-row').next('div.order-page-pro-row').html();
			$('.orders-products-models').html(html);
			$('.orders-products-models').modal({ fadeDuration: 100 });
		});
		
	});
</script>
<div class="My-orders-Page">
	<div class="container">
		<div id="tabs">			
			<div class="order-tabs">
				<ul>
					<li><a href="#tabs-1"> My orders </a></li>
					<li><a href="#tabs-2"> Incomplete orders </a></li>
					<li><a href="#tabs-3"> Canceled orders </a></li>
				 	<li><a href="#tabs-4"> Order status </a></li> 
				</ul>
				<div class="import-export-order">
					<a id="export_order" href="#">Export Orders</a>
				</div>
			</div>			
			<div class="cal-search-delete-order">
				<div class="cal-search-delete-order-inner">
				    <?php echo form_open('user/orders-search'); ?>
						<div class="startdate">
							<input type="text" name="start_date" id="start_date" value="<?=$start_date?>">
						</div>
						<div class="enddate">
							<input type="text" name="end_date" id="end_date" value="<?=$end_date?>">
						</div>
						<div class="order_search">
							<input type="text" name="order_search" id="order_search" placeholder="Enter order number">
						</div>
						
						<select name="shop_name" id="shop_orders">
							<?php foreach($stores as $store){ 
							if(isset($currentStore) && !empty($currentStore)){
								if($currentStore == $store['shop_name']){
									$sel = "selected";
								}else{
									$sel = "";
								}
							}
							if(isset($_GET) && !empty($_GET['shopname'])){
								if($_GET['shopname'] == $store['shop_name']){
									$sel = "selected";
								}else{
									$sel = "";
								}
							}
							?>
							<option value="<?php echo $store['shop_name'];?>" <?= $sel ?>> <?php echo $store['shop_name'];?> </option>
							<?php }?>
						</select>
						<button type="submit" class="btn start-date">Search</button>
					<?php echo form_close(); ?>
					<a id="delete_order" href="#">Delete</a>
				</div>
			</div>
			<?php if($this->session->flashdata('delete_Order')){ ?>
				<div class="successmsg" id="orders_dlt_msg">  
					<?php echo $this->session->flashdata('delete_Order');?>
				</div>
			<?php }?>
				<div class="my-orders" id="tabs-1">					
					<?php if($recordfound > 0){?>
					<div class="order-page-table my_orders_table">
						<div class="order-page-head">
							<div class="order-page-heading checkbox">
								<input id="check_myorder" type="checkbox">
							</div>
							<div class="order-page-heading order-no">
								Order no.
							</div>
							<div class="order-page-heading date">
								Date
							</div>
							<div class="order-page-heading customer">
								Customer Name
							</div>
							<div class="order-page-heading pay-status">
								Payment status
							</div>
							<div class="order-page-heading fulfillment-status">
								Fulfillment status
							</div>
							<div class="order-page-heading shiping-method">
								Shipping method
							</div>
							<div class="order-page-heading address">
								Shipping address
							</div>
							<div class="order-page-heading bill-address">
								Billing address
							</div>
							<div class="order-page-heading total-amount">
								Total amount
							</div>
							<div class="order-page-heading wholesale-total-amount">
								Wholesale Total amount
							</div>
							<div class="order-page-heading pay-button">
								Pay button
							</div>
						</div>
						<div class="order-page-body ">
							<?php    $i = 1;							
							    foreach ($orders as $order) {
								$total_sales = array();
								$whole_sales = array();
								$prodcut_ids = array();
								if(isset($order[0]['show'])){
									if($order['financial_status'] == 'paid'){
									$order_id = $order['id'];
									$allOrders1 = $allOrders1+1;
									$line_items = $order['line_items'];	
									foreach($line_items as $orderPro){
										if(!empty($orderPro[0]['shopify_product_id'])){
											$total_sales[] = $orderPro['price']*$orderPro['quantity'];
											$whole_sales[] = $orderPro[0]['wholeSale_price']*$orderPro['quantity'];
											$prodcut_ids[] = $orderPro[0]['store_pro_id']." ".$orderPro['quantity'];
										}
									}
								?>
							<div class = "tabledata page_<?=$className1?>" style="display:none;">
								<div class="order-page-row">								
									<div class="order-page-col checkbox"><input class="all_myOrder orders_id" id = "ordrId_<?php echo $i; ?>" type="checkbox" data-id="<?=$order_id?>"></div>
									<div class="order-page-col orderno"><span class="order-no"><a class="order_number order_no1" href="#"><?php echo $order['order_number']; ?></a></span></div>
									<div class="order-page-col date crtdate"><?php $cre_date = explode('T', $order['created_at']);									
									$newDate = date("jS F, Y", strtotime($cre_date[0]));
									$time = explode('-', $cre_date[1]);
									echo $newDate."<br>".$time[0]; 
									?> </div>
									<div class="order-page-col customer customername1"><?php echo $order['customer']['first_name'].' '.$order['customer']['last_name']; ?></div>
									<div class="order-page-col pay-status finstatus1"><?php if($order['financial_status'] == "voided"){ echo "Canceled"; }else{
									echo $order['financial_status']; }  ?></div>
									<div class="order-page-col fulfillment-status fulstatus1"><?php 
									if($order['fulfillment_status'] != "fulfilled"){
										echo "Unfulfilled";
									}else{
										echo $order['fulfillment_status']; 
									}
									?></div>
									<div class="order-page-col shipingmethod ordertitle ttl"><?php echo $order['shipping_lines'][0]['title']; ?></div>
									<div class="order-page-col address"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col bill-address ship_address"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col total-amount amount1">$<?php echo array_sum($total_sales);?>
									</div>
									<div class="order-page-col whole-sale-total-amount">$<?php echo array_sum($whole_sales);?>
									</div>
									<div class="order-page-col pay-button">
									    <?php if($orderPro[0]['paidtoadmin'] == 0){ ?>
											<form target="_blank" action="<?= site_url('orders/paytoadmin') ?>" class="import-checkout" method="POST">
												<input type="hidden" name="pro_ids" value="<?= implode(",",$prodcut_ids) ?>">
												<input type="hidden" name="or_id" value="<?= $order['order_number'].",".$currentStore ?>">
												<input name ="proids" type="submit" class="checkout-button" value="Pay">
											</form>
										<?php }else{ ?>
										      <span>Paid to admin</span> 
										<?php } ?>
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
										<div class="order-page-heading pay-status">
											Store price
										</div>
									</div>
									<div class="order-page-body">
										<?php  $ab = 1;
											foreach($line_items as $orderPro){
										?>
										<div class = "poptab11">
										<?php
											if(!empty($orderPro[0]['shopify_product_id'])){ ?>
											<div class="order-page-row ordered-product poptab1" id ="pop1Id_<?php echo $ab; ?>" data-id="<?=$order_id?>">
												<div class="order-page-col product-item pro-img imgsorce1"><img   src="<?php echo $orderPro[0]['img_url']; ?>"/></div>
												<div class="order-page-col product-item pro-name nme1"><?php echo $orderPro['name']; ?></div>
												<div class="order-page-col product-item pro-sku SKU1"><?php if(!empty($orderPro['sku'])){
																echo $orderPro['sku'];
                                                            }else{
																echo "____";
															} ?></div>
												<div class="order-page-col product-item pro-qty quanty1"><?php echo $orderPro['quantity']; ?></div>
												<div class="order-page-col wholesale-price"><?php echo $orderPro[0]['wholeSale_price']; ?></div>
												<div class="order-page-col product-item pro-price pricy1"><?php echo $orderPro['price']; ?></div>
											</div>
										<?php   $ab++; } 
										?> </div> <?php 
										} ?>
									</div>
								</div>
							</div>					
							<?php 
								if($allOrders1 %10 == 0){
									$className1++;
								}
								} } $i++;  }  ?>
						</div>
					</div>
					
					<ul class="pagination" id="pagination"></ul>
					<?php }else{  ?>
						<div class="no-orders">No orders found</div>								
					<?php } ?>
				</div>
				<div class="incomplete-orders" id="tabs-2">
				<?php if($recordfound > 0 && $inc_order == "incomplete"){?>					
					<div class="order-page-table incomplete_order_table">
						<div class="order-page-head">
							<div class="order-page-heading checkbox">
								<input id="incomplete_order" type="checkbox">
							</div>
							<div class="order-page-heading order-no">
								Order no.
							</div>
							<div class="order-page-heading date">
								Date
							</div>
							<div class="order-page-heading customer">
								Customer Name
							</div>
							<div class="order-page-heading pay-status">
								Payment status
							</div>
							<div class="order-page-heading fulfillment-status">
								Fulfillment status
							</div>
							<div class="order-page-heading shiping-method">
								Shipping method
							</div>
							<div class="order-page-heading address">
								Shipping address
							</div>
							<div class="order-page-heading bill-address">
								Billing address
							</div>
							<div class="order-page-heading total-amount">
								Total amount
							</div>
							<div class="order-page-heading wholesale-total-amount">
								Wholesale Total amount
							</div>
						</div>
						<div class="order-page-body">
							<?php  $q = 1;  							
							   foreach ($orders as $order) {
								$total_sales = array();
								$whole_sales = array();
								if(isset($order[0]['show'])){
								 if($order['financial_status'] == 'pending'){
                                    $order_id = $order['id'];
                                    $allOrders2 = $allOrders2+1;								
									$line_items = $order['line_items'];	
									foreach($line_items as $orderPro){
										if(!empty($orderPro[0]['shopify_product_id'])){
											$total_sales[] = $orderPro['price']*$orderPro['quantity'];
											$whole_sales[] = $orderPro[0]['wholeSale_price']*$orderPro['quantity'];
										}
									}
								?>
							<div class="tabledata1 page2_<?=$className2?>" style="display:none;">
								<div class="order-page-row">
									<div class="order-page-col checkbox"><input class="check_incomplete orders_id" type="checkbox" id = "inordrId_<?php echo $q; ?>" data-id="<?=$order_id?>"></div>
									<div class="order-page-col orderno"><span class="order-no"><a class="order_number orderno1" href="#"><?php echo $order['order_number']; ?></a></span></div>
									<div class="order-page-col date crtdate1"><?php $cre_date = explode('T', $order['created_at']);									
									$newDate = date("jS F, Y", strtotime($cre_date[0]));
									$time = explode('-', $cre_date[1]);	
									echo $newDate."<br>".$time[0] ?></div>
									<div class="order-page-col customer customername2"><?php echo $order['customer']['first_name'].' '.$order['customer']['last_name']; ?></div>
									<div class="order-page-col pay-status finstatus2"><?php if($order['financial_status'] == "voided"){ echo "Canceled"; }else{
									echo $order['financial_status']; }  ?></div>
									<div class="order-page-col fulfillment-status fulstatus2"><?php if($order['fulfillment_status'] != "fulfilled"){
										echo "Unfulfilled";
									}else{
										echo $order['fulfillment_status']; 
									} ?></div>
									<div class="order-page-col shipingmethod ordertitle1"><?php echo $order['shipping_lines'][0]['title']; ?></div>
									<div class="order-page-col address address1"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col bill-address ship_address2"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col total-amount amount2">$<?php echo array_sum($total_sales);?></div>
									<div class="order-page-col whole-sale-total-amount">$<?php echo array_sum($whole_sales);?></div>
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
										<div class="order-page-heading pay-status">
											Store price
										</div>
									</div>
									<div class="order-page-body">
										<?php foreach($line_items as $orderPro){  ?>
											<div class = "poptab22">
										<?php	if(!empty($orderPro[0]['shopify_product_id'])){ ?>
											<div class="order-page-row ordered-product" id = "pop2Id">
												<div class="order-page-col product-item pro-img imgsorce2"><img src="<?php echo $orderPro[0]['img_url']; ?>"/></div>
												<div class="order-page-col product-item pro-name nme2"><?php echo $orderPro['name']; ?></div>
												<div class="order-page-col product-item pro-sku SKU2"><?php if(!empty($orderPro['sku'])){
																echo $orderPro['sku'];
                                                            }else{
																echo "____";
															} ?></div>
												<div class="order-page-col product-item pro-qty quanty2"><?php echo $orderPro['quantity']; ?></div>
												<div class="order-page-col wholesale-price"><?php echo $orderPro[0]['wholeSale_price']; ?></div>
												<div class="order-page-col product-item pro-price pricy2"><?php echo $orderPro['price']; ?></div>
											</div>
										<?php }   ?>
										 </div>
									<?php	} ?>
									</div>
								</div>
							</div>
								<?php $q++; 
									if($allOrders2 %10 == 0){
										$className2++;
							} } } }  ?>
						</div>
					</div>
					<ul class="pagination" id="pagination2"></ul>
					<?php }else{  ?>
						<div class="no-orders">No orders found</div>								
					<?php } ?>
				</div>
				<div class="cancel-orders" id="tabs-3">
				   <?php if($recordfound > 0 && $cnsl_order == "cancel"){?>					
					<div class="order-page-table cancel_order_table">
						<div class="order-page-head">
							<div class="order-page-heading checkbox">
								<input id="canceled_order" type="checkbox">
							</div>
							<div class="order-page-heading order-no">
								Order no.
							</div>
							<div class="order-page-heading date">
								Date
							</div>
							<div class="order-page-heading customer">
								Customer Name
							</div>
							<div class="order-page-heading pay-status">
								Payment status
							</div>
							<div class="order-page-heading fulfillment-status">
								Fulfillment status
							</div>
							<div class="order-page-heading shiping-method">
								Shipping method
							</div>
							<div class="order-page-heading address">
								Shipping address
							</div>
							<div class="order-page-heading bill-address">
								Billing address
							</div>
							<div class="order-page-heading total-amount">
								Total amount
							</div>
							<div class="order-page-heading wholesale-total-amount">
								Wholesale Total amount
							</div>
							<div class="order-page-heading canceled_date">Canceled date</div>
							<div class="order-page-heading canceled_reason">Canceled reason</div>
						</div>
						<div class="order-page-body">
							<?php   $m = 1;										
							   foreach ($orders as $order) {
								$total_sales = array();
								$whole_sales = array();
								if(isset($order[0]['show'])){	
								 if(!empty($order['refunds'])){
									$order_id = $order['id'];
									$allOrders3 = $allOrders3+1;
									$line_items = $order['line_items'];	
									foreach($line_items as $orderPro){
										if(!empty($orderPro[0]['shopify_product_id'])){
											$total_sales[] = $orderPro['price']*$orderPro['quantity'];
											$whole_sales[] = $orderPro[0]['wholeSale_price']*$orderPro['quantity'];
										}
									}
								?>
							<div class = "tabledata2 page3_<?=$className3?>" style="display:none;">
								<div class="order-page-row">
									<div class="order-page-col checkbox"><input class="check_canceled orders_id" type="checkbox" id = "cnordrId_<?php echo $m; ?>" data-id="<?=$order_id?>"></div>
									<div class="order-page-col orderno"><span class="order-no "><a class="order_number orderno2" href="#"><?php echo $order['order_number']; ?></a></span></div>
									<div class="order-page-col date crtdate2"><?php $cre_date = explode('T', $order['created_at']);									
									$newDate = date("jS F, Y", strtotime($cre_date[0]));
									$time = explode('-', $cre_date[1]);	
									echo $newDate."<br>".$time[0] ?></div>
									<div class="order-page-col customer customername3"><?php echo $order['customer']['first_name'].' '.$order['customer']['last_name']; ?></div>
									<div class="order-page-col pay-status finstatus3"><?php if($order['financial_status'] == "voided"){ echo "Canceled"; }else{
									echo $order['financial_status']; }  ?></div>
									<div class="order-page-col fulfillment-status fulstatus3"><?php if($order['fulfillment_status'] != "fulfilled"){
										echo "Unfulfilled";
									}else{
										echo $order['fulfillment_status']; 
									} ?></div>
									<div class="order-page-col shipingmethod ordertitle2"><?php echo $order['shipping_lines'][0]['title']; ?></div>
									<div class="order-page-col address address2"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col bill-address ship_address3"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col total-amount amount3">$<?php echo array_sum($total_sales);?>
									</div>
									<div class="order-page-col whole-sale-total-amount">$<?php echo array_sum($whole_sales);?></div>
									<div class="order-page-col canceled_date"><?php 
												$date = explode('T', $order['cancelled_at']);
												echo $date[0]; ?></div>
									<div class="order-page-col canceled_reason"><?php echo $order['cancel_reason']; ?></div>
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
										<div class="order-page-heading pay-status">
											Store price
										</div>
									</div>
									<div class="order-page-body">
										<?php foreach($line_items as $orderPro){   ?>
											<div class = "poptab33">
										<?php	if(!empty($orderPro[0]['shopify_product_id'])){ ?>
											<div class="order-page-row ordered-product" id = "pop2Id">
												<div class="order-page-col product-item pro-img imgsorce3"><img src="<?php echo $orderPro[0]['img_url']; ?>"/></div>
												<div class="order-page-col product-item pro-name nme3"><?php echo $orderPro['name']; ?></div>
												<div class="order-page-col product-item pro-sku SKU3"><?php if(!empty($orderPro['sku'])){
																echo $orderPro['sku'];
                                                            }else{
																echo "____";
															} ?></div>
												<div class="order-page-col product-item pro-qty quanty3"><?php echo $orderPro['quantity']; ?></div>
												<div class="order-page-col wholesale-price"><?php echo $orderPro[0]['wholeSale_price']; ?></div>
												<div class="order-page-col product-item pro-price pricy3"><?php echo $orderPro['price']; ?></div>
											</div>
										<?php } ?>
										</div>
									<?php	} ?>
									</div>
								</div>
							</div>
								<?php  $m++;  
									if($allOrders3 %10 == 0){
										$className3++;
									}
								} } }  ?>							
						</div>
					</div>
					<ul class="pagination" id="pagination3"></ul>
					<?php }else{  ?>
						<div class="no-orders">No orders found</div>								
					<?php } ?>
				</div>
				<div class="order-status" id="tabs-4">
				<?php if($recordfound > 0){?>					
					<div class="order-page-table cancel_order_table">
						<div class="order-page-head">
							<div class="order-page-heading checkbox">
								<input id="orderstatus_order" type="checkbox">
							</div>
							<div class="order-page-heading order-no">
								Order no.
							</div>
							<div class="order-page-heading date">
								Date
							</div>
							<div class="order-page-heading customer">
								Customer Name
							</div>
							<div class="order-page-heading pay-status">
								Payment status
							</div>
							<div class="order-page-heading fulfillment-status">
								Fulfillment status
							</div>
							<div class="order-page-heading shiping-method">
								Shipping method
							</div>
							<div class="order-page-heading address">
								Shipping address
							</div>
							<div class="order-page-heading bill-address">
								Billing address
							</div>
							<div class="order-page-heading total-amount">
								Total amount
							</div>
							<div class="order-page-heading shipping-company">Shipping company</div>
							<div class="order-page-heading tracking-number">Tracking number</div>
						</div>
						<div class="order-page-body">
							<?php   $k = 1;
							   foreach ($orders as $order) {
								$total_sales = array();
								if(isset($order[0]['show'])){
                                    $order_id = $order['id'];
									$allOrders4 = $allOrders4+1;
									$line_items = $order['line_items'];	
									foreach($line_items as $orderPro){
										if(!empty($orderPro[0]['shopify_product_id'])){
											$total_sales[] = $orderPro['price']*$orderPro['quantity'];
										}
									}
								?>
							<div class = "tabledata3 page4_<?=$className4?>" style="display:none;">
								<div class="order-page-row">
									<div class="order-page-col checkbox"><input class="check_orderstatus orders_id" type="checkbox" id = "stordrId_<?php echo $k; ?>" data-id="<?=$order_id?>"></div>
									<div class="order-page-col orderno"><span class="order-no"><a class="order_number orderno4" href="#"><?php echo $order['order_number']; ?></a></span></div>
									<div class="order-page-col date crtdate4"><?php $cre_date = explode('T', $order['created_at']);									
									$newDate = date("jS F, Y", strtotime($cre_date[0]));
									$time = explode('-', $cre_date[1]);	
									echo $newDate."<br>".$time[0] ?></div>
									<div class="order-page-col customer customername4"><?php echo $order['customer']['first_name'].' '.$order['customer']['last_name']; ?></div>
									<div class="order-page-col pay-status finstatus4"><?php 
									if($order['financial_status'] == "voided"){ echo "Canceled"; }else{
									echo $order['financial_status']; } ?></div>
									<div class="order-page-col fulfillment-status fulstatus4"><?php if($order['fulfillment_status'] != "fulfilled"){
										echo "Unfulfilled";
									}else{
										echo $order['fulfillment_status']; 
									} ?></div>
									<div class="order-page-col shipingmethod ordertitle4"><?php echo $order['shipping_lines'][0]['title']; ?></div>
									<div class="order-page-col address address4"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col bill-address ship_address4"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col total-amount amount4">$<?php echo array_sum($total_sales);?>
									</div>
									<div class="order-page-col shipping-company ship_company"><?php if(!empty($order['fulfillment_status'])){ ?><?php echo $order['fulfillments'][0]['tracking_company']; ?><?php }?>
									</div>
									<div class="order-page-col tracking-number "><?php if(!empty($order['fulfillment_status'])){ ?>
											<a target="_blank" class = "track_num" href="<?php echo $order['fulfillments'][0]['tracking_url']; ?>"><?php echo $order['fulfillments'][0]['tracking_number']; ?></a>
										<?php } ?>
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
										<div class="order-page-heading pay-status">
											Store price
										</div>
									</div>
									<div class="order-page-body">
										<?php foreach($line_items as $orderPro){  ?>
											<div class = "poptab44">
										<?php	if(!empty($orderPro[0]['shopify_product_id'])){ ?>
											<div class="order-page-row ordered-product" id = "pop3Id">
												<div class="order-page-col product-item pro-img imgsorce4"><img src="<?php echo $orderPro[0]['img_url']; ?>"/></div>
												<div class="order-page-col product-item pro-name nme4"><?php echo $orderPro['name']; ?></div>
												<div class="order-page-col product-item pro-sku SKU4"><?php if(!empty($orderPro['sku'])){
																echo $orderPro['sku'];
                                                            }else{
																echo "____";
															} ?></div>
												<div class="order-page-col product-item pro-qty quanty4"><?php echo $orderPro['quantity']; ?></div>
												<div class="order-page-col wholesale-price"><?php echo $orderPro[0]['wholeSale_price']; ?></div>
												<div class="order-page-col product-item pro-price pricy4"><?php echo $orderPro['price']; ?></div>
											</div>
										<?php }   ?>
											</div>
									<?php	} ?>
									</div>
								</div>
							</div>
								<?php 
									if($allOrders4 %10 == 0){
										$className4++;
									}
								} $k++;  } ?>
							
						</div>
					</div>
					<ul class="pagination" id="pagination4"></ul>
					<?php }else{  ?>
						<div class="no-orders">No orders found</div>								
					<?php } ?>
				</div>
		</div>
	</div>
</div>


<div id="dlt_orders" class="common_popup delete_popup"  title="Delete Orders" style="display:none;">
  <p><span class="ui-icon ui-icon-alert"></span>Do you really want to delete these Orders ?</p>
</div>
<div class="modal orders-products-models">
</div>
<?php 
    $paginateLink1 = ceil($allOrders1/10);
	$pageshow1 = ceil($paginateLink1/2);
	$paginateLink2 = ceil($allOrders2/10);
	$pageshow2 = ceil($paginateLink2/2);
	$paginateLink3 = ceil($allOrders3/10);
	$pageshow3 = ceil($paginateLink3/2);
	$paginateLink4 = ceil($allOrders4/10);
	$pageshow4 = ceil($paginateLink4/2);
?>

<script>
jQuery(document).ready(function($){
	$('.page_1').css('display','block');
	$('.page2_1').css('display','block');
	$('.page3_1').css('display','block');
	$('.page4_1').css('display','block');
	
	<?php if($allOrders1 > 10){ ?>
		window.pagObj = $('#pagination').twbsPagination({
			totalPages: <?=$paginateLink1?>,
			visiblePages: <?=$pageshow1?>,
			onPageClick: function (event, page) {
				$('.tabledata').css('display','none');
				$('.page_'+page).css('display','block');
			}
		});
	<?php } ?>
	<?php if($allOrders2 > 10){ ?>
		window.pagObj = $('#pagination2').twbsPagination({
			totalPages: <?=$paginateLink2?>,
			visiblePages: <?=$pageshow2?>,
			onPageClick: function (event, page2) {
				$('.tabledata1').css('display','none');
				$('.page2_'+page2).css('display','block');
			}
		});
	<?php } ?>	
	<?php if($allOrders3 > 10){ ?>
		window.pagObj = $('#pagination3').twbsPagination({
			totalPages: <?=$paginateLink3?>,
			visiblePages: <?=$pageshow3?>,
			onPageClick: function (event, page3) {
				$('.tabledata2').css('display','none');
				$('.page3_'+page3).css('display','block');
			}
		});
	<?php } ?>
	<?php if($allOrders4 > 10){ ?>
		window.pagObj = $('#pagination4').twbsPagination({
			totalPages: <?=$paginateLink4?>,
			visiblePages: <?=$pageshow4?>,
			onPageClick: function (event, page4) {
				$('.tabledata3').css('display','none');
				$('.page4_'+page4).css('display','block');
			}
		});
	<?php } ?>
});	

		$("#export_order").click(function(){
			var _arr = ""; 
			var result = $("ul li.ui-state-active a").attr('id');		
			if(result == "ui-id-1"){						
			var CSVString = "";			
			var data = [];   
			var i = 0; 			
			var p = 1;
			var numItems = $('.poptab1').length;	
			var titles = ["Order no.","Created date","Customer","Finance Status","Fulfillment status","Shipping Method","Address","Shipping_address","Total amount","Product images","Product name","SKU","Quantity","Price"];
			$('.tabledata').each(function(){ 
				if($('#ordrId_'+p).is(':checked')){
					 var orderid = $('#ordrId_'+p).attr('data-id'); 
					var number = $(this).find('.order_no1').text(); 				
					var date = $(this).find('.crtdate').text().replace(",",' ');  
					var customer = $(this).find('.customername1').text();
					var status1 = $(this).find('.finstatus1').text();
					var status2 = $(this).find('.fulstatus1').text();				
					var title = $(this).find('.ttl').text();
					var address = $(this).find('.address').text();
					var ship = $(this).find('.ship_address').text();	
					var amount = $(this).find('.amount1').text();			
				 var get_class = $('#ordrId_'+p).parent().parent('.order-page-row').next('.order-page-pro-row').children('.order-page-body').children('.poptab11');				 
				 var img1 = new Array();   var name1 = new Array();   var sk1 = new Array();   var quat1 = new Array();  var pri1 = new Array();  var akp = 0;
				 $(get_class).each(function(){				  
				 img1[akp] = $(this).find('.imgsorce1 img').attr('src');
				 name1[akp] = $(this).find('.nme1').text();
				 sk1[akp] = $(this).find('.SKU1').text();
				 quat1[akp] = $(this).find('.quanty1').text();
				 pri1[akp] = $(this).find('.pricy1').text();				
				 akp++;
				 });				
					var pr = img1.toString();
					var product_images = pr.replace(/,/g,'     ');
					var product_names = name1.toString().replace(/,/g,"     ");
					var product_sku = sk1.toString().replace(/,/g,'     ');
					var product_quantis = quat1.toString().replace(/,/g,'     ');
					var product_prices = pri1.toString().replace(/,/g,'     ');		
					data[i] = [number,date,customer,status1,status2,title,address,ship,amount,product_images,product_names,product_sku,product_quantis,product_prices];	
				 }
					i++;  p++;	
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
		}
					
		else if(result == "ui-id-2"){  
			var CSVString = "";	
			var data = [];
			var i = 0;
			var o = 1;
			var titles = ["Order no.","Created date","Customer","Finance Status","Fulfillment status","Shipping Method","Address","Shipping_address","Total amount","Product Images","Product names","product SKU","product quantity","Product prices"];
			$('.tabledata1').each(function(){
			if($('#inordrId_'+o).is(':checked'))
			{// alert("sdfsgdh");
				var number = $(this).find('.orderno1').text();
					var date = $(this).find('.crtdate1').text().replace(",",' ');
					var customer = $(this).find('.customername2').text();
					var status1 = $(this).find('.finstatus2').text();
					var status2 = $(this).find('.fulstatus2').text();
					var title = $(this).find('.ordertitle1').text();
					var address = $(this).find('.address1').text();
					var ship = $(this).find('.ship_address2').text();	
					var amount = $(this).find('.amount2').text();
				 var get_class1 = $('#inordrId_'+o).parent().parent('.order-page-row').next('.order-page-pro-row').children('.order-page-body').children('.poptab22');				 
				 var img2 = new Array();   var name2 = new Array();   var sk2 = new Array();   var quat2 = new Array();  var pri2 = new Array();  var akp1 = 0;
				 $(get_class1).each(function(){				  
				 img2[akp1] = $(this).find('.imgsorce2 img').attr('src');
				 name2[akp1] = $(this).find('.nme2').text();
				 sk2[akp1] = $(this).find('.SKU2').text();
				 quat2[akp1] = $(this).find('.quanty2').text();
				 pri2[akp1] = $(this).find('.pricy2').text();				
				 akp1++;
				 });				
					var pr1 = img2.toString();
					var product_images1 = pr1.replace(/,/g,'     ');
					var product_names1 = name2.toString().replace(/,/g,"     ");
					var product_sku1 = sk2.toString().replace(/,/g,'     ');
					var product_quantis1 = quat2.toString().replace(/,/g,'     ');
					var product_prices1 = pri2.toString().replace(/,/g,'     ');		
				 data[i] = [number,date,customer,status1,status2,title,address,ship,amount,product_images1,product_names1,product_sku1,product_quantis1,product_prices1];	
			}
			 i++;  o++;
			 
		});
				CSVString = prepCSVRow(titles, titles.length, '');
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
				downloadLink.download = "incomplete_order.csv";
				document.body.appendChild(downloadLink);
				downloadLink.click();
				document.body.removeChild(downloadLink);
		
		}	
		else if(result == "ui-id-3"){   
			var CSVString = "";
			var data = [];
			var i = 0;
			var v = 1;
			var n = $( ".can_table" ).length;
			var titles = ["Order no.","Created date","Customer","Finance status","Fulfillment status","Shipping method","Address","Shipping_address","Total amount","Canceled date","Canceled reason","Product images","Product name","Product sku","Product quantity","Product price"];
			$('.tabledata2').each(function(){ 
			if($('#cnordrId_'+v).is(':checked')){  
					var number = $(this).find('.orderno2').text(); 
					var date = $(this).find('.crtdate2').text().replace(",",' ');
					var customer = $(this).find('.customername3').text();
					var status1 = $(this).find('.finstatus3').text();
					var status2 = $(this).find('.fulstatus3').text();				
					var title = $(this).find('.ordertitle2').text();
					var address = $(this).find('.address2').text();
					var ship = $(this).find('.ship_address3').text();	
					var amount = $(this).find('.amount3').text();
					var can_date = $(this).find('.canceled_date').text();
					var can_reason = $(this).find('.canceled_reason').text();				
				 var get_class2 = $('#cnordrId_'+v).parent().parent('.order-page-row').next('.order-page-pro-row').children('.order-page-body').children('.poptab33');				 
				 var img3 = new Array();   var name3 = new Array();   var sk3 = new Array();   var quat3 = new Array();  var pri3 = new Array();  var akp2 = 0;
				 $(get_class2).each(function(){				  
				 img3[akp2] = $(this).find('.imgsorce3 img').attr('src');
				 name3[akp2] = $(this).find('.nme3').text();
				 sk3[akp2] = $(this).find('.SKU3').text();
				 quat3[akp2] = $(this).find('.quanty3').text();
				 pri3[akp2] = $(this).find('.pricy3').text();				
				 akp2++;
				 });				
					var pr2 = img3.toString();
					var product_images2 = pr2.replace(/,/g,'     ');
					var product_names2 = name3.toString().replace(/,/g,"     ");
					var product_sku2 = sk3.toString().replace(/,/g,'     ');
					var product_quantis2 = quat3.toString().replace(/,/g,'     ');
					var product_prices2 = pri3.toString().replace(/,/g,'     ');	
				 data[i] = [number,date,customer,status1,status2,title,address,ship,amount,can_date,can_reason,product_images2,product_names2,product_sku2,product_quantis2,product_prices2];
			 }
				i++;	v++;		
			});
				CSVString = prepCSVRow(titles, titles.length, '');
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
				downloadLink.download = "canceled_orders.csv";
				document.body.appendChild(downloadLink);
				downloadLink.click();
				document.body.removeChild(downloadLink);
		
		}		
		else{   
				var CSVString = "";	
					var data = [];
					var i = 0;
					var t = 1;
					var titles = ["Order no.","Created date","Customer","Finance Status","Fulfillment status","Shipping Method","Address","Shipping_address","Total amount","Shipping company","tracking Number","Product images","Product name","Product sku","Product quantity","Product prices"];
				$('.tabledata3').each(function(){ 
				 if($('#stordrId_'+t).is(':checked'))
				 { 
					var number = $(this).find('.orderno4').text(); 
					var date = $(this).find('.crtdate4').text().replace(",",' ');
					var customer = $(this).find('.customername4').text();
					var status1 = $(this).find('.finstatus4').text();
					var status2 = $(this).find('.fulstatus4').text();
					var title = $(this).find('.ordertitle4').text();
					var address = $(this).find('.address4').text();
					var ship = $(this).find('.ship_address4').text();	
					var amount = $(this).find('.amount4').text();								
					var comp_name = $(this).find('.ship_company').text();						
					var tracking_num = $(this).find('.track_num').text();				
					 var get_class3 = $('#stordrId_'+t).parent().parent('.order-page-row').next('.order-page-pro-row').children('.order-page-body').children('.poptab44');				 
					 var img4 = new Array();   var name4 = new Array();   var sk4 = new Array();   var quat4 = new Array();  var pri4 = new Array();  var akp3 = 0;
					 $(get_class3).each(function(){				  
					 img4[akp3] = $(this).find('.imgsorce4 img').attr('src');
					 name4[akp3] = $(this).find('.nme4').text();
					 sk4[akp3] = $(this).find('.SKU4').text();
					 quat4[akp3] = $(this).find('.quanty4').text();
					 pri4[akp3] = $(this).find('.pricy4').text();				
					 akp3++;
					 });				
						var pr4 = img4.toString();
						var product_images3 = pr4.replace(/,/g,'     ');
						var product_names3 = name4.toString().replace(/,/g,"     ");
						var product_sku3 = sk4.toString().replace(/,/g,'     ');
						var product_quantis3 = quat4.toString().replace(/,/g,'     ');
						var product_prices3 = pri4.toString().replace(/,/g,'     ');				
				 data[i] = [number,date,customer,status1,status2,title,address,ship,amount,comp_name,tracking_num,product_images3,product_names3,product_sku3,product_quantis3,product_prices3];
				}
				i++;	t++;					
				});			
				CSVString = prepCSVRow(titles, titles.length, '');	
				var j = 0;
				$(data).each(function(){
					if(data[j]){  
					var	_arr = data[j];
						CSVString = prepCSVRow(_arr, titles.length, CSVString);
					}				 	
				j++;
				});		
				var downloadLink = document.createElement("a");
				var blob = new Blob(["\ufeff", CSVString]);
				var url = URL.createObjectURL(blob);
				downloadLink.href = url;
				downloadLink.download = "order_status.csv";
				document.body.appendChild(downloadLink);
				downloadLink.click();
				document.body.removeChild(downloadLink);
		}
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
</script>