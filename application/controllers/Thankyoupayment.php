<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Thankyoupayment extends MY_Controller {

  public function __construct(){ 
		parent::__construct();
		$userid = $this->session->userdata('login')->id;
		if($userid == 0){
			redirect('login');
		}
		$this->date	= date("Y-m-d H:i:s");
	}
	
	public function index(){
		$users = $this->session->userdata('login');
		$user_id = $users->id;		
		
		$key = '';
		$value = '';
		
		$paypal_response = array();
		foreach($_REQUEST as $k=>$val){
		  $key .=  $k.'===>'.$val.'<br/>';
		  $paypal_response[$k] = $val;
		}
		
		$paypal_response['customer_id'] = $user_id;
		
		if($paypal_response['item_name'] == 'Premium plan' || $paypal_response['item_name'] == 'Basic plan' ){
			$expiry_date = date('Y-m-d H:i:s', strtotime('+1 months'));
			$data = array(
				'user_id' => $user_id,
				'transaction_id' => $paypal_response['tx'],
				'subscription_plan' => $paypal_response['item_name'],
				'amount' => $paypal_response['amt'],
				'currency_code' => $paypal_response['cc'],
				'status' => $paypal_response['st'],
				'ci_session' => $paypal_response['ci_session'],
				'purchase_date' => date('Y-m-d H:i:s'),
				'expiry_date' => $expiry_date
			);
			
			$result = $this->User_model->save_payment($data);
			if($result == TRUE){
				$this->User_model->status_change($user_id);
				if($paypal_response['item_name'] == 'Premium plan'){
					$store_id = "";
					$update_shop = $this->User_model->update_shop_payment($user_id, $store_id);
				}
				if($paypal_response['item_name'] == 'Basic plan'){
					$get_stores = $this->User_model->get_user_stores($user_id);
					if(!empty($get_stores)){
						foreach($get_stores as $store){
							$store_id = $store['id'];
							$update_shop = $this->User_model->update_shop_payment($user_id, $store_id);
						}
					}
				}
			}
		}
		else{
			$item_name = explode(",",$paypal_response['item_name']);
			$item = $item_name[0];
			$store = $item_name[1];
			$data = array(
				'customer_id' => $user_id,
				'transaction_id' => $paypal_response['tx'],
				'item_name' => $item,
				'item_number' => $paypal_response['item_number'],
				'shopify_store' => $store,
				'amount' => $paypal_response['amt'],
				'currency_code' => $paypal_response['cc'],
				'status' => $paypal_response['st'],
				'pro_ids' => $paypal_response['cm'],
				'ci_session' => $paypal_response['ci_session'],
				'created_at' => date('Y-m-d H:i:s')
			);
			
			$inserorder = $this->Order_model->insert_admin_order($data);
		}
		
		$role= $this->session->userdata('login')->role_id;
		if($role == 2){
			$user_pay_status = $this->User_model->get_pay_status($user_id); 
			$expiry_date = $this->User_model->get_date($user_id);
			$this->data["user_pay_status"] = $user_pay_status;
			$this->data["expiry_date"] = $expiry_date; 
		}
		
		$this->load->view('headerView', $this->data);
        $this->load->view('footerView', $this->data);
	}	
}