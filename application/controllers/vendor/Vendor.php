<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends MY_Controller {

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
			
		$this->data["active"] = "dashboard";	
		$id = $this->session->userdata('login')->id;
		$vendor_name = $this->session->userdata('login')->firstname;
		$this->data['stores'] = $this->Shop->get_all_stores();
		$users = $this->session->userdata('login');
		$user_id = $users->id;
		$recordfound = 0;
		$salefound = 0;
		if(isset($_GET['shop_name']) && !empty($_GET['shop_name'])){
			 $this->data['shops'] = $this->Shop->get_storeByShopName($_GET['shop_name']);
		}else{
			$this->data['shops'] = $this->Shop->get_all_stores();
		}
		$start_date  = isset($_GET['startDate'])&&!empty($_GET['startDate'])?$_GET['startDate']:date("Y-m-d", strtotime("-7 days"));
		
		$end_date  = isset($_GET['endDate'])&&!empty($_GET['endDate'])? date('Y-m-d', strtotime($_GET['endDate'].'+1 day')) :date("Y-m-d", strtotime("+1 days"));
		$end_date = $end_date."T00:00:00-05:00";
		$sales = array();
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
		
			$total_months  = date('m');	            			
			for($i=1;$i <= $total_months; $i++){
				$query_date = date("Y-$i-$i");
				$created_at_min  = date('Y-m-01', strtotime($query_date));
				$created_at_max   = date('Y-m-t', strtotime($query_date));
				$sale  = $this->shopifyclient->call("GET", "/admin/orders.json?status=any&created_at_min=$created_at_min&created_at_max=$created_at_max");
				$sc = 0;
				foreach($sale as $order){
					$aj = 0;
					$line_items = $order['line_items'];
					foreach($line_items as $orderPro){
						$pid = $orderPro['product_id'];
						$pro_id = $this->Order_model->getvendorsprodcuts($currentStore, $pid, $user_id);
						if(!empty($pro_id)){
							$salefound = 1;
							array_push($sale[$sc], array("show" => "show"));
						}
						$values = array("shopify_product_id" => $pro_id[0]['shopify_product_id']
										);
						array_push($sale[$sc]['line_items'][$aj], $values );
						$aj++;
					}
					$sc ++;
				}
				$sales[] = $sale;
			}
		}
		$this->data['sales'] = $sales; 
		$this->data['salefound'] = $salefound;
		$this->data['vendorname'] = $vendor_name;		
		$this->load->view('headerView', $this->data);
		$this->load->view('vendor/vendorView', $this->data);
		$this->load->view('footerView', $this->data);
	}
}