<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliers extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct(); 
		$userid = $this->session->userdata('login')->id;
    if($userid == 0)
    {
			redirect('/login');
    }	
	}
	
/*Show Suppliers Listing*/ 
	public function index(){
		$role_id = $this->session->userdata('login')->role_id;  
		$user_id = $this->session->userdata('login')->id;	
		$this->data['bodyClass'] = 'supplier_page';
		$this->data["active"] = "all-suppliers";
		$this->load->view('headerView', $this->data);		
		$params = array();
        $limit = 10;
		
        $page = $this->uri->segment(2);
		if($role_id != 3)
		{
			$total_records = $this->Suppliers_model->record_count();
		}
			else
			{
				$total_records = $this->Suppliers_model->vandor_supplier($user_id);
			}		
		if($this->input->get('keyword'))
		{
			$keyword = $this->input->get('keyword');
			if($role_id != 3){
			$total_records = $this->Suppliers_model->name_count($keyword);
			}
			else
			{
				$total_records = $this->Suppliers_model->vandor_name_count($keyword, $user_id);
			}
		}	
			
		if(empty($page)){
			$page = 0;
			
		} else {
			
			$page = $page-1;
		}
         /* printR($page);
		 die(); */		
        if($total_records > 0){
			
            $params["supplier"] = $this->Suppliers_model->fetch_data($limit, $page*$limit, $user_id);
			if($this->input->get('keyword')){
				$params["supplier"] = $this->Suppliers_model->name_data($limit, $page*$limit, $keyword, $user_id);
			}                 
            $config['base_url'] = base_url() . 'all-suppliers';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit;
            $config["uri_segment"] = 2;             
             
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
             
            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';
			
			//$config['query_string_segment'] = 'offset';
			//$config['page_query_string'] = TRUE;
             
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
                             
            $params["links"] = $this->pagination->create_links();           
       }
		if(isset($_POST['Export'])){
			$role_id = $this->session->userdata('login')->role_id; 
			header('Content-Type: text/csv; charset=utf-8');  
			header('Content-Disposition: attachment; filename=Export_supplier.csv');  
			$output = fopen("php://output", "w");  
			fputcsv($output, array('Name', 'Email','Shop No.','Street','Country','State','City','Zip','phone','Whatsapp','Skypee','Website', 'Contact person','Category' ));  
			$result = $this->Suppliers_model->export_supplier($role_id,$user_id);	
			if(empty($result)){die;}
			else{
			foreach($result as $row)
			{		
			fputcsv($output, $row);
			}
			fclose($output);  
			die;	}
		}	  	
		if(isset($_FILES["file"]))
		{	
			$role_id = $this->session->userdata('login')->role_id;    
			$user_id = $this->session->userdata('login')->id;	
				$filename=$_FILES["file"]["tmp_name"];		
					
				 if($_FILES["file"]["size"] > 0)
				 {
					$file = fopen($filename, "r");
					$counter = 1;
					while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
					 {	 						
						if($counter >1)
						{
							$category = $this->Suppliers_model->get_categoryId($getData[13]);
							$state = $this->Suppliers_model->get_stateId($getData[5]);
							$country = $this->Suppliers_model->get_countryId($getData[4]);
							$email_chk = $this->Suppliers_model->mail_check($getData[1]);
							if($email_chk > 0)	
							{
								$this->session->set_flashdata('errorImport', "Some supplier(s) email already exists");
								continue;
							}
							else{
								$data = array(
									'added_by' =>$user_id,
									'name'=>$getData[0],
									'email'=>$getData[1],
									'address1'=>$getData[2],
									'address2'=>$getData[3],
									'country'=>$country[0]['id'],
									'state'=>$state[0]['id'],
									'city'=>$getData[6],
									'zip'=>$getData[7],
									'phone'=>$getData[8],
									'whatsapp'=>$getData[9],
									'skype'=>$getData[10],
									'website'=>$getData[11],
									'contact_person'=>$getData[12],
									'category'=>$category[0]['id']							
								);					
								$result = $this->Suppliers_model->import_supplier($data);
								$this->session->set_flashdata('successImport', "Supplier(s) successfully imported");
							}
						}
						$counter++;
					 }					
					 fclose($file);	
				 }
				 redirect('all-suppliers');
			 }  
		
        $this->load->view('supplier/allSuppliersView', $params);
		$this->load->view('footerView', $this->data);					
	}
	/*********** Download template ************/
		public function download_temp(){
			header('Content-Type: text/csv; charset=utf-8');  
			header('Content-Disposition: attachment; filename=Supplier_template.csv');  
			$output = fopen("php://output", "w");  
			fputcsv($output, array('Name', 'Email','Shop No.','Street','Country','State','City','Zip','phone','Whatsapp','Skypee','Website', 'Contact person','Category' )); 
			fclose($output);  
			die;
		}	
	/* get last added supplier without page reload on click of Add new*/
	public function last_supplier(){

		$lastsupplier = $this->Suppliers_model->fetch_suppliers();
		if($lastsupplier && !empty($lastsupplier)){
			$response = json_encode($lastsupplier);
		} else { 
			$response = false;
		}
		return $this->output->set_content_type('application/json')
		    ->set_output(json_encode(array('response' => $response,)));
	} 
	
	 /*show states of relevant countries */
	public function fetch_state()
	{
		if($this->input->post('country_id'))
		{
			echo $this->Suppliers_model->fetch_state($this->input->post('country_id'));
		}
	}
	
	public function addSupplier(){		
		$user_id = $this->session->userdata('login')->id;
		if($this->input->post()){					 
			$data = array(
				'added_by' => $user_id,
				'name' => $this->input->post('sup_name'),
			 	'address1' => $this->input->post('sup_shop_name'),
				'address2' => $this->input->post('sup_street_add'),
				'country' => $this->input->post('sup_country'),
				'state' => $this->input->post('sup_state'),
				'city' => $this->input->post('sup_city'),
				'zip' => $this->input->post('sup_zip'),
				'phone' => $this->input->post('sup_phone'),
				'whatsapp' => $this->input->post('sup_whatsapp'),
				'skype' => $this->input->post('sup_skype'),
				'email' => $this->input->post('sup_email'),
				'website' => $this->input->post('sup_site'),
				'contact_person' => $this->input->post('sup_con_person'),
				'category' => $this->input->post('sup_category') 
			);
			$result = $this->Suppliers_model->insert_suppliers($data);	
			if($result == TRUE){
			$this->session->set_flashdata('saves', "Supplier has been added successfully"); 
			}
		}	
		$data['countries'] = $this->Suppliers_model->fetch_country();			
		$data['categories'] = $this->Suppliers_model->fetchCategory();			
		$this->load->view('supplier/addSuppliers', $data);		
	}
		/* Delete Suppliers*/
	public function deleteSupplier(){
		 if($this->input->post()){
			$supplierId = $this->input->post('supplierId');
			$this->Suppliers_model->delete_supplier($supplierId);
			$this->session->set_flashdata('success', "Supplier has been deleted successfully"); 
		} 
	}
	public function email_check(){
		$email = $this->input->post('validEmail');
		 $result = $this->Suppliers_model->email_exist($email);
		 echo $result;			
	}		
	public function edit_supplier(){		
		$user_id = $this->session->userdata('login')->id;
		if($this->input->post()){			
			$supplierId = $this->input->post('supplierId'); 
			$data = array(
					'added_by' => $user_id,
					'name' => $this->input->post('sup_name'),
				 	'address1' => $this->input->post('sup_shop_name'),
					'address2' => $this->input->post('sup_street_add'),
					'country' => $this->input->post('sup_country'),
					'state' => $this->input->post('sup_state'),
					'city' => $this->input->post('sup_city'),
					'zip' => $this->input->post('sup_zip'),
					'phone' => $this->input->post('sup_phone'),
					'whatsapp' => $this->input->post('sup_whatsapp'),
					'skype' => $this->input->post('sup_skype'),
					'email' => $this->input->post('sup_email'),
					'website' => $this->input->post('sup_site'),
					'contact_person' => $this->input->post('sup_con_person'),
					'category' => $this->input->post('sup_category') 
			);					
			$this->Suppliers_model->update_supplier($supplierId, $data);
			$this->session->set_flashdata('editt', "Supplier has been updated successfully"); 
		}		
		if($_GET){
			$this->data['supplierId'] = $_GET['supplierId'];			 
			if(!empty($_GET['supplierId'])){				
				$new_supplier = $this->Suppliers_model->get_supplier($_GET['supplierId']);
			//	print_r($new_supplier[0]->country);  die;
				if($new_supplier[0]->country == "no"){
					$this->data['states'] = null;
					$this->data['country'] = $new_supplier[0]->country;
				}
				else{
				$country = $new_supplier[0]->country;				
				$countryIds = $this->Suppliers_model->countryid($country);		
				//	print_r($countryIds);  die;
				$countryId = $countryIds[0]->id;				
				$this->data['states'] = $this->Suppliers_model->get_states($countryId);
				}
		    }
		}
		$this->data['countries'] = $this->Suppliers_model->fetch_country();
		$this->data['categories'] = $this->Suppliers_model->fetchCategory();
		$this->data['suppliers'] = $this->Suppliers_model->fetch_suppliers(); 
		$this->load->view('supplier/addSuppliers', $this->data);
	}
	public function get_sup(){
		$supplier = $this->input->post('sup_name');
		$lastsupplier = $this->Suppliers_model->getbyemail($supplier);
		if($lastsupplier && !empty($lastsupplier)){
			$response = json_encode($lastsupplier);
		} else { 
			$response = false;
		}
		return $this->output->set_content_type('application/json')
		    ->set_output(json_encode(array('response' => $response,)));
	}
}