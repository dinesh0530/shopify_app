<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
if(!empty($end_date)){
	$endDate = $end_date;
}else{
	$endDate = date("Y-m-d");
}
if(!empty($start_date)){
	$startDate = $start_date;
}else{
	$startDate = date("Y-m-d", strtotime("-7 days"));
}

$allOrders1 = 0;
$className1 = 1;
$allOrders2 = 0;
$className2 = 1;
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
		
		$('#to_be_fullfill').click(function(event) {  
            if(this.checked) { 
                $('.check_tofill').each(function() { 
				    var check = $(this).parent().parent().parent('div.tabledata ').attr('style');
				    if(check == "display: block;"){
						this.checked = true;
				    }
                });
				 $('.check_track').each(function() { 
                    this.checked = false;     
                });
				$('#track_order').prop('checked', false);
            }else{
                $('.check_tofill').each(function() {
                    this.checked = false; 
                });        
            }
        });
		$('.orders_id').click(function(event){
			if($('#to_be_fullfill').is(':checked')){ 
					$('#to_be_fullfill').prop('checked', false);
			}
			else if($('#track_order').is(':checked')){ 
					$('#track_order').prop('checked', false);
			}			
		}); 
		$('#track_order').click(function(event) {  
            if(this.checked) { 
                $('.check_track').each(function() {
                    var check = $(this).parent().parent().parent('div.tabledata1').attr('style');
				    if(check == "display: block;"){					
						this.checked = true; 
                    }						
                });
				$('.check_tofill').each(function() { 
                    this.checked = false;     
                });
				$('#to_be_fullfill').prop('checked', false);
            }else{
                $('.check_track').each(function() {
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
								url: siteurl+"vendor/vendororders/delete_order",
								data: {
										orderid: oderId,
										shop_name: $('#shop_orders').val(),
									  },
								success: function(result){
									window.location.replace(siteurl+"vendor/orders?shopname="+$('#shop_orders').val());
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
					<li><a href="#tabs-1">Orders To Be Fulfilled</a></li>
					<li><a href="#tabs-2">Track Orders</a></li>
				</ul>
				<div class="import-export-order">
					<a id="export_order" href="#">Export template</a>
				</div> 
			</div>			
			<div class="cal-search-delete-order">
				<div class="cal-search-delete-order-inner">
				<?php echo form_open('vendor/orders-search'); ?>
				    <div class="startdate">
						<input type="text" name="start_date" id="start_date" value="<?php echo $startDate; ?>">
					</div>
					<div class="enddate">
						<input type="text" name="end_date" id="end_date" value="<?php echo $endDate; ?>">
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
				<div class="to_fullfill" id="tabs-1">
				    <?php  if($recordfound > 0){ ?>
				    <div class="order-page-table order-to-fullfill">
						<div class="order-page-head">
							<div class="order-page-heading checkbox">
								<input id="to_be_fullfill" type="checkbox">
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
						</div>
						<div class="order-page-body">
							<?php  $i = 1;
							   foreach ($orders as $order) {
								$total_sales = array();
								$order_id = $order['id'];
								if(isset($order[0]['show'])){ 
								if($order['fulfillment_status'] == ""){
									$allOrders1 = $allOrders1+1;
									$line_items = $order['line_items'];	
									foreach($line_items as $orderPro){
										if(!empty($orderPro[0]['shopify_product_id'])){
											$total_sales[] = $orderPro['price']*$orderPro['quantity'];
										}
									}
								?>
							 <div class = "tabledata page_<?=$className1?>" style="display:none;">
								<div class="order-page-row">
									<div class="order-page-col checkbox"><input class="check_tofill orders_id" type="checkbox" id = "orderId_<?php echo $i; ?>" data-id="<?=$order_id?>"></div>
									<div class="order-page-col orderno"><span class="order-no"><a class="order_number orderno1"  href="#"><?php echo $order['order_number']; ?></a></span></div>
									<div class="order-page-col date date1"><?php $cre_date = explode('T', $order['created_at']);									
									$newDate = date("jS F, Y", strtotime($cre_date[0]));
									$time = explode('-', $cre_date[1]);	
									echo $newDate."<br>".$time[0] ?></div>
									<div class="order-page-col customer name1"><?php echo $order['customer']['first_name'].' '.$order['customer']['last_name']; ?></div>
									<div class="order-page-col pay-status finstatus1"><?php if($order['financial_status'] == "voided"){ echo "Canceled"; }else{
									echo $order['financial_status']; }  ?></div>
									<div class="order-page-col fulfillment-status fulstatus1"><?php if($order['fulfillment_status'] != "fulfilled"){
										echo "Unfulfilled";
									}else{
										echo $order['fulfillment_status']; 
									} ?></div>
									<div class="order-page-col shipingmethod" class="ordertitle"><?php echo $order['shipping_lines'][0]['title']; ?></div>
									<div class="order-page-col ship_add"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col bill-address ship_add1"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col total-amount amnt1">$<?php echo array_sum($total_sales);?>
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
										<div class="order-page-heading pay-status">
											Price
										</div>
									</div>
									<div class="order-page-body">
										<?php foreach($line_items as $orderPro){  ?>
											<div class = "popup1">
										<?php	if(!empty($orderPro[0]['shopify_product_id'])){ ?>
											<div class="order-page-row ordered-product" id = "popId1">
												<div class="order-page-col product-item pro-img imgsorce1"><img src="<?php echo $orderPro[0]['img_url']; ?>"/></div>
												<div class="order-page-col product-item pro-name nme1"><?php echo $orderPro['name']; ?></div>
												<div class="order-page-col product-item pro-sku sku1"><?php if(!empty($orderPro['sku'])){
																echo $orderPro['sku'];
                                                            }else{
																echo "____";
															} ?></div>
												<div class="order-page-col product-item pro-qty qunty1"><?php echo $orderPro['quantity']; ?></div>
												<div class="order-page-col product-item pro-price pricy1"><?php echo $orderPro['price']; ?></div>
											</div>
										<?php }  ?>
										</div>
									<?php	} ?>
									</div>
								</div>
							</div>
							<?php $i++; 
							
								if($allOrders1 %10 == 0){
									$className1++;
								}
							
							} 
							
							} } ?>
						</div>
					</div>
					<ul class="pagination" id="pagination"></ul>
					<?php }else{  ?>
						<div class="no-orders">No orders found</div>
					<?php }  ?>
				</div>
				<div class="tracked_orders" id="tabs-2">
				   <?php  if($recordfound > 0){ ?>
				    <div class="order-page-table track-order">
						<div class="order-page-head">
							<div class="order-page-heading checkbox">
								<input id="track_order" type="checkbox">
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
						<?php 
							$m = 1;
						   foreach ($orders as $order) {
							$total_sales = array();
							$order_id = $order['id'];
							if(isset($order[0]['show'])){ 
							if($order['fulfillment_status'] !=""){
								$allOrders2 = $allOrders2+1;
								$line_items = $order['line_items'];	
								foreach($line_items as $orderPro){
									if(!empty($orderPro[0]['shopify_product_id'])){
										$total_sales[] = $orderPro['price']*$orderPro['quantity'];
									}
								}
							?>
							<div class = "tabledata1 page2_<?=$className2?>" style="display:none;">
								<div class="order-page-row">
									<div class="order-page-col checkbox"><input class="check_track orders_id" type="checkbox" id = "trk_order_<?php echo $m; ?>" data-id="<?=$order_id?>"></div>
									<div class="order-page-col orderno"><span class="order-no"><a class="order_number orderno2" href="#"><?php echo $order['order_number']; ?></a></span></div>
									<div class="order-page-col date datetime1"><?php $cre_date = explode('T', $order['created_at']);									
									$newDate = date("jS F, Y", strtotime($cre_date[0]));
									$time = explode('-', $cre_date[1]);	
									echo $newDate."<br>".$time[0] ?></div>
									<div class="order-page-col customer name2"><?php echo $order['customer']['first_name'].' '.$order['customer']['last_name']; ?></div>
									<div class="order-page-col pay-status finstatus2"><?php if($order['financial_status'] == "voided"){ echo "Canceled"; }else{
									echo $order['financial_status']; }  ?></div>
									<div class="order-page-col fulfillment-status fulstatus2"><?php if($order['fulfillment_status'] != "fulfilled"){
										echo "Unfulfilled";
									}else{
										echo $order['fulfillment_status']; 
									} ?></div>
									<div class="order-page-col shipingmethod" class="ordertitle ordertitle2"><?php echo $order['shipping_lines'][0]['title']; ?></div>
									<div class="order-page-col address ship_add2"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col bill-address ship_add3"><?php echo $order['shipping_address']['name'].'<br/>'.$order['shipping_address']['address1'].' '.$order['shipping_address']['address2'].'<br/>'.$order['shipping_address']['city'].' '.$order['shipping_address']['province'].'<br/>'.$order['shipping_address']['country'].' '.$order['shipping_address']['zip'].'<br/>'.$order['shipping_address']['phone']; ?>
									</div>
									<div class="order-page-col total-amount amnt2">$<?php echo array_sum($total_sales);?>
									</div>
									<div class="order-page-col shipping-company comp_name"><?php if(!empty($order['fulfillment_status'])){ ?><?php echo $order['fulfillments'][0]['tracking_company']; ?><?php } ?>
									</div>
									<div class="order-page-col tracking-number"><?php if(!empty($order['fulfillment_status'])){ ?><a target="_blank" class = "track_num" href="<?php echo $order['fulfillments'][0]['tracking_url']; ?>"><?php echo $order['fulfillments'][0]['tracking_number']; ?></a>
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
										<div class="order-page-heading pay-status">
											Price
										</div>
									</div>
									<div class="order-page-body">
										<?php foreach($line_items as $orderPro){  ?>
										 <div  class = "popup2">	
										<?php	if(!empty($orderPro[0]['shopify_product_id'])){ ?>
											<div class="order-page-row ordered-product" id = "popId2">
												<div class="order-page-col product-item pro-img imgsorce2"><img src="<?php echo $orderPro[0]['img_url']; ?>"/></div>
												<div class="order-page-col product-item pro-name nme2"><?php echo $orderPro['name']; ?></div>
												<div class="order-page-col product-item pro-sku sku2"><?php if(!empty($orderPro['sku'])){
																echo $orderPro['sku'];
                                                            }else{
																echo "____";
															} ?></div>
												<div class="order-page-col product-item pro-qty qunty2"><?php echo $orderPro['quantity']; ?></div>
												<div class="order-page-col product-item pro-price pricy2"><?php echo $orderPro['price']; ?></div>
											</div>
										<?php }  ?>
											</div>
									<?php	} ?>
									</div>
								</div>
							</div>
							<?php $m++;  
								if($allOrders2 %10 == 0){
									$className2++;
								}
							} }   } ?>
						</div>
					</div>
					<ul class="pagination" id="pagination2"></ul>
					<?php }else{  ?>
						<div class="no-orders">No orders found</div>
					<?php }  ?>
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
?>

<script>
jQuery(document).ready(function($){
	$('.page_1').css('display','block');
	$('.page2_1').css('display','block');
	
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
});	
		$('#export_order').click(function(){			
			var result = $("ul li.ui-state-active a").attr('id');	
			if(result == "ui-id-1"){
				var CSVString = "";		
				var p = 1;
				var o = 0;
				var data = [];
				var titles = ["Order no.","Date","Customer","Payment Status","Fulfillment status","Shipping Method","Shipping_address","Billing address","Total amount","Product images","Product name","SKU","Quantity","Price"];
				$('.tabledata').each(function(){
				if($('#orderId_'+p).is(':checked')){
					var order_no = $(this).find('.orderno1').text();
					var datetime = $(this).find('.date1').text().replace(",",' ');
					var ordername = $(this).find('.name1').text();
					var finan_sts = $(this).find('.finstatus1').text();
					var fulfil_sts = $(this).find('.fulstatus1').text();
					var methd = $(this).find('.ordertitle').text();
					var ship_address = $(this).find('.ship_add').text();
					var bill_address = $(this).find('.ship_add1').text();
					var amount = $(this).find('.amnt1').text();
					var get_class = $('#orderId_'+p).parent().parent('.order-page-row').next('.order-page-pro-row').children('.order-page-body').children('.popup1');				 
				 var img1 = new Array();   var name1 = new Array();   var sk1 = new Array();   var quat1 = new Array();  var pri1 = new Array();  var vakp = 0;
				 $(get_class).each(function(){			 
				 img1[vakp] = $(this).find('.imgsorce1 img').attr('src');
				 name1[vakp] = $(this).find('.nme1').text();
				 sk1[vakp] = $(this).find('.sku1').text();
				 quat1[vakp] = $(this).find('.qunty1').text();
				 pri1[vakp] = $(this).find('.pricy1').text();				
				 vakp++;
				 });		
						
					var pr = img1.toString();
					var product_images = pr.replace(/,/g,'     ');
					var product_names = name1.toString().replace(/,/g,"     ");
					var product_sku = sk1.toString().replace(/,/g,'     ');
					var product_quantis = quat1.toString().replace(/,/g,'     ');
					var product_prices = pri1.toString().replace(/,/g,'     ');		
					data[o] = [order_no,datetime,ordername,finan_sts,fulfil_sts,methd,ship_address,bill_address,amount,product_images,product_names,product_sku,product_quantis,product_prices];
				}	
					p++;    o++;
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
				downloadLink.download = "Fullfilled_orders.csv";
				document.body.appendChild(downloadLink);
				downloadLink.click();
				document.body.removeChild(downloadLink); 	
		   }
		 else if(result == "ui-id-2"){
			  var b = 1;
			  var CSVString = "";		
			  var data = [];
			  var v = 0;
			  var titles = ["Order no.","Date","Customer","Payment Status","Fulfillment status","Shipping Method","Shipping_address","Billing address","Total amount","Shipping company","Tracking no","Product images","Product name","SKU","Quantity","Price"];
			  $('.tabledata1').each(function(){
				  if($('#trk_order_'+b).is(':checked')){
					var order_no1 = $(this).find('.orderno2').text();
					var datetime2 = $(this).find('.datetime1').text().replace(",",' ');
					var ordername1 = $(this).find('.name2').text();
					var finan_sts1 = $(this).find('.finstatus2').text();
					var fulfil_sts1 = $(this).find('.fulstatus2').text();
					var methd1 = $(this).find('.ordertitle2').text();
					var ship_address1 = $(this).find('.ship_add2').text();
					var bill_address1 = $(this).find('.ship_add3').text();
					var amont1 = $(this).find('.amnt2').text();
					var cmp_name1 = $(this).find('.comp_name').text();
					var trak_num1 = $(this).find('.track_num').text();
					var get_class1 = $('#trk_order_'+b).parent().parent('.order-page-row').next('.order-page-pro-row').children('.order-page-body').children('.popup2');				 
					 var img2 = new Array();   var name2 = new Array();   var sk2 = new Array();   var quat2 = new Array();  var pri2 = new Array();  var vakp = 0;
					 $(get_class1).each(function(){				  
					 img2[vakp] = $(this).find('.imgsorce2 img').attr('src');
					 name2[vakp] = $(this).find('.nme2').text();
					 sk2[vakp] = $(this).find('.sku2').text();
					 quat2[vakp] = $(this).find('.qunty2').text();
					 pri2[vakp] = $(this).find('.pricy2').text();				
					 vakp++;
					 });				
					var pr = img2.toString();
					var product_images1 = pr.replace(/,/g,'     ');
					var product_names1 = name2.toString().replace(/,/g,"     ");
					var product_sku1 = sk2.toString().replace(/,/g,'     ');
					var product_quantis1 = quat2.toString().replace(/,/g,'     ');
					var product_prices1 = pri2.toString().replace(/,/g,'     ');		
					data[v] = [order_no1,datetime2,ordername1,finan_sts1,fulfil_sts1,methd1,ship_address1,bill_address1,amont1,cmp_name1,trak_num1,product_images1,product_names1,product_sku1,product_quantis1,product_prices1];
				  }
				  b++;   v++;
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
					downloadLink.download = "track_oeder.csv";
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