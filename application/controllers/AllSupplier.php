<?php defined('BASEPATH') OR exit('No direct script access allowed');
class AllSupplier extends MY_Controller {	
    public function __construct(){
		parent::__construct();
		$userid = $this->session->userdata('login')->id;
		if($userid == 0)
		{
			redirect('login');
		}
		$this->date	= date("Y-m-d H:i:s");
	}		
	/* get all products for admin */
			
		public function index(){

	}
	
	public function delete(){	

	 $supplier_id =$_POST['supplier_id'][0];
	$this->Product_model->delete_supplier($supplier_id);
    	
	}
	public function edit(){	
		$product_id =$_POST['product_id'][0];
		$this->data["product_data"] =  = $this->Product_model->edit_item($product_id);
		$this->load->view('products/edit_item_View', $this->data);	
	}
	
	
	
} 