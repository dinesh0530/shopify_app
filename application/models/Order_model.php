<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* start of file Order_model.php */
Class Order_model extends MY_Model
{
	public function __construct()
    {
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}
    
	public function getstoreprodcuts($currentStore, $pid){
		$where = "shop_name='".$currentStore."' AND shopify_product_id='".$pid."'";
		$this->db->select('*');
		$this->db->from('store_products');
		$this->db->where($where);
		$query = $this->db->get();
		if ( $query->num_rows() > 0 ){			
			return $query->result_array();
		}	
	}
	
	public function getvendorsprodcuts($currentStore, $pid, $user_id){
		$where = "shop_name='".$currentStore."' AND shopify_product_id='".$pid."' AND vendor_id=$user_id";
		$this->db->select('*');
		$this->db->from('store_products');
		$this->db->where($where);
		$query = $this->db->get();
		if ( $query->num_rows() > 0 ){			
			return $query->result_array();
		}	
	}
	
	public function getstoreprodcut($currentStore){
		$where = "shop_name='".$currentStore."'";
		$this->db->select('*');
		$this->db->from('store_products');
		$this->db->where($where);
		$query = $this->db->get();
		if ( $query->num_rows() > 0 ){			
			return $query->result();
		}	
	}
	
	public function getprodcutimages($ipid){
		$where = "product_id=$ipid";
		$this->db->select('src');
		$this->db->from(IMAGES);
		$this->db->where($where);
		$query = $this->db->get();
		if ( $query->num_rows() > 0 ){			
			return $query->result_array();
		}
	}
	
	public function wholesaleprice($ipid){
		$where = "id=$ipid";
		$this->db->select('*');
		$this->db->from(PRODUCTS);
		$this->db->where($where);
		$query = $this->db->get();
		if ( $query->num_rows() > 0 ){			
			return $query->result_array();
		}
	}
	
	public function getproOwner($proOwner){
		$where = "id=$proOwner";
		$this->db->select('*');
		$this->db->from(USER);
		$this->db->where($where);
		$query = $this->db->get();
		if ( $query->num_rows() > 0 ){			
			return $query->result_array();
		}
	}
	
	public function insert_admin_order($paypal_response){   	   		
		$this->db->insert('importapp_admin_orders', $paypal_response);	
	}
	
	
	public function getpaidorderstoadmin($order_id, $currentStore){
		$where = "item_name=$order_id AND shopify_store = '$currentStore'";
		$this->db->select('*');
		$this->db->from('importapp_admin_orders');
		$this->db->where($where);
		$query = $this->db->get();			
		if ( $query->num_rows() > 0 ){			
			return $query->result_array();
		}
	}
	
	public function get_admin_orders($limit, $start, $order_no, $start_date, $end_date){		
        // $where = array();
		// $where[] = "importapp_admin_orders.delete_order = 0";
		// if( !empty($order_no)){
			// $where[] = "importapp_admin_orders.id = $order_no";
		// }
		// if(!empty($start_date)){
			// $where[] = "importapp_admin_orders.created_at >= '".$start_date."'";
		// }
		// if(!empty($start_date)){
			// $where[] = "importapp_admin_orders.created_at <= '".$end_date."'";
		// }
		// if( count($where) > 0) { 
			// $where = ( empty($where) )? " ": implode(" && ", $where);
        // }
	// echo $where; die();
		$this->db->select('importapp_admin_orders.id,importapp_admin_orders.amount,importapp_admin_orders.currency_code,importapp_admin_orders.pro_ids,importapp_admin_orders.transaction_id,importapp_admin_orders.status,importapp_admin_orders.created_at , importapp_users.firstname , importapp_users.lastname , importapp_users.email');
		$this->db->from('importapp_admin_orders');
		$this->db->join('importapp_users', 'importapp_users.id = importapp_admin_orders.customer_id');
        $this->db->where('importapp_admin_orders.delete_order' , 0);
		if( !empty($order_no)){
			$this->db->where('importapp_admin_orders.id' , $order_no);
		}
		if(!empty($start_date)){
			$this->db->where('importapp_admin_orders.created_at >=' , $start_date);
		}
		if(!empty($end_date)){
			$this->db->where('importapp_admin_orders.created_at <=' , $end_date);
		}
		$this->db->order_by("id", "desc");
		$this->db->limit($limit,$start);		
		$query = $this->db->get();			
		if ( $query->num_rows() > 0 ){			
			return $query->result_array();
		}
	}
	
	public function admin_orders_prodcuts($product_id){  
	    $where = "importapp_products.id = $product_id";
		$this->db->select("importapp_products.*, importapp_images.src");
		$this->db->from(PRODUCTS);
		$this->db->join(IMAGES, 'importapp_products.id = importapp_images.product_id', 'left');
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get(); 
		return $query->result();
	}
	
	public function get_admin_orders_counts($order_no, $start_date, $end_date){
		$where = array();
		$where[] = "importapp_admin_orders.delete_order = 0";
		if( !empty($order_no)){
			$where[] = "importapp_admin_orders.id = $order_no";
		}
		if(!empty($start_date)){
			$where[] = "importapp_admin_orders.created_at >= '$start_date'";
		}
		if(!empty($start_date)){
			$where[] = "importapp_admin_orders.created_at <= '$end_date'";
		}
		if( count($where) > 0) { 
			$where = ( empty($where) )? " ": implode(" && ", $where);
			$query = $this->db->query('SELECT * from importapp_admin_orders where '.$where);
        }else{
			$query = $this->db->query('SELECT * from importapp_admin_orders');
		}
		 return $query-> num_rows();
	}
}
?>