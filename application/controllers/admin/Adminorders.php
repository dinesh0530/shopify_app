<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Adminorders extends MY_Controller {
	
    public function __construct(){ 
		parent::__construct();
		$user_id = $this->session->userdata('login')->id;
		if($user_id == 0){
			redirect('login');
		}
		$this->date	= date("Y-m-d H:i:s"); 
		$this->load->model('Admin_model');
	}
	
	public function index(){		
        if($this->input->method(TRUE)=="POST"){
			$order_no = $this->input->post("order_search");
			$start_date = $this->input->post("start_date");
			$end_date = $this->input->post("end_date");
			$end_date1  = date("Y-m-d", strtotime($end_date."+1 days"));
		}else{
			$order_no = "";
			$start_date  = date("Y-m-d", strtotime("-7 days"));
			$end_date  = date("Y-m-d");
			$end_date1  = date("Y-m-d", strtotime("+1 days"));
		}
		
		$params = array();
		$params['start_date'] = $start_date;
		$params['end_date'] = $end_date;
		$params['order_no'] = $order_no;
        $limit = 20;
        $page = $this->uri->segment(3);
        $total_records = $this->Order_model->get_admin_orders_counts($order_no, $start_date, $end_date1 );
		if(empty($page)){
			$page = 0;
		} else {
			$page = $page-1;
		}
        if($total_records > 0){
            $config['base_url'] = base_url() . 'admin/orders';
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

			$orders = $this->Order_model->get_admin_orders($limit, $page*$limit, $order_no, $start_date, $end_date1);
			
			if(!empty($orders)){
				$oc = 0;
				foreach($orders as $order) {
					$pro_ids = $order['pro_ids'];
					$pro_id = explode(",",$pro_ids);
					$prodcut = array();
					foreach($pro_id as $pid){
						$ipid = explode(" ",$pid);
						$impid = $ipid[0];
						$qty = $ipid[1];
						$ordered_pro = $this->Order_model->admin_orders_prodcuts($impid);
						$ordered_pro['or_qty'] = $qty;
						$prodcut[] = $ordered_pro;
					}
					array_push($orders[$oc], $prodcut);
					$oc++;
				}
			}
			$params['orders'] = $orders;
		}
		$this->data["active"] = "orders";
		$this->load->view('headerView', $this->data); 
		$this->load->view('admin/adminorders', $params);
		$this->load->view('footerView', $this->data);
	}
	
	public function delete_orders(){
		$orderid = $this->input->post('oderId');		
		foreach ($orderid as $row){
			if(!empty($row)){
				$data = array(
					'delete_order' => 1
				);
				$this->Admin_model->delete_order_admin($row,$data); 
				$this->session->set_flashdata('delete_Order', 'Orders has been deleted successfully.');
			}
		}
		
	}
}