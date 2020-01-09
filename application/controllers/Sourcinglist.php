<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sourcinglist extends MY_Controller {	

    public function __construct(){ 
		parent::__construct();
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
	}		

    public function index(){		
		$this->data['bodyClass'] = 'sourcing_page';
		$this->data["active"] = "Sourcing"; 
		$data =array();
		$this->data["results"] = $this->Sourcing_model->get_record();
		$this->load->view('headerView', $this->data);
		$this->load->view('sourcing/sourcinglistView', $this->data);
		$this->load->view('footerView', $this->data);	
	}
   
    public function delete_products(){						
		if($this->input->post()){
			$catId = $this->input->post('catId'); 
			$result = $this->Sourcing_model->delete_product($catId);
			if($result==true){
				$this->session->set_flashdata('delete_cat','Product has been deleted successfully');
			}
		}		
	}
	   
    public function product_detail_page($id=0){							
		$this->data['bodyClass'] = 'sourcing_detail_page';
		$this->data["active"] = "Sourcing detail page"; 
		$data =array();
		$this->data["results"] = $this->Sourcing_model->get_product_details($id);
		$this->load->view('headerView', $this->data);
		$this->load->view('sourcing/detail_page_View', $this->data);
		$this->load->view('footerView', $this->data);	
	   }

	   public function loadData($record=0) {		
		$uid = $this->session->userdata('login')->id;	
		$recordPerPage = 12;
		if($record != 0){
			$record = ($record-1) * $recordPerPage;
		}      	

    	$recordCount = $this->Sourcing_model->getRecordCount($uid);
		$empRecord = $this->Sourcing_model->get_sourcingRecord($record,$recordPerPage,$uid);
	    $config['base_url'] = base_url().'/sourcing';
      	$config['use_page_numbers'] = TRUE;
		$config['next_link'] = "<span class='plinks'>Next</span>";
		$config['prev_link'] = "<span class='plinks'>Previous</span>";
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
		$uid = $this->session->userdata('login')->id;	
		$session_data = $this->session->userdata('login');
		$product_title = $_POST['product_name'];
		$productFields ="title";
        
		$recordCount = $this->Sourcing_model->Count_All_Record($uid,$product_title);
		$empRecord = $this->Sourcing_model->get_All_Record($record,$recordPerPage,$uid,$product_title);
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
	public function approve_product_status(){
		$product_id = $_POST['product_id'][0];
		$recordCount = $this->Sourcing_model->change_status($product_id);
		$this->session->set_flashdata('status-successmsg','Product sourcing request has been approved successfully');
	    echo "1";
	}
	public function decline_product_status(){
		$product_id = $_POST['product_id'][0];
		$recordCount = $this->Sourcing_model->decline_status($product_id);
		$this->session->set_flashdata('status-successmsg','Product sourcing request has been declined successfully');
	  	echo "21";
	}
	public function get_product_bystatus($record=0){
		$product_status="";		 
			$recordPerPage = 12;
		if($record != 0){
			$record = ($record-1) * $recordPerPage;
		}      	
		$uid = $this->session->userdata('login')->id;	
		$session_data = $this->session->userdata('login');
		$product_status = $_POST['product_status'][0];
		$productFields ="title";	
		$recordCount = $this->Sourcing_model->Count_All_Record_by_status($uid,$product_status);
		$empRecord = $this->Sourcing_model->get_All_Record_status($record,$recordPerPage,$uid,$product_status);
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
	
} 