<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/* start of file MY_Controller.php */
class MY_Controller extends CI_Controller
{
	public $data	= array();
	public function __construct()
	{
		parent::__construct();
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		if($this->session->flashdata('success'))
		{
			$this->data["success"] = "<span class='success'>".$this->session->flashdata('success')."</span>";
		}
		if($this->session->flashdata('error'))
		{
			$this->data["error"] = "<span class='error'>".$this->session->flashdata('error')."</span>";
		}
		if($this->session->flashdata('errors'))
		{
			$this->data["errors"] = $this->session->flashdata('errors');
		}
		if(!$this->is_login() && get_cookie("login"))
		{
			$this->session->set_userdata('login', unserialize(get_cookie("login")));
		}
		if($this->is_login())
		{
			$this->data["login"] = $this->session->userdata('login');		
		}
		
		
	}
	public function is_login()
	{
		return $this->session->has_userdata('login');
	}
	public function redirect_login()
	{
		if($this->is_login())
		{
			$role_id = $this->session->userdata('login')->role_id;	
	
			if($role_id==1)
			{
				redirect("admin/dashboard");
			}
			if($role_id==2)
			{
				$user_id = $_SESSION['login']->id;
				if(!empty($user_id)){
					$result = $this->User_model->check_status($user_id);
					if(empty($result)){
						redirect('user/payment');
					}else{
						redirect("user/dashboard");
					}
				}
			}
			if($role_id==3)
			{
				redirect("vendor/dashboard");
			}
		}
	}
	public function redirect_logout($role=array())
	{
		if(!$this->is_login())
		{
			redirect("login");
		}
		if(!empty($role) && !in_array($this->data["login"]->role_id, $role))
		{
			redirect();
		}
	}
	public function get_notification_count()
	{
	  $id = $this->data['login']->id;	   
	  $this->data['notification_count'] = $this->home_model->notification_count($id);
	  $this->data['n'] = $this->home_model->notification($id);
	}
}
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
?>