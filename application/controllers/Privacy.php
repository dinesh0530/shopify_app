<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy extends MY_Controller {
	public function __construct(){
		parent::__construct();	
		if($this->session->userdata('login')){
			$userid = $this->session->userdata('login')->id;
			$role= $this->session->userdata('login')->role_id;
			if($role == 2){
				$user_pay_status = $this->User_model->get_pay_status($userid); 
				$expiry_date = $this->User_model->get_date($userid);
				$this->data["user_pay_status"] = $user_pay_status;
				$this->data["expiry_date"] = $expiry_date; 
			}
		}
		$this->date	= date("Y-m-d H:i:s");		
	}

	public function index(){
		$this->data["active"] = "privacy_policy";
		$this->load->view('headerView', $this->data);
		$this->load->view('user/privacy-policy', $this->data);
		$this->load->view('footerView', $this->data);
	}	
}