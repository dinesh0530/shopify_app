<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class User_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}

	public function user_details($userid){
		$this->db->select('app_payment_id');
		$this->db->from(USER);
		$this->db->where('id', $userid);
		return $this->db->get()->result();
	}
	public function save_payment($data){
		
		$this->db->insert('importapp_payments', $data);
		return true;
	}
	public function status_change($user_id){
		 $data = array(
			'payment_status' => 1
		 );
		$this->db->where('id', $user_id);
		$this->db->update('importapp_users', $data);
		return true;
	}
	public function get_date($userid){
		$this->db->select('*');
		$this->db->from('importapp_payments');		
		$this->db->where('user_id', $userid);
		$this->db->order_by("id", "desc");
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function check_status($user_id){
		$pay = 1;
		$role_id = 2;
		$this->db->select('*');
		$this->db->from('importapp_users');
		$this->db->where('id', $user_id);
		$this->db->where('payment_status', $pay);
		$this->db->where('role_id', $role_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}
	
	public function update_shop_payment($user_id, $store_id){
		if(!empty($store_id)){
			$where = "id=".$store_id." AND user_id=".$user_id;
		}else{
			$where = "user_id=".$user_id;
		}
		$data = array(
			'payment_status_shop' => 1
		);
		$this->db->where($where);
		$this->db->update('importapp_shop', $data);
		return true;
	}
	
	public function get_user_stores($user_id){
		$this->db->select('*');
		$this->db->from('importapp_shop');
		$this->db->limit(2);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}
	
	public function update_user_status($user_id){
		 $data = array(
			'payment_status' => 0
		 );
		$this->db->where('id', $user_id);
		$this->db->update('importapp_users', $data);
		return true;
	}
	
	public function update_shop_status($user_id){
		 $data = array(
			'payment_status_shop' => 0
		 );
		$this->db->where('user_id', $user_id);
		$this->db->update('importapp_shop', $data);
		return true;
	}
	
	public function get_pay_status($userid){
		$this->db->select('*');
		$this->db->from('importapp_payments');		
		$this->db->where('user_id', $userid);
		$this->db->order_by("id", "desc");
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}
    
}
?>