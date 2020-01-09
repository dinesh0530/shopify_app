<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendororders extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$userid = $this->session->userdata('login')->id;
	    $this->load->library('recaptcha');
		if($userid == 0)
		{
			redirect('login');
		}
		$this->date	= date("Y-m-d H:i:s");
	}
	
	public function index()
	{   
		$this->data["active"] = "orders";
		$this->data['stores'] = $this->Shop->get_all_stores();
		$users = $this->session->userdata('login');
		$user_id = $users->id;
		$recordfound = 0;
		$fullfiilment = 0;
		if(isset($_GET['shopname']) && !empty($_GET['shopname'])){
			 $this->data['shops'] = $this->Shop->get_storeByShopName($_GET['shopname']);
		}else{
			$this->data['shops'] = $this->Shop->get_all_stores();
		}
		$start_date  = isset($_GET['startDate'])&&!empty($_GET['startDate'])?$_GET['startDate']:date("Y-m-d", strtotime("-7 days"));
		
		$end_date  = isset($_GET['endDate'])&&!empty($_GET['endDate'])? date('Y-m-d', strtotime($_GET['endDate'].'+1 day')) :date("Y-m-d", strtotime("+1 days"));
		$end_date = $end_date."T00:00:00-05:00";
		if(!empty($this->data['shops'])){
			foreach ($this->data['shops'] as $shop) { 
				$storeName[] = $shop['shop_name'];
				$this->load->library( "shopifyclient" , array( "shop"  => $shop['shop_name'] , "token" => $shop['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
			}
			$currentStore = $storeName[0];
			$orders = $this->shopifyclient->call('GET', "/admin/orders.json?status=any&created_at_min=$start_date&created_at_max=$end_date");
			
			$ac = 0;
			foreach($orders as $order){
				$aj = 0;
				$line_items = $order['line_items'];
				foreach($line_items as $orderPro){
					if($orderPro['fulfillment_status'] != ''){
						$fullfiilment = 1;
					}
					if($orderPro['fulfillment_status'] == ''){
						$fullfiilment = 2;
					}
					$pid = $orderPro['product_id'];
					$pro_id = $this->Order_model->getvendorsprodcuts($currentStore, $pid, $user_id);
					$ipid = $pro_id[0]['product_id'];
					
					$images = $this->Order_model->getprodcutimages($ipid);
					$imgSRC = $images[0]['src'];
					$img_url = base_url()."uploads/product$ipid/$imgSRC";
					
					
					if(!empty($pro_id)){
						$recordfound = 1;
						array_push($orders[$ac], array("show" => "show"));
					}
					$values = array("img_url" => $img_url, 
									"shopify_product_id" => $pro_id[0]['shopify_product_id']
									);
					array_push($orders[$ac]['line_items'][$aj], $values );
					$aj++;
				}
				$ac++;
			}
			
			$this->data['orders'] = $orders;
			$this->data['recordfound'] = $recordfound;
			$this->data['currentStore'] = $currentStore;
		}
		$this->data['fullfiilment'] = $fullfiilment;
		$this->load->view('headerView', $this->data);
		$this->load->view('vendor/vendorOrdersView', $this->data);
		$this->load->view('footerView', $this->data);
	}
	
	public function orders_search(){ 
		if($this->input->method(TRUE)=="POST"){
			$order_no = $this->input->post("order_search");
            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");
			$enddate  = date('Y-m-d', strtotime($end_date.'+1 day'));
			$enddate  = $enddate."T00:00:00-05:00";
			$users = $this->session->userdata('login');
			$user_id = $users->id;
			$this->data['stores'] = $this->Shop->get_all_stores();
			
			$shop_name = $this->input->post("shop_name");
			$this->data['shops'] = $this->Shop->get_storeByShopName($_POST['shop_name']);
			$recordfound = 0;
			$fullfiilment = 0;
			if(!empty($this->data['shops'])){				
				foreach ($this->data['shops'] as $shop) {
					$storeName = $shop['shop_name'];
					$this->load->library( "shopifyclient" , array( "shop"  => $shop['shop_name'] , "token" => $shop['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				}
				$currentStore = $storeName;
				
				if(!empty($order_no)){ 
					$orders = $this->shopifyclient->call('GET', "/admin/orders.json?name=$order_no");
					if(!empty($orders[0]['line_items'])){
						$line_items = $orders[0]['line_items'];
						$aj = 0;
						foreach($line_items as $orderPro){
							if($orderPro['fulfillment_status'] != ''){
								$fullfiilment = 1;
						    }
							if($orderPro['fulfillment_status'] == ''){
								$fullfiilment = 2;
							}
							$pid = $orderPro['product_id'];
							$pro_id = $this->Order_model->getvendorsprodcuts($currentStore, $pid, $user_id);
							
							$ipid = $pro_id[0]['product_id'];
							
							$images = $this->Order_model->getprodcutimages($ipid);
							$imgSRC = $images[0]['src'];
							$img_url = base_url()."uploads/product$ipid/$imgSRC";
							
							if(!empty($pro_id)){
								$recordfound = 1;
								array_push($orders[0], array("show" => "show"));
							}
							$values = array("img_url" => $img_url, 
											"shopify_product_id" => $pro_id[0]['shopify_product_id']
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
						$aj = 0;
						$line_items = $order['line_items'];
						foreach($line_items as $orderPro){
							if($orderPro['fulfillment_status'] == 'fulfilled'){
								$fullfiilment = 1;
						    }
							if($orderPro['fulfillment_status'] == ''){
								$fullfiilment = 2;
							}
							$pid = $orderPro['product_id'];
							$pro_id = $this->Order_model->getvendorsprodcuts($currentStore, $pid, $user_id);
							
							$ipid = $pro_id[0]['product_id'];
							
							$images = $this->Order_model->getprodcutimages($ipid);
							$imgSRC = $images[0]['src'];
							$img_url = base_url()."uploads/product$ipid/$imgSRC";
							if(!empty($pro_id)){
								$recordfound = 1;
								array_push($orders[$ac], array("show" => "show"));
							}
							$values = array("img_url" => $img_url, 
											"shopify_product_id" => $pro_id[0]['shopify_product_id']
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
            $this->data['fullfiilment'] = $fullfiilment;
			$this->data["active"] = "orders";
			$this->load->view('headerView', $this->data);
			$this->load->view('vendor/vendorOrdersView', $this->data);
			$this->load->view('footerView', $this->data); 
	    }else{
			redirect('vendor/orders');
		}
	}
	
	public function delete_order(){
		if($this->input->method(TRUE)=="POST"){
			$users = $this->session->userdata('login');
			$user_id = $users->id;
			$this->data['stores'] = $this->Shop->get_all_stores();
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
			$this->data['currentStore'] = $currentStore;
		}		
	}
	
	
}
