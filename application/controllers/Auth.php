<?php
class Auth extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Shop');
    }
    
    public function install(){
        $this->load->view('installApp');
    }
    
    public function access(){
		$shop = $this->input->get('shop'); 
		if(!empty($_GET['shop_name'])){
			$shop = $_GET['shop_name'];
		}

		if($this->input->method(TRUE) == "POST"){
			$this->session->sess_destroy();
			$shop = $this->input->post('store_name');
		}

		if(isset($shop)){
			$this->session->set_userdata($shop);
		}

		if(($this->session->userdata('access_token'))){
			$data = array(
			'api_key' => $this->config->item('shopify_api_key'),
			'shop' => $shop
			);
			$shop_user =  $this->Shop->get_shop_user($shop);

			$login = $this->Shop->get_user($shop_user);
			$login->shop = $shop;
			if($login)
			{
				$this->session->set_userdata('login', $login);
			}

			/************************** register uninstall webhook *******************************/
			$shop_names =  $this->Shop->get_shopByName($shop);

			if($shop_names['uninstall_webhook'] == 0){
				$this->load->library( "shopifyclient" , array( "shop"  => $shop , "token" => $shop_names['access_token'] , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				$isHook = $this->shopifyclient->call("GET", "/admin/webhooks.json?topic=app/uninstalled");
				if(empty($isHook)){
					$uninstallWebHookArr = array(
						"webhook" => array(
						"topic" => "app/uninstalled",
						"address"=> "https://lifesconfession.com/app/importapp/cust/uninstall",
						"format" => "json")
					);
					$uninstallWebhook = $this->shopifyclient->call("POST", "/admin/webhooks.json", $uninstallWebHookArr);

					if(!empty($uninstallWebhook)){
						$mydata = array('uninstall_webhook'=>1);
						$this->Shop->saveUninstallHook($shop_names['id'],$mydata);
					}
				}else {
					$mydata = array('uninstall_webhook'=>1);
					$this->Shop->saveUninstallHook($shop_names['id'],$mydata);
				}
			}
			redirect('user/products');
		}
		else{
			$this->auth($shop);
		}
    }

    public function auth($shop){
        $data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $shop,
            'ACCESS_TOKEN' => ''
        );
        $this->load->library('Shopify' , $data);
        $scopes = array('read_products', 'write_products', 'read_themes', 'write_themes', 'read_script_tags', 'read_orders', 'write_orders', 'write_content','write_shipping');
        $redirect_url = $this->config->item('redirect_url');
        $paramsforInstallURL = array(
            'scopes' => $scopes,
            'redirect' => $redirect_url
        );
        $permission_url = $this->shopify->installURL($paramsforInstallURL);         
        $this->load->view('auth/escapeIframe', ['installUrl' => $permission_url]);        
    }

    public function authCallback(){
        $code = $this->input->get("code");
		$shop = $this->input->get("shop");
        $shop_names =  $this->Shop->get_shopByName($shop);	
		
		if(isset($code)){
            $data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $shop,
            'ACCESS_TOKEN' => ''
			);
            $this->load->library('Shopify' , $data);
        }

        $accessToken = $this->shopify->getAccessToken($code);
        $this->session->set_userdata(['shop' => $shop , 'access_token' => $accessToken]);
        
		
		$this->load->library( "shopifyclient" , array( "shop"  => $shop , "token" => $accessToken , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));

		$shop_details = $this->shopifyclient->call('GET', '/admin/shop.json');
		$user = array('firstname' => $shop_details['shop_owner'],'email' => $shop_details['email'],'role_id' => 2);						 
		$userId =  $this->Shop->create_user($user);
		
		$result = $this->Shop->payment_status($userId);
		
			if($result[0]['payment_status'] == 1){
				$plan = $this->Shop->check_payment_plan($userId);
				$expiry_date = $this->User_model->get_date($userId);
				$date1 = date("Y-m-d h:i:s");
				$datetime1 = new DateTime($date1);
				$datetime2 = new DateTime($expiry_date[0]['expiry_date']);
				if($plan[0]['amount'] == 29 && $datetime1 < $datetime2){
					$shopcount = $this->Shop->shop_count($userId);
					if($shopcount < 2){
						$dataArr = array( "shop_name" => $shop , "status" => "1" , "access_token" => $accessToken ,"payment_status_shop" => 1);
					}
					else{
						$dataArr = array( "shop_name" => $shop , "status" => "1" , "access_token" => $accessToken ,"payment_status_shop" => 0);
					}
				}
				else if($plan[0]['amount'] == 99 && $datetime1 < $datetime2){
					$dataArr = array( "shop_name" => $shop , "status" => "1" , "access_token" => $accessToken ,"payment_status_shop" => 1);
				}else{			
					$dataArr = array( "shop_name" => $shop , "status" => "1" , "access_token" => $accessToken ,"payment_status_shop" => 0);
				}
			}
			else{			
				$dataArr = array( "shop_name" => $shop , "status" => "1" , "access_token" => $accessToken ,"payment_status_shop" => 0);
			}
		
		if(empty($shop_names)){
			$shopId =  $this->Shop->saveShopDetail($dataArr);
			$this->Shop->saveUserId($shop,$userId);
		}
		$login = $this->Shop->login_user($shop_details['email']);
		$login->shop = $shop;
		if($login){
			$this->session->set_userdata('login', $login);
		}
		if(empty($shop_names)){
			redirect('https://'.$shop.'/admin/apps');
		}else{
			redirect('user/products');
		}
    }
	public function uninstall(){
        $filename = time();
        $input = file_get_contents('php://input');
        $inputArr = json_decode($input, true);
        $shopId = $this->Shop->getShopIdByName($inputArr['myshopify_domain']);
        $updateShop  = $this->db->where('id', $shopId)->update('shop', ['status'=> 0,'uninstall_webhook'=>0]);

    }
	public function after_uninstall(){
        $filename = time();
        $input = file_get_contents('php://input');
        $inputArr = json_decode($input, true);
        $shopId = $this->Shop->getShopIdByName($inputArr['myshopify_domain']);
        $updateShop  = $this->db->where('id', $shopId)->update('shop', ['status'=> 0,'uninstall_webhook'=>0]);
    }
	public function cust_data(){
        die('here');
    }

}