<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Orders extends MY_Controller {

  public function __construct(){ 
		parent::__construct();
		$user_id = $this->session->userdata('login')->id;
		if($user_id == 0){
			redirect('login');
		}
		$this->date	= date("Y-m-d H:i:s");
		$role= $this->session->userdata('login')->role_id;
		if($role == 2){
			$user_pay_status = $this->User_model->get_pay_status($user_id); 
			$expiry_date = $this->User_model->get_date($user_id);
			$this->data["user_pay_status"] = $user_pay_status;
			$this->data["expiry_date"] = $expiry_date; 
			$result = $this->User_model->check_status($user_id); 
			$expiry_date = $this->User_model->get_date($user_id);
			$date1 = date("Y-m-d h:i:s");
			$datetime1 = new DateTime($date1);
			$datetime2 = new DateTime($expiry_date[0]['expiry_date']);
			$shop = $this->session->userdata('shop');
			$shop_payment = $this->Shop->shop_payment_status($shop); 
			if(!empty($shop_payment)){
				if(empty($result) || $datetime1 > $datetime2 || $shop_payment[0]['payment_status_shop'] != 1){
					redirect('user/payment');
				}
			}
			if(empty($result) || $datetime1 > $datetime2){
				redirect('user/payment'); 
			}
		}
	}	

	public function get_orders(){
		$cansl = "";
		$incomp = "";
		$this->data["active"] = "orders";
		$this->load->view('headerView', $this->data);
		$users = $this->session->userdata('login');
		$user_id = $users->id;
		$this->data['shops'] = $this->Shop->get_all_shops($user_id);
		$this->data['stores'] = $this->Shop->get_all_shops($user_id);
		$start_date  = date("Y-m-d", strtotime("-7 days"));
		$end_date  = date("Y-m-d");
		$enddate  = date("Y-m-d", strtotime("+1 days"));
		$recordfound = 0;
		if(!empty($this->data['shops'])){
			if(isset($_GET['shopname']) && !empty($_GET['shopname'])){
			   $this->data['shops'] = $this->Shop->get_storeByShopName($_GET['shopname']);
			   $storeName = array();
				foreach ($this->data['shops'] as $shop) {
					$storeName[] = $shop['shop_name'];
					$this->load->library( "shopifyclient" , array( "shop"  => $shop['shop_name'] , "token" => $shop['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				}
				$currentStore = $storeName[0];
            }else{			
				if(!empty($this->session->userdata['shop'])){
					$currentStore = $this->session->userdata['shop'];
					$this->load->library( "shopifyclient" , array( "shop"  => $this->session->userdata['shop'] , "token" => $this->session->userdata['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				}else{
					$storeName = array();
					foreach ($this->data['shops'] as $shop) {
						$storeName[] = $shop['shop_name'];
						$this->load->library( "shopifyclient" , array( "shop"  => $shop['shop_name'] , "token" => $shop['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
					}
					$currentStore = $storeName[0];
				}
			}
			
			$orders = $this->shopifyclient->call('GET', "/admin/orders.json?status=any&created_at_min=$start_date&created_at_max=$enddate");			
			$ac = 0;
			$incomp = "";
			$cansl = "";
			foreach($orders as $order){
				$orderpaid = $this->Order_model->getpaidorderstoadmin($order['order_number'], $currentStore);
				if(!empty($orderpaid)){
					$paidtoadmin = 1;
				}else{
					$paidtoadmin = 0; 
				}
				$aj = 0;
				$line_items = $order['line_items'];
				if($order['financial_status'] == "voided"){
					$cansl = "cancel";
				}
				if($order['financial_status'] == "pending"){
					$incomp = "incomplete";
				}
				foreach($line_items as $orderPro){
					$pid = $orderPro['product_id'];
					$pro_id = $this->Order_model->getstoreprodcuts($currentStore, $pid);	
					$ipid = $pro_id[0]['product_id'];
					$prices = $this->Order_model->wholesaleprice($ipid);
					$proOwner = $prices[0]['product_add_by'];
					$proOwnerdetail = $this->Order_model->getproOwner($proOwner);
					 
					$images = $this->Order_model->getprodcutimages($ipid);
					$imgSRC = $images[0]['src'];
					$img_url = base_url()."uploads/product$ipid/$imgSRC";
					if(!empty($pro_id)){
						$recordfound = 1;
						array_push($orders[$ac], array("show" => "show"));
					}
					$values = array("img_url" => $img_url, 
					                "shopify_product_id" => $pro_id[0]['shopify_product_id'],
									"store_pro_id" => $pro_id[0]['product_id'],
									"wholeSale_price" => $prices[0]['wholesale_price'],
									"paidtoadmin" => $paidtoadmin,
								);
					array_push($orders[$ac]['line_items'][$aj], $values );
					$aj++;
				}
				$ac++;
			}
			$this->data['orders'] = $orders;
		
		}
		if(isset($currentStore))
		{
			$this->data['currentStore'] = $currentStore;
		}
		$this->data['inc_order'] = $incomp;
		$this->data['cnsl_order'] = $cansl;
		$this->data['recordfound'] = $recordfound;
		$this->data['start_date'] = $start_date;
		$this->data['end_date'] = $end_date;
		$this->load->view('orders/ordersView', $this->data);
		$this->load->view('footerView', $this->data);
	}

	public function orders_search(){
		if($this->input->method(TRUE)=="POST"){
			$order_no = $this->input->post("order_search");
            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");
			$enddate  = date('Y-m-d', strtotime($end_date.'+1 day'));
			//$enddate  = $enddate."T00:00:00-05:00";
			$this->data["active"] = "orders";
			$this->load->view('headerView', $this->data);
			$users = $this->session->userdata('login');
			$user_id = $users->id;
			$this->data['stores'] = $this->Shop->get_all_shops($user_id);
			
			$shop_name = $this->input->post("shop_name");
			$this->data['shops'] = $this->Shop->get_storeByShopName($shop_name);
			$recordfound = 0;
			$incomp = "";
			$cansl = "";
			if(!empty($this->data['shops'])){	
			
				foreach ($this->data['shops'] as $shop) {
					$storeName = $shop['shop_name'];
					$this->load->library( "shopifyclient" , array( "shop"  => $shop['shop_name'] , "token" => $shop['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				}
				$currentStore = $storeName;
				
				if(!empty($order_no)){
					$orderpaid = $this->Order_model->getpaidorderstoadmin($order_no ,$currentStore);
					if(!empty($orderpaid)){
						$paidtoadmin = 1;
					}else{
						$paidtoadmin = 0; 
					}
					$orders = $this->shopifyclient->call('GET', "/admin/orders.json?status=any&name=$order_no");
					
					if(!empty($orders)){
						if($orders[0]['financial_status'] == "voided"){
							$cansl = "cancel";
						}
						if($orders[0]['financial_status'] == "pending"){
							$incomp = "incomplete";
						}
					}
					if(!empty($orders[0]['line_items'])){
						$line_items = $orders[0]['line_items'];
						$aj = 0;
						foreach($line_items as $orderPro){
							$pid = $orderPro['product_id'];
							$pro_id = $this->Order_model->getstoreprodcuts($currentStore, $pid);
							
							$ipid = $pro_id[0]['product_id'];
							$prices = $this->Order_model->wholesaleprice($ipid);
							$images = $this->Order_model->getprodcutimages($ipid);
							$imgSRC = $images[0]['src'];
							$img_url = base_url()."uploads/product$ipid/$imgSRC";
							if(!empty($pro_id)){
								$recordfound = 1;
								array_push($orders[0], array("show" => "show"));
							}
							$values = array("img_url" => $img_url, 
											"shopify_product_id" => $pro_id[0]['shopify_product_id'],
											"store_pro_id" => $pro_id[0]['product_id'],
											"wholeSale_price" => $prices[0]['wholesale_price'],
											"paidtoadmin" => $paidtoadmin,
										);
							array_push($orders[0]['line_items'][$aj], $values );
							$aj++;
						}
					}
					$this->data['orders'] = $orders;
					$this->data['currentStore'] = $currentStore;
					$this->data['order_no'] = $this->input->post("order_search");
				}else{ 
					$orders = $this->shopifyclient->call('GET', "/admin/orders.json?status=any&created_at_min=$start_date&created_at_max=$enddate");
										
					$ac = 0;
					foreach($orders as $order){
						if($order['financial_status'] == "voided"){
							$cansl = "cancel";
						}
						if($order['financial_status'] == "pending"){
							$incomp = "incomplete";
						}
						$orderpaid = $this->Order_model->getpaidorderstoadmin($order['order_number'], $currentStore);
						if(!empty($orderpaid)){
							$paidtoadmin = 1;
						}else{
							$paidtoadmin = 0; 
						}
						$aj = 0;
						$line_items = $order['line_items'];
						foreach($line_items as $orderPro){
							$pid = $orderPro['product_id'];
							$pro_id = $this->Order_model->getstoreprodcuts($currentStore, $pid);
							
							$ipid = $pro_id[0]['product_id'];
							$prices = $this->Order_model->wholesaleprice($ipid);
							$images = $this->Order_model->getprodcutimages($ipid);
							$imgSRC = $images[0]['src'];
							$img_url = base_url()."uploads/product$ipid/$imgSRC";
							if(!empty($pro_id)){
								$recordfound = 1;
								array_push($orders[$ac], array("show" => "show"));
							}
							$values = array("img_url" => $img_url, 
											"shopify_product_id" => $pro_id[0]['shopify_product_id'],
											"store_pro_id" => $pro_id[0]['product_id'],
											"wholeSale_price" => $prices[0]['wholesale_price'],
											"paidtoadmin" => $paidtoadmin,
										);
							array_push($orders[$ac]['line_items'][$aj], $values );
							$aj++;
						}
						$ac++;
					}
					$this->data['orders'] = $orders;					
					$this->data['currentStore'] = $currentStore;
					$this->data['start_date'] = $this->input->post("start_date");
					$this->data['end_date'] = $this->input->post("end_date");
				}
			}
            $this->data['recordfound'] = $recordfound;
			$this->data['inc_order'] = $incomp;
		    $this->data['cnsl_order'] = $cansl;
			$this->load->view('orders/ordersView', $this->data);
			$this->load->view('footerView', $this->data); 
	    }else{
			redirect('user/orders');
		}	
	}
	
	public function delete_order(){
		if($this->input->method(TRUE)=="POST"){
			$users = $this->session->userdata('login');
			$user_id = $users->id;
			$this->data['stores'] = $this->Shop->get_all_shops($user_id);
			$shop_name = $this->input->post("shop_name");
			$this->data['shops'] = $this->Shop->get_storeByShopName($shop_name);
			if(!empty($this->data['shops'])){
				foreach ($this->data['shops'] as $shop) {
					$storeName = $shop['shop_name'];
					$this->load->library( "shopifyclient" , array( "shop"  => $shop['shop_name'] , "token" => $shop['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				}
				$currentStore = $storeName;
			}
			$orderIds = $this->input->post("orderid");
			foreach($orderIds as $orderId){
			   $orders = $this->shopifyclient->call('DELETE', "/admin/orders/$orderId.json");
			}
			$this->session->set_flashdata('delete_Order', 'Orders has been deleted successfully.');
		}		
	}
	
	public function paytoadmin(){ 
	    $pro_ids = $this->input->post("pro_ids");
	    $or_id = $this->input->post("or_id");
	    $pro_id = explode(",",$pro_ids);
		$wholesalePrice = array();
		foreach($pro_id as $pid){
			$ipid = explode(" ",$pid);
			$impid = $ipid[0];
			$qty = $ipid[1];
			$prices = $this->Order_model->wholesaleprice($impid);
			$wholesalePrice[] = $prices[0]['wholesale_price']*$qty;
		}
		   $price = array_sum($wholesalePrice);
		   $items = sizeof($wholesalePrice);
	   ?>
		
		    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" id="package_form"  method="post" >
				<input type="hidden" name="business" value="ram.sunder@dbuglab.com">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="item_name" value="<?= $or_id ?>">
				<input type="hidden" id="amount" name="amount" value="<?= $price?>">  
				<input type="hidden" name="first_name" value="<?= $this->session->userdata('login')->id  ?>"> 
				<input type="hidden" name="rm" value="2">
				<input type="hidden" name="item_number" value="<?=$items?>">
				<input type="hidden" name="last_name" value="">  
				<input type="hidden" name="address1" value="">  
				<input type="hidden" name="address2" value="">
				<input type="hidden" name="custom" value="<?= $pro_ids  ?>">
				<input type="hidden" name="on0" value="store">
				<input type="hidden" name="os0" value="quantity">
				<input type="hidden" name="city" value=""> 
				<input type="hidden" name="state" value="">   
				<input type="hidden" name="return" value="<?= site_url('thank-you')?>">
				<!-- <input type="hidden" name="notify_url" value="<?= site_url('thank-you')?>"> -->
				<input type="hidden" name="cancel_return" value="<?= site_url()?>">
				<input type="hidden" name="cbt" value="<?= site_url()?>">
				<input type="hidden" name="currency_code" value="USD">
				
		    </form>	
		    <script type="text/javascript">
				document.getElementById("package_form").submit();
			</script> 
	<?php
	}
}