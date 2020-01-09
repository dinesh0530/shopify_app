<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends MY_Controller{
	public function __construct()
	{
		parent::__construct();	
			$userid = $this->session->userdata('login')->id;	
			if($userid == 0) {			
				redirect('/login');	
			}
		$this->load->helper("image_resize_helper");
		$this->load->helper("image_crop_helper");

        $role= $this->session->userdata('login')->role_id;
		if($role == 2){
			$user_pay_status = $this->User_model->get_pay_status($userid); 
			$expiry_date = $this->User_model->get_date($userid);
			$this->data["user_pay_status"] = $user_pay_status;
			$this->data["expiry_date"] = $expiry_date; 
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
		}		
	}
	
	public function editprofile(){
		$userid = $this->session->userdata('login')->id;
		$role_id =  $this->session->userdata('login')->role_id;	
		if($this->input->post()){
			if($role_id==3){
				$data = array(
						'username' => $this->input->post('edit_uname'),
						'firstname' => $this->input->post('edit_fname'),
						'lastname' => $this->input->post('edit_lname'),
						'email' => $this->input->post('edit_email'),
						'phone' => $this->input->post('edit_phone'),					
						'city' => $this->input->post('city'),
						'post_code' => $this->input->post('post_code'),
						'job_location' => $this->input->post('job_location'),
						'wechat_id' => $this->input->post('wechat_id'),
						'address' => $this->input->post('address')
					);
			}else{
				$data = array(
					'username' => $this->input->post('edit_uname'),
					'firstname' => $this->input->post('edit_fname'),
					'lastname' => $this->input->post('edit_lname'),
					'email' => $this->input->post('edit_email'),
					'phone' => $this->input->post('edit_phone')
				); 
			}
			$config = array(
				'upload_path' => "./uploads/users",
				'allowed_types' => "jpg|png|jpeg",
				'overwrite' => TRUE	
			);
			$this->upload->initialize($config);		
			if($this->upload->do_upload('profile_img'))
			{
			    $data = array('image' => $this->upload->data());
				$data_upload_files = $this->upload->data();	
                if($role_id==3){
					$data = array(
						'username' => $this->input->post('edit_uname'),
						'firstname' => $this->input->post('edit_fname'),
						'lastname' => $this->input->post('edit_lname'),
						'email' => $this->input->post('edit_email'),
						'phone' => $this->input->post('edit_phone'),					
						'city' => $this->input->post('city'),
						'post_code' => $this->input->post('post_code'),
						'job_location' => $this->input->post('job_location'),
						'wechat_id' => $this->input->post('wechat_id'),
						'address' => $this->input->post('address'),
						'image' => $data_upload_files['file_name']
					);
				}else{
					$data = array(
						'username' => $this->input->post('edit_uname'),
						'firstname' => $this->input->post('edit_fname'),
						'lastname' => $this->input->post('edit_lname'),
						'email' => $this->input->post('edit_email'),
						'phone' => $this->input->post('edit_phone'),
						'image' => $data_upload_files['file_name']
					); 
				}
			}
			$this->Login_model->updateProfile($userid, $data);
			$this->session->set_flashdata('success', "Profile has been updated successfully"); 
		}
	
		$this->data['result'] = $this->Login_model->get__user($userid);
		$this->data['store'] = $this->Login_model->get_user_store($userid);
		$this->load->view('headerView',$this->data);
		$this->load->view('profile/editProfileView',$this->data);
		$this->load->view('footerView', $this->data);
	}
}
?>