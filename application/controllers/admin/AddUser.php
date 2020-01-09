<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class AddUser extends MY_Controller {
		public function __construct(){
			parent::__construct(); 
			$userid = $this->session->userdata('login')->id;
			if($userid == 0)
			{
					redirect('/login');
			}
		}
		public function index(){
			$this->form_validation->set_rules('username', 'user name', 'required');
			$this->form_validation->set_rules('firstname', 'first name', 'required');
			$this->form_validation->set_rules('lastname', 'last name', 'required');
			
			$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_email_check', array( 'required' => 'Please complete this field','valid_email' => 'Enter a valid e-mail address','email_check' => 'Please enter an alternative e-mail address'));
			$this->form_validation->set_rules('role_id','roles','required');
			$this->data['users'] = $this->Admin_model->get_roles();		
			if ($this->form_validation->run() == FALSE){
			
				$this->load->view('headerView', $this->data);	
				$this->load->view('admin/addUserView', $this->data);		
				$this->load->view('footerView', $this->data);
			}
			else{		 
				$user = array(			
				'firstname' => $this->input->post('firstname'),
				'username' => $this->input->post('username'),
				'lastname' => $this->input->post('lastname'),
				'email' => $this->input->post('email'),
				'role_id' => $this->input->post('role_id'),
				);				
				$this->Admin_model->insert_users($user);
				$user_id = $this->db->insert_id();
				$email = $user['email'];
				$fname = $user['firstname'];
				$r_url = base_url();	
				if($this->input->post('email')  !=""){
					$token = $this->Admin_model->addToken($user_id); 
					$token = $token[0]->reset_token;
					$bodyMsg = '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Email Address: '.$email.'</p>'; 
					$bodyMsg .= '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Click <a href="'.$r_url.'login/create_password/'.$token.'">here</a> to create your password </p>';  
					$dataMail = array('topMsg'=>'Hi '.$fname, 'bodyMsg'=>$bodyMsg, 'thanksMsg'=>'Best regards,', 'delimeter'=> 'Import App');
					
					$to_email = $email;
					$subject = 'Create password confirmation';
					$message = $this->load->view('login/mailView', $dataMail, TRUE);
					$headers = "From: Import App <test91172@gmail.com> \r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html\r\n";
			
					mail($to_email,$subject,$message,$headers);
				}
				$this->session->set_flashdata('mail_success_msg', 'New user is created. Please check e-mail to create a password.');
				
				$this->load->view('headerView', $this->data);
				$this->load->view('admin/addUserView', $this->data);		
				$this->load->view('footerView', $this->data);
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
					$data = array('password' => md5($this->input->post('password')));
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
		
		public function email_check($email){
			$query = $this->Admin_model->check_exist_email($email);
			if ($query->num_rows() > 0 ){ 
				$this->form_validation->set_message('exists_in_database', 'Please enter an existing email');
				return FALSE;
			}else{
				return TRUE;
			}
		}
		
	}