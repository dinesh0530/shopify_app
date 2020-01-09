<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Contact extends CI_Controller {
 
    public function __construct() {
        parent::__construct();
        $this->load->library('recaptcha');
    }

    public function index() {
        $this->data["active"] = "contact";		
		if($this->input->method(TRUE)=="POST")
		{			
			$this->form_validation->set_rules('fname', 'First Name', 'required');  
			$this->form_validation->set_rules('lname', 'Last Name', 'required');   
			$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'Phone Number ', 'required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('message', 'Message', 'required');
			$this->form_validation->set_rules('request', 'Request', 'required');        

			if ($this->form_validation->run() == TRUE)
			{
				
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
        $this->load->view('contact/ContactView', $this->data);
        $this->load->view('footerView', $this->data);
    }
}
?>