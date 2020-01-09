<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* start of file Login.php */
class Login extends MY_Controller{
	public function __construct()
	{
		parent::__construct();
	}
	public function index(){		
		$this->redirect_login();
		if($this->input->method(TRUE)=="POST")
		{
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if ($this->form_validation->run() == TRUE)
			{ 
				$login = $this->Login_model->login($this->input->post()); 			
				if($login)	
				{

					$this->session->set_userdata('login', $login);
					if($this->input->post("remember"))
					{
						set_cookie("login", serialize($login), 86400);
					}
					$this->redirect_login();
				} else {
					$this->data["error"] = "<span class='error'>Username or Password is invalid.</span>";
				}
			}
		}
		$this->data["active"] = "login";
		$this->load->view('headerView', $this->data);
		$this->load->view('login/loginView', $this->data); 
		$this->load->view('footerView', $this->data);
	}

	 
	public function forgot_pswd_view(){
		$this->load->view('login/forgot_password_view');
	}
	
	public function forgotten_password(){		
	    $user_email = $_POST['email'];
		$r_url = base_url();		
		$email_exists = $this->Login_model->check_email_exist($user_email);		
		if($email_exists['email'] ==""){
			$result="FALSE"; echo $result;				 
		}
		if($email_exists['email']  !=""){
			$user_name = 	$email_exists['firstname'];	
			$token = $this->Login_model->insertToken($email_exists['id']);
			$token = $token[0]->reset_token;
			$message='';
			$bodyMsg = '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Email Address: '.$user_email.'</p>'; 
			$bodyMsg .= '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Click <a href="'.$r_url.'login/reset_password/'.$token.'">here</a> to reset your password </p>';  
			$dataMail = array('topMsg'=>'Hi '.$user_name, 'bodyMsg'=>$bodyMsg, 'thanksMsg'=>'Best regards,', 'delimeter'=> 'Import App');
			$to_email = $user_email;
			$subject = 'Reset password confirmation';
			$message = $this->load->view('login/mailView', $dataMail, TRUE);
			$headers = "From: Import App <test91172@gmail.com> \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html\r\n";
			mail($to_email,$subject,$message,$headers);	
		}			
	}

	public function reset_password($token){
		$user_info = $this->Login_model->isTokenValid($token);
		if($user_info==false){
			$this->session->set_flashdata('flash_message', 'Token is invalid or expired');
			redirect('/login');
		}
		$data = array(
			'email'=>$user_info->email,                 
			'reset_token'=>$user_info->reset_token
		);
     				
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');
      
		if ($this->form_validation->run() == FALSE) {   
			$this->load->view('headerView', $data);
			$this->load->view('login/new_password', $data);
			$this->load->view('footerView', $data);
		}else{
			if($this->input->post()){
				$user_id = $user_info->id;
				$data = array(
					'password' => md5($this->input->post('password')),
					'reset_token'=>""
				);  
				$update = $this->Login_model->updatePassword($user_id, $data);
				if($update==false){
					$this->session->set_flashdata('flash_message', 'There was a problem updating your password.');
				}else{
					$this->session->set_flashdata('flash_reset_pass', 'Your password has been updated. You may now login.');
					redirect('/login'); 
				}
			}
		}
	}  
}
/* End of file Login.php */
/* Location: ./application/controllers/admin/Login.php */
?>