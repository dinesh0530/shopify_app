<script>
$(document).ready(function() {	

	$( "#filter_ByShop" ).change(function(){
				$("#form_filter_ByShop").submit();
			});
			$( "#datepicker1" ).datepicker({
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/images/calendar.png",
			dateFormat: "yy-mm-dd",
			onSelect: function(date) {
						$("#form_filter_ByShop").submit();
						}
			
		});
		$( "#datepicker1" ).datepicker( "setDate", "<?php echo isset($_GET['startDate'])&&!empty($_GET['startDate'])?$_GET['startDate']:date("Y-m-d", strtotime("-7 days")); ?>" );
		$( "#datepicker2" ).datepicker({
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/images/calendar.png",
			dateFormat: "yy-mm-dd",
			onSelect: function(date) {
							$("#form_filter_ByShop").submit();
						}
		});
		$( "#datepicker2" ).datepicker( "setDate", "<?php echo isset($_GET['endDate'])&&!empty($_GET['endDate'])?$_GET['endDate']:date("Y-m-d"); ?>" );
		
		var $divs = $("div.admin-orders");
		$('#total-sale').on('change', function (){
			if($(this).val() == "low_price"){
				var numericallyOrderedDivs = $divs.sort(function (a, b) {
					var vA = parseInt($(a).find("div.total_amounts").text().replace("$",''));
					var vb = parseInt($(b).find("div.total_amounts").text().replace("$",''));
					return vA - vb;
				});	
				$(".admin-main-orders").html(numericallyOrderedDivs);
            }
            if($(this).val() == "high_price"){
				var numericallyOrderedDivs = $divs.sort(function (a, b) {
					var vA = parseInt($(a).find("div.total_amounts").text().replace("$",''));
					var vb = parseInt($(b).find("div.total_amounts").text().replace("$",''));
					return vb - vA;
				});
				$(".admin-main-orders").html(numericallyOrderedDivs);
			}
		});
		
		$('body').on('click', 'a.order_number', function(e) {
			e.preventDefault();
			var html = $(this).parent().parent().parent('div.order-page-row').next('div.order-page-pro-row').html();
			$('.orders-products-models').html(html);
			$('.orders-products-models').modal({ fadeDuration: 100 });
		});
		$('.orders_id').click(function(event){
			if($('#admin_DB_order').is(':checked')){ 
				$('#admin_DB_order').prop('checked', false);
			}			
		});
		
});	
</script>
<?php 
$total_sale = array();
$current_order = 0;
$totalOrders = 0; 
$pendingOrders = 0;
$allOrders = 0;
$className = 1;
?>
	<div class="inner-pages"> 
		<section class="site-dashboard-page">
			<div class="dashboard-page-inner">
				<div class="container">
					<div class="dashboard-page-content">					
						<div class="dashboard-top-section">
							<div class="dashboard-total-sales">
								<span class="filter-search">Filter results by:</span>
								<span class="total-sales">
									<select  name="total-sales"  id="total-sale">
										<option value="">- Sort By Price -</option>
										<option	value="high_price">Highest Price</option>
										<option	value="low_price">Lowest Price</option>
									</select>
								</span>									
							</div>
							<div class="dashboard-date-range">
								<form method="get" id="form_filter_ByShop">
									<div class="datepic1">
										<input type="text" name="startDate" id="datepicker1">	
									</div>								
									<div class="datepic2">
										<input type="text" name="endDate" id="datepicker2" value="<?=date("Y-m-d")?>">	
									</div>										
									<div class="date-range-inner">
										<select name="shop_name" id="filter_ByShop">
											<?php foreach($shops as $store){ ?>
												<option value="<?php echo $store->shop_name;?>" 
												<?php if(isset($_GET['shop_name']) && !empty($_GET['shop_name'])){
													if($_GET['shop_name'] == $store->shop_name){
														echo "selected";
													}
												}?> > <?php echo $store->shop_name;?> </option>
											<?php }?>
										</select> 									
									</div>
								</form>
							</div>
						</div>
						<div class="dashboard-bottom-section">
							<div class="dashboard-left-bottom">
							    <?php if($recordfound	> 0){ ?>
								<div class="order-page-table dashboard-table-left">
									<div class="order-page-head">
										<div class="order-page-heading checkbox">
											<input id="admin_DB_order" type="checkbox">
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
										<div class="order-page-heading total-amount">
											Total amount
										</div>
									</div>
									<div class="order-page-body admin-main-orders">
									<?php
									   foreach ($orders as $order) {
										if(isset($order[0]['show'])){
											if($current_order != $order['id']){
												$allOrders = $allOrders+1;
												if($order['financial_status'] == "paid"){
													$totalOrders = $totalOrders+1;
												}
												if($order['financial_status'] == "pending"){
													$pendingOrders = $pendingOrders+1;
												}
											}
											$current_order = $order['id'];
											$total_sales = array();
											$order_id = $order['id'];
											$line_items = $order['line_items'];	
											foreach($line_items as $orderPro){
												if(!empty($orderPro[0]['shopify_product_id'])){
													$total_sales[] = $orderPro['price']*$orderPro['quantity'];
													if($order['financial_status'] == "paid"){
														$total_sale[] = $orderPro['price']*$orderPro['quantity'];
													}
												}
											}
										?>
										<div class="admin-orders page_<?=$className?>" style="display:none;">
											<div class="order-page-row">
												<div class="order-page-col checkbox"><input class="check_admin_DB_order orders_id" type="checkbox" data-id="<?=$order_id?>"></div>
												<div class="order-page-col orderno"><span class="order-no"><a class="order_number" href="#"><?php echo $order['order_number']; ?></a></span></div>
												<div class="order-page-col date"><?php $cre_date = explode('T', $order['created_at']);									
												$newDate = date("jS F, Y", strtotime($cre_date[0]));
												$time = explode('-', $cre_date[1]);	
												echo $newDate."<br>".$time[0] ?></div>
												<div class="order-page-col customer"><?php echo $order['customer']['first_name'].' '.$order['customer']['last_name']; ?></div>
												<div class="order-page-col pay-status"><?php if($order['financial_status'] == "voided"){ echo "Canceled"; }else{
												echo $order['financial_status']; }  ?></div>
												<div class="order-page-col fulfillment-status"><?php if($order['fulfillment_status'] != "fulfilled"){
													echo "Unfulfilled";
												}else{
													echo $order['fulfillment_status']; 
												} ?></div>
												<div class="order-page-col total-amount total_amounts">$<?php echo array_sum($total_sales);?>
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
													<?php foreach($line_items as $orderPro){
														if(!empty($orderPro[0]['shopify_product_id'])){ ?>
														<div class="order-page-row ordered-product">
															<div class="order-page-col product-item pro-img"><img src="<?php echo $orderPro[0]['img_url']; ?>"/></div>
															<div class="order-page-col product-item pro-name"><?php echo $orderPro['name']; ?></div>
															<div class="order-page-col product-item pro-sku"><?php if(!empty($orderPro['sku'])){
																echo $orderPro['sku'];
                                                            }else{
																echo "____";
															} ?></div>
															<div class="order-page-col product-item pro-qty"><?php echo $orderPro['quantity']; ?></div>
															<div class="order-page-col product-item pro-price"><?php echo $orderPro['price']; ?></div>
														</div>
													<?php } } ?>
												</div>
											</div>
										</div>
										<?php 
											if($allOrders %6 == 0){
												$className++;
											}										
										} } ?>
									</div>
								</div>
								<ul class="pagination" id="pagination"></ul> 
								<?php } else{ ?>
									<div class="no-orders">No orders found</div>
								<?php } ?>	
							</div>							
							<div class="dashboard-page-right">
								<div class="dashboard-right-top">
									<table class="dashboard-table-right">
										<thead>
											<th>Credit</th>
											<th>Pending issue</th>
											<th>Total sales</th>
											<th>Total orders</th>
										</thead>
										<tbody>
											<tr>
												<td>100</td>
												<td><?php echo $pendingOrders; ?></td>
												<td>$<?php echo array_sum($total_sale);?></td>
												<td><?php echo $totalOrders; ?></td>	
											</tr>
										</tbody>
									</table>
									<div class="dashboard-right-option">
										<div class="graph-sale-date">
											<div class="graph-sale-left">
												<li>Total sale</li>
												<li> $<?php echo array_sum($total_sale);?> </li>
											</div>
											<?php 
												$first_month = date('Y-01-01'); 
												$first_month = date("M 01", strtotime($first_month));
												$lastdate =    date("M d");
											?>
											<div class="graph-sale-right">
												<li> <?php echo $first_month.'-'.$lastdate;?> </li>
												<li> <?php echo $totalOrders; ?> orders </li>
											</div>
										</div>
										<div class="dashboard-right-graph">
										<div id="chartContainer" style="height: 270px; max-width:420px; margin: 0px auto;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>  
	</div>
	<div class="modal orders-products-models">
	</div>
	
	<?php $paginateLink = ceil($allOrders/6);
		  $pageshow = ceil($paginateLink/2);
	?>
	
