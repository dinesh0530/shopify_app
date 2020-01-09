<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Mylist extends MY_Controller {	
  public function __construct(){ 
		parent::__construct();
		$userid = $this->session->userdata('login')->id;
		if($userid == 0){
			redirect('login');
		}
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
		$this->date	= date("Y-m-d H:i:s");
	}	

  // Add to My list Product
	public function AddToList(){	
	$productId =  $this->input->post('product_id');	
	$productId = $productId[0];		
	$userid = $this->session->userdata('login')->id;
		$this->load->view('headerView', $this->data);
		$data = array(
						'user_id' => $userid,
						'product_id' => $productId
				);
		$inserted = $this->List_model->add_list($data);		
		
	}
	// Remove Product form Mylist 
	public function remove_product(){
		$userid = $this->session->userdata('login')->id;
		$productId =  $this->input->post('product_id');	
		$productId = $productId[0];
		$this->List_model->remove_added_product($productId, $userid);
	}
	
	public function remove_allproducts(){
		$userid = $this->session->userdata('login')->id;
		$productId =  $this->input->post('product_id');
		//$productIds = implode(",",$productId);
		$this->List_model->remove_all_added_product($productId, $userid);
		}
	
	
	
	// Get Mylist products
	public function get_list_detail(){
		if (array_key_exists("shop",$this->session->userdata('login')))
		{
			
			$store_name = $this->session->userdata('login');
			$userid = $this->session->userdata('login')->id;
			$this->data['categories'] = $this->Product_model->get_categories();
			$this->data["active"] = "mylist";
			$products_array = $this->List_model->get_added_product_detail($userid);
			$json  = json_encode($products_array);
			$params['product_details'] = json_decode($json, true);
			$total_records = $this->List_model->record_count($userid);
			foreach($params['product_details'] as $key=>$product){			
			$variantOptions = $this->List_model->getVariantOptions($product['product_id']);		
			$params['product_details'][$key]['variantOptions'] = $variantOptions;
			$myList_data = $this->List_model->getmyList_data($product['product_id'],$userid);
			$params['product_details'][$key]['myList_data'] = $myList_data;
			$product_variants = $this->List_model->get_product_variants($product['product_id']);
			$product_mylist_variants = $this->List_model->get_mylist_product_variants($product['product_id'],$userid);
			
			if(count($product_variants)>0){
			    $params['product_details'][$key]['product_variants'] = $product_variants;				
			    $params['product_details'][$key]['product_mylist_variants'] = $product_mylist_variants;				
			}
			else{
				$params['product_details'][$key]['product_variants'] = array();
				$params['product_details'][$key]['product_mylist_variants'] = array(); 
			}
			$product_images = $this->List_model->product_images_byId($product['product_id']);
			$product_images_ids = $this->List_model->product_images_byIds($product['product_id']);
		
			if(count($product_images)>0){
				
				$params['product_details'][$key]['product_images'] = $product_images;
				$params['product_details'][$key]['product_images_ids'] = $product_images_ids;
				
			} else {
				$params['product_details'][$key]['product_images'] = array();
				$params['product_details'][$key]['product_images_ids'] = array();
			}
			
 		}
	   /************** Get the store linked products ****************/
	    $shop_name = $this->session->userdata('login')->shop;
	    $created_by_ID = $this->session->userdata('login')->id;	
		
		
		/********* Linked products code ********************/
		
		$session_data = $this->session->userdata('login');
		$shop_details =  $this->Shop->get_shop_details($session_data->shop);		
		$accessToken = $shop_details->access_token;				
		$this->load->library( "shopifyclient" , array( "shop"  => $session_data->shop , "token" => $accessToken , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
		$params['linked_products'] = $this->shopifyclient->call('GET ', "/admin/products.json");
		
		
	    //$params['linked_products'] = $this->List_model->getstore_linked_product($shop_name,$created_by_ID);
	  
	   /************************** End Here *************************/
	
		$params['categories'] = $this->Categories_model->getCategory();
		$params['suppliers'] = $this->Suppliers_model->fetch_suppliers();	
		$params["variants"] = $this->List_model->product_variants();	
		$params["images"] = $this->List_model->product_images();
		$this->load->view('headerView', $this->data);
		$this->load->view('user/myListView', $params);
		$this->load->view('footerView', $this->data); 
		}
		else{
		$this->load->view('headerView', $this->data);
		$this->load->view('user/connect-with-store');
		$this->load->view('footerView', $this->data); 
	}
		
		
	}
	public function save_Mylist_data(){
		if($this->input->method(TRUE)=="POST")
		{ 	
	       
	          $product_data = $_POST['product_data'][0];
			  $userid = $this->session->userdata('login')->id;
			  
			  
			 $data = array(
					"product_id" => $product_data['id'],
					"user_id" => $userid,			 
					"product_title" => $product_data['title'],			
					"product_type" => $product_data['type'],			
					"collections" => $product_data['collection'],
					"description" => $product_data['description'],
					"product_category" => $product_data['category'],
					"tags" => $product_data['tags']
			); 		
	
			$res = $this->Shop->save_Mylist_details($data,$product_data['id']);	
			if(!empty($product_data['variants'])){
				foreach($product_data['variants'] as $variants){			
					
					$data = array(
							"product_id" => $product_data['id'],
							"user_id" => $userid,
							"variant_id" => $variants['variant_id'],		
							"variant_your_price" => $variants['variant_price'],
							"compared_at_price" => $variants['variant_comparePrice'],
						); 
                    $this->Shop->save_Mylist_verient($data,$variants['variant_id']);				
				}
			}
			if(!empty($product_data['images'])){
				foreach($product_data['images'] as $k => $images){			
					
					$images_data[] = $images[0];                  
				}
				$images_data = implode(",",$images_data);
				$img_data = array("image_ids" => $images_data);
				 $this->Shop->save_Mylist_details($img_data,$product_data['id']);
			}	
			if(!empty($res)){ echo $res;die; }
		}
		
	}
}