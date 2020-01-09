<?php

class Nauth extends CI_Controller{

	public function __construct()
    {
        parent::__construct();
        
        //$this->load->model('Shop');
    }
	
	public function orders(){
        
		$this->load->library( "shopifyclient" , array( "shop"  => 'rajmastana.myshopify.com' , "token" => 'f325373433727e3880d637dc09d2306f' , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
		 $orders = $this->shopifyclient->call('GET', '/admin/orders.json');
		 echo '<pre>'; print_r($orders); echo '</pre>';
		 
		  $order = $this->shopifyclient->call('GET', '/admin/orders/#{order_id}.json');
		// $tracking = $this->shopifyclient->call('POST', '/admin/orders/#{order_id}/fulfillments.json');
		// echo '<pre>'; print_r($order); echo '</pre>';
	    //echo 'testing<pre>'; print_r($tracking); echo '</pre>';
    }
	
}