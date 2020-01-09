<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Admin_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}
	
	public function delete_order_admin($id,$data){
		$this->db->where('id', $id);
		$data['updated_at'] = $this->date;
		$this->db->update('importapp_admin_orders', $data);
		//$this->db->delete('importapp_admin_orders');
	}
	
	public function fetch_users($limit,$start)
	{
		$this->db->select('*, importapp_users.id AS u_id');
		$this->db->from(USER);
		$this->db->where('importapp_users.id != ', 1);
		$this->db->order_by("importapp_users.id", "DESC");
		$this->db->limit($limit,$start);
	    $query = $this->db->get();
	    return $query->result();	  
	}
	public function fetch_vendor_users($limit,$start)
	{
		$this->db->where('id != ', 1);
		$this->db->where('role_id', 3);
		$this->db->order_by("id", "DESC");
		$this->db->limit($limit,$start);
	    $query = $this->db->get(USER);
	    return $query->result();	  
	}

	
	 public function user_record_count() {
	
		$this->db->where('role_id != ', 1);
		$query = $this->db->get(USER);
		return $query->num_rows();		
	}
	 public function vendor_user_record_count() {
	
		$this->db->where('role_id != ', 1);
		$this->db->where('role_id', 3);
		$query = $this->db->get(USER);
		return $query->num_rows();		
	}
	
	/* Add new user */	
	public function insert_users($user)  	
	{   	   		
	$this->db->insert(USER, $user);	
	}	
	
	public function get_roles()
	{
		$this->db->select('*');		
		$this->db->from(ROLE);			
		$query = $this->db->get();		
		return $query->result();	
	}
	
	
	public function store_users($u_id)
	{
		$this->db->select('*');		
		$this->db->from(SHOP);	
        $this->db->where('user_id', $u_id);		
		$query = $this->db->get();		
		return $query->result();	
	}
	
	
	// Check if email exists in db for forgot password
	public function check_exist_email($email)
	{
		$this->db->where("email",$email);
		$query = $this->db->get(USER);
		return $query;
		 
	}
	// Insert token in db for forgot password
	public function addToken($user_id)
	{   
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
// Check if token matches for forgot password
	public function isTokenValid($token)
	{						 
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
	  
	public function create_password($token)
	{
		$user_info = $this->Login_model->isTokenValid($token);                
		if($user_info==false){
			$this->session->set_flashdata('flash_message', 'Token is invalid or expired');
			redirect('/login');
			}          
			$data = array(
				'email'=>$user_info->email,                 
				'reset_token'=>$user_info->reset_token
			);
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');              
			if ($this->form_validation->run() == FALSE) {   
			$this->load->view('headerView', $data);
			$this->load->view('login/new_password', $data);
			$this->load->view('footerView', $data);
			}else{
				if($this->input->post()){
					$user_id = $user_info->id;
					$data = array('password' => md5($this->input->post('password')));
					$update = $this->Login_model->updatePassword($user_id, $data);
					if($update==false){
						$this->session->set_flashdata('flash_message', 'There was a problem updating your password.');
					}else{
						$this->session->set_flashdata('flash_reset_pass', 'Your password has been updated. You may now login.');
					redirect('/login'); 
					}
				}                       
			}
	}
	
	/*Delete user*/
	public function delete_user($id){
		$this->db->where('id', $id);
		$this->db->delete(USER);
		return true;		
	}
	
	/*Edit user*/
	public function update_user($id, $data){
		$data['updated_at'] = $this->date;
		$this->db->where('id', $id);
		$this->db->update(USER, $data);
		
	}
	
	/* Get Single user detail acc to id for edit functionality*/
	public function get_upuser($id){
		$this->db->select('*');
		$this->db->from(USER);
		 $this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_profile($id){
		$this->db->select('*');
		$this->db->from(USER);
		 $this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function fetch_users_shop($id){
		$this->db->select('shop_name');
		$this->db->from(SHOP);
		 $this->db->where('user_id', $id);
		 $this->db->where('payment_status_shop', 1);
		$query = $this->db->get();
		return $query->result();
	}

	
	
	// Users Sorting 
	public function users_sorting($filter,$filter_value,$limit,$start){	   
	
    if($filter=="date"){ $filter="created_at"; }else{ $filter=$filter; }
      		
		$this->db->select('*, importapp_users.id AS u_id');
		$this->db->from(USER);	
		$this->db->order_by($filter,$filter_value);
		$this->db->where('id != ', 1);
		$this->db->order_by("id", "DESC");
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		return $query->result();		
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
    
	public function vendor_users_sorting($filter,$filter_value,$limit,$start){	   
	
    if($filter=="date"){ $filter="created_at"; }else{ $filter=$filter; }
      		
		$this->db->select('*');
		$this->db->from(USER);	
		$this->db->order_by($filter,$filter_value);
		$this->db->where('id != ', 1);
		$this->db->where('role_id', 3);
		$this->db->order_by("id", "DESC");
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		return $query->result();		
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	public function name_count($keyword){
			$this->db->select('*');
			$this->db->from(USER);
			$this->db->where('firstname',$keyword);
			$query=$this->db->get();
			return $query->num_rows();
	}
	public function vendor_name_count($keyword){
			$this->db->select('*');
			$this->db->from(USER);
			$this->db->where('firstname',$keyword);
			$this->db->where('role_id',3);
			$query=$this->db->get();
			return $query->num_rows();
	}
	
	
	public function user_data($limit, $start, $keyword){
		$this->db->select('*, importapp_users.id AS u_id');
		$this->db->from(USER);
		$this->db->where('username',$keyword);
		$this->db->limit($limit,$start);
		$query = $this->db->get();
	//	return $query->result();
		 if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[]=$row;
			}
			return $data;
		}
		return false; 
	}
	public function vendor_user_data($limit, $start, $keyword){
		$this->db->select('*');
		$this->db->from(USER);
		$this->db->where('firstname',$keyword);
		$this->db->where('role_id',3);
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		 if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[]=$row;
			}
			return $data;
		}
		return false; 
	}
	
	
	public function get_roles_by_ID()
	{
		$this->db->select('email');		
		$this->db->from(USER);	
		$this->db->where('role_id',1);
		$query = $this->db->get();		
		return $query->row_object();	
	}
	public function get_all_stores(){
		$this->db->select('*');
		$this->db->where('uninstall_webhook',1);
		$this->db->from(SHOP);
		$this->db->order_by('shop_name','ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_storeByShopName($shop){
		$this->db->select('*');
		$this->db->from(SHOP);
		$this->db->where('shop_name', $shop);
		$query = $this->db->get();
		return $query->result();
	}
}