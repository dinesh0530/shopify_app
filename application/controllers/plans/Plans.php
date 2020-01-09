<?php defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
class Plans extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$userid = $this->session->userdata('login')->id;
		$this->load->library('recaptcha');
		if($userid == 0)
		{
			redirect('login');
		}
		$this->date	= date("Y-m-d H:i:s");
		
	}

	public function index()
	{  
	   ?>
		
		<!-- <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" id="package_form"  method="post" >
				<input type="hidden" name="business" value="ram.dbuglab@gmail.com">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="item_name" value="One store subscription">
				<input type="hidden" id="amount" name="amount" value="0.1">  
				<input type="hidden" name="first_name" value="<?php echo $this->session->userdata('login')->id;  ?>"> 
				<input type="hidden" name="rm" value="2">
				<input type="hidden" name="item_number" value="1">
				<input type="hidden" name="last_name" value="">  
				<input type="hidden" name="address1" value="">  
				<input type="hidden" name="address2" value=""> 
				<input type="hidden" name="custom" value="<?php echo $this->session->userdata('login')->id;  ?>"> 
				<input type="hidden" name="city" value=""> 
				<input type="hidden" name="state" value="">   
				<input type="hidden" name="return" value="<?php echo site_url('plans/plans/plan_data');?>">
				<input type="hidden" name="notify_url" value="<?php echo site_url('plans/plans/plan_data');?>">
				<input type="hidden" name="cancel_return" value="<?php echo site_url();?>">
				<input type="hidden" name="cbt" value="<?php echo site_url();?>">
				<input type="hidden" name="currency_code" value="USD">
				
		   </form>	
		   
		   <script type="text/javascript">
				document.getElementById("package_form").submit();	

				</script> --->
	<?php
	 
		// $this->load->view('headerView', $this->data);
		// $this->load->view('plans/plansView',$this->value);
		// $this->load->view('footerView', $this->data);
	
	}
	
	function plan_data()
	{
		$r_supp = REF_SUPPORT; //support@refeasy.com
		$r_info = REF_INFO; //info@refeasy.com
		$r_url = REF_URL;
		$fb_l = FB_L;
		$tw_l = TW_L;
		$li_l = LI_L;
		 $key = '';
		 $value = '';
		 $paypal_response = array();
		 foreach($_REQUEST as $k=>$val){
		  $key .=  $k.'===>'.$val.'<br/>';
		  $paypal_response[$k] = $val;
		 }
		$txn_id = $paypal_response['txn_id'];
		echo "<pre>";
		print_r($paypal_response);

	 }
}