<script>
jQuery(document).ready(function($){
	$('.page_1').css('display','block');
	<?php if($allOrders > 6){ ?>
		window.pagObj = $('#pagination').twbsPagination({
			totalPages: <?=$paginateLink?>,
			visiblePages: <?=$pageshow?>,
			onPageClick: function (event, page) {
				$('.admin-orders').css('display','none');
				$('.page_'+page).css('display','block');
			}
		});
	<?php } ?>

	$("#admin_DB_order").click(function(){
	   if(this.checked) { 
			$('.check_admin_DB_order').each(function() { 
				this.checked = true;     
			});
		}else{
			$('.check_admin_DB_order').each(function() {
				this.checked = false; 
			});        
		}
	});
}); 

/*********** Monthly sale chart JS **************/

window.onload = function () {
var options = {
	animationEnabled: true,  
	title:{
		text: "Monthly Sales - <?=date("Y")?>"
	},
	axisX: {
		valueFormatString: "MMM"
	},
	axisY: {
		title: "Sales (in USD)",
		prefix: "$",
		includeZero: false
	},
	data: [{
		yValueFormatString: "$#,###",
		xValueFormatString: "MMM",
		type: "spline",
		dataPoints: [
			<?php
				for($i=0;$i < sizeof($sales); $i++){
					if(!empty($sales[$i])){
						$total_price = array();
						foreach($sales[$i] as $sale_price){
							if(isset($sale_price[0]['show'])){
								if($sale_price['financial_status'] == "paid"){
									$line_items = $sale_price['line_items'];	
									foreach($line_items as $orderPro){
										if(!empty($orderPro[0]['shopify_product_id'])){
											$total_price[] = $orderPro['price']*$orderPro['quantity'];
										}
									}
								}
							}
							$price = array_sum($total_price);
						}
					}else{
						$price = 0;
					} ?>
                    { x: new Date(<?=date("Y")?>, <?=$i?>), y: <?=$price?> },
			<?php	}	?>
		]
	}]
};
$("#chartContainer").CanvasJSChart(options);

}	
</script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>