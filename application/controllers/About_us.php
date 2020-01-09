<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class About_us extends CI_Controller{
			
		public function __construct() {
        parent::__construct();
        
    }				
		public function index(){
			$this->data["active"] = "about";		
		     $this->load->view('headerView');
			 $this->load->view('about/aboutview');
			 $this->load->view('footerView');
		}	
}
?> 