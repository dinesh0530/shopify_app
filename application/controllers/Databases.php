<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Databases extends MY_Controller {
	public function __construct(){
		parent::__construct();		
	}

	public function index(){
			$this->data["active"] = "Databases";
			$this->load->view('headerView', $this->data);
			$this->load->view('database/databaseView', $this->data);
			$this->load->view('footerView', $this->data);
		
	}

	/*
	public function products_database(){
		
		$role_id = $this->session->userdata('login')->role_id;  
		$user_id = $this->session->userdata('login')->id;		
		$this->data['categories'] = $this->Categories_model->getCategory();		
		$this->data['suppliers'] = $this->Suppliers_model->fetch_suppliers();		
		$this->data['bodyClass'] = 'product_page';
		$this->data["active"] = "Products databases";		
		
		$params = array();
        $limit = 6;
        $page = $this->uri->segment(2);	
        $total_records = $this->Product_model->record_counts();	
		
			
		  if(empty($page)){
			  $page = 0;
			
		  } else {
			
			  $page = $page-1;
		  } 
        		

        if($total_records > 0){
			$user_id = $this->session->userdata('login')->id;
            $this->data["results"] = $this->Product_model->products_database($limit, $page*$limit);
			
			
            $config['base_url'] = base_url() . 'products_database';
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
            $params["links"] = $this->pagination->create_links();
       } 	   	
		//echo "<pre>";print_R($this->data);die;
		$this->load->view('headerView', $this->data);
		$this->load->view('database/product_databaseView', $this->data);
		$this->load->view('footerView', $this->data);
		
	  }	*/		
		
	}