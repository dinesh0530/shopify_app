<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	Class Shop extends CI_Model
	{
		public $created	= "";

		public function __construct()
		{
			parent::__construct();
			$this->created	= date("Y-m-d H:i:s");
		}

		public function saveShopDetail($data)
		{
			$installedAt = $this->created;
			$data['created_at'] = $installedAt;
			$count = $this->isShopExist($data['shop_name']);
			if($count > 0){
				$this->db->where("shop_name", $data['shop_name']);
				$this->db->update('shop', $data);
				$result = $this->get_shop_details($data['shop_name']);
				return $result;
			} else {
				$this->db->insert('shop', $data);
				$id = $this->db->insert_id();
				return $id; 
			}
		}

		public function isShopExist($shop){
				return $this->db->where("shop_name", $shop)->get('shop')->num_rows();
		}

		
		public function get_shop($shop){			
			$this->db->where("shop_name", $shop);
			$query = $this->db->get('shop'); 
			return $query->result();			
		}
		public function get_shopByName($shop){			
			$this->db->where("shop_name", $shop);
			$query = $this->db->get('shop'); 
			return $query->row_array();			
		}
		public function get_shop_details($shop){			
			$this->db->where("shop_name", $shop);
			$query = $this->db->get('shop')->row(); 
			return $query;		
		}
		public function get_shop_user($shop){			
			$this->db->where("shop_name", $shop);
			$query = $this->db->get('shop')->result_array(); 
			return $query[0]['user_id'];		
		}
		public function get_shop_id($shopname){
				$this->db->where("shop_name", $shopname);
			$query = $this->db->get('shop');
			return $query->result();	
		}
		public function create_user($user)  	
		{   
			$isUser =  $this->db->where("email", $user['email'])->get(USER)->num_rows();
			if($isUser > 0){
				$this->db->select('id');
				$this->db->where("email", $user['email']);
				$query = $this->db->get(USER); 
				$id =  $query->result_array();
				return $id[0]['id'];
				
			} else {
				
				$this->db->insert(USER, $user);
				$id = $this->db->insert_id();
				return $id;	
			}
					
		}
		public function login_user($email)
		{		
			$this->db->where('email', $email);  
			$query = $this->db->get(USER);
			return $query->row();  
		}
		public function shop_count($userId){
			$this->db->where('user_id', $userId);
			$query = $this->db->get('importapp_shop');
			return $query->num_rows();
		}
		 public function shop_payment_status($shop){
			$this->db->select('payment_status_shop');
			$this->db->where('shop_name', $shop);
			$query = $this->db->get('importapp_shop');
			return $query->result_array();
		} 
		public function get_user($id)
		{		
			$this->db->where('id', $id);  
			$query = $this->db->get(USER);
			return $query->row();
		}
		public function saveUserId ($shopName,$userId){
				$data['user_id'] = $userId;
				$this->db->where("shop_name", $shopName);
				$this->db->update('shop', $data);
		}
		public function saveUninstallHook ($id=0, $data){
			//print_r($data); die;
				$this->db->where('id', $id); 
				$this->db->update('shop', $data);
		}
		public function getProduct($product_id){
			$this->db->select('*');
			$this->db->from(PRODUCTS);
			$this->db->where('id', $product_id);
			$query = $this->db->get()->row();
			
			return $query;
		}
		public function getVendor($vendor_id){
			$this->db->select('*');
			$this->db->from(PRODUCTS);
			$this->db->where('id', $vendor_id);
			$query = $this->db->get()->row();
			return $query;
		}
		public function getProductImages($product_id){
			$this->db->select('*');
			$this->db->from(IMAGES);
			$this->db->where('product_id', $product_id);
			$this->db->where('variant_id=','');
			$query = $this->db->get()->result_array();
			return $query;
		}
		
		public function getProductallImages($product_id){
			$this->db->select('*');
			$this->db->from(IMAGES);
			$this->db->where('product_id', $product_id);
			$query = $this->db->get()->result_array();
			return $query;
		}
		
		public function get_all_shops($user_id){	
			$this->db->where("user_id", $user_id);
			$this->db->where("payment_status_shop", 1);
			$this->db->where("uninstall_webhook", 1);
			$query = $this->db->get(SHOP); 
			return $query->result_array();			
		}
		
		public function get_all_stores(){
		$this->db->select('*');
		$this->db->where('uninstall_webhook',1);
		$this->db->from(SHOP);
		$this->db->order_by('shop_name','ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
		
		public function get_storeByShopName($shop){
		$this->db->select('*');
		$this->db->from(SHOP);
		$this->db->where('shop_name', $shop);
		$query = $this->db->get();
		return $query->result_array();
	}
		public function getProductVariants($product_id){
			$this->db->select('*');
			$this->db->from(VARIANTS);
			$this->db->join(IMAGES, 'importapp_variants.product_id = importapp_images.product_id');
			$this->db->where('importapp_variants.product_id', $product_id);
			$this->db->where('importapp_images.variant_id !=', "");
			$query = $this->db->get()->result_array();
			return $query;
		}
       /********** save shop products ******/
	   
	    public function insert_shop_products($shop_products)
		{		
			$this->db->insert('importapp_store_products', $shop_products);
			$id = $this->db->insert_id();
			return $id; 			
		}
		
		public function insert_variants_image_mata($var_image_mata)
		{		
			$this->db->insert('importapp_all_variants_images', $var_image_mata);
			//$id = $this->db->insert_id();
			//return $id; 			
		}
		
		
		public function getShopIdByName($shopName){
			$result_arr = $this->db->select('id')->from('shop')
											 	->where('shop_name',$shopName)
											 	->get()->result_array();

			return $result_arr[0]['id'];
		}
		
		
		function fetch_shop_products($id)
		{
			$this->db->select('wholesale_price');
			$this->db->from('importapp_store_products');
			$this->db->where('shopify_product_id', $id );
			$query = $this->db->get();
			if ( $query->num_rows() > 0 )
			{
			  $row = $query->row_array();
			  return $row;
			}
		}
       public function 	save_Mylist_details($data,$product_id){
	   $this->db->where('product_id',$product_id);
       $pid = $this->db->get('importapp_save_Mylist_data');
		   if ( $pid->num_rows() > 0 ) 
		   {
			  $this->db->where('product_id',$product_id);
			  $this->db->update('importapp_save_Mylist_data',$data);
			  $afftectedRows = $this->db->affected_rows();
			  return $afftectedRows;
		   } else {
			  $this->db->insert('importapp_save_Mylist_data',$data);
			  $id = $this->db->insert_id();
			  return $id;
		   }
	   }	
	   
	  public function 	save_Mylist_description($data,$product_id){
	   $this->db->where('product_id',$product_id);
       $pid = $this->db->get('importapp_save_Mylist_data');
		   if ( $pid->num_rows() > 0 ) 
		   {
			  $this->db->where('product_id',$product_id);
			  $this->db->update('importapp_save_Mylist_data',$data);
		   } else {
			  $this->db->insert('importapp_save_Mylist_data',$data);
		   }
	   }	
	   
     public function save_Mylist_verient($data,$variant_id){

	   $this->db->where('variant_id',$variant_id);
       $pid = $this->db->get('importapp_mylist_pricing_variants');
		   if ( $pid->num_rows() > 0 ) 
		   {
			  $this->db->where('variant_id',$variant_id);
			  $this->db->update('importapp_mylist_pricing_variants',$data);
		   } else {
			  $this->db->insert('importapp_mylist_pricing_variants',$data);
		   }
	   }	
	   public function payment_status($userId){
		   $this->db->select('payment_status');
		   $this->db->from('importapp_users');
		   $this->db->where('id', $userId);
		   $query = $this->db->get();
		   return $query->result_array();
	   }
	   public function check_payment_plan($userId){
			$this->db->select('*');
			$this->db->from('importapp_payments');
			$this->db->order_by("id", "desc");
			$this->db->limit(1);
			$this->db->where('user_id', $userId);
			$query = $this->db->get();
			return $query->result_array();
	   }
	   public function test($userId){
		   $data = array(
			'payment_status' => 1
		   );
		   $this->db->where('id', $userId);
		   $this->db->update('importapp_users', $data);
	   }

	   /************** End Here ********/
	}
?>