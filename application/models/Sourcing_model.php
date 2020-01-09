<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Sourcing_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}
	
	public function getRecord($rowno,$rowperpage) {
		$this->db->select('*, importapp_images.product_id AS product_img_id' );
		$this->db->from('importapp_products');
		$this->db->limit($rowperpage, $rowno); 
		$this->db->join('importapp_images', 'importapp_images.product_id = importapp_products.id');		
		$query = $this->db->get();    	
		return $query->result_array();
	}
	
	public function getAllRecord($rowno,$rowperpage,$product_name) {
		$this->db->select('*, importapp_images.product_id AS product_img_id' );
		$this->db->from('importapp_products');
		$this->db->limit($rowperpage, $rowno); 
		$this->db->join('importapp_images', 'importapp_images.product_id = importapp_products.id'); 
		$this->db->like('importapp_products.product_title', $product_name);
		$query = $this->db->get();    	
		return $query->result_array();
	}
	
	public function getRecordCount($uid) {
		$roleid = $this->session->userdata('login')->role_id;
    	$this->db->select('count(*) as allcount');		
      	$this->db->from('importapp_sourcing_products');
		if($roleid == 2){
		$this->db->where('u_id',$uid);
		}		
      	$query = $this->db->get();		
      	$result = $query->result_array();      
      	return $result[0]['allcount'];
    }
 	
	public function Count_All_Record($uid,$product_title) {
    	$this->db->select('count(*) as allcount');		
      	$this->db->from('importapp_sourcing_products');
		$this->db->where('u_id',$uid);	
        $this->db->like('product_title', $product_title);	
      	$query = $this->db->get();		
      	$result = $query->result_array(); 
      	return $result[0]['allcount'];
    } 
	
	public function Count_All_Record_by_status($uid,$product_status) {
    	$this->db->select('count(*) as allcount');		
      	$this->db->from('importapp_sourcing_products');
		$this->db->where('u_id',$uid);	
		if($product_status==' '){      
		}else{
			  $this->db->like('status', $product_status);	
		}
      	$query = $this->db->get();		
      	$result = $query->result_array(); 
      	return $result[0]['allcount'];
    }
	
	public function get_All_Record($record,$recordPerPage,$uid,$product_title) {
		$this->db->select('*');
		$this->db->from('importapp_sourcing_products');
		$this->db->where('u_id',$uid);
        $this->db->like('product_title', $product_title);		
		$this->db->limit($recordPerPage,$record); 
        $this->db->order_by("id", "DESC");			
		$query = $this->db->get();    	
		return $query->result_array();
	}
	
	public function get_All_Record_status($record,$recordPerPage,$uid,$product_status) {
		$this->db->select('*');
		$this->db->from('importapp_sourcing_products');
		$this->db->where('u_id',$uid);
        if($product_status==' '){      
		}else{
			  $this->db->like('status', $product_status);	
		}	
		$this->db->limit($recordPerPage,$record); 	
        $this->db->order_by("id", "DESC");		
		$query = $this->db->get();    	
		return $query->result_array();
	}
	
	public function get_sourcingRecord($record,$recordPerPage,$uid) {

        $roleid = $this->session->userdata('login')->role_id;
		$this->db->select('*');
		$this->db->from('importapp_sourcing_products');
		if($roleid == 2){
		$this->db->where('u_id',$uid);
		}	
		$this->db->limit($recordPerPage,$record); 
        $this->db->order_by("id", "DESC");		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getAllRecordCount($product_name) {
	 	$this->db->select('count(*) as allcount');		
      	$this->db->from('importapp_products');
		$this->db->join('importapp_images', 'importapp_images.product_id = importapp_products.id'); 
		$this->db->like('importapp_products.product_title', $product_name);
      	$query = $this->db->get();		
      	$result = $query->result_array();      
      	return $result[0]['allcount'];
	}
	
	public function getStoreCount($user_id){
		$this->db->select("*");
		$this->db->from("importapp_shop");
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_record(){
		$this->db->select("*");
		$this->db->from("importapp_sourcing_products");	
        $this->db->order_by("id", "DESC");		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function add_sourcing_product($data){
		$this->db->insert('importapp_sourcing_products', $data); 
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}
	
	public function add_sourcing_store_product($data){
		$this->db->insert('importapp_sourcing_products', $data); 
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}
	
	public function delete_product($catId){				
		$this->db->where('id', $catId);
		$this->db->delete('importapp_sourcing_products');
		if($this->db->affected_rows() > 0)
		{
		   return true;
		}else{
           return false;
		}		
	}
	
	public function get_product_details($id){				
		$this->db->select("*");
		$this->db->from("importapp_sourcing_products");	
        $this->db->where('id',$id);		
		$query = $this->db->get();
		return $query->result_array();	
	}
	
	public function change_status($product_id){
        $this->db->set('status',1);	
		$this->db->where('id', $product_id);
		$this->db->update('importapp_sourcing_products');		
	}
	
    public function decline_status($product_id){
        $this->db->set('status',2);	
		$this->db->where('id', $product_id);
		$this->db->update('importapp_sourcing_products');		
	}
	
	public function get_pro_acc_url($sourcing_url){
        $this->db->select("*");
		$this->db->from("importapp_sourcing_products");	
        $this->db->where('sourcing_url',$sourcing_url);		
        $this->db->where('admin_status',1);	
		$this->db->limit(1);		
		$query = $this->db->get();
		return $query->row();		
	}
	
	public function insert_source_approved($sourced_data){
		$this->db->insert('importapp_sourced_approved_pro',$sourced_data);
	}
	
	public function get_src_pro_id($pro_id){
		$this->db->select("product_id");
		$this->db->from("importapp_sourced_approved_pro");	
        $this->db->where('sourcing_id',$pro_id);			
		$query = $this->db->get();
		return $query->row();
	}
}