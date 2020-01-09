<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* start of file Login.php */
class Logout extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->session->unset_userdata('login');
		delete_cookie('login');
		$this->redirect_logout();
	}
}
/* End of file Logout.php */
/* Location: ./application/controllers/Logout.php */
?>