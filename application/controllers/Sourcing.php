<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sourcing extends MY_Controller {	

  public function __construct(){ 
		parent::__construct();
		$this->load->helper('url');
        $this->load->library('pagination');
		$this->load->model('Sourcing_model');
		
		$userid = $this->session->userdata('login')->id;
		if($userid == 0)
		{
			redirect('login');
		}
		$role= $this->session->userdata('login')->role_id;
		if($role == 2){
			$user_pay_status = $this->User_model->get_pay_status($userid); 
			if($user_pay_status[0]['subscription_plan'] == "Basic plan"){
				redirect('/');
			}
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
		
		if($role != 2){
			redirect('/');
		}
		/******** Shopify Strore details **********/
		
		if(isset($_GET['shop_name']) && !empty($_GET['shop_name'])){			
		$this->data['stores']= $this->Admin_model->get_storeByShopName($_GET['shop_name']);		
		} else {
			$this->data['stores']= $this->Admin_model->get_all_stores(); 
		}		
		
		$this->data['shops'] = $this->Admin_model->get_all_stores(); 		
		if(!empty($this->data['stores'])){			
			foreach ($this->data['stores'] as $shop) {					
			  $this->data['current_store'] =  $shop->shop_name;		
			  $this->load->library( "shopifyclient" , array( "shop"  => "$shop->shop_name" , "token" => "$shop->access_token"  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
			}
		}		 
		/***************** End Here *******************/
		
		$this->date	= date("Y-m-d H:i:s");
		
	}		
	
	public function index(){		
	    $user_id = $this->session->userdata('login')->id;	
		$this->data['bodyClass'] = 'sourcing_page';
		$this->data["active"] = "Sourcing"; 		
		$session_data = $this->session->userdata('login');
        $role_id = $this->session->userdata('login')->role_id;	
		$this->data["recordCount"] = $this->shopifyclient->call('GET','/admin/products/count.json');
		$this->data["store"] = $this->Sourcing_model->getStoreCount($user_id);	
		$this->load->view('headerView', $this->data);
		$this->load->view('sourcing/sourcingView', $this->data);
		$this->load->view('footerView', $this->data);
	}	
	
	public function loadData($record=0){		
		$user_id = $this->session->userdata('login')->id;	
		$recordPerPage = 12;
		$last = $this->uri->total_segments();
        $record = $this->uri->segment($last);
		$session_data = $this->session->userdata('login');
		$recordCount = $this->shopifyclient->call('GET','/admin/products/count.json');
		$empRecord = $this->shopifyclient->call("GET","/admin/products.json?limit=$recordPerPage&page=$record");
	    
		foreach($empRecord as $k => $Record ){
			$id= $Record['id'];
			$empRecord[$k]['wholesale'] =  $this->Shop->fetch_shop_products($id);
		}
	    $config['base_url'] = base_url().'/sourcing';
      	$config['use_page_numbers'] = TRUE;
		$config['next_link'] = "Next";
		$config['prev_link'] = "Previous";
		$config['total_rows'] = $recordCount;
		$config['per_page'] = $recordPerPage;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();		
		$data['empData'] = $empRecord;	 
		echo json_encode($data);		
	}	
	
	public function product_search($record=0){	
		$recordPerPage = 12;
		if($record != 0){
			$record = ($record-1) * $recordPerPage;
		}		
		$session_data = $this->session->userdata('login');
		$product_name = $_GET['product_name'];
		$productFields ="title";
		$empRecord = $this->shopifyclient->call("GET","/admin/products.json?title=$product_name&limit=$recordPerPage&page=$record");
        $recordCount =count($empRecord);
	    $config['base_url'] = base_url().'/sourcing';
      	$config['use_page_numbers'] = TRUE;
		$config['next_link'] = "<span class='plinks'>Next</span>";
		$config['prev_link'] = "<span class='plinks'>Previous</span>";
		$config['total_rows'] = $recordCount;
		$config['per_page'] = $recordPerPage;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['empData'] = $empRecord;
		$data['empcount'] = $recordCount;
		echo json_encode($data);		
	}
	
	public function upload_sourcing_image(){
		if($_FILES["sourcing_image"] != ''){					
			$file_name=array();	
			$files = $_FILES['sourcing_image'];				
			$folder = "./uploads/sourcing";
			$config['upload_path']   = $folder;
			$config['allowed_types'] = 'gif|jpg|jpeg|png|mp4|3gp|avi|flv|mkv|m4v|mov|mpeg|mpg';
			$config['max_size'] = 0;
			$errors = array();
			$filesCount = count($files['name']);				
			for($j = 0; $j < $filesCount; $j++){
				if($j == $filesCount-1){
					$this->upload->initialize($config);	
					settype($files['name'][$j], "string");
					$imgName = str_replace(' ', '-', $files['name'][$j]);				
					$_FILES['sourcing_image']['name'] = rand().$imgName;
					$_FILES['sourcing_image']['type'] = $files['type'][$j];
					$_FILES['sourcing_image']['tmp_name'] = $files['tmp_name'][$j];
					$_FILES['sourcing_image']['error'] = $files['error'][$j];
					$_FILES['sourcing_image']['size'] = $files['size'][$j];
					if($this->upload->do_upload('sourcing_image')){					
						$data_upload_files = $this->upload->data();								
						$uploadData[$j]['src'] = $data_upload_files['file_name'];					
						$file_name[] = $_FILES['sourcing_image']['name'];							
					}
					else{					
						$errors[] = $this->upload->display_errors("<span class='error'>".$_FILES['sourcing_image']['name']." ", "</span>");
					}
				}	
			}
			$file_name = implode (",",$file_name);
			echo $file_name;				
		}
	}
	
	public function create_sourcing_product(){
		$user_id = $this->session->userdata('login')->id;	
		$this->data['bodyClass'] = 'sourcing_page';
		$this->data["active"] = "Sourcing"; 		
		$session_data = $this->session->userdata('login'); 
		$this->data["recordCount"] = $this->shopifyclient->call('GET','/admin/products/count.json');
		$this->data["store"] = $this->Sourcing_model->getStoreCount($user_id);		
		if($this->input->method(TRUE)=="POST")
		{			
			$file_name = $this->input->post('imges');	
			$file_name = implode (",",$file_name);
			$data = array(		
				'u_id' => $this->session->userdata('login')->id,				
				'product_title' => $this->input->post('product_title'),
				'product_price' => $this->input->post('product_price'),
				'country' => $this->input->post('country'),
				'sourcing_url' => $this->input->post('sourcing_url'),
				'sourcing_image' =>  $file_name,
				'created_date' => date('Y-m-d H:i:s'),
				'note' => $this->input->post('description')	,				
				'store_url' => $this->input->post('store_url')					
			);
			
			$pro_url = $this->Sourcing_model->get_pro_acc_url($this->input->post('sourcing_url'));
			$prosrcid = $pro_url->id;
			if(!empty($pro_url)){
				$data['admin_status'] = 1;
				$result = $this->Sourcing_model->add_sourcing_product($data);				
				$src_pro_id = $this->Sourcing_model->get_src_pro_id($prosrcid);
				
				$sourced_data = array(
                    'sourcing_id' => $result,				
					'product_id' => $src_pro_id->product_id,
					'requested_by_id' => $user_id,					
					'created_at' => $this->date
				);
				$this->Sourcing_model->insert_source_approved($sourced_data);
			}else{			
				$result = $this->Sourcing_model->add_sourcing_product($data);
			}
			
			if($result){				
				$this->session->set_flashdata('success_msg','Product has been added successfully');
			}			
		}		
		$this->load->view('headerView', $this->data);
		$this->load->view('sourcing/sourcinglistView', $this->data);
		$this->load->view('footerView', $this->data);		
	}
	
	function insert_source_product(){
		$user_id = $this->session->userdata('login')->id;
        $data = array();		
		$product_ids = implode(',', $_GET['product_ids']);			
		$shop_name = $_GET['shop_name'];
		$empRecord = $this->shopifyclient->call("GET","/admin/products.json?ids=$product_ids");
		$all_product_images =array();
        foreach($empRecord as $result){	
           if(empty($result['image'][0]['src'])){
			   $sourcing_image_url = $result['images'][0]['src'];
		   }else{
			   $sourcing_image_url = $result['image'][0]['src'];
		   }		
			
			foreach($result['images'] as $imagemeta){
				$all_product_images[]=$imagemeta['src'];
			}
		    $all_product_images = implode(',', $all_product_images);
			$data = array(
				'u_id' =>  $this->session->userdata('login')->id,
				'product_title' => $result['title'],
				'product_handle' => $result['handle'],
				'product_price' => $result['variants'][0]['price'],
				'country' => '',
				'sourcing_url' => 'https://'.$shop_name.'/products/'.$result['handle'],
				'sourcing_image' => '',
				'sourcing_image_url' => $sourcing_image_url,
				'status' => 0,
				'created_date' => date('Y-m-d H:i:s'),
				'note' => $result['body_html'],
				'store_url' => $shop_name,
				'all_product_images' => $all_product_images
			);
			
			$pro_url = $this->Sourcing_model->get_pro_acc_url($data['sourcing_url']);			
			if(!empty($pro_url)){
				$prosrcid = $pro_url->id;
				$data['admin_status'] = 1;
				$result = $this->Sourcing_model->add_sourcing_store_product($data);				
				$src_pro_id = $this->Sourcing_model->get_src_pro_id($prosrcid);				
				$sourced_data = array(
                    'sourcing_id' => $result,				
					'product_id' => $src_pro_id->product_id,
					'requested_by_id' => $user_id,
					'created_at' => $this->date
				);
				$this->Sourcing_model->insert_source_approved($sourced_data);
			}else{			
				$result = $this->Sourcing_model->add_sourcing_store_product($data);
			}
			
			$source_insert['result'] = $result;	
		}       
		echo json_encode($source_insert);
		$this->session->set_flashdata('success_msg','Product has been added successfully.');
	}
}