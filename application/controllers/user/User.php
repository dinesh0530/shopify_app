<?php defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
class User extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$userid = $this->session->userdata('login')->id;
		$this->load->library('recaptcha');
		if($userid == 0)
		{
			redirect('login');
		}		
		$this->date	= date("Y-m-d H:i:s");
		$this->load->model('User_model');
        $role= $this->session->userdata('login')->role_id;
		if($role == 2){
			$user_pay_status = $this->User_model->get_pay_status($userid); 
			$expiry_date = $this->User_model->get_date($userid);
			$this->data["user_pay_status"] = $user_pay_status;
			$this->data["expiry_date"] = $expiry_date;
		}		
	}

	public function index()
	{
		$userid = $this->session->userdata('login')->id;
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
		
		$this->data["active"] = "dashboard";	
		$id = $this->session->userdata('login')->id;
		$this->data['stores']= $this->Admin_model->fetch_users_shop($id);
		$users = $this->session->userdata('login');
		$user_id = $users->id;
		$recordfound = 0;
		$salefound = 0;
		$sales = array();		
		if(isset($_GET['shop_name']) && !empty($_GET['shop_name'])){
			  $this->data['shops'] = $this->Shop->get_storeByShopName($_GET['shop_name']);
		}else{
			$this->data['shops'] = $this->Shop->get_all_shops($user_id);
		}		
		$start_date  = isset($_GET['startDate'])&&!empty($_GET['startDate'])?$_GET['startDate']:date("Y-m-d", strtotime("-7 days"));		
		$end_date  = isset($_GET['endDate'])&&!empty($_GET['endDate'])? date('Y-m-d', strtotime($_GET['endDate'].'+1 day')) :date("Y-m-d", strtotime("+1 days"));
		$end_date = $end_date."T00:00:00-05:00";
		if(!empty($this->data['shops'])){
			if(isset($_GET['shop_name']) && !empty($_GET['shop_name'])){
			   $this->data['shops'] = $this->Shop->get_storeByShopName($_GET['shop_name']);
			   $storeName = array();
				foreach ($this->data['shops'] as $shop) {
					$storeName[] = $shop['shop_name'];
					$this->load->library( "shopifyclient" , array( "shop"  => $shop['shop_name'] , "token" => $shop['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				}
				$currentStore = $storeName[0];
			}else{			
				if(!empty($this->session->userdata['shop'])){
					$currentStore = $this->session->userdata['shop'];
					$this->load->library( "shopifyclient" , array( "shop"  => $this->session->userdata['shop'] , "token" => $this->session->userdata['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				}else{
					$storeName = array();
					foreach ($this->data['shops'] as $shop) {
						$storeName[] = $shop['shop_name'];
						$this->load->library( "shopifyclient" , array( "shop"  => $shop['shop_name'] , "token" => $shop['access_token']  , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
					}
					$currentStore = $storeName[0];
				}
			}
			$orders = $this->shopifyclient->call('GET', "/admin/orders.json?status=any&created_at_min=$start_date&created_at_max=$end_date");			
			$productarr= array() ;
			$orderscount = 0;
			$currentOrder = 0;
			$pendingorder = 0;
			foreach($orders as $order){
				foreach($order['line_items'] as $paidorder){
					$pid = $paidorder['product_id'];	
					$pro_id = $this->Order_model->getstoreprodcuts($currentStore, $pid);
					if(!empty($pro_id)){
						if($order['financial_status'] == 'paid'){
							if($currentOrder != $order['id']){
								$orderscount = $orderscount+1;
							}								
							$ipid = $pro_id[0]['product_id'];
							$images = $this->Order_model->getprodcutimages($ipid);
							if(!empty($images)){
								$imgSRC = $images[0]['src'];
								$img_url = base_url()."uploads/product$ipid/$imgSRC";
							}else{
								$img_url = "";
							}
							$values = array("img_url" => $img_url);
							array_push($paidorder, $values );
							$productarr[$paidorder['product_id']][] = $paidorder ;
						}							
						if($order['financial_status'] == 'pending'){
							if($currentOrder != $order['id']){
								$pendingorder = $pendingorder+1;
							}
						}
						$currentOrder = $order['id'];
					}
				}
			}			
			$this->data['orders'] = $productarr;
			$this->data['pendingorder'] = $pendingorder;
			$this->data['orderscount'] = $orderscount;
			$this->data['recordfound'] = $recordfound;
			$this->data['currentStore'] = $currentStore;		
			$total_months  = date('m');			
			for($i=1;$i <= $total_months; $i++){
				$query_date = date("Y-$i-$i");
				$created_at_min  = date('Y-m-01', strtotime($query_date));
				$created_at_max   = date('Y-m-t', strtotime($query_date));
				$sale  = $this->shopifyclient->call("GET", "/admin/orders.json?status=any&created_at_min=$created_at_min&created_at_max=$created_at_max");
				$sc = 0;
				foreach($sale as $order){
					$aj = 0;
					$line_items = $order['line_items'];
					foreach($line_items as $orderPro){
						$pid = $orderPro['product_id'];
						$pro_id = $this->Order_model->getstoreprodcuts($currentStore, $pid);
						if(!empty($pro_id)){
							$salefound = 1;
							array_push($sale[$sc], array("show" => "show"));
						} 
						$values = array("shopify_product_id" => $pro_id[0]['shopify_product_id']
										);
						array_push($sale[$sc]['line_items'][$aj], $values );
						$aj++;
					}
					$sc ++;
				}
				$sales[] = $sale;
			}
		}
		$this->data['sales'] = $sales;
		$this->data['salefound'] = $salefound;
		$this->load->view('headerView', $this->data);
		$this->load->view('user/userView', $this->data);
		$this->load->view('footerView', $this->data);
	}	
	public function user_profile($id)
	{   
		$this->value['show'] = $this->Admin_model->get_profile($id); 		
		$this->value['stores']= $this->Admin_model->fetch_users_shop($id);			 
		$this->data["active"] = "profile";	
		$this->load->view('headerView', $this->data);
		$this->load->view('user/userProfile',$this->value);
		$this->load->view('footerView', $this->data);
	}
	public function contact_us() {
		$userid = $this->session->userdata('login')->id;
		$result = $this->User_model->check_status($user_id);			
        $this->data["active"] = "contact";		
		if($this->input->method(TRUE)=="POST")
		{			
			$this->form_validation->set_rules('fname', 'First Name', 'required');  
			$this->form_validation->set_rules('lname', 'Last Name', 'required');   
			$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'Phone Number ', 'required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('message', 'Message', 'required');
			$this->form_validation->set_rules('request', 'Request', 'required');
			if ($this->form_validation->run() == TRUE){				
				$recaptcha = $this->input->post('g-recaptcha-response');      
				$fname = $this->input->post('fname');
				$lname = $this->input->post('lname');
				$email = $this->input->post('email');
				$phone = $this->input->post('phone');
				$request = $this->input->post('request');
				$message_text = $this->input->post('message');
				$file = $this->input->post('file_upload'); 
				$tmp_name  = $_FILES['file_upload']['tmp_name']; 
		        $type = $_FILES['file_upload']['type']; 
				if (!empty($recaptcha)) 
				{
					$response = $this->recaptcha->verifyResponse($recaptcha);
					$get_email = $this->Admin_model->get_roles_by_ID(); 
					$admin_email = $get_email->email;			
					if(!empty($email)) {                 
						// confirm mail1
						$message='';
						$bodyMsg = '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Nature of Request: '.$request.'</p>'; 
						$bodyMsg .= '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Message: '.$message_text.'</p>'; 					
						$delimeter = $fname.' '.$lname."<br>".$phone;
						$dataMail = array('topMsg'=>'Hi Admin', 'bodyMsg'=>$bodyMsg, 'thanksMsg'=>'Best regards,', 'delimeter'=> $delimeter); 
						$subject = 'Contact Form';
						$message = $this->load->view('login/mailView', $dataMail, TRUE);
						$from = "$admin_email";
						$from_name ="Import App";
						$new_name = $from_name.date('d-m-Y H:i:s').$tmp_name;
						$from_name =ucfirst($from_name);
						$header = "From: $from_name<$from>". "\r\n";
						$header .= "MIME-Version: 1.0\r\n";
						$header .= "Content-Type: text/html\r\n";
						$config['mailtype'] = 'html';
						$this->email->initialize($config);
						$this->email->to($admin_email);
						$this->email->from($from,$from_name);
						$this->email->subject($subject);
						$this->email->message($message);
						if(!empty($tmp_name)){
							$this->email->attach($tmp_name,'attachment',$new_name);
						}
						$this->email->send();    
			 
						// confirm mail2
						$bodyMsg = '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Thank you for contacting us.</p>';                 
						$dataMail = array('topMsg'=>'Hi '.$fname.' '.$lname, 'bodyMsg'=>$bodyMsg, 'thanksMsg'=>'Best regards,', 'delimeter'=> 'Import App');     
						$to_email = $email;           
						$subject = 'Contact Form Confimation';
						$message = $this->load->view('login/mailView', $dataMail, TRUE);
						$from = "$admin_email";
						$from_name ="Import App";
						$new_name = $from_name.date('d-m-Y H:i:s').$tmp_name;
						$from_name =ucfirst($from_name);
						$header = "From: Import App <$admin_email>\r\n";
						$header .= "MIME-Version: 1.0\r\n";
						$header .= "Content-Type: text/html\r\n";
						$config['mailtype'] = 'html';
						$this->email->initialize($config);
						$this->email->to($to_email);
						$this->email->from($from,$from_name);
						$this->email->subject($subject);
						$this->email->message($message);
						if(!empty($tmp_name)){
							$this->email->attach($tmp_name,'attachment',$new_name);
						}						
						$ss1 = $this->email->send(); 
					}               
					$this->session->set_flashdata('success_msg', 'Thank you for contacting us.');
				}else{
					$this->session->set_flashdata('captcha-err', 'Invalid Captcha');
				}		
			}			
		}
        $this->load->view('headerView', $this->data);
        $this->load->view('user/ContactView', $this->data);
        $this->load->view('footerView', $this->data);		
    }
	
	public function notify_payment(){
		$today = date("Y-m-d H:i:s");
		$expiry_date = date('Y-m-d H:i:s', strtotime('+1 months'));
		$data = array(
				'user_id' => '1', 
				'transaction_id' =>'asdf1525',
				'subscription_plan' =>'basic',
				'amount'  =>29,
				'purchase_date'  =>$today,
				'expiry_date'  =>$expiry_date
					); 
		$this->User_model->save_payment($data);					
	}
	public function payment_complete(){
		$expiry_date = date('Y-m-d H:i:s', strtotime('+1 months'));
		$data = array(
			'user_id' => $_SESSION['login']->id,
			'transaction_id' => $_POST['txn_id'],
			'subscription_plan' => $_POST['item_name'],
			'amount' => $_POST['mc_gross'],
			'purchase_date' => $_POST['payment_date'],
			'expiry_date' => $expiry_date
		);
		$result = $this->User_model->save_payment($data);
		if($result == TRUE){
			$this->User_model->status_change();
			redirect('/');
		}
	}
	public function checkout(){
		$cur_date	= date("Y-m-d");
		$users = $this->session->userdata('login');
		$user_id = $users->id;
		$user_plan = $this->User_model->get_pay_status($user_id);
		if(!empty($user_plan)){
			$this->data['user_plan'] = $user_plan[0]['subscription_plan'];
			$enddate  = date('Y-m-d', strtotime($user_plan[0]['expiry_date']));
			if($enddate <= $cur_date){
				$this->data['user_plan'] = "";
				$this->User_model->update_user_status($user_id);
				$this->User_model->update_shop_status($user_id);
			}
		}else{
			$this->data['user_plan'] = "";
			$this->User_model->update_user_status($user_id);
			$this->User_model->update_shop_status($user_id);
		}
		
		if(isset($_POST['plan'])){
			if(isset($_POST['subscription']) && $_POST['subscription'] == 1){
				$amount = 29;
				$plan_name = "Basic plan";
				$item_number = "basic01";
			} else {
				$amount = 99;
				$plan_name = "Premium plan";
				$item_number = "premium01";
			}
			?>
				<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" id="order_form"  method="post">
				<input type="hidden" name="business" value="ram.sunder@dbuglab.com">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="item_name" value="<?=$plan_name?>">
				<input type="hidden" id="amount" name="amount" value="<?=$amount?>">  
				<input type="hidden" name="first_name" value=""> 
				<input type="hidden" name="rm" value="2">
				<input type="hidden" name="item_number" value="<?=$item_number?>">
				<input type="hidden" name="last_name" value="">  
				<input type="hidden" name="address1" value="">  
				<input type="hidden" name="address2" value=""> 
				<input type="hidden" name="custom" value="test"> 
				<input type="hidden" name="city" value=""> 
				<input type="hidden" name="state" value="">   
				<input type="hidden" name="return" value="<?= site_url('thank-you')?>">
				<!-- <input type="hidden" name="notify_url" value="<?php echo site_url('notify-payment');?>"> -->
				<input type="hidden" name="cancel_return" value="<?php echo site_url();?>">
				<input type="hidden" name="cbt" value="<?php echo site_url();?>">
				<input type="hidden" name="currency_code" value="USD">
		    </form>	
		    <script type="text/javascript">
				document.getElementById("order_form").submit();	
			</script>
  <?php }
		
		$this->data["active"] = "checkout";
		$this->load->view('headerView', $this->data);
        $this->load->view('user/user_checkout_view', $this->data);
        $this->load->view('footerView', $this->data);
	}
}