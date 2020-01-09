<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Login_model extends MY_Model
{
	public function __construct(){
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}

	public function login($arr=array()){
		extract($arr);
		$this->db->where('email', $email);
		$this->db->where('password', md5($password));
		$query = $this->db->get(USER);
		return $query->row();
	}
	
	public function check_email_exist($user_email){
		$this -> db -> where("email",$user_email);
		$query = $this -> db -> get(USER);
		if ($query -> num_rows() > 0) {
			$return_data = $query->row_array();
			return $return_data;
		}
		else{
			return false;
		}
	}

	public function get_emails(){
		$this->db->select('email');
		$this->db->from(USER);
		$r = $this->db->get()->result();
	}
  
	public function get_user_store($userid){
		$this->db->select('shop_name');
		$this->db->from(SHOP);
		$this->db->where('user_id', $userid);
		$this->db->where('payment_status_shop', 1);
		return $this->db->get()->result();
	}
  
	public function getUserInfo($id){
		$q = $this->db->get_where(USER, array('id' => $id), 1);
		if($this->db->affected_rows() > 0){
			$row = $q->row();
			return $row;
		}else{
			error_log('no user found getUserInfo('.$id.')');
			return false;
		}
	}

	public function get__user($id){
		$this->db->where('id', $id);
		$query = $this->db->get(USER);
		return $query->row();
	}

	public function updateProfile($id,$data){
		$data['updated_at'] = $this->date;
		$this->db->where('id', $id);
		$this->db->update(USER, $data);
	}

	public function insertToken($user_id){
        $token = substr(sha1(rand()), 0, 30);
        $string = array(
			'reset_token'=> $token
		);
        $this->db->where('id', $user_id);
        $record = $this->db->update(USER,$string);
        $this->db->where('id', $user_id);
        $query = $this -> db -> get(USER);
        return $query->result();
    }

    public function isTokenValid($token){
		$this->db->where('reset_token', $token);
		$query = $this -> db -> get(USER);
		if($this->db->affected_rows() > 0){
			$row = $query->result();
			$user_info = $this->getUserInfo($row[0]->id);
			return $user_info;
		}else{
			return false;
		}
	}

	public function updatePassword($id, $data){
		$data['updated_at'] = $this->date;
		$data['reset_token']="";
		$this->db->where('id', $id);
		$this->db->update(USER, $data);
		if ($this->db->affected_rows() >= 0) {
			return true;
		} else {
			return false;
		}
	}
}
?>