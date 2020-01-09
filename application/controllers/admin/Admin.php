<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$userid = $this->session->userdata('login')->id;
		if($userid == 0)
		{
				redirect('/login');
        }
	} 
	public function index()
	{   
		$this->data["active"] = "dashboard";	
		$id = $this->session->userdata('login')->id;
		$users = $this->session->userdata('login');
		$user_id = $users->id;
		$recordfound = 0;
		$salefound = 0;
		$sales = array();
		if(isset($_GET['shop_name']) && !empty($_GET['shop_name'])){	
			$this->data['stores']= $this->Admin_model->get_storeByShopName($_GET['shop_name']);
		}else{
			$this->data['stores']= $this->Admin_model->get_all_stores(); 
		}
		
		$this->data['shops'] = $this->Admin_model->get_all_stores();
		$start_date  = isset($_GET['startDate'])&&!empty($_GET['startDate'])?$_GET['startDate']:date("Y-m-d", strtotime("-7 days"));
		
		$end_date  = isset($_GET['endDate'])&&!empty($_GET['endDate'])? date('Y-m-d', strtotime($_GET['endDate'].'+1 day')) :date("Y-m-d", strtotime("+1 days"));
		$end_date = $end_date."T00:00:00-05:00";
		if(!empty($this->data['stores'])){
			foreach ($this->data['stores'] as $shop) { 
				$storeName[] = $shop->shop_name;
				$this->load->library( "shopifyclient" , array( "shop"  => "$shop->shop_name" , "token" => "$shop->access_token"  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
			}
			$currentStore = $storeName[0];
			$orders = $this->shopifyclient->call('GET', "/admin/orders.json?status=any&created_at_min=$start_date&created_at_max=$end_date");
			
			$ac = 0;
			foreach($orders as $order){
				$aj = 0;
				$line_items = $order['line_items'];
				foreach($line_items as $orderPro){
					$pid = $orderPro['product_id'];	
					$pro_id = $this->Order_model->getstoreprodcuts($currentStore, $pid);
							
					$ipid = $pro_id[0]['product_id'];
					
					$images = $this->Order_model->getprodcutimages($ipid);
					$imgSRC = $images[0]['src'];
					$img_url = base_url()."uploads/product$ipid/$imgSRC";
					if(!empty($pro_id)){
						$recordfound = 1;
						array_push($orders[$ac], array("show" => "show"));
					}
					$values = array("img_url" => $img_url, 
									"shopify_product_id" => $pro_id[0]['shopify_product_id']
									);
					array_push($orders[$ac]['line_items'][$aj], $values );
					$aj++;
				}
				$ac++;
			}
			$this->data['orders'] = $orders;
			$this->data['recordfound'] = $recordfound;
			$this->data['currentStore'] = $currentStore;
		
			$total_months  = date('m');
			
			for($i=1;$i <= $total_months; $i++){
				$query_date = date("Y-$i-$i");
				$created_at_min  = date('Y-m-01', strtotime($query_date));
				$created_at_max   = date('Y-m-t', strtotime($query_date));
				$sale  = $this->shopifyclient->call("GET", "/admin/orders.json?status=any&created_at_min=$created_at_min&created_at_max=$created_at_max");
				$sc = 0;
				foreach($sale as $order){
					$aj = 0;
					$line_items = $order['line_items'];
					foreach($line_items as $orderPro){
						$pid = $orderPro['product_id'];
						$pro_id = $this->Order_model->getstoreprodcuts($currentStore, $pid);
						if(!empty($pro_id)){
							$salefound = 1;
							array_push($sale[$sc], array("show" => "show"));
						} 
						$values = array("shopify_product_id" => $pro_id[0]['shopify_product_id']
										);
						array_push($sale[$sc]['line_items'][$aj], $values );
						$aj++;
					}
					$sc ++;
				}
			    $sales[] = $sale;
			}
		}
		
		$this->data['sales'] = $sales;
		$this->data['salefound'] = $salefound;
		$this->data["active"] = "dashboard"; 
		$this->load->view('headerView', $this->data);
		$this->load->view('admin/adminView', $this->data); 
		$this->load->view('footerView', $this->data);
	}

	public function getUsers()
	{   	
		$params = array();
		$limit = 6;
		$page = $this->uri->segment(2);
		$this->session->userdata('login')->role_id;  
		if($this->input->post('keyword')){
			$keyword=$this->input->post('keyword');
			$total_records = $this->Admin_model->name_count($keyword);
		}
		else{
		    $total_records = $this->Admin_model->user_record_count();	
		}
		if(empty($page)){
		  $page = 0;
		} else {			
		  $page = $page-1;
		}	
		
		if($filter = $this->input->get()){		
			$filter = $this->input->get('filter', TRUE);
			$filter_value = $this->input->get('value', TRUE);		
			$this->data['users'] = $this->Admin_model->users_sorting($filter,$filter_value,$limit, $page*$limit);	

			$couter =0;
			foreach($this->data['users']  as $user){		
			$this->data['users'][$couter]->stores = $this->Admin_model->store_users($user->id); $couter++;
		  }	
		}else{				
			$this->data['users'] = $this->Admin_model->fetch_users($limit, $page*$limit);			 
			$couter =0;
			foreach($this->data['users']  as $user){		
				$this->data['users'][$couter]->stores = $this->Admin_model->store_users($user->id); $couter++;
			}			
		}
		
		if($this->input->post('keyword')){
			$this->data['users'] = $this->Admin_model->user_data($limit, $page*$limit, $keyword);
			$couter =0;
				foreach($this->data['users']  as $user){		
					$this->data['users'][$couter]->stores = $this->Admin_model->store_users($user->id); $couter++;
				}
     	}			
            $config['base_url'] = base_url() . 'all-users';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit;
            $config["uri_segment"] = 2;           
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;             
            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';	
            $config['first_link'] = 'First Page';
            $config['first_tag_open'] = '<span class="firstlink">';
            $config['first_tag_close'] = '</span>';             
            $config['last_link'] = 'Last Page';
            $config['last_tag_open'] = '<span class="lastlink">';
            $config['last_tag_close'] = '</span>';             
            $config['next_link'] = 'Next Page';
            $config['next_tag_open'] = '<span class="nextlink">';
            $config['next_tag_close'] = '</span>'; 
            $config['prev_link'] = 'Prev Page';
            $config['prev_tag_open'] = '<span class="prevlink">';
            $config['prev_tag_close'] = '</span>'; 
            $config['cur_tag_open'] = '<span class="curlink">';
            $config['cur_tag_close'] = '</span>'; 
            $config['num_tag_open'] = '<span class="numlink">';
            $config['num_tag_close'] = '</span>';             
            $this->pagination->initialize($config);               
            $this->data["links"] = $this->pagination->create_links();  	
			
			$this->data["active"] = "all-users"; 	
			$this->load->view('headerView', $this->data); 
			$this->data['categories'] = $this->Categories_model->getCategory();	
			$this->load->view('admin/userGridView', $this->data); 
			$this->load->view('footerView', $this->data);
	}
	
	
	public function user_profile($id)
	{
		$id = $this->uri->segment(3); echo $id;
	}
	
	public function delete_user($id=0)
	{
    	$result = $this->Admin_model->delete_user($id);	
		if($result==true){
			$this->session->set_flashdata('delete_user','User has been deleted successfully');
		}
		
	}
	
	/*
	@ Vendor User code
	@ function name 
	*/
	public function vendorUsers()
	{   
	   
		$params = array();
		$limit = 6;
		$page = $this->uri->segment(2);
		$this->session->userdata('login')->role_id;  
		if($this->input->post('keyword')){
			$keyword=$this->input->post('keyword');
			$total_records = $this->Admin_model->vendor_name_count($keyword);
		}
		else{
		$total_records = $this->Admin_model->vendor_user_record_count();	
		}
		if(empty($page)){
		  $page = 0;
		} else {			
		  $page = $page-1;
		}	
		
		if($filter = $this->input->get()){	 	
			$filter = $this->input->get('filter', TRUE);
			$filter_value = $this->input->get('value', TRUE);
			$this->data['users'] = $this->Admin_model->vendor_users_sorting($filter,$filter_value,$limit, $page*$limit);	
			
			}else{
			$this->data['users'] = $this->Admin_model->fetch_vendor_users($limit, $page*$limit);	

				 
			$couter =0;
			foreach($this->data['users']  as $user){		
				$this->data['users'][$couter]->stores = $this->Admin_model->store_users($user->id); $couter++;
			}
		
			}
			if($this->input->post('keyword')){
				//print_r($keyword);  die;
				$this->data['users'] = $this->Admin_model->vendor_user_data($limit, $page*$limit, $keyword);
				
			}			
            $config['base_url'] = base_url() . 'all-vendors';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit;
            $config["uri_segment"] = 2;           
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;             
            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';	
            $config['first_link'] = 'First Page';
            $config['first_tag_open'] = '<span class="firstlink">';
            $config['first_tag_close'] = '</span>';             
            $config['last_link'] = 'Last Page';
            $config['last_tag_open'] = '<span class="lastlink">';
            $config['last_tag_close'] = '</span>';             
            $config['next_link'] = 'Next Page';
            $config['next_tag_open'] = '<span class="nextlink">';
            $config['next_tag_close'] = '</span>'; 
            $config['prev_link'] = 'Prev Page';
            $config['prev_tag_open'] = '<span class="prevlink">';
            $config['prev_tag_close'] = '</span>'; 
            $config['cur_tag_open'] = '<span class="curlink">';
            $config['cur_tag_close'] = '</span>'; 
            $config['num_tag_open'] = '<span class="numlink">';
            $config['num_tag_close'] = '</span>';             
            $this->pagination->initialize($config);               
            $this->data["links"] = $this->pagination->create_links();  				
			$this->data["active"] = "all-users"; 	
			$this->load->view('headerView', $this->data); 
			$this->data['categories'] = $this->Categories_model->getCategory();	
			$this->load->view('database/vendorsView', $this->data); 
			$this->load->view('footerView', $this->data);
	}
}