<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class List_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}

	// Add products to mylist
	public function add_list($data){
	$data['created_at'] = $this->date;
	$inserted = $this->db->insert(MYLIST, $data);	$insert_id = $this->db->insert_id();	return  $insert_id;
	}

	// delete product from list
	public function remove_added_product($product_id, $user_id){
		$this->db->where('user_id', $user_id);
		$this->db->where('product_id', $product_id);
		$this->db->delete(MYLIST);       
	}
	
	public function remove_added_mylist_product($product_id, $user_id){
		$this->db->where('user_id', $user_id);
		$this->db->where('product_id', $product_id);
		$this->db->delete('importapp_mylist_pricing_variants');       
	}
	public function remove_main_mylist_product($product_id, $user_id){
		$this->db->where('user_id', $user_id);
		$this->db->where('product_id', $product_id);
		$this->db->delete('importapp_save_Mylist_data');       
	}
	
	
	
	public function remove_all_added_product($productId, $user_id){		
		if (!empty($productId)) {
			foreach($productId as $pid){			
					$this->db->where('user_id', $user_id);
					$this->db->where_in('product_id', $pid);
					$this->db->delete(MYLIST); 
			}	
		}
	}
	
	
	 //Get added products
	public function get_list($userId){
		$this->db->select('product_id');
		$this->db->from(MYLIST);
		$this->db->where('user_id', $userId);
		$query = $this->db->get();
		
		return $query->result_array();
	}
	// Get product details to show on mylist page
	public function get_added_product_detail($userId){
		
		$this->db->select("*");
		$this->db->from(MYLIST);
		$this->db->join(PRODUCTS, 'importapp_products.id = importapp_mylist.product_id', 'left');
		$this->db->join(IMAGES, 'importapp_images.product_id = importapp_mylist.product_id', 'left');
		$this->db->where('user_id', $userId);
		$this->db->where('importapp_images.variant_id', ''); 
		$this->db->group_by('importapp_products.id');
		///$this->db->limit($limit,$start);
		$this->db->order_by('importapp_mylist.id', 'DESC');
		$query = $this->db->get();
		return $query->result();

	}
	
	
	// get count of mylist products
	public function record_count($userId){		
		$this->db->where('user_id',$userId);
		$this->db->from(MYLIST);	
	    return $this->db->count_all_results();
	 
	}

	//Get All Product Variants
	public function product_variants(){
	
		$this->db->select('*, importapp_variants.id AS product_variant_ids' );
		$this->db->from(VARIANTS);
		$this->db->join(IMAGES, 'importapp_images.variant_id = importapp_variants.id', 'left');
		$query = $this->db->get();
		return $query->result_array();
		
	}

	// Get all product images
	public function product_images(){
		$this->db->select('*');
		$this->db->from(IMAGES);
		//$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function get_product_variants($productId){
	
		$this->db->select('*, importapp_variants.id AS product_variant_ids' );
		$this->db->from(VARIANTS);
		$this->db->join(IMAGES, 'importapp_images.variant_id = importapp_variants.id', 'left');
		$this->db->where('variants.product_id', $productId);
		$query = $this->db->get();
		return $query->result_array();
		
	}
	public function get_mylist_product_variants($productId,$userid){
	
		$this->db->select('*');
		$this->db->from('importapp_mylist_pricing_variants');		
		$this->db->where('product_id', $productId);
		$this->db->where('user_id', $userid);
		$query = $this->db->get();
		return $query->result_array();
		
	}
	
	public function getVariantOptions($product_id){
		$this->db->select('*');
		$this->db->from('variant_options');
		$this->db->where('pid=',$product_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
 // Get all product images by id
	public function product_images_byId($product_id){
		$this->db->select('*');
		$this->db->from(IMAGES);
		$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function product_images_byIds($product_id){
		$this->db->select('*');
		$this->db->from('importapp_save_Mylist_data');
		$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	
	
  /************************** Store LInked Products ****************/
    public function getstore_linked_product($shop_name,$created_by_ID){		
		$this->db->select('*');
		$this->db->from('importapp_store_products'); 
		$this->db->join('importapp_products', 'importapp_products.id=importapp_store_products.product_id', 'left');
		$this->db->join('importapp_images', 'importapp_images.product_id=importapp_products.id', 'left');
		$this->db->where('importapp_store_products.shop_name',$shop_name);
		$this->db->where('importapp_store_products.created_by',$created_by_ID);
		$this->db->order_by('importapp_store_products.id','asc');          
		$this->db->group_by('importapp_images.product_id');          
		$query = $this->db->get(); 
	    $query->result_array();
		return $query->result_array();	 
	}
	
	public function delete_linked_product($product_id){
		$this->db->where('shopify_product_id', $product_id);
		$this->db->delete('importapp_store_products'); 
	}
	
	public function getmyList_data($product_id,$userid){
		$this->db->select('*');
		$this->db->from('importapp_save_Mylist_data'); 
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $userid);
		$query = $this->db->get(); 
	    $query->result_array();
		return $query->result_array();; 
	}
	
  /***************************** End Here **************************/
}