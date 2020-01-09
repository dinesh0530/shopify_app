<?php

class Home extends MY_Controller{

    
    public function __construct()
    {
        parent::__construct();
		if($this->session->userdata('login')){
			$userid = $this->session->userdata('login')->id;
			$role= $this->session->userdata('login')->role_id;
			if($role == 2){
				$user_pay_status = $this->User_model->get_pay_status($userid); 
				$expiry_date = $this->User_model->get_date($userid);
				$this->data["user_pay_status"] = $user_pay_status;
				$this->data["expiry_date"] = $expiry_date; 
				$result = $this->User_model->check_status($userid); 
				$expiry_date = $this->User_model->get_date($userid);
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
    }
    
	public function index(){
		$this->data["active"] = "home";
		$this->load->view('headerView', $this->data);
		$this->load->view('homeView', $this->data);
		$this->load->view('footerView', $this->data);
	}   
       

}