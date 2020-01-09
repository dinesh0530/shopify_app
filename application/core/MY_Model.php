<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/* start of file MY_Model.php */
class MY_Model extends CI_Model
{
	public $data	= array();
	public function __construct()
	{
		parent::__construct();
	}
	public function last_query()
	{
		echo $this->db->last_query();
	}
	public function list_fields($table="")
	{
		return $this->db->list_fields($table);
	}
}
/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
?>