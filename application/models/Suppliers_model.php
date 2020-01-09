<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Suppliers_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}
	public function import_supplier($data){
		$this->db->insert('importapp_suppliers',$data);
	}
	public function get_categoryId($category){
			$this->db->select('id');
			$this->db->from('importapp_categories');
			$this->db->where('category_name',$category);
			$query = $this->db->get();
			if($query->num_rows()>0){
			return $query->result_array();
			}	
			else{
				$abc = array(array('id'=>'no'));
				return $abc;
			}			
		}
		
	public function get_countryId($country){
			$this->db->select('id');
			$this->db->from('importapp_countries');
			$this->db->where('country_name',$country);
			$query = $this->db->get();
			if($query->num_rows()>0){
			return $query->result_array();
			}
			 else{
				$abc = array(array('id'=>'no'));
				return $abc;
			} 
		}
	public function mail_check($email){
		$this->db->select('*');
		$this->db->from('importapp_suppliers');
		$this->db->where('email',$email);
		$query = $this->db->get();
		return $query->num_rows();
	}
		
		public function get_stateId($state){
			$this->db->select('id');
			$this->db->from('importapp_states');
			$this->db->where('state_name',$state);
			$query = $this->db->get();
			if($query->num_rows()>0){
			return $query->result_array();
			}
			 else{
				$abc = array(array('id'=>'no'));
				return $abc;
			} 
		}		
	public function fetch_country()
	{
	  $this->db->order_by("country_name", "ASC");
	  $query = $this->db->get("countries");
	  return $query->result();
	}
	public function fetch_state($country_id)
	{
	  $this->db->where('country_id', $country_id);
	  $this->db->order_by('state_name', 'ASC');
	  $query = $this->db->get('states');
	  $output = '<option value="">Select State</option>';
	  foreach($query->result() as $row)
	  {
	   $output .= '<option value="'.$row->id.'">'.$row->state_name.'</option>';
	  }
	  
	  return $output;
	}
	public function fetchCategory()
	{	
		$this->db->select('*');
		$this->db->from(CATEGORIES);
		$query = $this->db->get();
		return $query->result();
    }
	public function insert_suppliers($data)
	{   
	    $data['created_at'] = $this->date;
		$this->db->insert(SUPPLIERS, $data);
		return TRUE;
	}
	public function fetch_suppliers(){
		$this->db->select('*');
		$this->db->from(SUPPLIERS);		
		$query = $this->db->get();
		return $query->result();
	}

    public function getbyemail($supplier){
		$this->db->select('*');
		$this->db->from(SUPPLIERS);
        $this->db->where('email',$supplier);		
		$query = $this->db->get();
		return $query->result();
	}


	public function delete_supplier($id){
		$this->db->where('id', $id);
		$this->db->delete(SUPPLIERS); 
    }
	
	public function get_supplier($id){
		$this->db->select('*');
		$this->db->from(SUPPLIERS);
	    $this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}
	public function countryid($country_id){		
		$this->db->select('id');
		$this->db->from('countries');
		$this->db->where('id', $country_id);
		$query = $this->db->get();
		return $query->result();		
	}
	public function get_states($cid){
	  $this->db->select('*');
		$this->db->from('states');
		$this->db->where('country_id', $cid);
		$query = $this->db->get();
		return $query->result ();
	}
	public function update_supplier($supplierId, $data){	
		print_r($data);
		$data['updated_at'] = $this->date;
		$this->db->where('id', $supplierId);
		$this->db->update(SUPPLIERS, $data);		
	}
	public function fetch_data($limit, $start, $uid) {	

	$query = $this->db->query("SELECT role_id FROM importapp_users WHERE id=$uid");
	$row = $query->row();
	$role_id = $row->role_id; 	
	$this->db->select("*,importapp_suppliers.id as sup_id");
	$this->db->from("importapp_suppliers");
	$this->db->join("importapp_countries", 'importapp_countries.id = importapp_suppliers.country', 'left');
	$this->db->join("importapp_categories", 'importapp_categories.id = importapp_suppliers.category', 'left');
	$this->db->limit($limit,$start);
	if($role_id!=1){
				$this->db->where('added_by', $uid);
		}		
	$this->db->order_by('importapp_suppliers.id', 'DESC');
	$query = $this->db->get();			
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}			
			
		 return $data;
		}
		return false;
	}	
    public function record_count() {
		return $this->db->count_all(SUPPLIERS);  
	}	
	public function email_exist($email)
	{
	  $this->db->Select("*");
	  $this->db->from(SUPPLIERS);
	  $this->db->where('email',$email);
	  $query = $this->db->get();	 
      return $query->num_rows();
	}
	public function name_count($keyword){
		$this->db->Select('*');
		$this->db->where('name',$keyword);
		$query = $this->db->get('importapp_suppliers');
		return $query->num_rows();
	}
	public function name_data($limit, $start, $keyword, $uid){
		$query = $this->db->query("SELECT role_id FROM importapp_users WHERE id=$uid");
	    $row = $query->row();
	    $role_id = $row->role_id; 			
		$this->db->Select('* ,importapp_suppliers.id as sup_id');
		$this->db->from('importapp_suppliers');
		$this->db->where('name',$keyword);
		$this->db->join("importapp_countries", 'importapp_countries.id = importapp_suppliers.country', 'left');
		$this->db->join("importapp_categories", 'importapp_categories.id = importapp_suppliers.category', 'left');
		$this->db->limit($limit,$start);
		if($role_id!=1){
				$this->db->where('added_by', $uid);
		}	
		$query=$this->db->get();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
			
			return $data;
		}
		return false;
	}
	
	
	public function export_supplier($r_id,$u_id){
		$this->db->Select('name,email,address1,address2,country_name,state_name,city,zip,phone,whatsapp,skype,website,contact_person,category_name');
		$this->db->from('importapp_suppliers');
		
		$this->db->join("importapp_countries", 'importapp_countries.id = importapp_suppliers.country', 'left');
		$this->db->join("importapp_states", 'importapp_states.id = importapp_suppliers.state', 'left');
	//	$this->db->join("importapp_users", 'importapp_users.id = importapp_suppliers.added_by', 'left');
		$this->db->join("importapp_categories", 'importapp_categories.id = importapp_suppliers.category', 'left');		
		if($r_id!=1)
			{
				$this->db->where('added_by', $u_id);
			}	
			
		$query = $this->db->get();
		if($query->num_rows()>0){
			foreach($query->result_array() as $row){
				$data[] = $row;
			}		
			//	echo "<pre>";   print_r($data);   die;
			return $data;
		}
	}	
	
	public function vandor_supplier($uid){
			$this->db->Select('*');
			$this->db->from('importapp_suppliers');
			$this->db->where('added_by',$uid);
			$query = $this->db->get();
			return $query->num_rows();
	}
	public function vandor_supplier_data($uid){
			$this->db->Select('*');
			$this->db->from('importapp_suppliers');
			$this->db->where('added_by',$uid);
			$query = $this->db->get();
			return $query->result();
	}
	public function vandor_name_count($kyword, $uid){
		$this->db->Select('*');
		$this->db->from('importapp_suppliers');
		$this->db->where('name',$kyword);
		$this->db->where('added_by',$uid);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
}
?>