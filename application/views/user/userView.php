<script>
$.noConflict();
jQuery(document).ready(function($){

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
	
		var $divs = $("div.user-orders");
		$('#total-sale').on('change', function (){
			if($(this).val() == "low_price"){
				var numericallyOrderedDivs = $divs.sort(function (a, b) {
					var vA = parseInt($(a).find("div.total_amounts").text().replace("$",''));
					var vb = parseInt($(b).find("div.total_amounts").text().replace("$",''));
					return vA - vb;
				});	
				$(".user-main-orders").html(numericallyOrderedDivs);
            }
            if($(this).val() == "high_price"){
				var numericallyOrderedDivs = $divs.sort(function (a, b) {
					var vA = parseInt($(a).find("div.total_amounts").text().replace("$",''));
					var vb = parseInt($(b).find("div.total_amounts").text().replace("$",''));
					return vb - vA;
				});
				$(".user-main-orders").html(numericallyOrderedDivs);
			}
		});
		
		$('body').on('click', 'a.order_number', function(e) {
			e.preventDefault();
			var html = $(this).parent().parent().parent('div.order-page-row').next('div.order-page-pro-row').html();
			$('.orders-products-models').html(html);
			$('.orders-products-models').modal({ fadeDuration: 100 });
		});
	
});	
</script>
<?php 
$total_sale = array();
$current_order = 0;
$totalOrders = 0; 
$pendingOrders = 0; 
$allOrders = 0; 
$className = 1; ?>
	<div class="inner-pages"> 
		<section class="site-dashboard-page">
			<div class="dashboard-page-inner">
				<div class="container">
					<div class="dashboard-page-content">					
						<div class="dashboard-top-section">
							<div class="dashboard-total-sales">
								<span class="filter-search">Filter results by:</span>
								<span class="total-sales">
									<select name="total-sales"  id="total-sale">
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
											<?php foreach($stores as $store){ ?>
												<option value="<?php echo $store->shop_name;?>" 
												<?php 
													if($currentStore == $store->shop_name){
														echo "selected";
													}
												?> > <?php echo $store->shop_name;?> </option>
											<?php }?>
										</select> 									
									</div>
								</form>
							</div>
							<div class="edit_profile_button">
								<a id="shopify_connect" target="_blank" href="<?php echo site_url('install') ?>"> CONNECT MY STORE <img src="<?php echo base_url('images/shopify-white.png') ?>"></a>
							</div>
						</div>
						<div class="dashboard-bottom-section">
							<div class="dashboard-left-bottom">
							    <?php if(!empty($orders)){ ?>
								<div class="order-page-table dashboard-table-left">
									<div class="order-page-head">
										<div class="order-page-heading checkbox">
											<input id="user_DB_order" type="checkbox">
										</div>
										<div class="order-page-heading pro-img">
											Product image
										</div>
										<div class="order-page-heading pro-sku">
											Product sku
										</div>
										<div class="order-page-heading pro-name">
											Product name
										</div>
										<div class="order-page-heading pro-sale">
											Total no. of sales
										</div>
										<div class="order-page-heading pro-price">
											Total payment
										</div>
									</div>
									<div class="order-page-body user-main-orders">
									<?php
									   foreach ($orders as $order) {
										   $allOrders = $allOrders+1;
											$total_sales = array();
											$total_quat = array();	
											foreach($order as $orderPro){
												$total_sales[] = $orderPro['price']*$orderPro['quantity'];
												$total_quat[]  =  $orderPro['quantity'];
												$total_sale[] = $orderPro['price']*$orderPro['quantity'];
											}
										?>
									    <div class="user-orders page_<?=$className?>" style="display:none;">
											<div class="order-page-row">
												<div class="order-page-col checkbox"><input class="check_user_DB_order orders_id" type="checkbox" ></div>
												<div class="order-page-col pro-img"><img src="<?php echo $order[0][0]['img_url']; ?>"></div>
												<div class="order-page-col pro-sku"><?php echo $order[0]['sku']; ?></div>
												<div class="order-page-col pro-name"><?php echo $order[0]['title']; ?></div>
												<div class="order-page-col pro-sale"><?php echo array_sum($total_quat);?></div>
												<div class="order-page-col pro-price total_amounts">$<?php echo array_sum($total_sales);?>
												</div>
											</div>
										</div> 
										<?php 
										if($allOrders %6 == 0){
											$className++;
										}} ?>
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
											<th>Total paid sales</th>
											<th>Total paid orders</th>
										</thead>
										<tbody>
											<tr>
												<td>100</td>
												<td><?php echo $pendingorder; ?></td>
												<td>$<?php echo array_sum($total_sale);?></td>
												<td><?php echo $orderscount; ?></td>	
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
												<li> <?php echo $orderscount; ?> orders </li>
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
				$('.user-orders').css('display','none');
				$('.page_'+page).css('display','block');
			}
		}); 
	<?php } ?>
	
	$("#user_DB_order").click(function(){
	   if(this.checked) { 
			$('.check_user_DB_order').each(function() { 
				this.checked = true;     
			});
		}else{
			$('.check_user_DB_order').each(function() {
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
		xValueFormatString: "MMMM",
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
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>