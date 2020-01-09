<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Categories extends MY_Controller {
	public function __construct(){
		parent::__construct(); 
		$userid = $this->session->userdata('login')->id;
		if($userid == 0)
		{
				redirect('/login');
		}
	}
		
	public function index(){
		$this->data['bodyClass'] = 'category_page';
		$this->data["active"] = "all-categories";
		$this->load->view('headerView', $this->data);		
		$params = array();
        $limit = 10;
        $page = $this->uri->segment(3);
		if($this->input->get('keyword')){
			$keyword=$this->input->get('keyword');
			$total_records=$this->Categories_model->category_count($keyword);
		}
			else{
        $total_records = $this->Categories_model->record_counts();
			}
		  if(empty($page)){
			  $page = 0;
			
		  } else {
			
			  $page = $page-1;
		  }
  	
        if($total_records > 0){		
			
            $params["results"] = $this->Categories_model->cate_data($limit, $page*$limit);
			if($this->input->get('keyword')){
				$params["results"] = $this->Categories_model->category_data($keyword);
			}	  		  
            $config['base_url'] = base_url() . 'admin/all-categories';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit;
            $config["uri_segment"] = 3;             
             
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
        $this->load->view('admin/allCategoriesView', $params);
		$this->load->view('footerView', $this->data);		
	}
	
	public function createcategory(){
        if($this->input->post()){
			$data = array(
				'category_name'=> strtolower($this->input->post('value'))
			);			
			$result=$this->Categories_model->saveCategory($data);
			$this->session->set_flashdata('save_cat','Category has been added successfully');		
		}
		$this->load->view('admin/createCategory', $this->data);
	}
	
	
	public function editcategory(){

        if($this->input->post()){			
			$cat_Id = $this->input->post('cat_Id'); 			
			$data = array(
				'category_name'=> strtolower($this->input->post('value'))
			);			
			$result = $this->Categories_model->editCategory($data , $cat_Id);
			$this->session->set_flashdata('update_cat','Category has been updated successfully');
		}
		if($_GET){
			$this->data['catId'] = $_GET['catId'];
		
		}		
		$this->data['categories'] = $this->Categories_model->getCategory();
		$this->load->view('admin/createCategory', $this->data);
	}

    public function deleteCategory(){
		if($this->input->post()){
			$catId = $this->input->post('catId');
			$result = $this->Categories_model->deleteCategory($catId);
			if($result==true){
				$this->session->set_flashdata('delete_cat','Category has been deleted successfully');
			}
		}
	}
	
	public function get_category(){
		$cat_name = $this->input->post('cat_name');
		$cat = $this->Categories_model->getCategories($cat_name);
		if($cat && !empty($cat)){
			$response = json_encode($cat);
		}else {
			$response = false;
		}
		return $this->output->set_content_type('application/json')
		    ->set_output(json_encode(array('response' => $response,)));
	}
	
	
}